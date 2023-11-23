<?php
 function mysql_connect($host, $username, $password) {
  if(isset($GLOBALS["con"])) return;
  $mysql = new stdClass();
  $mysql->username = $username;
  $mysql->password = $password;
  $mysql->host = $host;
  $GLOBALS["mysql"] = $mysql;
  return true;
 }

 function mysql_select_db($database) {
  if(!isset($GLOBALS["mysql"])) die("Connection does not exist contact developer at aff.rip");
  $GLOBALS["con"] = new mysqli($GLOBALS["mysql"]->host, $GLOBALS["mysql"]->username, $GLOBALS["mysql"]->password, $database);
  return true;
 }

 function mysql_query($query) {
  if(!isset($GLOBALS["con"])) die("No database connection established");
  return $GLOBALS["con"]->query($query);
 }

 function mysql_fetch_array($result) {
  return $result->fetch_array();
 }

 function mysql_num_rows($result) {
  return $result->num_rows;
 }

 function mysql_insert_id() {
  if(!isset($GLOBALS["con"])) die("No connection");
  return $GLOBALS["con"]->insert_id;
 }

 function mysql_close() {
  return true;
 }
?>