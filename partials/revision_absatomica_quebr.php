   <?php  // include "connections/config.php";
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
          
            $('#progress_modal').modal('show');
           alert('Liberando Orden');
           $.ajax({
                		url: 'liberar_resultados_quebr.php' ,
                		type: 'POST' ,
                		dataType: 'html',
                		data: {trn_id_a:trn_id_a, metodo_id_a:metodo_id_a, u_id_a:u_id_a, unidad_id_a:unidad_id_a},
                	})
                	.done(function(respuesta){  
                           alert(respuesta);                     
                })
            actualizar_lib(unidad_id_l);
        }
            
 </script>
 
<style type="text/css">
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
                <div class="col-2 col-md-2 col-lg-2">
                    <button type='button' class='btn btn-primary' onclick='liberar_res(<?php  echo $trn_id_abs.", ".$metodo_id.", ".$u_id_abs.", ".$unidad_id?>)' >
                        <span class='fa fa-envelope-o fa-2x'> Liberar </span>
                    </button>
                </div>  
            <br />             
     <?php 
       $datos_orden = $mysqli->query("SELECT
                                            un.nombre AS unidad, ord.folio, date_format(ord.fecha, '%d-%m-%Y') AS fecha_inicio, ord.hora, us.nombre AS usuario, det.folio_interno
                                            ,(CASE WHEN ord.tipo = 0 THEN 1 ELSE 0 END) AS reensaye
                                            ,(CASE WHEN ord.tipo = 7 THEN 7 WHEN ord.tipo = 8 THEN 8 WHEN ord.tipo = 9 THEN 9 END ) AS tipo_orden
                                       FROM 
                                       `arg_ordenes_detalle` det
                                       LEFT JOIN arg_ordenes ord
                                            ON ord.trn_id = det.trn_id_rel                                     
                                       LEFT JOIN arg_empr_unidades AS un
                                            ON un.unidad_id = ord.unidad_id
                                       LEFT JOIN arg_usuarios us
                                            ON us.u_id = ord.usuario_id
                                       WHERE det.trn_id = ".$trn_id_abs
                                   ) or die(mysqli_error($mysqli));               
        $orden_encabezado = $datos_orden->fetch_assoc(); 
        $reensaye = $orden_encabezado['reensaye'];

        if($reensaye == 0){            
            $tipo_orden = $orden_encabezado['tipo_orden'];
        }
        else{
            $datos_orden_ree = $mysqli->query("SELECT
                                                    ord.trn_origen
                                                FROM 
                                                `arg_ordenes_detalle` det
                                                LEFT JOIN arg_ordenes ord
                                                    ON ord.trn_id = det.trn_id_rel
                                                WHERE det.trn_id = ".$trn_id_abs
                                            ) or die(mysqli_error($mysqli));               
            $datos_orden_reen = $datos_orden_ree->fetch_assoc(); 
            $tipo_orden_origen = $datos_orden_reen['trn_origen'];

            $datos_ree = $mysqli->query("SELECT
                                                    oor.tipo AS tipo_orden
                                                FROM 
                                                    arg_ordenes_detalle AS odet
                                                    LEFT JOIN arg_ordenes AS oor
                                                        ON oor.trn_id = odet.trn_id_rel
                                                WHERE odet.trn_id = ".$tipo_orden_origen
                                            ) or die(mysqli_error($mysqli));               
            $datos_reen = $datos_ree->fetch_assoc(); 
            $tipo_orden = $datos_reen['tipo_orden'];

        }
    
        $datos_metodo = $mysqli->query("SELECT nombre FROM `arg_metodos` WHERE metodo_id = ".$metodo_id) or die(mysqli_error($mysqli));               
        $metodo = $datos_metodo->fetch_assoc(); 
        $metodo_nombre = $metodo['nombre'];
    
      /*  $existe_revisi = $mysqli->query("SELECT COUNT(*) AS existe FROM `temp_controles` WHERE trn_id_batch = ".$trn_id_abs) or die(mysqli_error());               
        $existe_revision = $existe_revisi->fetch_assoc(); 
        $existe = $existe_revision['existe'];*/
        
        if ($metodo_id == 30){
            $result = $mysqli->query("SELECT
                                        hum.trn_id_batch,    
                                        hum.trn_id_rel,                                        
                                        mm.folio AS muestra,
                                        peso_charola,
                                        peso_humedo,
                                        peso_seco,
                                        porcentaje
                                    FROM
                                        `arg_ordenes_humedad` hum
                                    LEFT JOIN arg_ordenes_muestrasMetalurgia AS mm
                                    ON
                                        hum.trn_id_batch = mm.trn_id_rel 
                                        AND hum.trn_id_rel = mm.trn_id
                                    WHERE
                                        hum.trn_id_batch =  ".$trn_id_abs) or die(mysqli_error($mysqli));
             ?>
             <br/> <br/>
             <div class="container">            
              <?php                 
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
                                    <th scope='col'>Revisión de ".$metodo_nombre."</th>
                                     <th scope='col'>Unidad: ".$orden_encabezado['unidad']."</th>
                                  </tr>";
                  $html_en.="</thead></table>";
                  
                  $html_det .= "<table class='table' id='detalle_abs'>
                                <thead>                   
                                    <tr class='table-secondary' justify-content: left;>
                                        <th scope='col1'>Folio Muestra</th>
                                        <th scope='col1'>Peso Charola</th>
                                        
                                        <th scope='col1'>Peso húmedo </th>
                                        <th scope='col1'>Peso seco </th>
                                        <th scope='col1'>Porcentaje</th>";                                        
                        $html_det.="</tr>
                               </thead>
                               <tbody>";
        while ($fila = $result->fetch_assoc()) {  
            $html_det.= "<tr>";
               $html_det.="<td align='left' >".$fila['muestra']."</td>";               
               $html_det.="<td align='left' >".$fila['peso_charola']."</td>";               
               $html_det.="<td align='left' >".$fila['peso_humedo']."</td>";
               $html_det.="<td align='left' >".$fila['peso_seco']."</td>";
               $html_det.="<td align='left' >".$fila['porcentaje']."</td>";               
           $html_det.= "</tr>";            
         }       
       $html_det.="</tbody></table>";  
     }
     elseif ($metodo_id == 28){//Metodo impurezas
            mysqli_multi_query ($mysqli, "CALL arg_prc_revisionAbsorcionCian ($trn_id_abs,$metodo_id_abs)") OR DIE (mysqli_error($mysqli));            
             ?>
             <br/> <br/>
             <div class="container">            
              <?php                 
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
                                    <th scope='col'>Revisión de ".$metodo_nombre."</th>
                                     <th scope='col'>Unidad: ".$orden_encabezado['unidad']."</th>
                                  </tr>";
                  $html_en.="</thead></table>";
                  if ($tipo_orden == 9){ // Barras
                    $unidad_medida = '%';
                  }
                  else{
                    $unidad_medida = 'g/ton';
                  }
                  
                  $html_det .= "<table class='table' id='detalle_abs'>
                                <thead>                   
                                    <tr class='table-secondary' justify-content: left;>
                                        <th scope='col1'>Folio Muestra</th>
                                        <th scope='col1'>peso mg</th>                                        
                                        <th scope='col1'>Cu ".$unidad_medida."</th>
                                        <th scope='col1'>Fe ".$unidad_medida."</th>
                                        <th scope='col1'>Zn ".$unidad_medida."</th>                                        
                                        <th scope='col1'>Pb ".$unidad_medida."</th>
                                        <th scope='col1'>Cd ".$unidad_medida."</th>";                                        
                        $html_det.="</tr>
                               </thead>
                               <tbody>";
        if($result = mysqli_store_result($mysqli)){
            while ($fila = mysqli_fetch_assoc($result)) { 
                $html_det.= "<tr>";
                   $html_det.="<td align='left' >".$fila['muestra']."</td>";               
                   $html_det.="<td align='left' >".$fila['peso']."</td>";
                   $html_det.="<td align='left' >".$fila['cu']."</td>";
                   $html_det.="<td align='left' >".$fila['fe']."</td>";
                   $html_det.="<td align='left' >".$fila['zn']."</td>";
                   $html_det.="<td align='left' >".$fila['pb']."</td>"; 
                   $html_det.="<td align='left' >".$fila['cd']."</td>";            
               $html_det.= "</tr>";            
            } 
       }     
       $html_det.="</tbody></table>";  
     }
     elseif ($metodo_id == 29){
            $result = $mysqli->query("SELECT
                                        hum.trn_id_batch,    
                                        hum.trn_id_rel,                                        
                                        mm.folio AS muestra,
                                        peso_charola,
                                        peso,
                                        densidad
                                    FROM
                                        `arg_ordenes_densidad` hum
                                    LEFT JOIN arg_ordenes_muestrasMetalurgia AS mm
                                    ON
                                        hum.trn_id_batch = mm.trn_id_rel 
                                        AND hum.trn_id_rel = mm.trn_id
                                    WHERE
                                        hum.trn_id_batch =  ".$trn_id_abs) or die(mysqli_error($mysqli));
             ?>
             <br/> <br/>
             <div class="container">            
              <?php                 
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
                                    <th scope='col'>Revisión de ".$metodo_nombre."</th>
                                     <th scope='col'>Unidad: ".$orden_encabezado['unidad']."</th>
                                  </tr>";
                  $html_en.="</thead></table>";
                  
                  $html_det .= "<table class='table' id='detalle_abs'>
                                <thead>                   
                                    <tr class='table-secondary' justify-content: left;>
                                        <th scope='col1'>Folio Muestra</th>
                                        <th scope='col1'>Factor</th>                                        
                                        <th scope='col1'>Peso </th>
                                        <th scope='col1'>Densidad</th>";                                        
                        $html_det.="</tr>
                               </thead>
                               <tbody>";
        while ($fila = $result->fetch_assoc()) {  
            $html_det.= "<tr>";
               $html_det.="<td align='left' >".$fila['muestra']."</td>";               
               $html_det.="<td align='left' >".$fila['peso_charola']."</td>";
               $html_det.="<td align='left' >".$fila['peso']."</td>";
               $html_det.="<td align='left' >".$fila['densidad']."</td>";             
           $html_det.= "</tr>";            
         }       
       $html_det.="</tbody></table>";  
     } // Fin método 29 Densidad
      elseif ($metodo_id == 5){
            $result = $mysqli->query("SELECT
                                        hum.trn_id_batch,    
                                        hum.trn_id_rel,                                        
                                        mm.folio AS muestra,
                                        p_malla12 AS pp_malla12,
                                        p_malla38 AS pp_malla38,
                                        p_malla14 AS pp_malla14,
                                        p_malla10 AS pp_malla10,
                                        p_malla50 AS pp_malla50,
                                        p_malla100 AS pp_malla100,
                                        p_mallamenos100 AS pp_mallamenos100,
                                        
                                        (100-((p_malla12/total_mallas)*100)) AS p_malla12,
                                        (100-(p_malla12/total_mallas)*100)-((p_malla38/total_mallas)*100)
                                         AS p_malla38,
                                        (100-(p_malla12/total_mallas)*100)-((p_malla38/total_mallas)*100)-((p_malla14/total_mallas)*100) 
                                         AS p_malla14,
                                        (100-(p_malla12/total_mallas)*100)-((p_malla38/total_mallas)*100)-((p_malla14/total_mallas)*100)-((p_malla10/total_mallas)*100) 
                                        AS p_malla10,
                                        (100-(p_malla12/total_mallas)*100)-((p_malla38/total_mallas)*100)-((p_malla14/total_mallas)*100)-((p_malla10/total_mallas)*100)-((p_malla50/total_mallas)*100) 
                                        AS p_malla50,
                                        (100-(p_malla12/total_mallas)*100)-((p_malla38/total_mallas)*100)-((p_malla14/total_mallas)*100)-((p_malla10/total_mallas)*100)-((p_malla50/total_mallas)*100)-((p_malla100/total_mallas)*100) 
                                        AS p_malla100,
                                        (100-(p_malla12/total_mallas)*100)-((p_malla38/total_mallas)*100)-((p_malla14/total_mallas)*100)-((p_malla10/total_mallas)*100)-((p_malla50/total_mallas)*100)-((p_malla100/total_mallas)*100)-((p_mallamenos100/total_mallas)*100)
                                        AS p_mallamenos100                                        
                                      FROM
                                        `arg_ordenes_granulometria` hum
                                        LEFT JOIN arg_ordenes_muestrasMetalurgia AS mm
                                            ON hum.trn_id_batch = mm.trn_id_rel 
                                            AND hum.trn_id_rel = mm.trn_id
                                      WHERE
                                          hum.trn_id_batch = ".$trn_id_abs) or die(mysqli_error($mysqli));
             ?>
             <br/> <br/>
             <div class="container">            
              <?php                 
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
                                    <th scope='col'>Revisión de ".$metodo_nombre."</th>
                                     <th scope='col'>Unidad: ".$orden_encabezado['unidad']."</th>
                                  </tr>";
                  $html_en.="</thead></table>";
                  
                  $html_det .= "<table class='table' id='detalle_abs'>
                                <thead>                   
                                    <tr class='table-secondary' justify-content: left;>
                                        <th scope='col1'>Folio Muestra</th>
                                        <th scope='col1'>Peso</th>
                                        <th scope='col1'>% M+1/2</th>
                                        <th scope='col1'>Peso</th>                                  
                                        <th scope='col1'>% M+3/8</th>
                                        <th scope='col1'>Peso</th>
                                        <th scope='col1'>% M+1/4</th>
                                        <th scope='col1'>Peso</th>
                                        <th scope='col1'>% M+10</th>
                                        <th scope='col1'>Peso</th>
                                        <th scope='col1'>% M+50</th>
                                        <th scope='col1'>Peso</th>
                                        <th scope='col1'>% M+100</th>
                                        <th scope='col1'>Peso</th>
                                        <th scope='col1'>% M-100</th>
                                        ";                                        
                        $html_det.="</tr>
                               </thead>
                               <tbody>";
        while ($fila = $result->fetch_assoc()) {  
            $html_det.= "<tr>";
               $html_det.="<td align='left' >".$fila['muestra']."</td>";
               $html_det.="<td align='left' >".number_format($fila['pp_malla12'], 2, '.')."</td>";           
               $html_det.="<td align='left' >".number_format($fila['p_malla12'], 3, '.')."</td>";
               $html_det.="<td align='left' >".number_format($fila['pp_malla38'], 2, '.')."</td>";
               $html_det.="<td align='left' >".number_format($fila['p_malla38'], 3, '.')."</td>"; 
               $html_det.="<td align='left' >".number_format($fila['pp_malla14'], 2, '.')."</td>";
               $html_det.="<td align='left' >".number_format($fila['p_malla14'], 3, '.')."</td>";
               $html_det.="<td align='left' >".number_format($fila['pp_malla10'], 2, '.')."</td>";
               $html_det.="<td align='left' >".number_format($fila['p_malla10'], 3, '.')."</td>";
               $html_det.="<td align='left' >".number_format($fila['pp_malla50'], 2, '.')."</td>";
               $html_det.="<td align='left' >".number_format($fila['p_malla50'], 3, '.')."</td>";
               $html_det.="<td align='left' >".number_format($fila['pp_malla100'], 2, '.')."</td>";
               $html_det.="<td align='left' >".number_format($fila['p_malla100'], 3, '.')."</td>";
               $html_det.="<td align='left' >".number_format($fila['pp_mallamenos100'], 2, '.')."</td>";
               $html_det.="<td align='left' >".number_format($fila['p_mallamenos100'], 3, '.')."</td>";
           $html_det.= "</tr>";            
         }       
       $html_det.="</tbody></table>";  
     }
     elseif ($metodo_id == 33){//Cianurado
        if ($reensaye == 0){
            
            echo 'entraa';
            mysqli_multi_query ($mysqli, "CALL arg_prc_revisionAbsorcionCian ($trn_id_abs,$metodo_id_abs)") OR DIE (mysqli_error($mysqli)); 
        }
        else{
            echo 'entro';
            mysqli_multi_query ($mysqli, "CALL arg_prc_revisionAbsorcionCian_Ree ($trn_id_abs,$metodo_id_abs)") OR DIE (mysqli_error($mysqli)); 
        }
            $renglon = 1;
             ?>
             <br/> <br/>
             <div class="container">            
              <?php                 
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
                                    <th scope='col'>Revisión de ".$metodo_nombre."</th>
                                     <th scope='col'>Unidad: ".$orden_encabezado['unidad']."</th>
                                  </tr>";
                  $html_en.="</thead></table>";
                  
                  $html_det .= "<table class='table' id='detalle_abs'>
                                <thead>                   
                                    <tr class='table-secondary' justify-content: left;>
                                        <th scope='col1'>No</th>
                                        <th scope='col1'>Folio Muestra</th>
                                        <th scope='col1'>Control</th>
                                        <th scope='col1'>Absorcion Au</th>                                        
                                        <th scope='col1'>Absorcion Ag </th>
                                        <th scope='col1'>Absorcion Cu</th>
                                        <th scope='col1'>Max</th>
                                        <th scope='col1'>Min</th>
                                        <th scope='col1'>Reensaye</th>";                                        
                        $html_det.="</tr>
                               </thead>
                               <tbody>";
        if ($result = mysqli_store_result($mysqli)) {   
            $prom_cian_au  = 0;
            $total_cian_au = 0;
            $prom_cian_ag  = 0;
            $total_cian_ag = 0;
            $prom_cian_cu  = 0;
            $total_cian_cu = 0;
            while ($fila = mysqli_fetch_assoc($result)) {
                if ($renglon == 1){
                    $folio_origen = $fila['folio_interno'];
                }
                if ($fila['reensaye'] == 1 or $fila['reensaye'] == 2){
                    $html_det.="<tr style='color: #BD2819; background: #FDEBD0';>";
                }
                else{
                    $html_det.= "<tr>";               
                }
                if ($fila['tipo_id'] == 4 || $fila['tipo_id'] == 0){
                    $total_cian_au = ($total_cian_au+$fila['absorcion']);
                    $total_cian_ag = ($total_cian_ag+$fila['absorcion_ag']);
                    $total_cian_cu = ($total_cian_cu+$fila['absorcion_cu']);
                }  
                $html_det.="<td align='left' >".$renglon."</td>";     
                $html_det.="<td align='left' >".$fila['folio_interno']."</td>"; 
                $html_det.="<td align='left' >".$fila['nombre']."</td>";      
                $html_det.="<td align='left' >".number_format($fila['absorcion'], 2, '.')."</td>";    
                $html_det.="<td align='left' >".number_format($fila['absorcion_ag'], 2, '.')."</td>";    
                $html_det.="<td align='left' >".number_format($fila['absorcion_cu'], 2, '.')."</td>";    
                $html_det.="<td align='left' >".number_format($fila['minimo'], 3, '.')."</td>";    
                $html_det.="<td align='left' >".number_format($fila['maximo'], 3, '.')."</td>";   
                $html_det.="<td align='left' >".$fila['reensaye']."</td>";
               $html_det.= "</tr>";
               $renglon++;        
            }
            $prom_cian_au = ($total_cian_au/5);
            $prom_cian_ag = ($total_cian_ag/5);
            $prom_cian_cu = ($total_cian_cu/5);
            $html_det.= "<tr>";
            $html_det.= "<td align='left' >".$renglon."</td>";    
            $html_det.= "<td align='left' >Promedio</td>"; 
            $html_det.= "<td align='left' ></td>";    
            $html_det.="<td align='left' >".$prom_cian_au."</td>";
            $html_det.="<td align='left' >".$prom_cian_ag."</td>";  
            $html_det.="<td align='left' >".$prom_cian_cu."</td>";    
            $html_det.= "</tr>";
       }       
       $html_det.="</tbody></table>";  
     }  // fin de metodo cianurado 33
     
     elseif ($metodo_id == 9  || $metodo_id == 35){//act_cab 9      
            mysqli_multi_query ($mysqli, "CALL arg_prc_revisionAbsorcionCian ($trn_id_abs,$metodo_id_abs)") OR DIE (mysqli_error($mysqli)); 
        
            $renglon = 1;
             ?>
             <br/> <br/>
             <div class="container">            
              <?php                 
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
                                    <th scope='col'>Revisión de ".$metodo_nombre."</th>
                                     <th scope='col'>Unidad: ".$orden_encabezado['unidad']."</th>
                                  </tr>";
                  $html_en.="</thead></table>";
                  
                  $html_det .= "<table class='table' id='detalle_abs'>
                                <thead>                   
                                    <tr class='table-secondary' justify-content: left;>
                                        <th scope='col1'>No</th>
                                        <th scope='col1'>Folio Muestra</th>
                                        <th scope='col1'>Control</th>
                                        <th scope='col1'>Absorcion Au</th>";                                        
                        $html_det.="</tr>
                               </thead>
                               <tbody>";

        if ($result = mysqli_store_result($mysqli)) {   
         
            while ($fila = mysqli_fetch_assoc($result)) {
               
                $folio_interno = $fila['muestra'];
                $control = $fila['control'];
               
               $html_det.= "<tr>";
               $html_det.="<td align='left' >".$renglon."</td>";
               $html_det.="<td align='left' >".$folio_interno."</td>";
               $html_det.="<td align='left' >".$control."</td>";
               $html_det.="<td align='left' >".number_format($fila['au'], 2, '.')."</td>";
               $html_det.= "</tr>";
               $renglon++;        
            }
          
       }       
       $html_det.="</tbody></table>";  
     }  // fin de metodo act_cb 9

     //Método 27 Au_LibreAA
     elseif ($metodo_id == 27){//Cianurado
     
            mysqli_multi_query ($mysqli, "CALL arg_prc_revisionAbsorcionCian ($trn_id_abs,$metodo_id_abs)") OR DIE (mysqli_error($mysqli)); 
            $renglon = 1;
             ?>
             <br/> <br/>
             <div class="container">            
              <?php                 
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
                                    <th scope='col'>Revisión de ".$metodo_nombre."</th>
                                     <th scope='col'>Unidad: ".$orden_encabezado['unidad']."</th>
                                  </tr>";
                  $html_en.="</thead></table>";
                  
                  $html_det .= "<table class='table' id='detalle_abs'>
                                <thead>                   
                                    <tr class='table-secondary' justify-content: left;>
                                        <th scope='col1'>No</th>
                                        <th scope='col1'>Folio Muestra/Control</th>
                                        <th scope='col1'>Control</th>
                                        <th scope='col1'>Peso</th>  
                                        <th scope='col1'>Incuarte</th>  
                                        <th scope='col1'>Doré</th>  
                                        <th scope='col1'>Abs Au</th>                                        
                                        <th scope='col1'>Abs Ag </th>
                                                                               
                                        <th scope='col1'>Au_mg</th>                                                                               
                                        <th scope='col1'>Ag_mg</th>
                                        <th scope='col1'>%mall</th>
                                        
                                        <th scope='col1'>Au_gton</th>                                        
                                        <th scope='col1'>Ag_gton</th>  
                                        
                                        <th scope='col1'>Max</th>
                                        <th scope='col1'>Min</th>
                                        <th scope='col1'>Ree</th>";                                        
                        $html_det.="</tr>
                               </thead>
                               <tbody>";
        if ($result = mysqli_store_result($mysqli)) {   
            $prom_cian_au  = 0;
            $total_cian_au = 0;
            $prom_cian_ag  = 0;
            $total_cian_ag = 0;
            $prom_cian_cu  = 0;
            $total_cian_cu = 0;
            while ($fila = mysqli_fetch_assoc($result)) {
               /* if ($renglon == 1){
                    $folio_origen = $fila['folio_interno'];
                }*/
                if ($fila['reensaye'] == 1 or $fila['reensaye'] == 2){
                    $html_det.="<tr style='color: #BD2819; background: #FDEBD0';>";
                }
                else{
                    $html_det.= "<tr>";               
                }
                /*if ($fila['tipo_id'] == 4 || $fila['tipo_id'] == 0){
                    $total_cian_au = ($total_cian_au+$fila['absorcion']);
                    $total_cian_ag = ($total_cian_ag+$fila['absorcion_ag']);
                    $total_cian_cu = ($total_cian_cu+$fila['absorcion_cu']);
                } */ 
                $html_det.="<td align='left' >".$renglon."</td>";     
                $html_det.="<td align='left' >".$fila['folio_interno']."</td>"; 
                $html_det.="<td align='left' >".$fila['nombre']."</td>";      
                $html_det.="<td align='left' >".number_format($fila['peso'], 2, '.')."</td>";        
                $html_det.="<td align='left' >".number_format($fila['incuarte'], 2, '.')."</td>";        
                $html_det.="<td align='left' >".number_format($fila['dore'], 2, '.')."</td>";        
                $html_det.="<td align='left' >".number_format($fila['absorcion'], 2, '.')."</td>";    
                $html_det.="<td align='left' >".number_format($fila['absorcion_ag'], 2, '.')."</td>";  
                 
                $html_det.="<td align='left' >".number_format($fila['au_mg_malla200'], 2, '.')."</td>";
                $html_det.="<td align='left' >".number_format($fila['ag_mg_malla'], 2, '.')."</td>"; 
                   
                $html_det.="<td align='left' >".number_format($fila['porc_malla200'], 3, '.')."</td>"; 
                
                $html_det.="<td align='left' >".number_format($fila['abs_au'], 2, '.')."</td>";    
                $html_det.="<td align='left' >".number_format($fila['abs_ag'], 2, '.')."</td>"; 
                $html_det.="<td align='left' >".number_format($fila['minimo'], 3, '.')."</td>";    
                $html_det.="<td align='left' >".number_format($fila['maximo'], 3, '.')."</td>";   
                $html_det.="<td align='left' >".$fila['reensaye']."</td>";
               $html_det.= "</tr>";
               $renglon++;        
            }
           /* $prom_cian_au = ($total_cian_au/5);
            $prom_cian_ag = ($total_cian_ag/5);
            $prom_cian_cu = ($total_cian_cu/5);
            $html_det.= "<tr>";
            $html_det.= "<td align='left' >".$renglon."</td>";    
            $html_det.= "<td align='left' >Promedio</td>"; 
            $html_det.= "<td align='left' ></td>";    
            $html_det.="<td align='left' >".$prom_cian_au."</td>";
            $html_det.="<td align='left' >".$prom_cian_ag."</td>";  
            $html_det.="<td align='left' >".$prom_cian_cu."</td>";    
            $html_det.= "</tr>";*/
       }       
       $html_det.="</tbody></table>";  
     }  // fin de metodo Au_LibreAA 27
     
      elseif ($metodo_id == 2 and $tipo_orden == 7){//EF-Grav2 2 
        if ($reensaye == 0){            
            mysqli_multi_query ($mysqli, "CALL arg_prc_revisionAbsorcionCian ($trn_id_abs,$metodo_id_abs)") OR DIE (mysqli_error($mysqli)); 
        }   
        else{            
            mysqli_multi_query ($mysqli, "CALL arg_prc_revisionAbsorcionCian_Ree ($trn_id_abs,$metodo_id_abs)") OR DIE (mysqli_error($mysqli)); 
        } 
            $renglon = 1;
             ?>
             <br/> <br/>
             <div class="container">            
              <?php                 
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
                                    <th scope='col'>Revisión de ".$metodo_nombre."</th>
                                     <th scope='col'>Unidad: ".$orden_encabezado['unidad']."</th>
                                  </tr>";
                  $html_en.="</thead></table>";
                  
                  $html_det = "<table class='table' id='detalle_abs'>
                                <thead>                   
                                    <tr class='table-secondary' justify-content: left;>
                                        <th scope='col1'>No.</th>
                                        <th scope='col1'>Folio</th>
                                        
                                        <th scope='col1'>peso</th>  
                                        <th scope='col1'>Incu</th>                                       
                                        <th scope='col1'>Peso Doré</th>                                       
                                        <th scope='col1'>Peso Oro</th>
                                        
                                        <th scope='col1'>Au_kgton</th>                                        
                                        <th scope='col1'>Ag_kgton</th> 
                                        
                                        <th scope='col1'>Prom Au_kgton</th>                                        
                                        <th scope='col1'>Prom Ag_kgton</th>   
                                        
                                        <th scope='col1'>Min Au</th>
                                        <th scope='col1'>Max Au</th>

                                        <th scope='col1'>Min Ag</th>
                                        <th scope='col1'>Max Ag</th>
                                        
                                        <th scope='col1'>%RSD Au</th>
                                        <th scope='col1'>%RSD Ag</th>

                                        <th scope='col1'>Reen</th>";                                        
                        $html_det.="</tr>
                               </thead>
                               <tbody>";
        if ($result = mysqli_store_result($mysqli)) {   
            while ($fila = mysqli_fetch_assoc($result)) {
               if ($fila['reensaye'] == 1 or $fila['reensaye'] == 2){
                    $html_det.="<tr style='color: #BD2819; background: #FDEBD0';>";
                }
                elseif ($fila['reensaye'] == 4 or $fila['reensaye'] == 5){ // % RSD
                    $html_det.="<tr style='color: #BD2519; background: #c9c1b3';>";      
                }
                else{
                    $html_det.= "<tr>";
                }
                /*if ($fila['tipo_id'] == 4 || $fila['tipo_id'] == 0){
                    $total_cian_au = ($total_cian_au+$fila['absorcion']);
                    $total_cian_ag = ($total_cian_ag+$fila['absorcion_ag']);
                    $total_cian_cu = ($total_cian_cu+$fila['absorcion_cu']);
                } */ 
                $html_det.="<td align='left' >".$renglon."</td>";  
                //if ($fila['tipo_id'] == 0){   
                    $html_det.="<td align='left' >".$fila['folio_interno']."</td>"; 
                /*}
                else{
                    $html_det.="<td align='left' >".$fila['folio_interno']."</td>"; 
                }*/      
                
                $html_det.="<td align='left' >".number_format($fila['peso'], 3, '.')."</td>"; 
                $html_det.="<td align='left' >".number_format($fila['incuarte'], 3, '.')."</td>";   
                $html_det.="<td align='left' >".number_format($fila['dore'], 3, '.')."</td>"; 
                $html_det.="<td align='left' >".number_format($fila['peso_oro'], 3, '.')."</td>";  
               
                if ($fila['abs_au'] >= 0){
                    $html_det.="<td align='left' >".number_format($fila['abs_au'], 3, '.')."</td>";    
                    $html_det.="<td align='left' >".number_format($fila['abs_ag'], 3, '.')."</td>";
                }   
                else{
                    $html_det.="<td align='left' ></td>"; 
                    $html_det.="<td align='left' ></td>";
                }
                
                if ($fila['tipo_id'] == 0){
                    $html_det.="<td align='left' >".number_format($fila['au_mg_malla200'], 3, '.')."</td>";
                    $html_det.="<td align='left' >".number_format($fila['ag_mg_malla'], 3, '.')."</td>";
                }
                else{
                    $html_det.="<td align='left' ></td>"; 
                    $html_det.="<td align='left' ></td>";
                }
                $html_det.="<td align='left' >".number_format($fila['minimo'], 3, '.')."</td>";    
                $html_det.="<td align='left' >".number_format($fila['maximo'], 3, '.')."</td>";  
                
                $html_det.="<td align='left' >".number_format($fila['minimo_ag'], 3, '.')."</td>";    
                $html_det.="<td align='left' >".number_format($fila['maximo_ag'], 3, '.')."</td>";
               
                $html_det.="<td align='left' >".number_format($fila['rsd_au'], 1, '.')."</td>";
                $html_det.="<td align='left' >".number_format($fila['rsd_ag'], 1, '.')."</td>"; 
                $html_det.="<td align='left' >".$fila['reensaye']."</td>";
               $html_det.= "</tr>";
               $renglon++;        
            }        
            $html_det.= "</tr>";
       }       
       $html_det.="</tbody></table>";  
     }  // fin de metodo EF-Grav2 2 para barras y precipitado
     elseif ($metodo_id == 2 & ($tipo_orden == 8 || $tipo_orden == 9)) {//EF-Grav2 2     
            mysqli_multi_query ($mysqli, "CALL arg_prc_revisionAbsorcionCian ($trn_id_abs,$metodo_id_abs)") OR DIE (mysqli_error($mysqli)); 
            $renglon = 1;
             ?>
             <br/> <br/>
             <div class="container">            
              <?php                 
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
                                    <th scope='col'>Revisión de ".$metodo_nombre."</th>
                                     <th scope='col'>Unidad: ".$orden_encabezado['unidad']."</th>
                                  </tr>";
                  $html_en.="</thead></table>";
                  
                  $html_det .= "<table class='table' id='detalle_abs'>
                                <thead>                   
                                    <tr class='table-secondary' justify-content: left;>
                                        <th scope='col1'>No</th>
                                        <th scope='col1'>Folio</th>
                                        
                                        <th scope='col1'>Peso</th>  
                                        <th scope='col1'>Incu</th>                                       
                                        <th scope='col1'>Doré</th>                                       
                                        <th scope='col1'>Oro</th>
                                        
                                        <th scope='col1'>Au_g/ton</th>                                        
                                        <th scope='col1'>Ag_g/ton</th>
                                        <th scope='col1'>Au Prom g/ton</th>                                        
                                        <th scope='col1'>Ag Prom g/ton</th>
                                                                    
                                        <th scope='col1'>% Rec</th>            
                                        <th scope='col1'>Ley Au</th>
                                        <th scope='col1'>Ley Ag</th>
                                        
                                        <th scope='col1'>Max</th>
                                        <th scope='col1'>Min</th>
                                        <th scope='col1'>Reen</th>";                                        
                        $html_det.="</tr>
                               </thead>
                               <tbody>";
        if ($result = mysqli_store_result($mysqli)) {   
            while ($fila = mysqli_fetch_assoc($result)) {
               if ($fila['reensaye'] == 1 or $fila['reensaye'] == 2){
                    $html_det.="<tr style='color: #BD2819; background: #FDEBD0';>";
                }
                else{
                    $html_det.= "<tr>";               
                }
               
                $html_det.="<td align='left' >".$renglon."</td>";  
                $html_det.="<td align='left' >".$fila['folio_interno']."</td>"; 
                
                $html_det.="<td align='left' >".number_format($fila['peso'], 2, '.')."</td>"; 
                $html_det.="<td align='left' >".number_format($fila['incuarte'], 2, '.')."</td>";   
                $html_det.="<td align='left' >".number_format($fila['dore'], 2, '.')."</td>"; 
                $html_det.="<td align='left' >".number_format($fila['peso_oro'], 2, '.')."</td>";  
               
                 if ($fila['tipo_id'] == 0 or $fila['tipo_id'] == 4){
                    $html_det.="<td align='left' >".number_format($fila['abs_au'], 2, '.')."</td>";    
                    $html_det.="<td align='left' >".number_format($fila['abs_ag'], 2, '.')."</td>";                    
                   // $html_det.="<td align='left' >".number_format($fila['ag_g_tm'], 2, '.')."</td>";
                }   
                else{
                    $html_det.="<td align='left' ></td>"; 
                    $html_det.="<td align='left' ></td>";
                }
                
                if ($fila['tipo_id'] == 1){
                    $html_det.="<td align='left' >".number_format($fila['au_mg_malla200'], 2, '.')."</td>";
                    $html_det.="<td align='left' >".number_format($fila['ag_mg_malla'], 2, '.')."</td>";
                    $html_det.="<td align='left' >".number_format($fila['porcentaje'], 2, '.')."</td>";                    
                }
                else{
                    $html_det.="<td align='left' ></td>"; 
                    $html_det.="<td align='left' ></td>";
                }
                $html_det.="<td align='left' >".number_format($fila['minimo'], 3, '.')."</td>";    
                $html_det.="<td align='left' >".number_format($fila['maximo'], 3, '.')."</td>";   
                $html_det.="<td align='left' >".$fila['reensaye']."</td>";
               $html_det.= "</tr>";
               $renglon++;        
            }        
            $html_det.= "</tr>";
       }       
       $html_det.="</tbody></table>";  
     }  // fin de metodo EF-Grav2 2 barras y precipitados
        
 }   
 echo ("$html_en");
 echo ("$html_det");
 ?>
</div>     
</div>                   
          

