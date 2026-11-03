<?php
define('DB_HOST','localhost'); define('DB_NAME','REKREA'); define('DB_USER','root'); define('DB_PASS','');
define('BASE','/rekrea/');
function db(){ static $p=null; if(!$p){ $p=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]); } return $p; }
