<?php
define('DB_NAME','filemanager');
define('DB_USER','admin');
define('DB_PASW','admin');


try {
    $db = new PDO("mysql:host=localhost;dbname=" . DB_NAME, DB_USER, DB_PASW);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("set names utf8");
}
catch (PDOException $e) {
    echo $e->getMessage();
}