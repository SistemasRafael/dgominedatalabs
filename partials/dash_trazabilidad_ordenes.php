<? include "../connections/config.php";
$unidad_id = $_GET['unidad_id'];
$_SESSION['unidad_id'] = $unidad_id;
//echo $trn_id;
?>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js">

<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
    body {
    overflow-x: hidden;
    height: 100%;
    background-repeat: no-repeat;
}

.card {
    z-index: 0;
    background-color: #ECEFF1;
    padding-bottom: 20px;
    margin-top: 90px;
    margin-bottom: 90px;
    border-radius: 10px;
}

.top {
    padding-top: 40px;
    padding-left: 13% !important;
    padding-right: 13% !important;
}

/*Icon progressbar*/
#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    color: #455A64;
    padding-left: 0px;
    margin-top: 30px;
} 

#progressbar li {
    list-style-type: none;
    font-size: 13px;
    width: 25%;
    float: left;
    position: relative;
    font-weight: 400;
}

#progressbar .step0:before {
    font-family: FontAwesome;
    content: "\f10c";
    color: #fff;
}

#progressbar li:before {
    width: 40px;
    height: 40px;
    line-height: 45px;
    display: block;
    font-size: 20px;
    background: #C5CAE9;
    border-radius: 50%;
    margin: auto;
    padding: 0px;
}

/*ProgressBar connectors*/
#progressbar li:after {
    content: '';
    width: 100%;
    height: 12px;
    background: #C5CAE9;
    position: absolute;
    left: 0;
    top: 16px;
    z-index: -1;
}

#progressbar li:last-child:after {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
    position: absolute;
    left: -50%;
}

#progressbar li:nth-child(2):after, #progressbar li:nth-child(3):after {
    left: -50%;
}

#progressbar li:first-child:after {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    position: absolute;
    left: 50%;
}

#progressbar li:last-child:after {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
}

#progressbar li:first-child:after {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
}

/*Color number of the step and the connector before it*/
#progressbar li.active:before, #progressbar li.active:after {
    background: #651FFF;
}

#progressbar li.active:before {
    font-family: FontAwesome;
    content: "\f00c";
}

.icon {
    width: 60px;
    height: 60px;
    margin-right: 15px;
}

.icon-content { 
    padding-bottom: 20px;
}

@media screen and (max-width: 992px) {
    .icon-content {
        width: 50%;
    }
}
</style>

<script>

  function iniciar_humedad (trn_id, unidad)
        {               
            trn_id = trn_id;
            unidad_id = unidad;
            //alert(trn_id);
           
            document.getElementById("mina_hum").value = unidad;
            $.ajax({
            		url: 'iniciar_humedad.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {trn_id: trn_id},
            	})
            	.done(function(respuesta){
            	   $('#humedad_modal').modal('show');
                    $("#datos_humedad").html(respuesta);
                        $('#humedad_modal').on('shown.bs.modal', function (e) {
                            $(this).find('#peso_hum1').focus();
                            $(this).find('#peso_sec1').focus();
                        })
              })
              //actualizar_prep(unidad_id);
        }
          
  
    
 
    
     function actualizar_importar()
    {
        var unidad_id = document.getElementById('mina_abs_esp').value;
        var direccionar = '<?echo "\seguimiento_ordenes.php?unidad_id="?>'+unidad_id;                                  
        window.location.href = direccionar;  
    }
    
   
</script>

<!--Modal quebrado--!>
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="quebrado_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable" style="max-width: 650px!important;" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="quebrado">ETAPA QUEBRADO</h5>                   
              </div>
              <div class="modal-body">                    
                    <label for="fecha_quebrado" class="col-form-label">Fecha:</label>
                    <input type="date"  name="fecha_pesaje" id="fecha_pesaje" size=20 style="width:125px; color:#996633" value="<?php echo date("Y-m-d");?>" min="<?php echo date("Y-m-d");?>" disabled />
                   <!-- <label for="mina" class="col-form-label">Mina:</label>-->
                    <input type="hidden"  name="mina" id="mina" size=20 style="width:125px; color:#996633"  disabled />          
              </div>
               <div class="modal-body" class="col-md-12 col-lg-12" style="font-size:5px;" id="datos_quebrado">
               </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="actualizar();" data-dismiss="modal">Cerrar</button>
                    <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" id="quebrado_btn" onclick="quebrado_guardar()">Guardar</button>--!>
              </div>
            </div>
    </div>              
</div>

<?php 
if (isset($_GET['unidad_id'])){
    $mysqli -> set_charset("utf8");
             $datos_orden_detalle = $mysqli->query("SELECT 
                                                        ord.folio, ord.fecha, ord.hora, us.nombre AS usuario, ord.trn_id
                                                       ,om.trn_id AS trn_id_batch, om.folio_interno, om.folio_inicial, om.folio_final, om.cantidad, om.estado as estado_id
                                                       ,(CASE om.estado WHEN 0 THEN 'Pendiente' WHEN 1 THEN 'Iniciada' WHEN 2 THEN 'Finalizada' END) AS estado
                                                       ,buscar_fase(om.trn_id,0) AS fase_id
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
                                                    WHERE ord.unidad_id = ".$unidad_id
                                            ) or die(mysqli_error()); 
                                            
             $datos_metodos = $mysqli->query("SELECT nombre FROM arg_metodos WHERE tipo_id = 1") or die(mysqli_error());             
             $total_metodos = (mysqli_num_rows($datos_metodos));
             
             $unidad_mi = $mysqli->query("SELECT nombre FROM arg_empr_unidades WHERE unidad_id = ".$unidad_id) or die(mysqli_error());             
             $unidad_min = $unidad_mi->fetch_assoc();
             $unidad_mina = $unidad_min['nombre'];
             
            ?>
             <div class="container-fluid">
             <br/><br/><br/>
                <?
                    $fecha_minima_val = date('Y-m-j');
                    $nuevafecha = strtotime ( $fecha_minima_val ) ;
                    $nuevafecha = date ( 'm/d/Y' , $nuevafecha );      
                ?>
                    
                <div class="col-md-2 col-lg-2">
                                <?  $fecha_minima_val = date('Y-m-j');
                                    $nuevafecha = strtotime ( '+3 day' , strtotime ( $fecha_minima_val ) ) ;
                                    $nuevafecha = date ( 'Y-m-d' , $nuevafecha );                                
                                    //echo $nuevafecha; //2020-12-31
                                ?>
                                <label for="fecha_inicial"><b>DESDE:</b></label>
                                <input type="date" name="fecha_inicial" class="form-control" id="fecha_inicial" onchange="ValidaVigencias(<?echo $u_id;?>);" min="<?echo $nuevafecha;?>"/>
                </div>
                <div class="col-md-2 col-lg-2">
                                <label for="fecha_final"><b>HASTA:</b></label><br/>
                                <input type="date" name="fecha_final" class="form-control" id="fecha_final" onchange="ValidaVigenciasFin(<?echo $u_id;?>);" min="<?echo $nuevafecha;?>"/>                                
                </div>                
                <div class="col-md-2 col-lg-4">
                    <label for="print"></label><br/><br/>
                    <button type='button' class='btn btn-success' name='print' id='print' >VER</button>                      
                </div>
                <br/><br/><br/><br/>
                
<div class="container px-1 px-md-4 py-5 mx-auto">
    <div class="card">
        <div class="row d-flex justify-content-between px-3 top">
            <div class="d-flex">
                <h5>ORDER <span class="text-primary font-weight-bold">#Y34XDHR</span></h5>
            </div>
            <div class="d-flex flex-column text-sm-right">
                <p class="mb-0">Expected Arrival <span>01/12/19</span></p>
                <p>USPS <span class="font-weight-bold">234094567242423422898</span></p>
            </div>
        </div>
        <!-- Add class 'active' to progress -->
        <div class="row d-flex justify-content-center">
            <div class="col-12">
            <ul id="progressbar" class="text-center">
                <li class="active step0"></li>
                <li class="active step0"></li>
                <li class="active step0"></li>
                <li class="step0"></li>
                <li class="step0"></li>
            </ul>
            </div>
        </div>
        <div class="row justify-content-between top">
            <div class="row d-flex icon-content">
                <img class="icon" src="https://i.imgur.com/9nnc9Et.png">
                <div class="d-flex flex-column">
                    <p class="font-weight-bold">Order<br>Processed</p>
                </div>
            </div>
            <div class="row d-flex icon-content">
                <img class="icon" src="https://i.imgur.com/u1AzR7w.png">
                <div class="d-flex flex-column">
                    <p class="font-weight-bold">Order<br>Shipped</p>
                </div>
            </div>
            <div class="row d-flex icon-content">
                <img class="icon" src="https://i.imgur.com/TkPm63y.png">
                <div class="d-flex flex-column">
                    <p class="font-weight-bold">Order<br>En Route</p>
                </div>
            </div>
            <div class="row d-flex icon-content">
                <img class="icon" src="https://i.imgur.com/HdsziHP.png">
                <div class="d-flex flex-column">
                    <p class="font-weight-bold">Order<br>Arrived</p>
                </div>
            </div>
        </div>
    </div>
</div>
                
                 <? 
                 
                /*  $html_det = "<table class='table table-striped' id='motivos'>
                                <thead>                                
                                     <tr class='table-info'>      
                                        <th colspan='4'>Ordenes de trabajo: ".$unidad_mina."</th>      
                                        <th align='center' colspan='3'>Batch</th>
                                        <th colspan='2' center>Seguimiento</th>
                                        <th colspan='1' center>Listado</th>
                                        <th></th>
                                     </tr>
                                    <tr class='table-info' justify-content: center;>            
                                        <th scope='col1'>Folio</th>
                                        <th scope='col1'>Batch</th>
                                        <th scope='col1'>Fecha</th>
                                        <th scope='col1'>Hora</th>
                                        <th scope='col1'>Total muestras</th>
                                        <th scope='col1'>De la muestra</th>
                                        <th scope='col1'>A la muestra</th>        
                               </thead>
                               <tbody>";
                               
                               while ($fila = $datos_orden_detalle->fetch_assoc()) {
                                   $num = 1;
                                   $variable_img = $fila['etapa_img'];
                                   $html_det.="<tr>";
                                      $html_det.="<td> <a href='orden_trabajo_print.php?trn_id=".$fila['trn_id']."' target='_blank'>".$fila['folio']."</td>";
                                      $html_det.="<td> <a href='orden_trabajo_print.php?trn_id=".$fila['trn_id']."' target='_blank'>".$fila['folio_interno']."</a></td>";
                                      $html_det.="<td>".$fila['fecha']."</td>";                                     
                                      $html_det.="<td>".$fila['hora']."</td>";
                                      $html_det.="<td>".$fila['cantidad']."</td>";                                 
                                      $html_det.="<td>".$fila['folio_inicial']."</td>";
                                      $html_det.="<td>".$fila['folio_final']."</td>";                                     
                                      $html_det.="<td>".$fila['estado']."</td>";
                                      
                                    
                                      if ($fila['estado_id'] == 0){
                                            if ($fila['humedad'] == 1){
                                                $html_det.="<td><a type='button' class='btn btn-warning' name='print' id='print' onclick = iniciar_humedad(".$fila['trn_id_batch'].",".$unidad_id.")>
                                                                <span class='fa fa-percent fa-2x'> Humedad </span>
                                                      </a>
                                                    </td>";
                                            }
                                            else{
                                                $html_det.="<td><a type='button' class='btn btn-warning' name='print' id='print' onclick = iniciar_batch(".$fila['trn_id_batch'].",".$unidad_id.")>
                                                                <span class='fa fa-check-circle-o fa-2x'>Preparar</span>
                                                      </a>
                                                    </td>";
                                            }                                        
                                      }
                                      if ($fila['estado_id'] == 1 or $fila['estado_id'] == 2){
                                            if($fila['fase_id'] == 1){
                                                
                                                if($fila['reensaye'] == 1 ){
                                                    $html_det.="<td> <button type='button' class='btn btn-info' onclick = iniciar_etapa_reen(".$fila['trn_id_batch'].",".$fila['etapa_id'].",".$unidad_id.")>
                                                                    <span class='fa fa-hourglass-start fa-2x'>Iniciar ".$fila['etapa']." </span>
                                                                 </button></td>";  
                                                }
                                                else{
                                                    $html_det.="<td> <button type='button' class='btn btn-info' onclick = iniciar_etapa(".$fila['trn_id_batch'].",".$fila['etapa_id'].",".$unidad_id.")>
                                                                    <span class='fa fa-hourglass-start fa-2x'>Iniciar ".$fila['etapa']." </span>
                                                                 </button></td>";  
                                                }                                                                                              
                                            }else{
                                                $html_det.="<td>";
                                                  $metodos_lista = $mysqli->query("SELECT  nombre
                                                                                          ,om.metodo_id
                                                                                          ,m.color
                                                                                          ,buscar_fase(".$fila['trn_id_batch'].", om.metodo_id) AS fase_id
                                                                                          ,buscar_etapa(".$fila['trn_id_batch'].", om.metodo_id) AS etapa_id
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
                                                        $html_det.="
                                                                <button type='button' class='".$variable_color."' onclick = iniciar_metodo(".$fila['trn_id_batch'].",".$fila_met['metodo_id'].",".$fila_met['fase_id'].",".$fila_met['etapa_id'].",".$unidad_id.")>
                                                                    <span class='".$variable_img."'>".$fila_met['nombre']."  ".$fila_met['etapa']." </span>
                                                                 </button>";                                                                  
                                                  }
                                                  $html_det.="</td>";
                                              }
                                      }
                               
                                    
                                      
                                   
                  $html_det.="</tbody></table>";*/
                  
                // echo ("$html_en");
              //   echo ("$html_det");
                ?>
        </div>
            <?
  //  }
}
?>                    
 

