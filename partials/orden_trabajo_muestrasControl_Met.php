   <? // include "connections/config.php";
        $trn_id = $_GET['trn_id_batch'];
        $metodo_id = $_GET['metodo_id'];
        $unidad_id = $_GET['unidad_id'];
        $ree = $_GET['ree'];
   ?>
      <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
      <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
      <link href="http://192.168.20.22/MineData-Labs/css/check.css" rel="stylesheet">
      <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">      
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">     
 <script>
 
       function enviar_correo($trn_id)
            {
                 var trn_id = $trn_id;
                 //var enviar_email = '<?php echo "\ enviarEmail.php?trn_id="?>'+trn_id;
                   //window.location.href = enviar_email;
                 var print_d = '<?php echo "\app.php?trn_id="?>'+trn_id;                
                 window.location.href = print_d;
            }
            
       function app($unidad_id)
            {
                 var unidad_id = $unidad_id;                 
                 var print_d = '<?php echo "\app.php?unidad_id="?>'+unidad_id;                
                 window.location.href = print_d;
            }
            
       function print_muestras(trn_id, metodo)
       {
                var trn_id_ex = trn_id;
                var metodo_pree = metodo;
                // alert(fecha_final_ex);
                var exportar = '<?php echo "\ listado_muestras_met.php?trn_id="?>'+trn_id_ex+'&metodo_id='+metodo;                                  
                window.location.href = exportar;
       }
            
       function redireccion(trn_id, unidad_id){           
            var trn_id = trn_id
            var metodo_id = document.getElementById("metodo_id_sel").value;
            var unidad_id = unidad_id
            
            //alert('aqui');
            //alert(metodo_id);
            //window.history.back();
            var direccionar = '<?php echo "\ orden_trabajo_muestrasControl_Met.php?trn_id="?>'+trn_id+'&metodo_id='+metodo_id+'&unidad_id='+unidad_id;                                  
            window.location.href = direccionar;   
        }
 </script>
 
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
if (isset($_GET['trn_id'])){
    /*$metodo_filtro = $_GET['metodo_id_sel'];
    $metodo_filtro_fun = $metodo_filtro;
    echo 'AQUI '.$metodo_filtro;
    if ($metodo_filtro <> '' or $metodo_filtro <> 0){
        $metodo_id = $metodo_filtro;
    }*/    
    
   /* $metodo = $_GET['metodo_id_sel'];
        echo 'aca'.$metodo;
    echo 'aca'.$metodo;*/
    
    if (isset($_GET['metodo_id_sel'])){
        $metodo_id = $_GET['metodo_id_sel'];
        //echo 'aqui'.$metodo_id;
    }
    $trn_id = $_GET['trn_id'];
    $unidad_id = $_GET['unidad_id'];
    $datos_orden = $mysqli->query("SELECT
                                         un.nombre AS unidad, ord.folio, ord.fecha_inicio, ord.hora, us.nombre AS usuario
                                        ,(CASE WHEN ord.trn_id_rel <> 0 THEN 1 ELSE 0 END) AS reensaye
                                        ,(CASE ord.tipo WHEN 3 THEN 3 WHEN 4 THEN 4 WHEN 7 THEN 7 WHEN 8 THEN 8 ELSE 9 END) AS tipo_orden
                                        ,trn_origen
                                   FROM 
                                        arg_ordenes_detalle AS det
                                        LEFT JOIN `arg_ordenes` ord
                                            ON det.trn_id_rel  = ord.trn_id                                    
                                        LEFT JOIN arg_empr_unidades AS un
                                            ON un.unidad_id = ord.unidad_id
                                        LEFT JOIN arg_usuarios us
                                            ON us.u_id = ord.usuario_id
                                   WHERE det.trn_id = ".$trn_id
                                   ) or die(mysqli_error());               
    $orden_encabezado = $datos_orden->fetch_assoc();                                            
    $orden_origen = $orden_encabezado['trn_origen'];
               
    $datos_batchs = $mysqli->query("SELECT trn_id_rel as trn_id_batch, folio_interno, m.nombre as metodo
                                    FROM 
                                        `ordenes_detalle_metodos` ml
                                        LEFT JOIN arg_metodos AS m
                                            ON m.metodo_id = ml.metodo_id
                                    WHERE trn_id_rel =".$trn_id." AND ml.metodo_id = (CASE WHEN ".$metodo_id." = 0 THEN ml.metodo_id ELSE ".$metodo_id." END) ORDER BY ml.metodo_id"
                                            ) or die(mysqli_error());
    $datos_batch = $datos_batchs->fetch_assoc();
            ?>
             <br/> <br/>
             <div class="container">
                <div class="container">
                    <div class="col-md-4 col-lg-4">                 
                    <h3>                         
                         <?
                            echo ("Orden de Trabajo: ".$orden_encabezado['folio']);
                            echo ("</br>");
                            echo ("</br>");
                         ?>
                    </div>           
                    <div class="col-md-4 col-lg-4">
                            <select name="metodo_id_sel" id="metodo_id_sel" value="<?echo $metodo_id;?>" onchange="redireccion(<?echo $trn_id.", ".$metodo_id.", ".$unidad_id?>)"  class="form-control"> 
                            <?$result_h = $mysqli->query("SELECT 0 AS metodo_id, 'Seleccione Método: ' AS nombre UNION ALL SELECT od.metodo_id, m.nombre FROM `ordenes_detalle_metodos` AS od LEFT JOIN arg_metodos AS m ON od.metodo_id = m.metodo_id WHERE od.trn_id_rel = ".$trn_id) or die(mysqli_error());                             
                            while ( $row2 = $result_h ->fetch_array(MYSQLI_ASSOC)) {
                                $banco_sele = $row2['nombre'];?>       
                                <option value="<?echo $row2['metodo_id']?>"><?echo $banco_sele?></option>
                            <?}?>
                            </select>            
                    </div>
                    
                     <div class="col-2 col-md-2 col-lg-2">
                     </div>
                     <div class="col-4 col-md-4 col-lg-4">
                        <button type="button" class="btn btn-info" name="print_muestras" id="print_muestras" onclick="print_muestras(<?echo $trn_id.", ".$metodo_id.", 1";?>)">  <span class="fa fa-file-pdf-o fa-2x">  Imprimir</span>    </button>
                     
                        <button type="button" class="btn btn-success" name="export_muestras" id="export_muestras" onclick="exportar_muestras(<?echo $trn_id.", 1)"?>;">  <span class="fa fa-file-excel-o fa-2x">  Exportar</span>    </button>
                     </div>
                  
               </div>               
                   
               </h3><?                 
                 
                 $html_en = "<table class='table table-striped' id='encabezado'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Unidad de Mina: ".$orden_encabezado['unidad']."</th>
                                    <th scope='col'>Fecha: ".$orden_encabezado['fecha_inicio']."</th>
                                    <th scope='col'>Hora: ".$orden_encabezado['hora']."</th>
                                  </tr>
                                  
                                  <tr>            
                                    <th scope='col'>Usuario: ".$orden_encabezado['usuario']."</th>
                                    <th scope='col'>Departamento: Geología</th>
                                    <th scope='col'>Prioridad: 1</th>
                                  </tr>";
                  $html_en.="</thead></table>";
                  
                  $html_det .= "<table class='table table-striped' id='motivos'>
                                <thead>
                                
                                <tr class='table-info'>      
                                        <th colspan='3'>Batch Folio: ".$datos_batch['folio_interno']."</th>";
                                          $html_det.="<th colspan='4'> Método: ".$datos_batch['metodo']."</th>";
                                    
                                if($metodo_id == 0){ 
                                    $muestras_det = $mysqli->query("SELECT
                                                                    0 AS bloque,
                                                                    0 AS posicion,
                                                                    0 AS trn_id_rel,
                                                                    0 AS trn_id_dup,
                                                                    0 as material_id,
                                                                    folio AS muestra_geologia,
                                                                    0  as muestra,
                                                                    0 as tipo_id
                                                                FROM
                                                                    arg_ordenes_muestrasMetalurgia ot WHERE ot.trn_id_rel =  ".$trn_id)
                                                               or die(mysqli_error());
                                }
                                elseif($orden_encabezado['tipo_orden'] == 3){ // Quebradora/Stackers
                                           $muestras_det = $mysqli->query("SELECT 
                                                                              om.trn_id_batch
                                                                             ,om.trn_id_rel
                                                                             ,om.folio_interno
                                                                             ,om.control
                                                                          FROM
                                                                             ordenes_metalurgia om 
                                                                          WHERE 
                                                                          	om.trn_id_batch = " . $trn_id . " 
                                                                            AND om.metodo_id = " . $metodo_id . "
                                                                          ORDER BY
                                                                            om.bloque, om.posicion"
                                                                          ) or die(mysqli_error($mysqli));
                                }
                                elseif($orden_encabezado['tipo_orden'] == 4){ // Met Mineral
                                           $muestras_det = $mysqli->query("SELECT 
                                                                              om.trn_id_batch
                                                                             ,om.trn_id_rel
                                                                             ,om.folio_interno
                                                                             ,om.control
                                                                          FROM
                                                                             ordenes_metalurgia om 
                                                                          WHERE 
                                                                          	om.trn_id_batch = " . $trn_id . " 
                                                                            AND om.metodo_id = " . $metodo_id . "
                                                                          ORDER BY
                                                                            om.bloque, om.posicion"
                                                                          ) or die(mysqli_error($mysqli));
                                }
                                elseif($orden_encabezado['tipo_orden'] == 7){ // Carbones
                                           $muestras_det = $mysqli->query("SELECT 
                                                                              om.trn_id_batch
                                                                             ,om.trn_id_rel
                                                                             ,om.folio_interno
                                                                             ,om.control
                                                                             ,(CASE WHEN om.ricos = 0 THEN 'Ricos' 
                                                                                    WHEN om.ricos = 1 then 'Pobres' 
                                                                               ELSE '' END) AS ricos
                                                                          FROM
                                                                             ordenes_metalurgia om 
                                                                          WHERE 
                                                                          	om.trn_id_batch = " . $trn_id . " 
                                                                            AND om.metodo_id = " . $metodo_id . "
                                                                          ORDER BY
                                                                            om.posicion"
                                                                          ) or die(mysqli_error($mysqli));
                                }
                  
                $html_det.="</th> </tr>
                            <tr class='table-secondary' justify-content: left;>
                                <th scope='col1'>Posición</th>
                                <th scope='col1'>Bloque</th> 
                                <th scope='col1'>Folio Interno</th>                             
                                <th scope='col1'>Control</th>";
                                if ($orden_encabezado['tipo_orden'] == 7){
                                     $html_det.="<th scope='col1'>Ricos/Pobres</th>";
                                }
                $html_det.="</tr></thead><tbody>"; 
                $i = 1;
                while ($muestras_detallado = $muestras_det->fetch_assoc()) {                
                    $html_det.="<tr>";
                    $html_det.="<td align='left' >".$i."</td>";
                    $html_det.="<td align='left' >".$i."</td>";   
                    $html_det.="<td>".$muestras_detallado['folio_interno']."</td>"; 
                    $html_det.="<td>".$muestras_detallado['control']."</td>";   
                    if ($orden_encabezado['tipo_orden'] == 7){
                                     $html_det.="<th scope='col1'>".$muestras_detallado['ricos']."</th>";
                                }                               
                    $html_det.= "</tr>";
                    $i++;
                }     
                $html_det.="</tbody></table>";
                echo ("$html_en");
                echo ("$html_det");
                ?>
                <br /> 
    </div>
    <?
    }
    ?>                    
<script type="text/javascript" src="js/jquery.min.js"></script>