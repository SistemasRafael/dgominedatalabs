<?// include "../connections/config.php";
$unidad_id = $_GET['unidad_id'];
$_SESSION['unidad_id'] = $unidad_id;
$u_id = $_SESSION['u_id'];
$fecha_inicial = $_GET['fecha_inicial'];
$fecha_final = $_GET['fecha_final'];

?>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style type="text/css">
	.izq{
		background-color:;
	}
	.derecha{
		background-color:;
	}
	.btnSubmit
    {
        width: 50%;
        border-radius: 1rem;
        padding: 1.5%;
        border: none;
        cursor: pointer;
    }
    .circulos{
    	padding-top: 5em;
    }
    img{
      max-width: 100%;
    }
</style>

<script>

    jQuery.noConflict();
    
   function exportar_listado(unidad_id_e)
    {
                 var unidad_id_ex     = unidad_id_e;
                 var fecha_inicial_ex = document.getElementById('fecha_inicial_ex').value;
                 var fecha_final_ex   = document.getElementById('fecha_final_ex').value;
                // alert(fecha_inicial_ex);
                // alert(fecha_final_ex);
                 //document.getElementById("metodo_id_sel").value;
                     var exportar = '<?php echo "\ exportar_listado_ordenes.php?unidad_id="?>'+unidad_id_ex+'&fecha_inicial='+fecha_inicial_ex+'&fecha_final='+fecha_final_ex;                                  
                     window.location.href = exportar;
    }
    
    function ver_listado(unidad_id_e,fecha_inicial_ex, fecha_final_ex)
    {
                 var unidad_id_ex     = unidad_id_e;
                 var fecha_inicial_ex = document.getElementById('fecha_inicial_ex').value;
                 var fecha_final_ex   = document.getElementById('fecha_final_ex').value;
                // alert(fecha_inicial_ex);
                // alert(fecha_final_ex);
                 
                 //document.getElementById("metodo_id_sel").value;
                     var exportar = '<?php echo "\ reportes.php?unidad_id="?>'+unidad_id_ex+'&fecha_inicial='+fecha_inicial_ex+'&fecha_final='+fecha_final_ex;                                  
                     window.location.href = exportar;
    }
   
    
</script>

<?php 
if (isset($_GET['unidad_id'])){
    $mysqli -> set_charset("utf8");
    
    if (is_null($fecha_inicial)){
        $fecha_inicial = date('Y-m-d');
    }
    
    if (is_null($fecha_final)){
        $fecha_final = date('Y-m-d');  
    }
    
             $datos_orden_detalle = $mysqli->query("SELECT 
                                                        ord.folio, date_format(ord.fecha, '%Y-%m-%d') as fecha, ord.hora, us.nombre AS usuario, ord.trn_id
                                                       ,om.trn_id AS trn_id_batch, om.folio_interno, om.folio_inicial
                                                       , om.folio_final, om.cantidad, om.estado as estado_id
                                                       ,(CASE om.estado WHEN 0 THEN 'Pendiente' WHEN 1 THEN 'Iniciada' WHEN 2 THEN 'Finalizada' END) AS estado
                                                       ,buscar_fase(om.trn_id,0) AS fase_id
                                                       ,buscar_fase_nombre(om.trn_id, 0) AS fase_nombre
                                                       ,buscar_etapa(om.trn_id,0) AS etapa_id                                                       
                                                       ,buscar_etapa_nombre(om.trn_id,0) AS etapa
                                                       ,buscar_metodo(om.trn_id) AS metodo_id                                                                                                 
                                                       ,(CASE WHEN met1 = 1 THEN 'X' ELSE '' END) AS met1
                                                       ,(CASE WHEN met2 = 2 THEN 'X' ELSE '' END) AS met2
                                                       ,(CASE WHEN ord.trn_id_rel <> 0 THEN 1 ELSE 0 END) AS reensaye
                                                       ,buscar_humedad(om.trn_id) AS humedad
                                                    FROM
                                                     `ordenes_metodos` om
                                                    LEFT JOIN `arg_ordenes` ord
                                                        ON ord.trn_id = om.trn_id_rel
                                                    LEFT JOIN arg_usuarios us
                                            	       ON us.u_id = ord.usuario_id                                              
                                                    WHERE 
                                                       ord.trn_id_rel = 0
                                                       AND ord.unidad_id = ".$unidad_id."
                                                       AND date_format(ord.fecha, '%Y-%m-%d') BETWEEN '".$fecha_inicial."' AND '".$fecha_final."'"
                                            ) or die(mysqli_error()); 
                                            
             $datos_metodos = $mysqli->query("SELECT nombre FROM arg_metodos WHERE tipo_id = 1") or die(mysqli_error());             
             $total_metodos = (mysqli_num_rows($datos_metodos));
             
             $unidad_mi = $mysqli->query("SELECT nombre FROM arg_empr_unidades WHERE unidad_id = ".$unidad_id) or die(mysqli_error());             
             $unidad_min = $unidad_mi->fetch_assoc();
             $unidad_mina = $unidad_min['nombre'];
             
            ?>
             <div class="container-fluid">
             <br/><br/><br/><br/><br/><br/>
                <?
                    $fecha_minima_val = date('Y/m/j');
                    $nuevafecha = strtotime ($fecha_minima_val) ;
                    $nuevafecha = date ('m/d/Y', $nuevafecha);
                        
                ?>
                    
                <div class="col-md-2 col-lg-2">
                      <?
                        //$fecha_minima_val = date('Y-m-j');
                       // $fecha_minima_val = strtotime ( $fecha_minima_val);
                        $nuevafecha = date('Y-m-d');                                
                        //echo $nuevafecha; //2020-12-31
                      ?>
                      <label for="fecha_inicial_ex"><b>DESDE:</b></label>
                      <input type='date' class='form-control' name='fecha_inicial_ex' id='fecha_inicial_ex' value="<?echo $fecha_inicial;?>" >                 
                
                </div>
                <div class="col-md-2 col-lg-2">
                      <label for="fecha_final_ex"><b>HASTA:</b></label><br/>
                      <input type="date" class="form-control" name='fecha_final_ex' id='fecha_final_ex' value="<?echo $fecha_final;?>">                              
                </div>                
                <div class="col-md-2 col-lg-4">
                    <label for="print"></label><br/><br/>
                    <button type='button' class='btn btn-info' name='print' id='print' onclick="ver_listado(<?echo $unidad_id.', '.$nuevafecha.', '.$nuevafecha;?>)" > <span class="fa fa-eye fa-2x">  Ejecutar</span>    </button>                      
                
                 
                    <button type='button' class='btn btn-success' name='export' id='export' onclick="exportar_listado(<?echo $unidad_id;?>)" >  <span class="fa fa-file-excel-o fa-2x">  Exportar</span>    </button>      
                               
                </div>
                <br/><br/><br/><br/>
                
                 <? 
                  $html_det = "<table class='table table-striped' id='motivos'>
                                <thead>                                
                                     <tr class='table-info'>      
                                        <th colspan='7'>Ordenes de trabajo: ".$unidad_mina."</th>      
                                        <th align='center' colspan='2'></th>
                                        <th></th>
                                     </tr>
                                    <tr class='table-info' justify-content: center;>            
                                        <th scope='col1'>FOLIO</th>
                                        <th scope='col1'>BATCH/ORDEN</th>
                                        <th scope='col1'>FECHA</th>
                                        <th scope='col1'>HORA</th>
                                        <th scope='col1'>TOTAL DE MUESTRAS</th>
                                        <th scope='col1'>DE LA MUESTRA</th>
                                        <th scope='col1'>A LA MUESTRA</th>                                        
                                        <th scope='col1'>ESTADO</th>
                                        <th scope='col1'>FASE Y ETAPAS POR METODO</th>
                                        <th scope='col3'>DESCARGAR RESULTADOS</th>";             
                                    $html_det.="</tr>
                               </thead>
                               <tbody>";
                               
                               while ($fila = $datos_orden_detalle->fetch_assoc()) {
                                   $num = 1;
                                   $variable_img = $fila['etapa_img'];
                                   $html_det.="<tr>";
                                      $html_det.="<td> <a href='orden_trabajo_rep.php?trn_id=".$fila['trn_id']."' target='_blank'>".$fila['folio']."</td>";
                                      $html_det.="<td> <a href='orden_trabajo_rep.php?trn_id=".$fila['trn_id']."' target='_blank'>".$fila['folio_interno']."</a></td>";
                                      $html_det.="<td>".$fila['fecha']."</td>";                                     
                                      $html_det.="<td>".$fila['hora']."</td>";
                                      $html_det.="<td>".$fila['cantidad']."</td>";                                 
                                      $html_det.="<td>".$fila['folio_inicial']."</td>";
                                      $html_det.="<td>".$fila['folio_final']."</td>";                                     
                                      $html_det.="<td>".$fila['estado']."</td>";                                      
                                    
                                      if ($fila['estado_id'] == 0){
                                            if ($fila['humedad'] == 1){
                                                $html_det.="<td><a type='button' class='btn btn-warning' name='print' id='print'";                                                           
                                                $html_det.="><span class='fa fa-percent fa-2x'>
                                                 Humedad </span>
                                                                </a>
                                                            </td>";
                                            }
                                            else{
                                                $html_det.="<td><a type='button' class='btn btn-warning' name='print' id='print'";                                                               
                                                                $html_det.="><span class='fa fa-check-circle-o fa-2x'>Pendiente</span>
                                                                </a>
                                                            </td>";
                                            }                                        
                                      }
                                      
                                      if ($fila['estado_id'] == 1 or $fila['estado_id'] == 2){
                                            if($fila['fase_id'] == 1){                                                
                                                if($fila['reensaye'] == 1 ){
                                                    $html_det.="<td> <button type='button' class='btn btn-info'";                                                     
                                                        $html_det.="><span class='fa fa-hourglass-start fa-2x'></span>
                                                                </button></td>";
                                                } 
                                                else  {
                                                    $html_det.="<td> <button type='button' class='btn btn-info'";                                                     
                                                        $html_det.="><span class='fa fa-hourglass-start fa-2x'>".$fila['fase_nombre']." - ".$fila['etapa']."</span>
                                                                </button></td>";
                                                }                                                                                      
                                            }else{
                                                $html_det.="<td>";
                                                  $metodos_lista = $mysqli->query("SELECT  nombre
                                                                                          ,om.metodo_id
                                                                                          ,m.color
                                                                                          ,buscar_fase(".$fila['trn_id_batch'].", om.metodo_id) AS fase_id
                                                                                          ,buscar_fase_nombre(".$fila['trn_id_batch'].", om.metodo_id) AS fase_nombre
                                                                                          ,buscar_etapa(".$fila['trn_id_batch'].", om.metodo_id) AS etapa_id
                                                                                          ,buscar_etapa_acceso(".$fila['trn_id_batch'].", om.metodo_id, 1) AS boton_acceso
                                                                                          ,buscar_etapa_nombre(om.trn_id_rel,om.metodo_id) AS etapa
                                                                                          ,buscar_etapa_img(om.trn_id_rel,om.metodo_id) AS etapa_img
                                                                                   FROM arg_metodos m
                                                                                   LEFT JOIN arg_ordenes_metodos om
                                                                                    ON m.metodo_id = om.metodo_id
                                                                                   WHERE m.metodo_id <> 4 AND om.trn_id_rel = ".$fila['trn_id_batch']) or die(mysqli_error());
                                                  while ($fila_met = $metodos_lista->fetch_assoc()) {
                                                        
                                                        if ($fila['estado_id'] == 2){
                                                            $variable_color = 'btn btn-success';
                                                        }
                                                        else{
                                                            $variable_color = $fila_met['color'];
                                                        }
                                                        $variable_img = $fila_met['etapa_img'];
                                                        $html_det.="<button type='button' class='".$variable_color."'";                                                                  
                                                                    $html_det.="><span class='".$variable_img."'>".$fila_met['nombre']." ".$fila_met['fase_nombre']." - ".$fila_met['etapa']." </span>
                                                                    </button>";
                                                       if ($fila['etapa_id'] == 11){   
                                                             $preel = 1;
                                                             $html_det.="<td><a button type='button'class='btn btn-success' href='liberar_orden.php?trn_id_a=".$fila['trn_id_batch']."&metodo_id_a=".$fila_met['metodo_id']."&pree=".$preel."' target='_blank'>
                                                                        <span class='fa fa-flask fa-2x'>".$fila_met['nombre']."</span>
                                                                     </button></td>";    
                                                       }  
                                                       if ($fila['etapa_id'] == 12){   
                                                             $preel = 0;  
                                                             $html_det.="<td><a button type='button'class='btn btn-success' href='liberar_orden.php?trn_id_a=".$fila['trn_id_batch']."&metodo_id_a=".$fila_met['metodo_id']."&pree=".$preel."' target='_blank'>
                                                                        <span class='fa fa-flask fa-2x'>".$fila_met['nombre']."</span>
                                                                     </button></td>";                                                                
                                                       }
                                                                                         
                                                  }
                                                  $html_det.="</td>";
                                              }
                                      }
                                     
                                                                     
                                  
                               }                              
                  $html_det.="</tbody></table>";
                  
                 echo ("$html_en");
                 echo ("$html_det");
                ?>
        </div>
            <?
    }
?>                    
 

