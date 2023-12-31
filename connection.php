<?php
$dbhost="localhost";
$dbuser="root";
$dbpass="";
$dbname="silvermoon";
if(!$conn=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{
    die("connection failed");
}
?>