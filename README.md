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

## Demo

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
  
  Also, you can use **multiple conditions**
  ```sh
  /api/users/&where=group_id=1&where=rank_id=2
  ```
  
### Limit
  Retreive **the two first** users who are in the group 1
  ```sh
  /api/users/&where=group_id=1&limit=2
  ```
### Order
 
  Retreive the two first users who are in the group 1, **ordered by their name**
  ```sh
  /api/users/&where=group_id=1&limit=2&order=name
  ```
