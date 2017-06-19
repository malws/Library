<?php
  /*Połączenie z bazą danych*/
  $dbhost = 'localhost'; 	
  $dblogin = '108144';
  $dbpass = 'biblioteka';
  $dbselect = '108144';
  mysql_connect($dbhost,$dblogin,$dbpass);
  mysql_select_db($dbselect) or die("Błąd przy wyborze bazy danych");
  mysql_query("SET CHARACTER SET UTF8");
?>