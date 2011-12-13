<?php 
$dbhost="localhost";
$dbuser="root";
$dbpassword="css";
$dbdatabase="njufriends";
//phpinfo();
$db = mysql_connect("localhost", "root", "css");
mysql_query("set names utf8");
if(!$db)
{
   echo "fuck";
}
else
{
   echo "good";
}
mysql_select_db($dbdatabase,$db);
$result = mysql_query("select * from activity;");
$rows = mysql_num_rows($result);
echo $rows;
$row = mysql_fetch_assoc($result);
echo $row['name'];
?>