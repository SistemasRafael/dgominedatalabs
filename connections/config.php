<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mysqli = new mysqli('localhost:3306', 'root', '', 'arg_minedata_dgo');
if ($mysqli->connect_error) {
    die('Error de conexión: ' . $mysqli->connect_error);
    $mysqli->set_charset("utf8"); 
}
?>    