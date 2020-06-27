
<?php

session_start();
$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "root";
$DB_name = "contact_list";

try
{
   $DB = new PDO("mysql:host={$DB_host};dbname={$DB_name}", $DB_user, $DB_pass);
   $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
   echo $e->getMessage();
}
include_once 'process.php';
$process = new process($DB);


if ($_SESSION["errorType"] != "" && $_SESSION["errorMsg"] != "" ) {
   $ERROR_TYPE = $_SESSION["errorType"];
   $ERROR_MSG = $_SESSION["errorMsg"];
   $_SESSION["errorType"] = "";
   $_SESSION["errorMsg"] = "";
   }
?>