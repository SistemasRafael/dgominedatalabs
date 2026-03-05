<?php
//include "connections/config.php";;
$unidad_id = $_GET['unidad_id'];
//echo $unidad_id;
$_SESSION['unidad_id'] = $unidad_id;
$mysqli -> set_charset("utf8");
?>
 <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
 
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>--!>

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
    function exportar_materiales(tipo, metodo_filtro_fun)
            {
                 var tipo = tipo;
                 var metodo_id = metodo_filtro_fun;//document.getElementById("metodo_id_sel").value;
                 var exportar = '<?php echo "\ exportar_controles.php?tipo="?>'+tipo+'&metodo_id='+metodo_id;                                  
                     window.location.href = exportar;
            }
            
    function MostrarControl(metodo_id)
            {
                  
                 var metodo_id = metodo_id;
                   //alert(metodo_id)
                 $.ajax({
            		url: 'ver_controles.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {metodo_id:metodo_id},
            	}).done(function(respuesta){
            	   //alert(respuesta);  
                     $("#materiales").html(respuesta);                   
            	})
                 $('#MostrarControles').modal('show');                
            }
   
    function GuardarMetodos(unidad_id)
        {                     
            var tipo_id = document.getElementById("tipo_id_sel").value;            
            var nombre = document.getElementById("nombre_metodo").value; 
            var nombre_largo = document.getElementById("nombre_largo_metodo").value;           
            var unidad_id = unidad_id
            //alert(tipo_id);
            $.ajax({
            		url: 'datos_metodos.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {tipo_id:tipo_id, nombre:nombre, nombre_largo:nombre_largo},
            	})
            	.done(function(respuesta){
            		///$("#placas_dat").html(respuesta);                                       
                    console.log(respuesta);
                    if (respuesta == 'Se registro exitosamente.'){
                       alert('Se guardó con éxito');
                       var direccionar = '<?php echo "\ metodos.php?unidad_id="?>'+unidad_id;                                  
                      window.location.href = direccionar;                      
                    }
              })
      }
      
</script>
<!-- Modal Agregar Métodos  --> 
<div class="modal fade" id="ModalMetodos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalHerr">Agregar Método de Análisis</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">              
                    
                    <label for="tipo_id_sel" class="col-form-label">Tipo de Método:</label>  
                    <select name="tipo_id_sel" id="tipo_id_sel" class="form-control"> 
                        <?$result_h = $mysqli->query("SELECT tipo_id, nombre FROM `arg_metodos_tipos`") or die(mysqli_error());                             
                                              while ( $row2 = $result_h ->fetch_array(MYSQLI_ASSOC)) {
                                                $banco_sele = $row2['nombre'];                                
                                              ?>       
                                               <option value="<?echo $row2['tipo_id']?>"><?echo $banco_sele?></option>
                        <?}?>
                    </select>
                    <label for="nombre_metodo" class="col-form-label">Código:</label>
                    <input name="nombre_metodo" id="nombre_metodo" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="nombre_largo_metodo" class="col-form-label">Descripción:</label>
                    <input name="nombre_largo_metodo" id="nombre_largo_metodo" size=40 style="width:470px; color:#996633"  value="" enabled />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="GuardarMetodos(<?echo $unidad_id;?>)">Guardar</button>
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>
   </div>
   
 <!-- Mostrar materiales  --> 
<div class="modal fade" id="MostrarControles" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalHerr">Controles de Calidad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">              
                    <table class='table table-striped' id='materiales'>
    			  <thead>
    				<tr class='table-succes' justify-content: center; id='titulo'>
                        <th scope='col1'>Tipo Control</th>
                        <th scope='col1'>Nombre</th>
    				</tr>
    			  </thead>
    	          <tbody><tr>
                    
                </tr>
               </tbody></table>
              </div>
              <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
   </div> 
<?php

if (isset($unidad_id)){
    ?>
    <div class="container">

          <br />
            <br />
                    <? $datos_metodos = $mysqli->query("SELECT 
                                                             ti.nombre AS tipo,
                                                             met.metodo_id, met.nombre AS metodo,
                                                             met.nombre_largo,
                                                            (CASE activo WHEN 1 THEN 'ACTIVO' ELSE 'INACTIVO' END) AS activo
                                                        FROM 
                                                            arg_metodos met  
                                                        LEFT JOIN arg_metodos_tipos ti
                                                        	ON met.tipo_id = ti.tipo_id
                                                        WHERE
                                                            activo = 1
                                                        ORDER BY ti.nombre, met.nombre
                                                        
                                                        ") or die(mysqli_error());
                     ?>
                     <br />
                    <div class="col-md-2 col-lg-4">
                                <!--    <button type='button' class='btn btn-primary' name='agregar_metodo' id='agregar_metodo' data-toggle="modal" data-target="#ModalMetodos" >+ AGREGAR MÉTODOS</button>--!>
                                <!--    <button type='button' class='btn btn-success' name='exportar_material' id='exportar_metodos' onclick="exportar_metodos(1, <?echo $unidad_id;?>)" >EXPORTAR
                                        <span class='fa fa-file-excel-o fa-1x'></span>
                                    </button>--!>
                                                         
                     </div>
                     <br/><br/><br/>
                            <?
                        $html_det = "<div class='container'>
                                 <table class='table table-striped' id='materiales'>
                                    <thead>
                                        <tr class='table-info' align: center;>
                                            <th colspan='1'></th>
                                            <th colspan='3'>Método de Análisis</th>
                                            <th colspan='1'></th>
                                        </tr>
                                        <tr class='table-info' justify-content: center;>
                                            <th scope='col1'>Tipo</th>
                                            <th scope='col1'>Código</th>
                                            <th scope='col1'>Nombre</th>                                            
                                            <th scope='col1'>Estado</th> 
                                            <th scope='col1'>Controles de Calidad</th>";                                                            
                                        $html_det.="</tr>
                                   </thead>
                                   <tbody>";
                                        while ($fila = $datos_metodos->fetch_assoc()) {
                                           $num = 1;
                                           $html_det.="<tr>"; 
                                              $html_det.="<td>".$fila['tipo']."</td>";
                                              $html_det.="<td>".$fila['metodo']."</td>"; 
                                              $html_det.="<td>".$fila['nombre_largo']."</td>";   
                                              $html_det.="<td>".$fila['activo']."</td>";
                                              $html_det.="<td><button type='button' class='btn btn-info' name='ver_controles' id='ver_controles' onclick='MostrarControl(".$fila['metodo_id'].")' >
                                                                    <span class='fa fa-search fa-1x'> VER DETALLE</span>
                                                              </button>                                                              
                                                          </td>";                                                                               
                                           $html_det.= "</tr>";
                                       }
                        $html_det.="</tbody></table></div>";                    
                        echo ("$html_det");?>
         
        </div>
        <?
   }
?>                    
<br /><br /><br /><br /><br /><br /><br /><br />     
          

