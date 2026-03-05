<?php
session_start();
$mysqli = new mysqli('localhost', 'root', 'M4d3r4$$!0m3t3p3', 'arg_minedata');
if ($mysqli->connect_error) {
    die('Error de conexión: ' . $mysqli->connect_error);
$mysqli->set_charset("utf8"); }
?>