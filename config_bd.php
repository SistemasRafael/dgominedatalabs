<?php

// Datos de conexion a la base de datos
$servidor='192.168.20.22';
$usuario='danira';
$pass='Danira!';
$bd='arg_registroVisitas';

// Nos conectamos a la base de datos
$mysqli = new mysqli($servidor, $usuario, $pass, $bd);	

// Definimos que nuestros datos vengan en utf8
$mysqli->set_charset('utf8');

// verificamos si hubo algun error y lo mostramos
if ($mysqli->connect_errno) {
	echo "Error al conectar la base de datos {$conexion->connect_errno}";
}

include 'constants.php';

?>
