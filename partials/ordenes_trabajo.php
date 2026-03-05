<? //include "../connections/config.php"; 
$unidad_id = $_GET['unidad_id'];
//echo $trn_id;
?>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<script>
  function generar_controles(trn_id)
        {                     
            trn_id = trn_id;
            //alert(trn_id);
            $.ajax({
            		url: 'generar_controles.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {trn_id: trn_id},
            	})
            	.done(function(respuesta){
            		///$("#placas_dat").html(respuesta);                                       
                    console.log(respuesta);
                    if (respuesta == 'Se registro exitosamente.'){
                       alert('Se inicó la órden con éxito');
                       var direccionar = '<?php// echo "\ orden_trabajo_print.php?unidad_id="?>'+unidad_id;                                  
                      window.location.href = direccionar;                      
                    }
              })*/
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
 
if (isset($_GET['unidad_id'])){
            $datos_orden = $mysqli->query("SELECT
                                                un.nombre AS unidad, ord.folio, ord.fecha_inicio, ord.hora, us.nombre AS usuario
                                           FROM `arg_ordenes` ord                                            
                                           LEFT JOIN arg_empr_unidades AS un
                                            	ON un.unidad_id = ord.unidad_id
                                           LEFT JOIN arg_usuarios us
                                            	ON us.u_id = ord.usuario_id
                                           WHERE ord.trn_id = ".$trn_id
                                        ) or die(mysqli_error());
             $orden_encabezado = $datos_orden->fetch_assoc();
             
            /* $datos_orden_detalle = $mysqli->query("SELECT
                                                        folio_inicial, folio_final, cantidad
                                                       ,(CASE WHEN met1 = 1 THEN 'X' ELSE '' END) AS met1
                                                       ,(CASE WHEN met2 = 2 THEN 'X' ELSE '' END) AS met2
                                                       ,(CASE WHEN met3 = 3 THEN 'X' ELSE '' END) AS met3
                                                       ,(CASE WHEN met4 = 4 THEN 'X' ELSE '' END) AS met4
                                                       ,(CASE WHEN met5 = 5 THEN 'X' ELSE '' END) AS met5
                                                       ,(CASE WHEN met6 = 6 THEN 'X' ELSE '' END) AS met6
                                                       ,(CASE WHEN met7 = 7 THEN 'X' ELSE '' END) AS met7
                                                       ,(CASE WHEN met8 = 8 THEN 'X' ELSE '' END) AS met8
                                                    FROM `ordenes_metodos`                                                    
                                                    WHERE trn_id_rel = ".$trn_id
                                            ) or die(mysqli_error()); */
                                            
            $datos_orden_encab = $mysqli->query("SELECT
                                                	   od.trn_id, od.trn_id_rel, od.folio_inicial, od.folio_final, od.cantidad
                                                  FROM
                                                    arg_ordenes_detalle od
                                                  WHERE od.trn_id_rel = ".$trn_id."
                                                  ORDER BY
                                                	trn_id_rel")or die(mysqli_error());
            
            
             //$datos_metodos = $mysqli->query("SELECT nombre FROM arg_metodos WHERE activo = 1 AND tipo_id = 1") or die(mysqli_error());             
             //$total_metodos = (mysqli_num_rows($datos_metodos));
             
            $datos_metodos = $mysqli->query("SELECT
                                                	om.metodo_id, met.nombre
                                                FROM
                                                    arg_ordenes_detalle od
                                                    LEFT JOIN `arg_ordenes_metodos` om
                                                        ON od.trn_id = om.trn_id_rel
                                                	LEFT JOIN arg_metodos met
                                                    	ON om.metodo_id = met.metodo_id
                                                WHERE od.trn_id_rel = ".$trn_id."
                                                ORDER BY
                                                	om.metodo_id"
                                                  ) or die(mysqli_error());
             $total_metodos = (mysqli_num_rows($datos_metodos));
             
             $total_mues = $mysqli->query("SELECT SUM(cantidad) AS total_muestras FROM arg_ordenes_detalle WHERE trn_id_rel = ".$trn_id) or die(mysqli_error());
             $total_muest = $total_mues->fetch_assoc();
             $total_muestras = $total_muest['total_muestras'];
             
            
            ?>
             <div class="container-fluid">
                  <br /> <br />
                  <h3><? echo ("Orden de Trabajo: ".$orden_encabezado['folio']); ?></h3>
                 <?
                 $html_en = "<table class='table table-bordered' id='encabezado'>
                             <thead>
                                 <tr class='table-secondary'>   
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
                 
                  $html_det = "<table class='table table-bordered'>
                                <thead>                                
                                     <tr class='table'>      
                                        <th colspan='3'>Folios</th>      
                                        <th colspan='".$total_metodos."'>Elementos a Analizar</th>
                                     </tr>
                                    <tr class='table-secondary' justify-content: center;>            
                                        <th scope='col1'>De la muestra</th>
                                        <th scope='col1'>A la muestra</th>
                                        <th scope='col1'>Total muestras</th>";                                  
                                       while ($fila_met = $datos_metodos->fetch_assoc()) {
                                            $html_det.="<th align='center'>".$fila_met['nombre']."</th>";
                                       }
                                    $html_det.="<th scope='col1'>Iniciar</th>";                             
                                    $html_det.="</tr>
                               </thead>
                               <tbody>";
                               
                               while ($fila = $datos_orden_encab->fetch_assoc()) {
                                   $po = 1;
                                   $trn_id_sig = $fila['trn_id'];
                                   $html_det.="<tr>";
                                      $html_det.="<td style='display:none;'>".$fila['trn_id']."</td>";
                                      $html_det.="<td>".$fila['folio_inicial']."</td>";
                                      $html_det.="<td>".$fila['folio_final']."</td>";
                                      $html_det.="<td>".$fila['cantidad']."</td>";
                                      
                                      $num = 1;             
                                      $datos_metodos_o = $mysqli->query("SELECT
                                    	                                   om.metodo_id, met.nombre
                                                                        FROM
                                                                            arg_ordenes_detalle od
                                                                            LEFT JOIN `arg_ordenes_metodos` om
                                                                                ON od.trn_id = om.trn_id_rel
                                                                            LEFT JOIN arg_metodos met
                                                                                ON om.metodo_id = met.metodo_id
                                                                            WHERE od.trn_id_rel = ".$trn_id
                                                                        ) or die(mysqli_error());
                                      while ($fila_met = $datos_metodos_o->fetch_assoc()) {  echo $num;
                                        $valor_pos[$num] = $fila_met['metodo_id'];                                        
                                        $num++;                                        
                                      }  
                                      
                                      $po = 1;                                                                             
                                      while ($po <= $total_metodos){   
                                            $val = $valor_pos[$po];
                                            $datos_metodos_d = $mysqli->query("SELECT
                                	                                                om.metodo_id, met.nombre
                                                                               FROM
                                                                                    `arg_ordenes_metodos` om
                                                                                    LEFT JOIN arg_metodos met
                                                                                    	ON om.metodo_id = met.metodo_id
                                                                               WHERE om.trn_id_rel = ".$trn_id_sig." and om.metodo_id = ".$val
                                                                              ) or die(mysqli_error()); 
                                                                        
                                            if ($datos_metodos_d->num_rows > 0) {                                       
                                                while ($fila_meto = $datos_metodos_d->fetch_assoc()) {
                                                    $met_id = $fila_meto['metodo_id'];
                                                    $val = $valor_pos[$po];
                                                    //echo 'met: '.$met_id;
                                                    //echo 'pos: '.$valor_pos[$po];
                                                      if ($met_id == $val){
                                                            $html_det.="<td align='center'>".'X'."</td>";
                                                      }
                                                      else{
                                                            $html_det.="<td align='center'></td>";
                                                           }
                                                }
                                            }
                                            else{
                                                    $html_det.="<td align='center'></td>";
                                            }                                                        
                                            $po++; 
                                      }
                                   $html_det.="<td><a type='button' class='btn btn-warning' name='iniciar' id='iniciar' onclick='generar_controles(".$fila['trn_id'].")'>
                                                                                          <span class='fa fa-hourglass-o fa-2x'></span>
                                                   </a>
                                                </td>";
                                   $html_det.= "</tr>";
                               }
                               $html_det.="<td colspan='2'><strong>TOTAL MUESTRAS: </strong></td>";
                               $html_det.="<td><strong>".$total_muestras."</strong></td>";
                  $html_det.="</tbody></table>";
                  
                 echo ("$html_en");
                 echo ("$html_det");
                ?>
              
           <div class="container">
           <div class="col-4 col-md-12 col-lg-12">
                <div class="col-2 col-md-2 col-lg-2">
                     <form method="post" action="orden_trabajo_pdf.php?trn_id=<?echo $trn_id;?>" name="Printform" id="Printform">  
                        <fieldset>  
                            <input type="submit" class="btn btn-info" name="print" id="print" value="IMPRIMIR ORDEN" />                
                       </fieldset>  
                    </form> 
                </div>
                
                <div class="col-2 col-md-2 col-lg-2">
                      <form method="post" action="orden_trabajo_muestras.php?trn_id=<?echo $trn_id;?>" name="newform" id="newform">  
                            <fieldset>  
                                <input type="submit" class="btn btn-info" name="ver_muestras" id="ver_muestras" value="IMPRIMIR MUESTRAS" />                
                           </fieldset>  
                        </form>
                </div>
                
                <div class="col-2 col-md-2 col-lg-2">
                      <form method="post" action="exportar.php" name="newform" id="newform">  
                            <fieldset>  
                                <input type="submit" class="btn btn-success" name="nueva_orden" id="nueva_orden" value="EXPORTAR .XLS" />                
                           </fieldset>  
                        </form>
                </div>
                
                <div class="col-2 col-md-2 col-lg-2">
                      <form method="post" action="app.php?unidad_id=<?echo $unidad_id;?>" name="newform" id="newform">  
                            <fieldset>  
                                <input type="submit" class="btn btn-primary" name="nueva_orden" id="nueva_orden" value="NUEVA ORDEN" />                
                           </fieldset>  
                        </form>
                </div>
             </div>
             </div>
          
    </div>
            <?
    }
?>                    
    