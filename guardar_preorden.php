<?php
//include '\xampp\htdocs\minedata_labs\connections\config.php';
include "connections/config.php";
$html   = '';
$unidad_id_sel = $_POST['unidad_id_s'];
$banco_sel     = $_POST['banco_s'];
$voladura_sel  = $_POST['voladura_s'];
$u_id = $_SESSION['u_id'];
     
if (isset($unidad_id_sel)){
        $max_trn_id = $mysqli->query("SELECT IFNULL(MAX(trn_id), 0) AS trn_id FROM arg_preordenes") or die(mysqli_error());
                            $ma_trn_id = $max_trn_id ->fetch_array(MYSQLI_ASSOC);
                            $trn_id = $ma_trn_id['trn_id'];
                            $trn_id = $trn_id + 1;
                                         
                            $max_fol = $mysqli->query("SELECT IFNULL(MAX(folio), 0) AS folio FROM arg_preordenes WHERE unidad_id = ".$unidad_id_sel) or die(mysqli_error());
                            $max_foli = $max_fol ->fetch_array(MYSQLI_ASSOC);
                            $max_folio = $max_foli['folio'];
                            $folio_preorden = $max_folio + 1;
                                 
                                 //Ordenes
                            $query = "INSERT INTO arg_preordenes (trn_id,  unidad_id,  folio, banco_id, voladura_id, usuario_id) ".
                                                         "VALUES ($trn_id, $unidad_id_sel, $folio_preorden,'$banco_sel', '$voladura_sel', $u_id)";
                            $mysqli->query($query) ;
        
         $resultado = $mysqli->query("SELECT trn_id
                                      FROM arg_preordenes pre
                                      WHERE 
                                     	  pre.trn_id = ".$trn_id
                                    ) or die(mysqli_error());
  }      
if ($resultado->num_rows > 0) {
   while ($row = $resultado->fetch_assoc()) {  
        $existe =($row['trn_id']);  
        if ($existe > 0){
            $html .= $existe; 
        }
    }
}
else{
    $html='';
}

echo $html;
?>