# Mini-REST

MINI-REST is a a very tiny REST Client designed to make simple call with a MYSQL Database

## Demo

  ### Simple Calls
  Retreive all users
  ```sh
  /api/users
  ```
  
### Filter (where)
  Retreive all users **who are in the group 1**
  ```sh
  /api/users/&where=group_id=1
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
