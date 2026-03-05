<?// include "../connections/config.php"; 
$trn_id = $_GET['trn_id'];
$met = $_GET['metodo'];
if ($met == 0){
    $met = 3;
}
//echo $trn_id;
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
    .circulos{
    	padding-top: 5em;
    }
    img{
      max-width: 100%;
    }
</style>

<script>
 function actualizar_pagina(trnid)
    {
        //var trn_dest   = document.getElementById("reen_id").value;
        var trnid_origen = trnid;//document.getElementById("trnid_origen").value;      
        //alert('Su orden de reensaye se actualizará por favor espere...');
            
        var direccionar = '<?echo "\orden_trabajo_mover.php?trn_id="?>'+trnid_origen;                                  
        window.location.href = direccionar;  
    }
    
function cambiar_orden (trn_id, trn_idmuestra, metodo)
        {               
            trn_id = trn_id;
            trn_idmuestra = trn_idmuestra;
            metodo = metodo
            $.ajax({
            		url: 'cambiar_orden_ree.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {trn_id: trn_id, trn_idmuestra:trn_idmuestra, metodo: metodo},
            	})
            .done(function(respuesta){
                        
                            $('#cambiar_orden_ree').modal('show');
                            $("#datos_orden").html(respuesta); 
                           /* $('#payon_modal').on('shown.bs.modal', function (e) {
                                $(this).find('#peso_pay1').focus();
                            })                
                            $('#payon_modal').modal('show').trigger('shown');*/
                        //}
                    })
        }
        
    //Copelado
    function mover_guardar(trn_id_mov, trn_id_mu, metodo_mov,unidadid)
        {               
            var trn_orig   = trn_id_mov;            
            var trnid_mu   = trn_id_mu;
            var metodo_m   = metodo_mov;
            var unidad     = unidadid;
            var trn_dest   = document.getElementById("reen_id").value;  
            
            if (trn_dest == 0 || trn_dest == ''){
                alert('No ha seleccionado orden destino. Reintente por favor');
            }
            else 
            {
                $('#boton_save_fun').html('<div class="loading"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span></div>'); 
                    $.ajax({
                    		url: 'guardar_moverOrden.php' ,
                    		type: 'POST' ,
                    		dataType: 'html',
                    		data: {trn_orig:trn_orig, trn_dest:trn_dest, trnid_mu:trnid_mu, metodo_m:metodo_m},
                    	})
                    	.done(function(respuesta){
                    		///$("#placas_dat").html(respuesta);  
                            alert(respuesta);
                           // $('#boton_save_fun').html('<div class="loading" ><i  disabled="disabled" class="fa fa-cloud fa-1x"  disabled="disabled" ></i><span class="sr-only">Loading...</span></div>'); 
                      })
            }
        }            
        
 </script>
 
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="cambiar_orden_ree" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable" style="max-width: 750px!important;" role="document">
            <div class="modal-content">
              <div class="modal-header" >
                <h5 class="modal-title" id="nombre_etapa">ORDENES DE REENSAYE</h5>                             
                <div class="col-md-1 col-lg-1">                
                    <input type="date" id="fecha_metodo"  value="<?php echo date("Y-m-d");?>" min="<?php echo date("Y-m-d");?>" disabled />                    
                    <input type="hidden" id="mina_met" size=20 style="width:125px; color:#996633"  disabled /> 
                </div>
              </div> 
              <div class="modal-body"  style="font-size:4px;" class="col-md-12 col-lg-12" id="datos_orden">
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="actualizar_pagina(<?echo $trn_id;?>);" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
    </div>              
</div>


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
                                                
          
                                               
            $datos_orden_encab = $mysqli->query("SELECT
                                                	 od.trn_id, od.trn_id_rel, od.folio_interno, ore.folio_interno AS folio_muestra
                                                     ,ore.trn_id_muestra
                                                     ,ore.metodo_id
                                                     ,me.nombre AS metodo
                                                     ,(CASE WHEN ore.tipo_id = 0 THEN muestra_geologia ELSE control END) AS muestra
                                                  FROM
                                                    arg_ordenes_detalle od
                                                    LEFT JOIN ordenes_reensayes AS ore
                                                        ON ore.trn_id_rel = od.trn_id
                                                    LEFT JOIN arg_metodos me
                                                    	ON me.metodo_id = ore.metodo_id
                                                  WHERE 
                                                    od.trn_id_rel = ".$trn_id."
                                                    
                                                  ORDER BY
                                                	trn_id_rel")or die(mysqli_error());                                                    
           
             
             //$count_metodos = (mysqli_num_rows($datos_metodos));
             $fecha_cambio = date('Y-m-d H:m:s');             
            
            ?>
             <div class="container-fluid">
                  <br /> <br /><br /> <br /><br /> <br />
                  <h3><? echo ("Orden de Trabajo: ".$orden_encabezado['folio']); ?></h3>
                 <?
                 $html_en = "<table class='table table-bordered' id='encabezado'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Unidad de Mina: ".$orden_encabezado['unidad']."</th>
                                    <th scope='col'>Fecha de Cambio: ".$fecha_cambio."</th>
                                    <th scope='col'></th>
                                  </tr>
                                  
                                  <tr class='table-secondary'>            
                                    <th scope='col'>Usuario: ".$orden_encabezado['usuario']."</th>
                                    <th scope='col'>Departamento: Geología</th>
                                    <th scope='col'></th>
                                  </tr>";
                  $html_en.="</thead></table>";
                 
                  $html_det = "<table class='table table-bordered'>
                                <thead>                                
                                     
                                    <tr class='table-secondary' justify-content: center;>   
                                        <th scope='col1'>Batch</th> 
                                        <th scope='col1'>Folio Interno</th>
                                        <th scope='col1'>Muestra</th>
                                        <th scope='col1'>Método</th>                              
                                        <th scope='col1'>Acción</th>";                                                               
                                    $html_det.="</tr>
                               </thead>
                               <tbody>";
                               
                               while ($fila = $datos_orden_encab->fetch_assoc()) {
                                   $po = 1;
                                   $trn_id_sig = $fila['trn_id'];
                                   $trn_id_muestra = $fila['trn_id_muestra'];
                                   $met = $fila['metodo_id'];
                                   $html_det.="<tr>";
                                      $html_det.="<td style='display:none;'>".$fila['trn_id']."</td>";
                                      $html_det.="<td>".$fila['folio_interno']."</td>";
                                      $html_det.="<td>".$fila['folio_muestra']."</td>";
                                      $html_det.="<td>".$fila['muestra']."</td>";           
                                      $html_det.="<td>".$fila['metodo']."</td>"; 
                        
                                                //$html_det.="<td align='center'>X</td>";<span class='fa fa-list fa-2x'></span>*/
                                                $html_det.="<td> <a button type='button' class='btn btn-info' onclick='cambiar_orden($trn_id_sig,$trn_id_muestra,$met)'
                                                                 </button>Cambiar ".$fila['metodo']."</td>";
                                         
                                      }                                                             
                                   $html_det.= "</tr>";
                               
                               
                  $html_det.="</tbody></table>";
                  
                 echo ("$html_en");
                 echo ("$html_det");
                ?>
              
           <div class="container">
           <div class="col-4 col-md-12 col-lg-12">
                <div class="col-2 col-md-2 col-lg-2">
                     <form method="post" action="seguimiento_ordenes.php?unidad_id=<?echo $unidad_id;?>" name="Printform" id="Printform">  
                        <fieldset>  
                            <input type="submit" class="btn btn-info" name="print" id="print" value="SEGUIMIENTO DE ORDENES" />                
                       </fieldset>  
                    </form> 
                </div>
                
                
             </div>
             </div>
          
    </div>
            <?
    }
?>                    
    