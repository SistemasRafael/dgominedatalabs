<? //include "../connections/config.php";
$unidad_id = $_GET['unidad_id'];
//echo $trn_id;
?>
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

<?php 
if (isset($_GET['unidad_id'])){
            //$trn_id = $_GET['trn_id'];
            /*$datos_orden = $mysqli->query("SELECT
                                                un.nombre AS unidad, ord.folio, ord.fecha_inicio, ord.hora, us.nombre AS usuario
                                           FROM `arg_ordenes` ord                                            
                                           LEFT JOIN arg_empr_unidades AS un
                                            	ON un.unidad_id = ord.unidad_id
                                           LEFT JOIN arg_usuarios us
                                            	ON us.u_id = ord.usuario_id
                                           WHERE ord.trn_id = ".$trn_id
                                        ) or die(mysqli_error());
             $orden_encabezado = $datos_orden->fetch_assoc();*/
             
             $datos_orden_detalle = $mysqli->query("SELECT 
                                                        ord.folio, ord.fecha_inicio, ord.hora, us.nombre AS usuario, ord.trn_id
                                                       ,om.folio_inicial, om.folio_final, om.cantidad
                                                       ,(CASE WHEN met1 = 1 THEN 'X' ELSE '' END) AS met1
                                                       ,(CASE WHEN met2 = 2 THEN 'X' ELSE '' END) AS met2
                                                       ,(CASE WHEN met3 = 3 THEN 'X' ELSE '' END) AS met3
                                                       ,(CASE WHEN met4 = 4 THEN 'X' ELSE '' END) AS met4
                                                       ,(CASE WHEN met5 = 5 THEN 'X' ELSE '' END) AS met5
                                                       ,(CASE WHEN met6 = 6 THEN 'X' ELSE '' END) AS met6
                                                       ,(CASE WHEN met7 = 7 THEN 'X' ELSE '' END) AS met7
                                                       ,(CASE WHEN met8 = 8 THEN 'X' ELSE '' END) AS met8
                                                    FROM `ordenes_metodos` om
                                                    LEFT JOIN `arg_ordenes` ord
                                                        ON ord.trn_id = om.trn_id_rel
                                                    LEFT JOIN arg_usuarios us
                                            	       ON us.u_id = ord.usuario_id                                              
                                                    WHERE ord.unidad_id = ".$unidad_id
                                            ) or die(mysqli_error()); 
                                            
             $datos_metodos = $mysqli->query("SELECT nombre FROM arg_metodos WHERE activo = 1 AND tipo_id = 1") or die(mysqli_error());             
             $total_metodos = (mysqli_num_rows($datos_metodos));
             
             $unidad_mi = $mysqli->query("SELECT nombre FROM arg_empr_unidades WHERE unidad_id = ".$unidad_id) or die(mysqli_error());             
             $unidad_min = $unidad_mi->fetch_assoc();
             $unidad_mina = $unidad_min['nombre'];
             /*$total_mues = $mysqli->query("SELECT SUM(cantidad) AS total_muestras FROM arg_ordenes_detalle WHERE trn_id_rel = ".$trn_id) or die(mysqli_error());
             $total_muest = $total_mues->fetch_assoc();
             $total_muestras = $total_muest['total_muestras'];*/
            ?>
             <div class="container-fluid">
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
                                <label for="fecha_inicial"><b>Desde:</b></label>
                                <input type="date" name="fecha_inicial" class="form-control" id="fecha_inicial" onchange="ValidaVigencias(<?echo $u_id;?>);" min="<?echo $nuevafecha;?>"/>
                </div>
                <div class="col-md-2 col-lg-2">
                                <label for="fecha_final"><b>Hasta:</b></label><br/>
                                <input type="date" name="fecha_final" class="form-control" id="fecha_final" onchange="ValidaVigenciasFin(<?echo $u_id;?>);" min="<?echo $nuevafecha;?>"/>                                
                </div>
                
                <div class="col-md-2 col-lg-4">
                    <label for="print"></label><br/><br/>
                    <button type='button' class='btn btn-success' name='print' id='print' >VER</button>
                    <button type='button' class='btn btn-success' name='print' id='print' >EXPORTAR
                                                    <span class='fa fa-file-excel-o fa-1x'></span>
                                            </button>
                                        
                </div>
                <br/><br/><br/><br/>
                
                 <?  
                 /*$html_en = "<table class='table' id='encabezado'>
                             <thead>
                                 <tr class='table-secondary'>   
                                    <th colspan='1' ></th>
                                    <th colspan='1' >Desde: <input type='date' name='fecha_inicial' class='form-control' id='fecha_inicial' value='<?echo $nuevafecha;?>'/>
                                    </th>
                                    <th colspan='1'>Hasta: ".$entrada['fecha_final']."</th>
                                    <th colspan='1'><button type='button' class='btn btn-success' name='print' id='print' >
                                                    VER
                                            </button></th>
                                  </tr>;
                              </thead></tbody></table>";*/
                    
                    
                  $html_det = "<table class='table table-bordered' id='motivos'>
                                <thead>                                
                                     <tr class='table'>      
                                        <th colspan='6'>Ordenes de trabajo: ".$unidad_mina."</th>      
                                        <th colspan='".$total_metodos."'>Elementos a Analizar</th>
                                        <th></th>
                                     </tr>
                                    <tr class='table-secondary' justify-content: center;>            
                                        <th scope='col1'>Folio</th>
                                        <th scope='col1'>Fecha</th>
                                        <th scope='col1'>Hora</th>
                                        <th scope='col1'>De la muestra</th>
                                        <th scope='col1'>A la muestra</th>
                                        <th scope='col1'>Total muestras</th>";                                  
                                       while ($fila_met = $datos_metodos->fetch_assoc()) {
                                            $html_det.="<th>".$fila_met['nombre']."</th>";
                                       }
                                    $html_det.="<td></td>";                       
                                    $html_det.="</tr>
                               </thead>
                               <tbody>";
                               
                               while ($fila = $datos_orden_detalle->fetch_assoc()) {
                                   $num = 1;
                                   $html_det.="<tr>";
                                      $html_det.="<td>".$fila['folio']."</td>";
                                      $html_det.="<td>".$fila['fecha_inicio']."</td>";                                     
                                      $html_det.="<td>".$fila['hora']."</td>";
                                      $html_det.="<td>".$fila['folio_inicial']."</td>";
                                      $html_det.="<td>".$fila['folio_final']."</td>";
                                      $html_det.="<td>".$fila['cantidad']."</td>";
                                      while($num <= $total_metodos){
                                           $metodo = 'met'.$num;
                                           $html_det.="<td align='center'>".$fila[$metodo]."</td>";
                                          
                                            $num ++;
                                      }
                                      $html_det.="<td><a type='button' class='btn btn-info' name='print' id='print' href=http://192.168.20.3:81/minedata_labs/orden_trabajo_pdf.php?trn_id=".$fila['trn_id'].">
                                                                                          <span class='fa fa-file-pdf-o fa-1x'></span>
                                                  </a>
                                                </td>";
                                   $html_det.= "</tr>";
                               }
                              
                  $html_det.="</tbody></table>";
                  
                 echo ("$html_en");
                 echo ("$html_det");
                ?>
        </div>
            <?
    }
?>                    
    
<script type="text/javascript" src="js/jquery.min.js"></script>

