# Mini-REST

MINI-REST is a a very tiny REST Client designed to make simple call with a MYSQL Database

### + Advantages

- Simple and fast setup
- Single page
- Subqueries (**Ex :** *user_id* will be converted to an *user* object)

### - Inconvenients
- No complex query (*Join* etc...)
- Only one filter ([details here](https://github.com/CYRIAQU3/Mini-REST/blob/master/README.md#order))
- No Put, Delete method for the moment (you must use the *Post* method)
- For Subqueries, you must use my database model

##Configuration

### 1 - Setup your database informations (api.php)

All the database infos are defined at the begin of **api.php**

Here is an example :

```php
// BEGIN DB CONFIGURATION \\

$PARAM_host='localhost';
$PARAM_port='3306';
$PARAM_db_name='yourdbname';
$PARAM_user='username';
$PARAM_pass='password';

// END DB CONFIGURATION \\
```

### 2 - Setup your database model (models.json)
When a "table" URL parameter is called : the script retreive the table (in the database) to be called and the rows to display.

You probably don't want to display the hashed password of an user for example, that's why i advise to never use the "*" selector


An example of the expected shema :

```json
{
	"users":
	{
		"table" : "users",
		"rows" :
		[
			"id","nickname"
		]
	},
	"me":
	{
		"table" : "users",
		"rows" :
		[
			"id","nickname","last_login_date","signup_date","email","avatar_url","banner_url"
		]
	}
}
```

### 3 - .Htaccess (optionnal)

The default .htaccess is configured for url calling like :
```sh
  /api/tablename/&parameter=value
```

Without it, it will be :
```sh
  /api.php?table=tablename&parameter=value
```
Do not forget to check it a look !

## Examples

### Simple Calls
  Retreive **users** (default limit = 10)
  ```sh
  /api/users
  ```
  
### Filter (where)
  Retreive all users **who are in the group 1**
  ```sh
  /api/users/&where=group_id=1
  ```
  
  Also, you can use **multiple conditions** with the separator **:**
  ```sh
  /api/users/&where=group_id=1:nickname=CYRIAQU3
  ```
  
### Limit

  By default, the limit is set to 10, but you can specify a custom value upper or lower
  
  Retreive **the two first** users who are in the group 1
  ```sh
  /api/users/&where=group_id=1&limit=2
  ```
### Order
 
  Retreive the two first users who are in the group 1, **ordered by their name**
  ```sh
  /api/users/&where=group_id=1&limit=2&order=name
  ```
