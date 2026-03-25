

<?php
$db_host = 'localhost'; //you can use localhost
$db_name = 'calendersystem'; // database name
$db_user = 'root';
$db_pass = '';
$db_port = 3306;

//mysql connect
$db = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

if ($db->connect_error) {
    echo 'Connection failed.' . $db->connect_error;
    die();
}
