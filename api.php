<?php
require('server.php');

$urlq = array();

if($_SERVER['REQUEST_METHOD'] == "POST")					// if the query is a post
{
	$postTarget = htmlspecialchars($_GET['table']);
	$f = "post/".$postTarget.".php";
	include($f);
	exit();
}

foreach ($_GET as $key => $value)
{
	$urlq[htmlspecialchars($key)] = htmlspecialchars($value);
}

$singleValue = true;
$qTable = $urlq['table'];
$qId;
$queryTable = $qTable;
$queryTarget = "";
$queryLimit = 10;
$queryOrder = "id";

$return = array();
$return['state'] = false;
$return['count'] = 0;

if(!empty($urlq['id']))		//set the query id
{
	$qId = intval($urlq['id']);
	$singleValue = false;
	$queryTarget = $qId;
	$queryWhere = "WHERE id = ".$queryTarget;
}
elseif(!empty($urlq['where']))
{
	$p = explode("=", $urlq['where']);
	if(count($p) > 1)
	{
		$queryWhere = 'WHERE '.$p[0].' = "'.$p[1].'"';
	}
}
else
{
	$queryWhere = "";
}


							// EXEPTION
							//  EXAMPLE :  if the query is /me, show the current logged user info
if($urlq['table'] == "me")
{
	$queryTable = "users";

	if(isset($_SESSION['id']))
	{
		$queryWhere = "WHERE id = ".$_SESSION['id'];
	}
	else
	{
		$queryWhere = "WHERE id = 0";
	}
}

						// END EXEPTION

if(!empty($_GET['limit']))		// set a limit
{
	$limit = intval($_GET['limit']);
	$queryLimit = $limit;
}

if(!empty($_GET['order']))		// set the order
{
	$order = htmlspecialchars($_GET['order']);
	$queryOrder = $order;
}

$queryFilter = generateFilter($qTable);

$q = "SELECT ".$queryFilter." FROM ".$queryTable." ".$queryWhere." order by ".$queryOrder." limit ".$queryLimit;

$b = $db->query($q);

if(!$b)						// in case of invalid table : stop
{
	$return['state'] = false;
	$return['error'] = "INVALID_TABLE";
	$return['table'] = $queryTable;
	print(json_encode($return));
	exit;
}

$return['state'] = true;
$return[$qTable] = array();

while($r = $b->fetch(PDO::FETCH_ASSOC))
{
	$return['count']++;

	foreach ($r as $key => $value)
	{
		$dk = explode("_", $key);
		if(count($dk > 1) && $dk[0] != "id")
		{
			$lastk = $dk[count($dk) -1];	//search if its another table reference (ranks, users...)
			if($lastk == "id")
			{
				$kname = str_replace("_".$lastk, "", $key);	// name of the key in the result
				$tname = $kname."s";
				
				// launch another query

				$queryFilter = generateFilter($tname);

				$y = $db->query('SELECT '.$queryFilter.' from '.$tname.' where id = '.$value.' limit 1');
				if($y)
				{
					$u = $y->fetch(PDO::FETCH_ASSOC);
					$r[$kname] = $u;
					unset($r[$key]);
				}
				
			}
		}
	}

	array_push($return[$qTable],$r);
	$return['state'] = true;
}

if($return['count'] == 0)
{
	$return['state'] = false;
	$return['error'] = "NO_RESULTS";
}

function generateFilter($qTable)				// get only the autorized param for a query
{
	$queryFilter = "";

	$filter = json_decode(file_get_contents("models.json"));		// filter the query using the json
	if(property_exists ($filter,$qTable))
	{
		$f = $filter->$qTable;
		$c = 0;
		foreach ($f as $value)									// generate the filter inside the query
		{
			$v = "";
			if($c > 0)
			{
				$v = ", ";
			}

			$queryFilter = $queryFilter.$v.$value;
			$c++;
		}
	}

	return $queryFilter;
}

print json_encode($return);