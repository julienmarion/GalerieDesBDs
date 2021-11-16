<?php
set_include_path("./src");

/* Inclusion des classes utilisées dans ce fichier */
require_once("Router.php");
require_once("src/model/BdStorageMySQL.php");
require_once("/users/21609103/private/mysql_config.php");


$dsn = "mysql:host=".$MYSQL_HOST.";port=".$MYSQL_PORT.";dbname=".$MYSQL_DB.";charset=utf8";
$db = new PDO($dsn, $MYSQL_USER, $MYSQL_PASSWORD);



$router = new Router();
$router->main(new BdStorageMySQL($db),new AccountStorageMySQL($db));
?>
