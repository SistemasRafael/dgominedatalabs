<?php
include "connections/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $unidad_id = $mysqli->real_escape_string($_POST['unidad_id']);
    $folio  = $mysqli->real_escape_string($_POST['existe_tex']);
    
    $existe_mu = $mysqli->query("SELECT COUNT(*) AS existe FROM arg_ordenes_muestrasMetalurgia WHERE folio = '".$folio."'");
    $existe_mue = $existe_mu->fetch_array(MYSQLI_ASSOC);
    $existe_mues = $existe_mue['existe'];

    if($existe_mues == 0)
    {
        echo 'No existe.'; 
    }
    else{            
        echo 'existe';
    }
}
    
