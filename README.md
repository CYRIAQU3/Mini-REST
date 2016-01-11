# SQL to JSON

SQL to JSON is a small PHP script allowing you to make [GET](https://github.com/CYRIAQU3/Mini-REST/blob/master/README.md#get) and [POST](https://github.com/CYRIAQU3/Mini-REST/blob/master/README.md#post) request to a database and retreive the result as a JSON object

[GET](https://github.com/CYRIAQU3/Mini-REST/blob/master/README.md#get)

[POST](https://github.com/CYRIAQU3/Mini-REST/blob/master/README.md#post)

### + Advantages and Features

- Simple and fast setup
- Only 2 files required ( [api.php](https://github.com/CYRIAQU3/Mini-REST/blob/master/README.md#1---setup-your-database-informations-apiphp) and [models.json](https://github.com/CYRIAQU3/Mini-REST/blob/master/README.md#2---setup-your-database-model-modelsjson) )
- Subqueries (**Ex :** *user_id* will be converted to an *user* object) [Explanations here](https://github.com/CYRIAQU3/Mini-REST/blob/master/README.md#the-subqueries)

### - Drawbacks / Missing Features
- No complex query (*Join* etc...)
- No Put, Delete method at the moment (you must use the *Post* method)
- For Subqueries, you must use [my database model](https://github.com/CYRIAQU3/Mini-REST/blob/master/README.md#the-subqueries)

##Configuration

### 1 - Setup your database informations (api.php)

All required database infos must be defined at the beginning of **api.php**

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
When a "table" URL parameter is called : the script retrieves the table (in the database) to be called and the rows to display.

You probably don't want to display the hashed password of an user for example, that's why I advise to never use the "*" selector


An example of an expected output :

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

The default .htaccess is configured to pass parameters like this :
```sh
  /api/tablename/&parameter=value
```

Without it, it will be :
```sh
  /api.php?table=tablename&parameter=value
```
Do not forget to take a look at it!

## GET

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

  By default, the limit is set to 10, but you can specify a custom value
  
  Retreive **the two first** users who are in the group 1
  ```sh
  /api/users/&where=group_id=1&limit=2
  ```
### Order
 
  Retreive the two first users who are in the group 1, **ordered by their name**
  ```sh
  /api/users/&where=group_id=1&limit=2&order=name
  ```

## POST
When the script receive a POST query, it simply includes the file located in the sub-repository **/post/{tableparam}**
You can do whatever you want with it

Post query :
```sh
/api/users
```
File called :
```sh
/api/post/users.php
```

##Others
### The Subqueries

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
