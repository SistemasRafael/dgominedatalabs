<?php
//include '\xampp\htdocs\minedata_labs\connections\config.php';
include "connections/config.php";
      
//if ($resultado->num_rows > 0) {

//function TraerDatosGraficosBar(){   
    
$resultado = $mysqli->query("SELECT mes, cantidad_muestras FROM `dash_grafico_widget` WHERE unidad_id = 1 AND tipo = 0" ) or die(mysqli_error());
$arreglo = array(); 
  // while ($consulta_VU = mysqli_fetch_array($resultado)) {  
    $i = 0;
   while($consulta_VU = $resultado->fetch_assoc()){
       // $arreglo['mes'] = $consulta_VU['mes'];
        $arreglo[] = $consulta_VU;
    
  }
   echo json_encode($arreglo);
   // return $arreglo;
//}

?>