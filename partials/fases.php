<?php
//include "../connections/config.php";
$unidad_id = $_GET['unidad_id'];
$_SESSION['unidad_id'] = $unidad_id;
$mysqli -> set_charset("utf8");
?>
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
    .nav-tabs > li { 
        /* width = (100/number of tabs). This example assumes 3 tabs. */ 
        width:15%; 
    }
    .circulos{
    	padding-top: 5em;
    }
    img{
      max-width: 100%;
    }
</style>

<script>
    function exportar_fases(tipo, metodo_filtro_fun)
            {
                 var tipo = tipo;
                 var metodo_id = metodo_filtro_fun//document.getElementById("metodo_id_sel").value;
                     var exportar = '<?php echo "\ exportar_controles.php?tipo="?>'+tipo+'&metodo_id='+metodo_id;                                  
                     window.location.href = exportar;
            }
    
    function redireccion(unidad_id){
        var unidad_id = unidad_id
        var metodo_id_sel = document.getElementById("metodo_id_sel").value; 
        //alert(metodo_id_sel);
        //window.history.back();
        var direccionar = '<?php echo "\ controles.php?unidad_id="?>'+unidad_id+'&metodo_id_sel='+metodo_id_sel;                                  
        window.location.href = direccionar;   
    }
    
    function GuardarAsignarFases(unidad_id)
        {                     
            var metodo_id = document.getElementById("metodo_id_sel").value;
            var fase_id = document.getElementById("fase_id_sel").value;
            var orden = document.getElementById("orden").value;
            var unidad_id = unidad_id
            //alert(metodo_id);
            $.ajax({
            		url: 'datos_asignacion.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {metodo_id:metodo_id, orden:orden, fase_id:fase_id},
            	})
            	.done(function(respuesta){
            		///$("#placas_dat").html(respuesta);                                       
                    //console.log(respuesta);
                    if (respuesta == 'Se registro exitosamente.'){
                       alert('Se guardó con éxito');
                       var direccionar = '<?php echo "\ fases.php?unidad_id="?>'+unidad_id;                                  
                      window.location.href = direccionar;                      
                    }
              })
      }
            
    function GuardarFases(unidad_id)
        {                     
            var nombre = document.getElementById("nombre_fase").value;
            var unidad_id = unidad_id
            //alert(metodo_id);
            $.ajax({
            		url: 'datos_fases.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {nombre:nombre},
            	})
            	.done(function(respuesta){                                    
                    console.log(respuesta);
                    if (respuesta == 'Se registro exitosamente.'){
                       alert('Se guardó con éxito');
                       var direccionar = '<?php echo "\ fases.php?unidad_id="?>'+unidad_id;                                  
                      window.location.href = direccionar;                      
                    }
                    else alert(respuesta);
              })
      }
      
       function GuardarEtapa(unidad_id)
        {                     
            var nombre = document.getElementById("nombre_etapa").value;
            var unidad_id = unidad_id
            alert(nombre);
            $.ajax({
            		url: 'datos_etapas.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {nombre:nombre},
            	})
            	.done(function(respuesta){                                    
                    console.log(respuesta);
                    if (respuesta == 'Se registro exitosamente.'){
                       alert('Se guardó con éxito');
                       var direccionar = '<?php echo "\ fases.php?unidad_id="?>'+unidad_id;                                  
                      window.location.href = direccionar;                      
                    }
                    else alert(respuesta);
              })
      }
      
     function GuardarFasesyetapa(unidad_id)
        {                     
            var fase_sel = document.getElementById("fase_sel").value;
            var etapa_sel = document.getElementById("etapa_id_sel").value;
            var orden_etapa = document.getElementById("orden_etapa").value;
            var cantidad_id_sel = document.getElementById("cantidad_id").value;
            var cantidad_muestras_sel = document.getElementById("cantidad_muestras").value;
            var unidad_id = unidad_id
            alert(fase_sel);
           // alert(etapa_id);
            $.ajax({
            		url: 'datos_fasesetapas.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {fase_sel:fase_sel, etapa_sel:etapa_sel, orden_etapa:orden_etapa, cantidad_id_sel:cantidad_id_sel, cantidad_muestras_sel:cantidad_muestras_sel},
            	})
            	.done(function(respuesta){
            		///$("#placas_dat").html(respuesta);                                       
                    console.log(respuesta);
                    if (respuesta == 'Se registro exitosamente.'){
                       alert('Se guardó con éxito');
                       var direccionar = '<?php echo "\ fases.php?unidad_id="?>'+unidad_id;                                  
                      window.location.href = direccionar;                      
                    }
              })
      }
      
</script>
<!-- Modal Agregar Fases  --> 
<div class="modal fade" id="ModalFases" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalHerr">Agregar Fase</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              
                    <label for="fases_exis" class="col-form-label">Fases Existentes:</label>  
                    <select name="fases_exis" id="fases_exis" class="form-control"> 
                        <?$result_h = $mysqli->query("SELECT fase_id, nombre FROM `arg_fases`") or die(mysqli_error());                             
                                              while ( $row2 = $result_h ->fetch_array(MYSQLI_ASSOC)) {
                                                $banco_sele = $row2['nombre'];                                
                                              ?>       
                        <option value="<?echo $row2['fase_id']?>"><?echo $banco_sele?></option>
                        <?}?>
                    </select>
                    <label for="nombre_fase" class="col-form-label">Nombre de fase a crear:</label>
                    <input name="nombre_fase" id="nombre_fase" size=40 style="width:470px; color:#996633"  value="" enabled />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="GuardarFases(<?echo $unidad_id;?>)">Guardar</button>
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>
   </div>
   
   <div class="modal fade" id="ModalEtapas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalHerr">Agregar Etapa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              
                    <label for="etapa_exis" class="col-form-label">Etapas Existentes:</label>  
                    <select name="etapa_exis" id="etapa_exis" class="form-control"> 
                        <?$result_h = $mysqli->query("SELECT etapa_id, nombre FROM `arg_etapas`") or die(mysqli_error());                             
                                              while ( $row2 = $result_h ->fetch_array(MYSQLI_ASSOC)) {
                                                $banco_sele = $row2['nombre'];                                
                                              ?>       
                        <option value="<?echo $row2['etapa_id']?>"><?echo $banco_sele?></option>
                        <?}?>
                    </select>
                    <label for="nombre_etapa" class="col-form-label">Nombre de la etapa a crear:</label>
                    <input name="nombre_etapa" id="nombre_etapa" size=40 style="width:470px; color:#996633"  value="" enabled />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="GuardarEtapa(<?echo $unidad_id;?>)">Guardar</button>
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>
   </div>
   
   <!-- Modal Agregar Fases  --> 
<div class="modal fade" id="ModalAsignarFases" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalHerr">Asignar Fases a los Métodos de Análisis</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              
                    <label for="metodo_id_sel" class="col-form-label">Elige un método de análisis:</label>  
                    <select name="metodo_id_sel" id="metodo_id_sel" class="form-control"> 
                        <?$result_h = $mysqli->query("SELECT metodo_id, nombre FROM `arg_metodos`") or die(mysqli_error());                             
                                              while ( $row2 = $result_h ->fetch_array(MYSQLI_ASSOC)) {
                                                $met_sele = $row2['nombre'];                                
                                              ?>       
                        <option value="<?echo $row2['metodo_id']?>"><?echo $met_sele?></option>
                        <?}?>
                    </select>
                    
                    <label for="fase_id_sel" class="col-form-label">Fases A Asignar:</label>  
                    <select name="fase_id_sel" id="fase_id_sel" class="form-control"> 
                        <?$result_h = $mysqli->query("SELECT fase_id, nombre FROM `arg_fases`") or die(mysqli_error());                             
                                              while ( $row2 = $result_h ->fetch_array(MYSQLI_ASSOC)) {
                                                $fase_sele = $row2['nombre'];                                
                                              ?>       
                        <option value="<?echo $row2['fase_id']?>"><?echo $fase_sele?></option>
                        <?}?>
                    </select>
                    
                    <label for="orden" class="col-form-label">Orden de la fase:</label>
                    <input name="orden" id="orden" size=40 style="width:470px; color:#996633"  value="" enabled />
                              
                     
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="GuardarAsignarFases(<?echo $unidad_id;?>)">Guardar</button>
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>
   </div>
    
   <!-- Modal Agregar etapas a Fases  --> 
<div class="modal fade" id="ModalFaseEtapa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalHerr">Asignar etapas de las fases</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              
                    <label for="fase_sel" class="col-form-label">Fase:</label>  
                    <select name="fase_sel" id="fase_sel" class="form-control"> 
                        <?$result_h = $mysqli->query("SELECT fase_id, nombre FROM `arg_fases`") or die(mysqli_error());                             
                                              while ( $row2 = $result_h ->fetch_array(MYSQLI_ASSOC)) {
                                                $met_sele = $row2['nombre'];                                
                                              ?>       
                        <option value="<?echo $row2['fase_id']?>"><?echo $met_sele?></option>
                        <?}?>
                    </select>
                    
                    <label for="etapa_id_sel" class="col-form-label">Elige una etapa a asignar:</label>  
                    <select name="etapa_id_sel" id="etapa_id_sel" class="form-control"> 
                        <?$result_h = $mysqli->query("SELECT etapa_id, nombre FROM `arg_etapas`") or die(mysqli_error());                             
                                              while ( $row2 = $result_h ->fetch_array(MYSQLI_ASSOC)) {
                                                $fase_sele = $row2['nombre'];                                
                                              ?>       
                        <option value="<?echo $row2['etapa_id']?>"><?echo $fase_sele?></option>
                        <?}?>
                    </select>
                    
                    <label for="orden_etapa" class="col-form-label">Orden de la etapa en esta fase:</label>
                    <input name="orden_etapa" id="orden_etapa" size=40 style="width:470px; color:#996633"  value="" enabled />
                    
                    <label for="cantidad_id" class="col-form-label">Cantidad de muestras a aplicar en:</label>
                    <select name="cantidad_id" id="cantidad_id" class="form-control"> 
                        <?$result_h = $mysqli->query("SELECT cantidad_id, nombre FROM arg_fases_tipoCantidad") or die(mysqli_error());                             
                                              while ( $row2 = $result_h ->fetch_array(MYSQLI_ASSOC)) {
                                                $met_sele = $row2['nombre'];                                
                                              ?>       
                        <option value="<?echo $row2['cantidad_id']?>"><?echo $met_sele?></option>
                        <?}?>
                    </select>              
                    <label for="cantidad_muestras" class="col-form-label">Cantidad:</label>
                    <input name="cantidad_muestras" id="cantidad_muestras" size=40 style="width:470px; color:#996633"  value="" enabled />
                     
              </div>
              <div class="modal-footer">
                 <button type="button" class="btn btn-primary" onclick="GuardarFasesyetapa(<?echo $unidad_id;?>)">Guardar</button>
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>
   </div>
  
<?php

if (isset($unidad_id)){
    $metodo_filtro = $_GET['metodo_id_sel'];
    $metodo_filtro_fun = $metodo_filtro;
    //echo $metodo_filtro;
    if ($metodo_filtro == '' or $metodo_filtro == 0){
        $metodo_filtro = 'met.metodo_id';
        $metodo_filtro_fun = 0;
    }
    ?>
    <div class="container">

     <ul class="nav nav-tabs" id="myTab">
        
        <li class="active"><a data-toggle="tab" href="#home">MÉTODOS Y SUS FASES</a></li>
        <li><a data-toggle="tab" href="#menu1">FASES Y ETAPAS</a></li>
        <li><a data-toggle="tab" href="#menu2">FASES</a></li>
        <li><a data-toggle="tab" href="#menu3">ETAPAS</a></li>
        
      </ul>    
    
    <div class="tab-content">
    
    <div id="home" class="tab-pane fade show active">
        <div class="container" class="col-md-12 col-lg-12"> 
            <br />
            <br />
                    <? $mysqli -> set_charset("utf8");
                        $datos_fases = $mysqli->query("SELECT
                                                            met.nombre AS metodo,
                                                            met.nombre_largo,
                                                            mefa.orden as ordenfase,
                                                            fa.nombre AS fase,
                                                            et.nombre AS etapa,
                                                            ef.orden
                                                       FROM
                                                       	   arg_metodos_fases mefa
                                                        LEFT JOIN arg_fases fa ON
                                                            mefa.fase_id = fa.fase_id
                                                        LEFT JOIN arg_fases_etapas ef
                                                        	On fa.fase_id = ef.fase_id
                                                        LEFT JOIN arg_etapas et ON
                                                            et.etapa_id = ef.etapa_id
                                                        LEFT JOIN arg_metodos met ON
                                                            mefa.metodo_id = met.metodo_id
                                                        WHERE mefa.metodo_id = ".$metodo_filtro."
                                                        ORDER BY
                                                            met.nombre, mefa.orden, ef.orden"
                                            ) or die(mysqli_error());  //(CASE WHEN cantidad_tipo = 0 THEN 'Unidades' ELSE 'Porciento' END) AS tipo_cantidad,
                                                            //cantidad_muestras
                     ?>
                     <br />
                     <div class="container" class="col-md-2 col-lg-12">
                            <div class="col-md-2 col-lg-4">
                                    <button type='button' class='btn btn-primary' name='agregar_metodo_fase' id='agregar_metodo_fase' data-toggle="modal" data-target="#ModalAsignarFases" >ASIGNAR FASE A MÉTODO</button>
                                    <button type='button' class='btn btn-success' name='exportar_material' id='exportar_material' onclick="exportar_fases(1, <?echo $metodo_filtro_fun;?>)" >EXPORTAR
                                        <span class='fa fa-file-excel-o fa-1x'></span>
                                    </button>
                            </div>
                            
                            <div class="col-md-2 col-lg-5">
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <select name="metodo_id_sel" id="metodo_id_sel" value="<?echo $metodo_filtro;?>" class="form-control"> 
                                <?$result_h = $mysqli->query("SELECT metodo_id, nombre FROM `arg_metodos` UNION ALL SELECT 0 AS metodo_id, 'Todos los métodos' as nombre ORDER BY metodo_id ") or die(mysqli_error());                             
                                              while ( $row2 = $result_h ->fetch_array(MYSQLI_ASSOC)) {
                                                    $banco_sele = $row2['nombre'];?>       
                                                    <option value="<?echo $row2['metodo_id']?>"><?echo $banco_sele?></option>
                                <?}?>
                                </select>            
                            </div>
                             <div class="col-md-2 col-lg-1">
                                <button type='button' class='btn btn-warning' name='filtro' id='filtro' onclick="redireccion(1, <?echo $unidad_id?>)" >FILTRAR
                                        <span class='fa fa-filter fa-1x'></span>
                                </button>
                             </div>                                
                     </div>
                        <br/><br/>
                            <?
                        $html_det = "<div class='container'>
                                     <table class='table table-striped' id='materiales'>
                                     <thead>
                                        <tr class='table-info' justify-content: center;>                                        
                                            <th scope='col1'>Metodo de Análisis</th>          
                                            <th scope='col1'>Nombre</th>
                                            <th scope='col1'>Orden Fase</th>
                                            <th scope='col1'>Fase</th>                                         
                                            <th scope='col1'>Etapa</th>
                                            <th scope='col1'>Orden Etapa</th>";                                                            
                                        $html_det.="</tr>
                                     </thead>
                                   <tbody>";
                                        while ($fila = $datos_fases->fetch_assoc()){
                                           $num = 1;
                                           $html_det.="<tr>";
                                              $html_det.="<td>".$fila['metodo']."</td>"; 
                                              $html_det.="<td>".$fila['nombre_largo']."</td>"; 
                                              $html_det.="<td>".$fila['ordenfase']."</td>";  
                                              $html_det.="<td>".$fila['fase']."</td>";
                                              $html_det.="<td>".$fila['etapa']."</td>";
                                              $html_det.="<td>".$fila['orden']."</td>";                                                                                                                                 
                                           $html_det.= "</tr>";
                                       }
                        $html_det.="</tbody></table></div>";                    
                        echo ("$html_det");?>
           	 </div>
        </div>
    
	<div id="menu1" class="tab-pane fade">
        <div class="container" class="col-md-12 col-lg-12"> 
            <br />
            <br />
                    <? $mysqli -> set_charset("utf8");
                       $datos_fases = $mysqli->query("SELECT
                                                            fae.fase_id,
                                                            fa.nombre as fase,
                                                            et.nombre as etapa,
                                                            fae.orden,
                                                            (CASE fae.cantidad_tipo WHEN 0 THEN 'Unidades' WHEN 1 THEN 'Porciento' WHEN 2 THEN 'Uno en cada ciclo de' END) AS cantidad_tipo,
                                                            fae.cantidad_muestras
                                                        FROM
                                                            arg_fases_etapas fae
                                                        LEFT JOIN arg_fases fa 
                                                        	ON  fa.fase_id = fae.fase_id
                                                        LEFT JOIN arg_etapas et 
                                                        	ON et.etapa_id = fae.etapa_id
                                                        ORDER BY fae.fase_id, fae.orden"
                                            ) or die(mysqli_error());
                     ?>
                     <br />
                     <div class="container" class="col-md-2 col-lg-12">
                            <div class="col-md-2 col-lg-4">
                                    <button type='button' class='btn btn-primary' name='agregar_etapaAfase' id='agregar_etapaAfase' data-toggle="modal" data-target="#ModalFaseEtapa" >ASIGNAR ETAPA A FASE</button>
                                    <button type='button' class='btn btn-success' name='exportarfasesyetapas' id='exportarfasesyetapas' onclick="exportar_fases(1, <?echo $metodo_filtro_fun;?>)" >EXPORTAR
                                        <span class='fa fa-file-excel-o fa-1x'></span>
                                    </button>
                            </div>
                                                      
                     </div>
                        <br/><br/>
                            <?
                        $html_det = "<div class='container'>
                                 <table class='table table-striped' id='materiales'>
                                    <thead>
                                        <tr class='table-info' justify-content: center;>                                        
                                            <th scope='col1'>Fase</th>          
                                            <th scope='col1'>Etapa</th>
                                            <th scope='col1'>Orden Etapa</th>                                            
                                            <th scope='col1'>Cantidad</th>
                                            <th scope='col1'>Porcentaje/Unidad</th>";                                                          
                                        $html_det.="</tr>
                                   </thead>
                                   <tbody>";
                                        while ($fila = $datos_fases->fetch_assoc()) {
                                           $num = 1;
                                           $html_det.="<tr>";
                                              $html_det.="<td>".$fila['fase']."</td>"; 
                                              $html_det.="<td>".$fila['etapa']."</td>";
                                              $html_det.="<td>".$fila['orden']."</td>";  
                                              $html_det.="<td>".$fila['cantidad_muestras']."</td>";
                                              $html_det.="<td>".$fila['cantidad_tipo']."</td>";                                                                                                                                
                                           $html_det.= "</tr>";
                                       }
                        $html_det.="</tbody></table></div>";                    
                        echo ("$html_det");?>
           	 </div>
        </div>   
        
        <div id="menu2" class="tab-pane fade">
        <div id="content" class="col-md-12 col-lg-12"> 
            <br />
            <br />
                    <? $datos_fases = $mysqli->query("SELECT 
                                                         fa.nombre
                                                      FROM
                                                         arg_fases fa
                                                        ") or die(mysqli_error());
                     ?>
                     <br />
                     <div class="container" class="col-md-2 col-lg-12">
                            <div class="col-md-2 col-lg-4">
                                    <button type='button' class='btn btn-primary' name='agregar_fase' id='agregar_fase' data-toggle="modal" data-target="#ModalFases" >+ AGREGAR FASE</button>
                                    <button type='button' class='btn btn-success' name='exportar_material' id='exportar_material' onclick="exportar_fases(1, <?echo $metodo_filtro_fun;?>)" >EXPORTAR
                                        <span class='fa fa-file-excel-o fa-1x'></span>
                                    </button>
                            </div>                       
                     </div>
                     <br/><br/>
                            <?
                     $html_det = "<div class='container'>
                                 <table class='table table-striped' id='materiales'>
                                    <thead>
                                        <tr class='table-info' justify-content: center;>                                        
                                            <th scope='col1'>Nombre de Fase</th>";                                                            
                                        $html_det.="</tr>
                                   </thead>
                                   <tbody>";
                                        while ($fila = $datos_fases->fetch_assoc()) {
                                           $num = 1;
                                           $html_det.="<tr>";
                                              $html_det.="<td>".$fila['nombre']."</td>";                                                                                                                                
                                           $html_det.= "</tr>";
                                       }
                     $html_det.="</tbody></table></div>";                    
                     echo ("$html_det");?>
           	 </div>
        </div>
        
        <div id="menu3" class="tab-pane fade">
        <div id="content" class="col-md-12 col-lg-12">
            <br />
            <br />
                    <? $datos_fases = $mysqli->query("SELECT 
                                                         nombre
                                                      FROM
                                                         arg_etapas 
                                                        ") or die(mysqli_error());
                     ?>
                     <br />
                     <div class="container" class="col-md-2 col-lg-12">
                            <div class="col-md-2 col-lg-4">
                                    <button type='button' class='btn btn-primary' name='agregar_etapa' id='agregar_etapa' data-toggle="modal" data-target="#ModalEtapas" >+ AGREGAR ETAPA</button>
                                    <button type='button' class='btn btn-success' name='exportar_material' id='exportar_material' onclick="exportar_fases(1, <?echo $metodo_filtro_fun;?>)" >EXPORTAR
                                        <span class='fa fa-file-excel-o fa-1x'></span>
                                    </button>
                            </div>               
                     </div>
                     <br/><br/>
                            <?
                     $html_det = "<div class='container'>
                                 <table class='table table-striped' id='materiales'>
                                    <thead>
                                        <tr class='table-info' justify-content: center;>                                        
                                            <th scope='col1'>Etapas</th>";                                                            
                                        $html_det.="</tr>
                                   </thead>
                                   <tbody>";
                                        while ($fila = $datos_fases->fetch_assoc()) {
                                           $num = 1;
                                           $html_det.="<tr>";
                                              $html_det.="<td>".$fila['nombre']."</td>";                                                                                                                                
                                           $html_det.= "</tr>";
                                       }
                     $html_det.="</tbody></table></div>";                    
                     echo ("$html_det");?>
           	 </div>
        </div>
        
        
    </div>
    </div>
 <?}?>                    
<br /><br /><br /><br /><br /><br /><br /><br />    
<!--<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/vehiculos.js"></script>-->  
          

