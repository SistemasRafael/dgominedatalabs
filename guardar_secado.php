<?include "connections/config.php";?>
<?php
$html = '';
$trn_id = $_POST['trn_id_sec'];
$trn_id_rel = $_POST['trn_id_rel_sec'];
$peso = $_POST['peso_seco'];
$final = $_POST['fin'];
$u_id = $_SESSION['u_id'];
$con = 0;

if (isset($trn_id)){
   mysqli_multi_query ($mysqli, "CALL arg_prc_ordenSecado(".$trn_id.", ".$trn_id_rel.", ".$peso.", ".$u_id.", ".$final.")") OR DIE (mysqli_error($mysqli));
   //if ($final == 1){
       $resultado = $mysqli->query("SELECT
                                         se.trn_id as trn_id_batch
                                        ,se.trn_id_rel
                                        ,peso
                                        ,om.muestra_geologia as muestra
                                    FROM 
                                        arg_muestras_secado se
                                        LEFT JOIN ordenes_transacciones om
                                             ON se.trn_id = om.trn_id_batch
                                             AND se.trn_id_rel = om.trn_id_rel
                                        WHERE 
                                            se.trn_id = ".$trn_id."
                                            AND se.peso = 0
                                    ORDER BY om.muestra_geologia") or die(mysqli_error());

        $orden_tr = $mysqli->query("SELECT folio_interno AS orden_trabajo
                                    FROM
                                        arg_ordenes_detalle
                                    WHERE
                                        trn_id = ".$trn_id) or die(mysqli_error());
       $orden_tra = $orden_tr->fetch_assoc();
       $orden_trabajo  = $orden_tra['orden_trabajo'];                                        
       
       if ($resultado->num_rows > 0) {
            $html =  "<div class='col-md-12 col-lg-12'>
                       <table class='table text-black' id='tabla_secado'>     
                            <thead class='table-info' align='left'>                      
                            <tr class='table-warning' align='center'>
                                 <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                            </tr>                           
                            <tr  class='table-info' >
                                    <th colspan='1'>No.</th>
                                    <th colspan='1'>Muestra</th>
                                    <th colspan='1'>Peso kg</th>
                                    <th colspan='2'></th>
                            </thead>
                            <tbody>";
             while ($res_muestras = $resultado->fetch_assoc()) {
                    $con = $con+1;    
                    $trn_id_batch = $res_muestras['trn_id_batch'];
                    $trn_id_rel = $res_muestras['trn_id_rel'];
                    $muestra = $res_muestras['muestra'];
                    $html.="<tr>                                  
                                            <td>$con</td>                             
                                            <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra."' disabled></td> 
                                            <td> <input type='number' name='peso_seco".$con."' id='peso_seco".$con."' class='form-control'/> </td>
                                            <td> <button type='button'class='btn btn-primary' id='boton_save_secado' onclick='peso_guardar(".$trn_id_batch.",".$trn_id_rel.",".$con.")' >
                                                    <span class='fa fa-cloud fa-1x'></span>
                                            </button></td>
                            </tr>";
                }
             $html .= "</tbody></table></div>";
        }//<td colspan='6' id=".$res_muestras['trn_id_rel'].">".$res_muestras['muestra']."</td>
        else{
            $html = 'La etapa ha finalizado.';
        }
         //$mysqli -> set_charset("utf8");
     
         echo utf8_encode($html);
 // }
 }
?>