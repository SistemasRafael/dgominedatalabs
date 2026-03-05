<?include "connections/config.php";?>

<!--<link href="http://192.168.20.3:81/__pro/argonaut/boostrapp/css/check.css" rel="stylesheet">--!>
<!--<link href="http://192.168.20.22/intranet-spa/css/check.css" rel="stylesheet"> --!>
<?php
$html = '';
$trn_id    = $_POST['trn_id'];
$trn_idmuestra    = $_POST['trn_idmuestra'];
$metodo_id = $_POST['metodo'];
$unidad_id = $_SESSION['unidad_id'];
//$unidad_id = $_POST['unidad'];
//echo 'llego'.$trn_id;
if (isset($trn_id)){
   $tipo_orden = $mysqli->query("SELECT 
                                    (CASE WHEN ord.tipo = 0 THEN 1 ELSE 0 END) AS reensaye
                                    ,odet.folio_interno
                                    ,ord.trn_id
                                FROM arg_ordenes ord
                                LEFT JOIN arg_ordenes_detalle odet
                                    ON ord.trn_id =  odet.trn_id_rel
                                WHERE odet.trn_id = ".$trn_id) or die(mysqli_error());             
   $tipo_ord = $tipo_orden->fetch_assoc();
   $reensaye = $tipo_ord['reensaye'];
   $orden_trabajo = $tipo_ord['folio_interno'];   
   $trnid_ori = $tipo_ord['trn_id'];

//echo $reensaye;
        //Inicia metodo con pesajeecho
    if($reensaye == 1){         
        $ordenes_reensaye = $mysqli->query("SELECT   od.trn_id, od.folio_interno
                                                    ,oe.trn_id as trnid_destino
                                                	FROM arg_ordenes oe
                                                    LEFT JOIN arg_ordenes_detalle AS od
                                                    	ON od.trn_id_rel = oe.trn_id
                                                    LEFT JOIN arg_ordenes_metodos AS om
                                                    	ON om.trn_id_rel = od.trn_id
                                                WHERE 
                                                	oe.tipo = 0
                                                    
                                                    AND od.estado = 0
                                                    AND od.trn_id <> ".$trn_id.";                                                "
                                                ) or die(mysqli_error());
                    
                               
        $html =  "<table class='table text-black' id='datos_orden'>
                            <thead class='thead-info' align='center'>
                                <tr class='table-warning' align='center'>
                                    <th colspan='4'>ORDEN DE REENSAYE: ".$orden_trabajo."</th>                                    
                                </tr>
                                
                            </thead>
                            <tbody>
                            <tr>";       
                            ///$result_h = $mysqli->query("SELECT  ins_id as ins_id_cop, nombre FROM arg_instrumentos WHERE unidad_id = ".$unidad_id) or die(mysqli_error());   
                                $html.="<td>Mover a la ORDEN DE REENSAYE: </td>";            
                                $html.="<td><select name='reen_id' id='reen_id' class='form-control'>";
                                while ( $row2 = $ordenes_reensaye ->fetch_assoc()) {
                                    $ordenreensaye = $row2['folio_interno'];
                                    $trn_id_reen  = $row2['trn_id'];
                                    $html.="<option value='$trn_id_reen'>$ordenreensaye</option>";    
                                                                    
                                }
                                $html.="</select></td>
                                    
                                <td> <button type='button'class='btn btn-primary' id='boton_save_fun' onclick='mover_guardar(".$trn_id.", ".$trn_idmuestra.", ".$metodo_id.",".$unidad_id.")' >
                                             <span class='fa fa-cloud fa-1x'></span>
                                     </button>
                                </td>   
                          </tr>";
      
        $html.="</tbody></table>";  
      } 
 }     
 /* */
//echo ($html);
$mysqli -> set_charset("utf8");
echo ($html);
?>
