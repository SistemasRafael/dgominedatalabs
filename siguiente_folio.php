<?php
//include '\xampp\htdocs\minedata_labs\connections\config.php';
include "connections/config.php";
$html   = '';
$trn_id_pre  = $_POST['trn_id_pre'];
     
if (isset($trn_id_pre)){
         $resultado = $mysqli->query("SELECT folio_actual, pr.banco_id, bv.banco, pr.voladura_id 
                                      FROM 
                                        arg_preordenes pr
                                        LEFT JOIN arg_bancos_voladuras bv
                                            ON bv.banco_id = pr.banco_id
                                            AND bv.voladura_id = pr.voladura_id
                                      WHERE 
                                     	  pr.trn_id = ".$trn_id_pre
                                    ) or die(mysqli_error());
  }      
if ($resultado->num_rows > 0) {
    
    //$html .= //("<option value=$idtop>$nombretop</option>"); 
   while ($row = $resultado->fetch_assoc()) {
        $vol = str_pad($row['voladura_id'],3,"0", STR_PAD_LEFT);;
        $folio = str_pad($row['folio_actual']+1, 3, "0",STR_PAD_LEFT);
        $folio_sig =($row['banco'].$vol.$folio);  
        $html .= $folio_sig;//("<option value=$voladura_id>$voladura_id</option>"); 
    }
}

echo $html;
?>