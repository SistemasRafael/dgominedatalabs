<?php
include "connections/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $unidad_id = $mysqli->real_escape_string($_POST['unidad_id']);
    $folio  = $mysqli->real_escape_string($_POST['folio']);
    $area = $mysqli->real_escape_string($_POST['area']);    
    $ciclica = $mysqli->real_escape_string($_POST['ciclica']);
    $orden = $mysqli->real_escape_string($_POST['orden']);
    $id_max_ve = $mysqli->query("SELECT IFNULL(max(trn_id), 0) AS maximo FROM arg_ordenes_muestrasSoluciones");
    $id_maximo = $id_max_ve->fetch_array(MYSQLI_ASSOC);
    $id_max_u = $id_maximo['maximo'];
    $id_max_u = $id_max_u + 1;

    $select =  $mysqli->query("SELECT * FROM arg_ordenes_muestrasSoluciones WHERE folio = '$folio' AND area_id = ".$area);
    $orden_existente = $mysqli->query("SELECT * FROM arg_ordenes_muestrasSoluciones WHERE orden = $orden AND unidad_id = ".$unidad_id);


    if (mysqli_num_rows($select) == 0) {
        if (mysqli_num_rows($orden_existente) != 0) {            
            //$resultado = $mysqli->query("SELECT trn_id, folio FROM arg_ordenes_muestrasSoluciones WHERE trn_id = " . $id_max_u . "") or die(mysqli_error($mysqli));
            //if (mysqli_num_rows($resultado) > 0) {
                echo 'La posición ya está asignada.';
           // }
        }else{
            $query = "INSERT INTO arg_ordenes_muestrasSoluciones (unidad_id, trn_id, folio, tipo_id, area_id, orden, ciclica) " .
                "VALUES ($unidad_id, $id_max_u, '$folio', 2, $area, $orden, $ciclica)";
            $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
            echo 'Se registro exitosamente.'; 
        }   
    } else {
        echo 'Muestra existente.';
    }
}
