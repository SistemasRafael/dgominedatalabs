<?include "connections/config.php";?>
<?php
$html = '';
$trn_id = $_POST['trn_id'];
$trn_id_rel = $_POST['trn_id_rel'];
$peso = $_POST['total_peso'];
$final = $_POST['fin'];
$u_id = $_SESSION['u_id'];
$con = 0;

if (isset($trn_id)){
   mysqli_multi_query ($mysqli, "CALL arg_prc_ordenSecado(".$trn_id.", ".$trn_id_rel.", ".$peso.", ".$u_id.", ".$final.")") OR DIE (mysqli_error($mysqli));
   //if ($final == 1){
       $resultado = $mysqli->query("SELECT
                                        se.trn_id as trn_id_batch, se.trn_id_rel, peso, om.folio_interno as muestra
                                    FROM 
                                        arg_muestras_secado se
                                        LEFT JOIN ordenes_transacciones om
                                             ON se.trn_id = om.trn_id_batch
                                             AND se.trn_id_rel = om.trn_id_rel
                                        WHERE se.trn_id = ".$trn_id) or die(mysqli_error());
       if ($resultado->num_rows > 0) {
            $html =  "<div class='col-md-10 col-lg-10'>
                       <table class='table text-black' id='tabla_peso'>
                            <thead class='table-info' align='left'>
                            <tr  class='table-info' >
                                <th colspan='1'>No.</th>
                                <th colspan='7'>Muestra</th>
                                <th colspan='3'>Peso KG</th>
                            </thead>
                            <tbody>";
             while ($res_muestras = $resultado->fetch_assoc()) {
                    $con = $con+1;                    
                    $html.="<tr>
                                <td>".$con."</td>
                                <td style='display:none;'> <input type='input' id='trn_batch".$con."' value='".$res_muestras['trn_id_batch']."'/></td>
                                <td style='display:none;'> <input type='input' id='trn_rel".$con."' value='".$res_muestras['trn_id_rel']."'/></td>
                                <td colspan='6' id=".$res_muestras['trn_id_rel'].">".$res_muestras['muestra']."</td>                                            
                                <td colspan='3'> <input type='number' name='peso".$con."' id='peso".$con."' value='".$res_muestras['peso']."' class='form-control' disabled /></td> 
                            </tr>"; 
                }
             $html .= "</tbody></table></div>";
        }
        else{
            $html = 'Hubo un error, reintente por favor.';
        }
         //$mysqli -> set_charset("utf8");
     
         echo utf8_encode($html);
 // }
 }
?>