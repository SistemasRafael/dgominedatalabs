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
            
       function print_orden($trn_id, $unidad_id)
            {
                 var trn_id = $trn_id;
                 var unidad_id = $unidad_id;                
                 var print_d = '<?php echo "\orden_trabajo_print.php?trn_id="?>'+trn_id+'&unidad_id='+unidad_id;                
                 window.location.href = print_d;
            }
            
       function redireccion(trn_id, unidad_id){           
            var trn_id = trn_id
            var metodo_id = document.getElementById("metodo_id_sel").value;
            var unidad_id = unidad_id
            
            //alert('aqui');
            //alert(metodo_id);
            //window.history.back();
            var direccionar = '<?php echo "\ orden_trabajo_muestrasControl.php?trn_id="?>'+trn_id+'&metodo_id='+metodo_id+'&unidad_id='+unidad_id;                                  
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
                                    //echo 'entra';
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
                                                                    arg_ordenes_muestras ot WHERE ot.trn_id_rel =  ".$trn_id)
                                                               or die(mysqli_error());
                                }
                                else
                                {
                                    $muestras_det = $mysqli->query("SELECT
                                                                    ot.bloque,
                                                                    ot.posicion,
                                                                    ot.pos_geo,
                                                                    ot.trn_id_rel,
                                                                    ot.trn_id_dup,
                                                                    ot.material_id,
                                                                    ot.muestra_geologia,
                                                                    ot.folio_interno,
                                                                    ot.tipo_id,
                                                                    ot.control
                                                                FROM
                                                                    ordenes_transacciones ot
                                                                WHERE ot.tipo_id = 0 AND ot.trn_id_batch = ".$trn_id."
                                                                
                                                                UNION ALL
                                                                SELECT
                                                                    ot.bloque,
                                                                    ot.posicion,
                                                                    ot.pos_geo,
                                                                    ot.trn_id_rel,
                                                                    ot.trn_id_dup,
                                                                    ot.material_id,
                                                                    ot.muestra_geologia,
                                                                    ot.folio_interno,                                                                
                                                                    ot.tipo_id,
                                                                    ot.control
                                                                FROM
                                                                    ordenes_transacciones ot
                                                                WHERE ot.tipo_id <> 0 AND ot.trn_id_batch = ".$trn_id." AND ot.metodo_id = ".$metodo_id."
                                                                ORDER BY
                                                                    bloque,
                                                                    posicion"
                                                                ) or die(mysqli_error());
                                }
                  
                                    $html_det.="</th> </tr>
                                    <tr class='table-secondary' justify-content: left;>
                                        <th scope='col1'>Posición</th>
                                        <th scope='col1'>Bloque</th> 
                                        <th scope='col1'>Muestra Geología</th>
                                        <th scope='col1'>Folio Interno</th>                             
                                        <th scope='col1'>Control</th>";
                                    $html_det.="</tr>
                               </thead>
                               <tbody>"; 
                             while ($muestras_detallado = $muestras_det->fetch_assoc()) {
                                   
                                    $html_det.="<tr>";
                                        $html_det.="<td align='left' >".$muestras_detallado['posicion']."</td>";
                                       // $html_det.="<td align='left' >".$muestras_detallado['pos_geo']."</td>";
                                        $html_det.="<td align='left' >".$muestras_detallado['bloque']."</td>";
                                        $html_det.="<td>".$muestras_detallado['muestra_geologia']."</td>";   
                                        $html_det.="<td>".$muestras_detallado['folio_interno']."</td>"; 
                                       // $html_det.="<td>".$muestras_detallado['tipo_id']."</td>";  
                                        $html_det.="<td>".$muestras_detallado['control']."</td>";                                  
                                  $html_det.= "</tr>";
                                  $i++;
                            }
                       
                  $html_det.="</tbody></table>";
                  
                 
                      echo ("$html_en");
                      echo ("$html_det");
                ?>
            
            <br />
           <div class="container">
           <div class="col-4 col-md-11 col-lg-11">
                <div class="col-2 col-md-2 col-lg-2">
                    <form method="post" action="orden_trabajo_print.php?trn_id=<?echo $trn_id_rel;?>" name="Printherr" id="Printherr">  
                        <fieldset>  
                            <input type="submit" class="btn btn-info" name="Printherram" id="Printherram" value="IMPRIMIR MUESTRAS" />                
                       </fieldset>  
                    </form> 
                </div>
                
               <!-- <div class="col-2 col-md-2 col-lg-2">
                    <form method="post" action="orden_trabajo_print.php?trn_id=<?echo $trn_id_rel;?>" name="Printherr" id="Printherr">  
                        <fieldset>  
                            <input type="submit" class="btn btn-success" name="Printherram" id="Printherram" value="EXPORT .XLS" />                
                       </fieldset>  
                    </form> 
                </div>--!>
                  
                <div class="col-1 col-md-1 col-lg-1">
                    <input type="submit" class="btn btn-primary" name="nueva_orden" onclick="app(<?echo $unidad_id;?>); " id="nueva_orden" value="NUEVA ORDEN" /> 
                </div>
             </div>
             </div>
          
    </div>
            <?
    }
?>                    
    
<script type="text/javascript" src="js/jquery.min.js"></script> 
          

