<?// include "../connections/config.php"; 
$trn_id = $_GET['trn_id'];
$unidad_id = $_GET['unidad_id'];
//echo $trn_id;
?>
 <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">--!>

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


 
if (isset($_GET['trn_id'])){
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
                                                
            $datos_orden_metodos = $mysqli->query("SELECT 
                                                         me.nombre AS metodo, om.metodo_id, om.trn_id_orden, om.trn_id_rel                                                
                                                    FROM
                                                     `ordenes_metodos_lista` om
                                                     LEFT JOIN arg_metodos me
                                                        ON me.metodo_id = om.metodo_id                                          
                                                    WHERE om.trn_id_orden = ".$trn_id." ORDER BY om.metodo_id"
                                            ) or die(mysqli_error()); 
                                               
            $datos_orden_encab = $mysqli->query(" SELECT
                                                	 od.trn_id, od.trn_id_rel, folio_interno, od.folio_inicial, od.folio_final, od.cantidad
                                                  FROM
                                                    arg_ordenes_detalle od
                                                  WHERE od.trn_id_rel = ".$trn_id."
                                                  ORDER BY
                                                	trn_id_rel")or die(mysqli_error());
                                                    
            $datos_metodos = $mysqli->query("SELECT 
                                                        DISTINCT me.nombre AS metodo, om.metodo_id                                                 
                                                    FROM
                                                     `ordenes_metodos_lista` om
                                                     LEFT JOIN arg_metodos me
                                                        ON me.metodo_id = om.metodo_id                                          
                                                    WHERE om.trn_id_orden = ".$trn_id
                                            ) or die(mysqli_error()); 
             
             $count_metodos = (mysqli_num_rows($datos_metodos));
                       
             $total_mues = $mysqli->query("SELECT SUM(cantidad) AS total_muestras FROM arg_ordenes_detalle WHERE trn_id_rel = ".$trn_id) or die(mysqli_error());
             $total_muest = $total_mues->fetch_assoc();
             $total_muestras = $total_muest['total_muestras'];             
            
            ?>
             <div class="container-fluid">
                  <br /> <br /><br /> <br /><br /> <br />
                  <h3><? echo ("Orden de Trabajo: ".$orden_encabezado['folio']); ?></h3>
                 <?
                 $html_en = "<table class='table table-bordered' id='encabezado'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Unidad de Mina: ".$orden_encabezado['unidad']."</th>
                                    <th scope='col'>Fecha: ".$orden_encabezado['fecha_inicio']."</th>
                                    <th scope='col'>Hora: ".$orden_encabezado['hora']."</th>
                                  </tr>
                                  
                                  <tr class='table-secondary'>            
                                    <th scope='col'>Usuario: ".$orden_encabezado['usuario']."</th>
                                    <th scope='col'>Departamento: Geología</th>
                                    <th scope='col'>Prioridad: 1</th>
                                  </tr>";
                  $html_en.="</thead></table>";
                 
                  $html_det = "<table class='table table-bordered'>
                                <thead>                                
                                     <tr class='table-info'>      
                                        <th colspan='4'>Folios</th>      
                                        <th colspan='".$count_metodos."'>Elementos a Analizar</th>
                                     </tr>
                                    <tr class='table-secondary' justify-content: center;>   
                                        <th scope='col1'>Batch</th>   
                                        <th scope='col1'>De la muestra</th>
                                        <th scope='col1'>A la muestra</th>
                                        <th scope='col1'>Total muestras</th>";                          
                                        while ($fila_met = $datos_metodos->fetch_assoc()) {
                                            $html_det.="<th align='center'>".$fila_met['metodo']."</th>";
                                            $fila[$pos] = $fila_met['metodo'];
                                       }                                                                
                                    $html_det.="</tr>
                               </thead>
                               <tbody>";
                               
                               while ($fila = $datos_orden_encab->fetch_assoc()) {
                                   $po = 1;
                                   $trn_id_sig = $fila['trn_id'];
                                   $html_det.="<tr>";
                                      $html_det.="<td style='display:none;'>".$fila['trn_id']."</td>";
                                      $html_det.="<td>".$fila['folio_interno']."</td>";
                                      $html_det.="<td>".$fila['folio_inicial']."</td>";
                                      $html_det.="<td>".$fila['folio_final']."</td>";
                                      $html_det.="<td>".$fila['cantidad']."</td>";
                                       
                                      $datos_metodos = $mysqli->query("SELECT 
                                                        DISTINCT me.nombre AS metodo, om.metodo_id                                                 
                                                    FROM
                                                     `ordenes_metodos_lista` om
                                                     LEFT JOIN arg_metodos me
                                                        ON me.metodo_id = om.metodo_id                                          
                                                    WHERE om.trn_id_orden = ".$trn_id
                                            ) or die(mysqli_error());            
                                      
                                      while ($fila_metodo = $datos_metodos->fetch_assoc()) {  
                                            $met = $fila_metodo['metodo_id'];
                                            $detalle_metodos = $mysqli->query("SELECT
                                        	                                      COUNT(om.metodo_id) as existe
                                                                               FROM
                                                                            ordenes_metodos_lista om
                                                                           WHERE om.trn_id_orden = ".$trn_id." 
                                                                            AND om.trn_id_rel = ".$fila['trn_id']." 
                                                                            AND metodo_id = ".$met
                                                                        ) or die(mysqli_error());
                                                              
                                           $detalle_met = $detalle_metodos->fetch_assoc();
                                           $existe = $detalle_met['existe'];
                                           if($existe>0){
                                                $html_det.="<td align='center'>X</td>";
                                           }
                                           else{
                                                $html_det.="<td align='center'></td>";
                                           }
                                      }                                                                  
                                   $html_det.= "</tr>";
                               }
                               $html_det.="<td colspan='3'><strong>TOTAL MUESTRAS: </strong></td>";
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
    