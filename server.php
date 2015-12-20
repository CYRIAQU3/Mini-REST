<?php
header('HTTP/1.1 200 OK');	// prevent 404
session_start();
try
{
	$PARAM_host='localhost';
	$PARAM_port='3306';
	$PARAM_db_name='yourdbName';
	$PARAM_user='root';
	$PARAM_pass='';
	$db = new PDO('mysql:host='.$PARAM_host.';port='.$PARAM_port.';dbname='.$PARAM_db_name, $PARAM_user, $PARAM_pass);
}
catch(Exception $e)
{
        echo 'Error : '.$e->getMessage().'<br />';
        echo 'N° : '.$e->getCode();
		die();
}