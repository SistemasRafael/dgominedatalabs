<!-- jQuery 
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>-->
<!-- BS JavaScript 
<script type="text/javascript" src="js/bootstrap.js"></script>-->
<!-- Have fun using Bootstrap JS -->

<?php
//include "../connections/config.php";
$tipo = $_GET['tipo'];
$unidad_id = $_GET['unidad_id'];
$_SESSION['unidad_id'] = $unidad_id;
 
$unidad = $mysqli->query("SELECT
                            nombre
                          FROM
                            `arg_empr_unidades`
                          WHERE
                            unidad_id = ".$unidad_id
                         ) or die(mysqli_error());
$unidad_sele = $unidad->fetch_assoc();
$unidad_mina = $unidad_sele['nombre'];

?>
 <script>
        function exportar(tipo,unidad_id)
            {
                 var tipo = tipo;
                 var unidad_id = unidad_id;
                 //alert(tipo);
                 var exportar = '<?php echo "\ export.php?tipo="?>'+tipo+'&unidad_id='+unidad_id;                                  
                 window.location.href = exportar;
            } 
  //Crear Bancos
    function GuardarBancos(unidad_id)
        {                     
            var unidad_id = unidad_id
            var banco = document.getElementById("banco").value; 
            var nombre = document.getElementById("nombre_banco").value;
            /*alert(unidad_id);
            alert(banco);
            alert(nombre);**/
            $.ajax({
            		url: 'datos_bancos.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {unidad_id: unidad_id, banco:banco, nombre:nombre},
            	})
            	.done(function(respuesta){
            		///$("#placas_dat").html(respuesta);                                       
                    console.log(respuesta);
                    if (respuesta == 'Se registro exitosamente.'){
                       alert('Se guardó con éxito');
                       var direccionar = '<?php echo "\ catalogos.php?tipo=1&unidad_id="?>'+unidad_id;                                  
                      window.location.href = direccionar;                      
                    }
              })
      }
      
  //Crear Voladuras
    function GuardarVoladuras(unidad_id)
        {                     
            var unidad_id = unidad_id
            var banco_id = document.getElementById("banco_ori").value; 
            //var banco = document.getElementById("banco_num").value;
            var voladura_id = document.getElementById("voladura_id").value;
            var folio_ini = document.getElementById("folio_inicial").value;
            var folio_act = document.getElementById("folio_actual").value;  
            //alert(banco_id);
            //alert(folio_ini);
            /*alert(nombre);**/
            $.ajax({
            		url: 'datos_voladuras.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {unidad_id: unidad_id, banco_id:banco_id, voladura_id:voladura_id, folio_ini:folio_ini, folio_act:folio_act},
            	})
            	.done(function(respuesta){
            		///$("#placas_dat").html(respuesta);
                    alert(respuesta);                                    
                    //console.log(respuesta);
                    if (respuesta == 'Se registró exitosamente.'){
                       //alert('Se guardó con éxito');
                       var direccionar = '<?php echo "\ catalogos.php?tipo=2&unidad_id="?>'+unidad_id;                                  
                      window.location.href = direccionar;                      
                    }
              })
      }
      
      function actualizar_met()
        {
            var unidad_id = document.getElementById('unidad_mina').value;
            var direccionar = '<?echo "\catalogos.php?tipo=2&unidad_id="?>'+unidad_id;                                  
            window.location.href = direccionar;  
        }
 </script>
 
 <!-- Modal bancos  --> 
 <div class="modal fade" id="ModalBan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Bancos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                    <label for="unidad_mina" class="col-form-label">Unidad de Mina:</label>
                    <input name="unidad_mina" id="unidad_mina" size=40 style="width:470px; color:#996633"  value="<?echo $unidad_mina;?>" disabled />                   
                    <label for="banco" class="col-form-label">Banco:</label>
                    <input name="banco" id="banco" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="nombre_banco" class="col-form-label">Descripción:</label>
                    <input name="nombre_banco" id="nombre_banco" size=40 style="width:470px; color:#996633"  value="" enabled />                    
              </div>
              <div class="modal-footer">
                 <button type="button" class="btn btn-primary" onclick="GuardarBancos(<?echo $unidad_id;?>)">Guardar</button>
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>
   </div>        

 <!-- Modal Voladuras  --> 
<div class="modal fade" id="ModalVol" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalHerr">Agregar Voladuras</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                    <label for="unidad_mina" class="col-form-label">Unidad de Mina:</label>
                    <input name="unidad_mina" id="unidad_mina" size=40 style="width:470px; color:#996633"  value="<?echo $unidad_mina;?>" disabled /> 
                    <label for="banco_ori" class="col-form-label">Banco:</label>
                    <select name="banco_ori" id="banco_ori" class="form-control"> 
                        <?$result_h = $mysqli->query("SELECT banco_id, banco, nombre FROM `arg_bancos` WHERE unidad_id = ".$unidad_id) or die(mysqli_error());                             
                                              while ( $row2 = $result_h ->fetch_array(MYSQLI_ASSOC)) {
                                                $banco_sele = $row2['banco']; 
                                                $banco_nombre = $row2['nombre'];                                
                                              ?>       
                        <option value="<?echo $row2['banco_id']?>"><?echo $banco_sele.' - '.$banco_nombre?></option>
                        <?}?>
                        </select>
                    
                    <label for="voladura_id" class="col-form-label">Voladura:</label>
                    <input name="voladura_id" id="voladura_id" size=40 style="width:470px; color:#996633"  value="" enabled />
                   <!-- <label for="folio_inicial" class="col-form-label">Folio Inicial:</label>--!>
                    <input type="hidden" name="folio_inicial" id="folio_inicial" size=40 style="width:470px; color:#996633"  value="0" enabled />
                     <label for="folio_actual" class="col-form-label">Folio actual:</label>
                    <input name="folio_actual" id="folio_actual" size=40 style="width:470px; color:#996633"  value="" enabled />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="GuardarVoladuras(<?echo $unidad_id;?>)">Guardar</button>
                 <button type="button" class="btn btn-secondary" onclick="actualizar_vol();" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
   </div> 

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

    if (isset($unidad_id)){
        if ($tipo == 1){
                $datos_bancos_detalle = $mysqli->query("SELECT
                                                	       un.nombre as unidad_mina, ba.banco_id, ba.banco, ba.nombre
                                                        FROM
                                                	       `arg_bancos` ba
                                                	        LEFT JOIN arg_empr_unidades un
                                                    	       ON un.unidad_id = ba.unidad_id
                                                         WHERE
        	                                               ba.unidad_id = ".$unidad_id
                                            ) or die(mysqli_error()); 
        
                ?>
                <br />
                <div class="container" class="col-md-2 col-lg-4">
                            <button type='button' class='btn btn-primary' name='agregar_banco' id='agregar_banco' data-toggle="modal" data-target="#ModalBan" >+ AGREGAR</button>
                            <button type='button' class='btn btn-success' name='export' id='export' onclick="exportar(1, <?echo $unidad_id?>)">EXPORTAR
                                <span class='fa fa-file-excel-o fa-1x'></span>
                            </button>           
                </div>
                <br/><br/>
                <?
                $html_det = "<div class='container'>
                        <table class='table table-striped' id='motivos'>
                                <thead>
                                    <tr class='table-info' justify-content: center;>            
                                        <th scope='col1'>Unidad de Mina</th>
                                        <th scope='col1'>Id</th>
                                        <th scope='col1'>Banco</th>
                                        <th scope='col1'>Descripción</th>";                                                            
                                    $html_det.="</tr>
                               </thead>
                               <tbody>";
                               
                               while ($fila = $datos_bancos_detalle->fetch_assoc()) {
                                   $num = 1;
                                   $html_det.="<tr>";
                                      $html_det.="<td>".$fila['unidad_mina']."</td>";
                                      $html_det.="<td>".$fila['banco_id']."</td>";   
                                      $html_det.="<td>".$fila['banco']."</td>";                                  
                                      $html_det.="<td>".$fila['nombre']."</td>";                                      
                                   $html_det.= "</tr>";
                               }
                              
                 $html_det.="</tbody></table></div>";
                
                 echo ("$html_det");
        }
         if ($tipo == 2){
                $datos_bancos_detalle = $mysqli->query("SELECT
                                                	       un.nombre as unidad_mina, b.banco_id, b.banco, b.nombre, vol.voladura_id, folio_inicial, folio_actual
                                                        FROM
                                                	       `arg_bancos_voladuras` vol
                                                            LEFT JOIN arg_bancos b
                                                                ON b.banco_id = vol.banco_id
                                                	        LEFT JOIN arg_empr_unidades un
                                                    	       ON un.unidad_id = b.unidad_id
                                                         WHERE
        	                                               b.unidad_id = ".$unidad_id
                                            ) or die(mysqli_error()); 
        
                ?>
                <br />
                <div class="container" class="col-md-2 col-lg-4">
                            <button type='button' class='btn btn-primary' name='agregar_voladura' id='agregar_voladura' data-toggle="modal" data-target="#ModalVol" >+ AGREGAR</button>
                            <button type='button' class='btn btn-success' name='export' id='export' onclick="exportar_voladura(1, <?echo $unidad_id?>)">EXPORTAR
                                <span class='fa fa-file-excel-o fa-1x'></span>
                            </button>           
                </div>
                <br/><br/>
                <?
                $html_det = "<div class='container'>
                        <table class='table table-striped' id='motivos'>
                                <thead>
                                    <tr class='table-info' justify-content: center;>            
                                        <th scope='col1'>Unidad de Mina</th>
                                        <th scope='col1'>Banco</th>                                        
                                        <th scope='col1'>Descripción</th>
                                        <th scope='col1'>Voladura</th>
                                        <th scope='col1'>Folio Actual</th>";                       
                                    $html_det.="</tr>
                               </thead>
                               <tbody>";
                               
                               while ($fila = $datos_bancos_detalle->fetch_assoc()) {
                                   $num = 1;
                                   $html_det.="<tr>";
                                      $html_det.="<td>".$fila['unidad_mina']."</td>";
                                      $html_det.="<td>".$fila['banco']."</td>";                                    
                                      $html_det.="<td>".$fila['nombre']."</td>";
                                      $html_det.="<td>".$fila['voladura_id']."</td>";
                                      $html_det.="<td>".$fila['folio_actual']."</td>";                                      
                                   $html_det.= "</tr>";
                               }
                              
                 $html_det.="</tbody></table></div>";
                
                 echo ("$html_det");
        }
   }

?>                    
<br /><br /><br /><br /><br /><br /><br /><br />    
<script type="text/javascript" src="js/jquery.min.js"></script>
<!--<script type="text/javascript" src="js/vehiculos.js"></script>-->  
          

