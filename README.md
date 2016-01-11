# Mini-REST

MINI-REST is a a very tiny REST Client designed to make simple call with a MYSQL Database

### + Advantages

- Simple and fast setup
- Single page
- Subqueries (**Ex :** *user_id* will be converted to an *user* object)

### - Inconvenients
- No complex query (*Join* etc...)
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

##Others
### The database Model

The script auto convert some values to object if they follow this following model :

- The table name must be plural (ex : users, movies)
- Every rows must have an id with the row name "id" (not user->user_id but user->id)
- When you referenced another object in your rows, the table name must be singular following by **_id**  ex : **table_id**

An example :
```json
{
	"articles":
	{
		"table" : "articles",
		"rows" :
		[
			"id","user_id","name","etc"
		]
	}
}
```
Look at the **user_id** row.
When you call an article, the script will check if the table users actually exist, in this case, it will transform *user_id* into *user* and you will get a result like it :

**Call**
```sh
  /api/articles/1
```
**Article structure in Database**
```json
{
	"id": "1",
	"name": "Insert a clickbait article name here",
	"user_id" : "1"
}
```

**Result**
```json
{
	"success" : true,
	"count" : 1,
	"articles": [
	{
		"id": "1",
		"name": "Insert a clickbait article name here",
		"user" : 
		{
			"id" : "1",
			"nickname" : "CYRIAQU3",
			"etc..." : "etc..."
		}
	}]
}
```
