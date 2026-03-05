   <? // include "connections/config.php";
        $trn_id_abs = $_GET['trn_id_abs'];
        $metodo_id_abs = $_GET['metodo_id_abs'];
        $u_id_abs = $_SESSION['u_id'];
        $unidad_id = $_GET['unidad_id'];
        $_SESSION['unidad_id'] = $unidad_id;
   ?>
     <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">     
 <script>
 
       function enviar_correo($trn_id)
            {
                 var trn_id = $trn_id;
                 var enviar_email = '<?php echo "\ enviarEmail.php?trn_id="?>'+trn_id;
                 window.location.href = enviar_email;
            }
            
       function actualizar_lib(unidad_id)
            {
                var unidad_id = unidad_id;                
                var print_d = '<?php echo "\seguimiento_ordenes.php?unidad_id="?>'+unidad_id;                
                window.location.href = print_d;
            }
            
             
       function liberar_res(trn_id_l, metodo_id_l, u_id_l, unidad_id_l){           
            var trn_id_a    = trn_id_l;
            var metodo_id_a = metodo_id_l;
            var u_id_a      = u_id_l;
            var unidad_id_a = unidad_id_l;
            
            /*alert(trn_id_a);
            alert(metodo_id_l);
            alert(u_id_l);
            alert(unidad_id_l);*/
            $('#progress_modal').modal('show');
            alert('Liberando Orden');
            $.ajax({
                		url: 'liberar_resultados.php' ,
                		type: 'POST' ,
                		dataType: 'html',
                		data: {trn_id_a:trn_id_a, metodo_id_a:metodo_id_a, u_id_a:u_id_a, unidad_id_a:unidad_id_a},
                	})
                	.done(function(respuesta){  
                           alert(respuesta);                     
                })
            actualizar_lib(unidad_id_l); 
        }
        
        function deshacer_rev(trn_id_l, metodo_id_l, u_id_l, unidad_id_l){           
            var trn_id_a    = trn_id_l;
            var metodo_id_a = metodo_id_l;
            var u_id_a      = u_id_l;
            var unidad_id_a = unidad_id_l;
            
            //alert(trn_id_a);
            $.ajax({
                		url: 'deshacer_revision.php' ,
                		type: 'POST' ,
                		dataType: 'html',
                		data: {trn_id_a:trn_id_a, metodo_id_a:metodo_id_a, u_id_a:u_id_a},
                	})
                	.done(function(respuesta){  
                           alert(respuesta);                     
                })
            actualizar_lib(unidad_id_l); 
        }
        
        function deshacer_rev_solidas(trn_id_sol, metodo_id_sol, u_id_sol, unidad_id_sol){           
            var trn_id_so    = trn_id_sol;
            var metodo_id_so = metodo_id_sol;
            var u_id_so      = u_id_sol;
            var unidad_id_so = unidad_id_sol;
            
            //alert(trn_id_a);
            $.ajax({
                		url: 'deshacer_revision_sol.php' ,
                		type: 'POST' ,
                		dataType: 'html',
                		data: {trn_id_sol:trn_id_sol, metodo_id_sol:metodo_id_sol, u_id_sol:u_id_sol},
                	})
                	.done(function(respuesta){  
                           alert(respuesta);                     
                })
            actualizar_lib(unidad_id_so); 
        }
      
       function habilitar_boton(){  
            const btncompra = document.getElementById('button_deshacer');
            btncompra.disabled = 0;
       } 
       
            
      /* function liberar_res(trn_id_l, metodo_id_l, u_id_l, unidad_id_l){           
            var trn_id_a    = trn_id_l;
            var metodo_id_a = metodo_id_l;
            var u_id_a      = u_id_l;
            var unidad_id_a = unidad_id_l;
            
           /* alert(trn_id_a);
            alert(metodo_id_l);
            alert(u_id_l);
            alert(unidad_id_l);*/
            /*$.ajax({
                		url: 'liberar_orden.php' ,
                		type: 'POST' ,
                		dataType: 'html',
                		data: {trn_id_a:trn_id_a, metodo_id_a:metodo_id_a, u_id_a:u_id_a, unidad_id_a:unidad_id_a},
                	})
                	.done(function(respuesta){  */            	   
                           //alert(respuesta);
                              
                           // var print_d = '<?php echo "\liberar_orden.php?trn_id_a="?>'+trn_id_a+'&metodo_id_a='+metodo_id_a+'&u_id_a='+u_id_a;                
                         //   window.location.href = print_d;
                                                
              //  })
            
       // }
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


<!-- Modal PROGRESS-->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="progress_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Liberando Orden</h5> 
        <input type="text"  name="mina_abs_esp" id="mina_abs_esp" size=20 style="width:125px; color:#996633"  disabled />  
      </div>
      <div class="modal-body">
        <div class="text-center">
            <h4>Liberando orden, por favor espere...</h4>        
            <img  src="images\upload.gif">          
        </div>
      </div>
    </div>
  </div>
</div>

<?php
if (isset($_GET['trn_id_abs'])){
    $metodo_id = $_GET['metodo_id_abs'];    
?>
    <div class="container">
           <br />
           <br />                
                
             
    <?
        $datos_orden = $mysqli->query("SELECT
                                            un.nombre AS unidad, ord.folio, ord.fecha_inicio, ord.hora, us.nombre AS usuario, det.folio_interno
                                            ,(CASE WHEN ord.trn_id_rel <> 0 THEN 1 ELSE 0 END) AS reensaye
                                            ,ord.tipo
                                       FROM 
                                       `arg_ordenes_detalle` det
                                       LEFT JOIN arg_ordenes ord
                                            ON ord.trn_id = det.trn_id_rel                                     
                                       LEFT JOIN arg_empr_unidades AS un
                                            ON un.unidad_id = ord.unidad_id
                                       LEFT JOIN arg_usuarios us
                                            ON us.u_id = ord.usuario_id
                                       WHERE det.trn_id = ".$trn_id_abs
                                   ) or die(mysqli_error());               
    $orden_encabezado = $datos_orden->fetch_assoc(); 
    
    $datos_metodo = $mysqli->query("SELECT nombre, elemento FROM `arg_metodos` WHERE metodo_id = ".$metodo_id) or die(mysqli_error());               
    $metodo = $datos_metodo->fetch_assoc(); 
    $metodo_nombre = $metodo['nombre'];
    $elemento      = $metodo['elemento'];
    
    if ($metodo_id == 3 || $metodo_id == 6 || $metodo_id == 24){
        
        $existe_revision = $mysqli->query("SELECT COUNT(*) AS total_revision
                                           FROM 
                                            `arg_muestras_resultados`
                                           WHERE `trn_id` = ".$trn_id_abs." 
                                             AND metodo_id = ".$metodo_id." 
                                             AND reensaye = 0"
                                   ) or die(mysqli_error());               
        $existe_revi = $existe_revision->fetch_assoc();
        $existe_rev = $existe_revi['total_revision'];
        
    }
    ?>
    <div class="col-2 col-md-2 col-lg-2">
                    <button type='button' class='btn btn-primary' onclick='liberar_res(<? echo $trn_id_abs.", ".$metodo_id.", ".$u_id_abs.", ".$unidad_id?>)' >
                        <span class='fa fa-envelope-o fa-2x'> Liberar </span>
                    </button>
                </div>                
                  
                <?
                 if ($orden_encabezado['tipo'] == 2){ 
                 ?> 
                    <div class="col-1 col-md-1 col-lg-1">
                        <button type='button' class='btn btn-danger' onclick='deshacer_rev(<? echo $trn_id_abs.", ".$metodo_id.", ".$u_id_abs.", ".$unidad_id?>)' >
                            <span class='fa fa-trash-o fa-2x'> Deshacer Lectura </span>
                        </button>
                    </div>              
                 <?
                   }
                   
                 if ($existe_rev == 0){ 
                 ?>                 
                   <div class="col-1 col-md-1 col-lg-1">
                        <button type='button' id='button_deshacer' class='btn btn-danger' onclick='deshacer_rev_solidas(<? echo $trn_id_abs.", ".$metodo_id.", ".$u_id_abs.", ".$unidad_id?>)' >
                            <span class='fa fa-trash-o fa-2x'> Deshacer Revisión </span>
                        </button>
                    </div>                 
                <br/>                
                <?
                }
    
    ///REVISANDO TIPO DE ORDEN (SOLIDOS/SOLUCIONES)
    if ($orden_encabezado['tipo'] == 2){ 
            //ORDENES DE SOLUCIONES
            $hoy = date("Y-m-d H:i:s");                 
            $html_en = "<table class='table table-striped' id='encabezado'>
                        <thead>
                            <tr class='table-info'>                                  
                                <th scope='col'>Batch: ".$orden_encabezado['folio_interno']."</th>
                                <th scope='col'>Método: ".$metodo_nombre."</th>
                                <th scope='col'>Fecha Orden: ".$orden_encabezado['fecha_inicio']."</th>
                                <th scope='col'>Fecha Revisión: ".$hoy."</th>                                    
                            </tr>
                            <tr class='table-info'>            
                                <th scope='col'>Usuario: ".$orden_encabezado['usuario']."</th>
                                <th scope='col'>Departamento: Laboratorio</th>
                                <th scope='col'>Revisión de Absorción Atómica</th>
                                <th scope='col'>Unidad: ".$orden_encabezado['unidad']."</th>
                            </tr>";
            $html_en.="</thead></table>";
                  
            $html_det .= "<table class='table'  table-striped id='detalle_abs'>
                        <thead>   
                                <tr class='table-secondary' justify-content: left;>
                                    <th scope='col1'>No</th>
                                    <th scope='col2'>Muestra</th>
                                    <th colspan='5'>".$elemento."</th>                                      
                                </tr>
                        </thead>
                        <tbody>"; 
            mysqli_multi_query ($mysqli, "CALL arg_prc_revisionAbsorcionSoluciones ($trn_id_abs,$metodo_id_abs)") OR DIE (mysqli_error($mysqli)); 
            $renglon = 1;
               if ($result = mysqli_store_result($mysqli)) {                
                  while ($row = mysqli_fetch_assoc($result)) { 
                      $html_det.="<tr >";
                        $html_det.="<td align='left' >".$renglon."</td>";
                        $html_det.="<td align='left' >".$row['folio']."</td>";
                        $html_det.="<td align='left' >".number_format($row['resultado'], 3, '.', '')."</td>"; 
                        $renglon++; 
                      $html_det.= "</tr>";
                  }
            }
            
        
    }
    else{
    
            if ($orden_encabezado['reensaye'] == 0){   
                mysqli_multi_query ($mysqli, "CALL arg_prc_revisionAbsorcion ($trn_id_abs,$metodo_id_abs)") OR DIE (mysqli_error($mysqli));
                
            }
            else{
                $origen_reen = $mysqli->query("SELECT 
                                                                        COUNT(*) AS existe_rech
                                                                   FROM 
                                                                       arg_muestras_reensaye mre
                                                                   WHERE mre.trn_id_rel = " . $trn_id_abs . " 
                                                                   AND mre.metodo_id = (CASE WHEN " . $metodo_id_abs . " = 0 
                                                                                        THEN mre.metodo_id ELSE " . $metodo_id_abs . " 
                                                                                        END) 
                                                                   AND mre.trn_id_muestra IN (SELECT trn_id_rel 
                                                                                          FROM muestras_recheck)"
                                    ) or die(mysqli_error($mysqli));
                $existe_reche = $origen_reen->fetch_assoc();
                $existe_rec = $existe_reche['existe_rech'];
                                    
                 if ($existe_rec == 0){
                    mysqli_multi_query ($mysqli, "CALL arg_prc_revisionAbsorcion_ree ($trn_id_abs,$metodo_id_abs)") OR DIE (mysqli_error($mysqli)); 
                 }else{
                    mysqli_multi_query ($mysqli, "CALL arg_prc_revisionAbsorcion_reeRech ($trn_id_abs,$metodo_id_abs)") OR DIE (mysqli_error($mysqli)); 
                 }
            }
                    ?>
                     <br/> <br/>
                     <div class="container">            
                      <?                 
                         $hoy = date("Y-m-d H:i:s");                 
                         $html_en = "<table class='table table-striped' id='encabezado'>
                                     <thead>
                                         <tr class='table-info'>                                  
                                              
                                             <th scope='col'>Batch: ".$orden_encabezado['folio_interno']."</th>
                                             <th scope='col'>Método: ".$metodo_nombre."</th>
                                              <th scope='col'>Fecha Orden: ".$orden_encabezado['fecha_inicio']."</th>
                                            <th scope='col'>Fecha Revisión: ".$hoy."</th>                                    
                                         </tr>
                                         <tr class='table-info'>            
                                            <th scope='col'>Usuario: ".$orden_encabezado['usuario']."</th>
                                            <th scope='col'>Departamento: Laboratorio</th>
                                            <th scope='col'>Revisión de Absorción Atómica</th>
                                             <th scope='col'>Unidad: ".$orden_encabezado['unidad']."</th>
                                          </tr>";
                          $html_en.="</thead></table>";
                  
                          $html_det .= "<table class='table' id='detalle_abs'>
                                        <thead>                   
                                            <tr class='table-secondary' justify-content: left;>
                                                <th scope='col1'>No</th>
                                                <th scope='col1'>Folio Interno</th>
                                                <th scope='col1'>Folio Geología</th> 
                                                <th scope='col1'>Tipo Control</th>  
                                                <th scope='col1'>Absorción</th> 
                                                <th scope='col1'>Porcentaje </th>
                                                <th scope='col1'>Minimo </th>
                                                <th scope='col1'>Máximo </th>
                                                <th scope='col1'>Reensaye </th>";                                        
                                            $html_det.="</tr>
                                       </thead>
                                       <tbody>"; 
               if ($result = mysqli_store_result($mysqli)) {                
                  while ($row = mysqli_fetch_assoc($result)) { 
                        if ($row['reensaye'] == 1 or $row['reensaye'] == 2 or $row['sobrelimite'] == 1){
                            $html_det.="<tr  style='color: #BD2819; background: #FDEBD0';>";
                                                $html_det.="<td align='left' >".$row['posicion']."</td>";
                                                $html_det.="<td align='left' >".$row['folio_interno']."</td>";
                                                $html_det.="<td align='left' >".$row['muestra_geologia']."</td>";
                                                //$html_det.="<td align='left' >".$row['tipo_id']."</td>";
                                                $html_det.="<td align='left' >".$row['control']."</td>";
                                                //$html_det.="<td align='left' >".$row['material_id']."</td>";
                                                $html_det.="<td align='left' >".$row['absorcion']."</td>";  
                                                $html_det.="<td align='left' >".$row['porcentaje']."</td>";
                                                $html_det.="<td align='left' >".$row['minimo']."</td>"; 
                                                $html_det.="<td align='left' >".$row['maximo']."</td>"; 
                                                $html_det.="<td align='left' >".$row['reensaye']."</td>";                      
                                         $html_det.= "</tr>";
                        }
                        else
                            {
                                $html_det.="<tr>";
                                                $html_det.="<td align='left' >".$row['posicion']."</td>";
                                                $html_det.="<td align='left' >".$row['folio_interno']."</td>";
                                                $html_det.="<td align='left' >".$row['muestra_geologia']."</td>";
                                                //$html_det.="<td align='left' >".$row['tipo_id']."</td>";
                                                $html_det.="<td align='left' >".$row['control']."</td>";
                                                //$html_det.="<td align='left' >".$row['material_id']."</td>";
                                                $html_det.="<td align='left' >".$row['absorcion']."</td>";  
                                                $html_det.="<td align='left' >".$row['porcentaje']."</td>";
                                                $html_det.="<td align='left' >".$row['minimo']."</td>"; 
                                                $html_det.="<td align='left' >".$row['maximo']."</td>"; 
                                                $html_det.="<td align='left' >".$row['reensaye']."</td>";                      
                                         $html_det.= "</tr>";
                            }            
               }
            } 
        
    $html_det.="</tbody></table>";
    
  }  
    echo ("$html_en");
    echo ("$html_det");
    
   
    ?>
    </div>     
</div>
<?
}
?>                    
          

