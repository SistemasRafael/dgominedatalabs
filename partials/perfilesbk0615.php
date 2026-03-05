<!-- jQuery 
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>-->
<!-- BS JavaScript 
<script type="text/javascript" src="js/bootstrap.js"></script>-->
<!-- Have fun using Bootstrap JS -->

<?php
//include "../connections/config.php";
$unidad_id = $_GET['unidad_id'];
$_SESSION['unidad_id'] = $unidad_id;
$u_id = $_SESSION['u_id'];
 
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
    //Check box para agregar perfil al usuario
    function ActivarCasillaAdd(menu) 
        {
    	var perfil_add = "perfil_add"+menu;
        //alert(perfil_del);
        var valoract = document.getElementById(perfil_add).value;
        if(valoract == 1){
            document.getElementById(perfil_add).value='0';
        }
        else{
            document.getElementById(perfil_add).value='1'; 
        }
    }
    //Check box para eliminar o agregar perfil al usuario
    function ActivarCasillaDel(menu) 
        {
    	var perfil_del = "perfil_del"+menu;
        //alert(perfil_del);
        var valoract = document.getElementById(perfil_del).value;
        if(valoract == 1){
            document.getElementById(perfil_del).value='0';
        }
        else{
            document.getElementById(perfil_del).value='1'; 
        }
    }
    //Check box para eliminar o agregar directiva al perfil
    function ActivarCasilla(menu) 
        {
    	var menu = "menu"+menu;
        //alert(menu);
        var valoract = document.getElementById(menu).value;
        if(valoract == 1){
            document.getElementById(menu).value='0';
        }
        else{
            document.getElementById(menu).value='1'; 
        }
    }
        function exportar(tipo,unidad_id)
            {
                 var tipo = tipo;
                 var unidad_id = unidad_id;
                 //alert(tipo);
                 var exportar = '<?php echo "\ export_perfiles.php?tipo="?>'+tipo+'&unidad_id='+unidad_id;                                  
                 window.location.href = exportar;
            } 
  //Ver usuarios y sus perfiles
    function ver_usuarios(u_id,user_id,unidad)
        {                     
            var u_id_ver = u_id;
            var unidad_mina_p = unidad;
            //document.getElementById("mina_perf").value = unidad_mina_p;
            //alert(unidad_mina_p);
            $.ajax({
            		url: 'datos_usuarios_perfiles.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {u_id_ver:u_id_ver},
            	})
            	.done(function(respuesta){
            	    jQuery.noConflict();
            		$('#usuarios_perfiles_modal').modal('show');
                    $("#datos_usuarios_perfiles").html(respuesta);
                   
              })
      }
      
    //Agregar perfil a usuario
    function agregar_perfil_usuario(u_id_v, accion)
        {                     
            var userid_add = u_id_v
            var accion = accion
            
            $.ajax({
            		url: 'datos_perfiles.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {userid_add: userid_add, accion:accion},
            	})
            	.done(function(respuesta){
            	   // alert(respuesta);
                   if (accion == 0){
                        jQuery.noConflict();
                        $('#agregar_perfilausuario_modal').modal('show');
                        $("#datos_agregarperfil").html(respuesta);
                   }
                   if (accion == 1){
                        jQuery.noConflict();
                        $('#agregar_perfil_modal').modal('show');
                        $("#datos_agregar").html(respuesta);
                   }
             })
      }
      
    function ver_detalle_perfil(perfil, directiva)
        {                     
            var perfil_id = perfil;
            var dir_id    = directiva;
            $.ajax({
            		url: 'datos_perfiles_detalle.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {perfil_id:perfil_id, dir_id:dir_id},
            	})
            	.done(function(respuesta){
            	    //alert(respuesta);
            	    jQuery.noConflict();
            		$('#usuarios_perfiles_modal').modal('show');
                    $("#datos_perfiles").html(respuesta);
                   
              })
      }
      
    function ver_accesos_perfil(perfil, u_id, unidad_min)
        {                     
            var perfil_id = perfil;
            //var dir_id    = directiva;
            $.ajax({
            		url: 'datos_perfiles_accesos.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {perfil_id:perfil_id, u_id:u_id},
            	})
            	.done(function(respuesta){
            	    //alert(respuesta);
            	    jQuery.noConflict();
            		$('#perfiles_modal').modal('show');
                    $("#datos_perfiles_det").html(respuesta);
                   
              })
      }
      
      function editar_perfil(perfil, u_id)
        {                     
            var perfil_id = perfil;
            var u_id      = u_id;
            $.ajax({
            		url: 'datos_perfiles_accesos.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {perfil_id:perfil_id, u_id:u_id},
            	})
            	.done(function(respuesta){
            	   //alert(respuesta);
            	    jQuery.noConflict();
            		$('#perfiles_editar_modal').modal('show');
                    $("#datos_perfiles_edi").html(respuesta);
                   
              })
      }
    
    //Crear Directivas en perfiles
    function GuardarUsuarioPerfil(accion,unidad_id)
        {
          var accion = accion;               
        //$('#guardando_modal_peso').modal('show');
        if (accion == 0){
            var k = 1;
            var table_eli = document.getElementById("datos_eliminar_perfil").rows.length;
            jQuery.noConflict();
            table_eli = table_eli-1; 
            while(k <= table_eli)
             {
                perfil_eli  = "perfil_id_del"+k;
                user_id_eli = "user_id_del"+k;
                elimina     = "perfil_del"+k;
                //alert(perfil_id);
                perfileli   = document.getElementById(perfil_eli).value;
                usereli     = document.getElementById(user_id_eli).value;
                elimina_eli = document.getElementById(elimina).value;  
                         
              if (elimina_eli == '1'){                
                       //alert(perfileli); alert(usereli);//alert(elimina_eli);
                       $.ajax({
                    		url: 'usuario_editar_guardar.php' ,
                    		type: 'POST' ,
                    		dataType: 'html',
                    		data: {perfileli:perfileli, usereli:usereli, accion:accion},
                        }).done(function(respuesta){
                            //alert(respuesta);
                            jQuery.noConflict();
                    		$('#usuarios_perfiles_modal').modal('show');
                            $("#datos_usuarios_perfiles").html(respuesta);
                           // $('#dir_agregar_modal').modal('show');
                          //  $("#datos_dir").html(respuesta);   
                            /*$('#boton_save_sec').hide();*/                     
                        })
                  k++;           
               }
               else{
                k++; 
               }
             }       
         }//End if delete
         //Agregar perfil
        if (accion == 1){
             var l = 1;
             var table_add = document.getElementById("datos_agregar_perfil").rows.length;
             table_add = table_add-1;
             while(l <= table_add)
             {
                perfil_eli  = "perfil_id_add"+l;
                user_id_eli = "user_id_add"+l;
                elimina     = "perfil_add"+l;
                perfileli   = document.getElementById(perfil_eli).value;
                usereli     = document.getElementById(user_id_eli).value;
                elimina_eli = document.getElementById(elimina).value;  
                         
              if (elimina_eli == '1'){                
                      // alert(perfileli); alert(usereli);//alert(elimina_eli);
                       $.ajax({
                    		url: 'usuario_editar_guardar.php' ,
                    		type: 'POST' ,
                    		dataType: 'html',
                    		data: {perfileli:perfileli, usereli:usereli, accion:accion},
                        }).done(function(respuesta){
                            //alert(respuesta);
                            jQuery.noConflict();
                    		$('#usuarios_perfiles_modal').modal('show');
                            $("#datos_usuarios_perfiles").html(respuesta);
                           // $('#dir_agregar_modal').modal('show');
                          //  $("#datos_dir").html(respuesta);   
                            /*$('#boton_save_sec').hide();*/                     
                        })
                  l++;           
               }
               else{
                l++; 
               }
             }
        }                             
       }
      
    //Crear Directivas en perfiles
   // jQuery.noConflict();
    function GuardarDirectiva()
        {
         var j = 1;
         var table_per = document.getElementById("datos_dir_tabla").rows.length;
         
         table_per = table_per-1;        
        //$('#guardando_modal_peso').modal('show');
    
        while(j <= table_per)
         {
            perfil_id = "perfil_id"+j;
            dir_id    = "dir_id"+j;
            menu_id   = "menu"+j;
            valor = "valor"+j;
            //alert(perfil_id);
            perfil = document.getElementById(perfil_id).value;
            direct = document.getElementById(dir_id).value;
            valorm = document.getElementById(valor).value;
            menu   = document.getElementById(menu_id).value;  
                     
          if (menu == '1'){                
                  //alert(perfil); alert(direct); alert(valorm);
                   $.ajax({
                		url: 'directivas_guardar.php' ,
                		type: 'POST' ,
                		dataType: 'html',
                		data: {perfil:perfil, direct:direct, valorm:valorm},
                    }).done(function(respuesta){
                        //alert(respuesta);
                        jQuery.noConflict();
                        $('#dir_agregar_modal').modal('show');
                        $("#datos_dir").html(respuesta);   
                        /*$('#boton_save_sec').hide();*/                     
                    })
                j++;      
           }
           else{
              j++;  
           // $('#perfiles_editar_modal').modal('show');
             }       //$("#datos_perfiles_edi").html(respuesta);        
         }                             
       }   
      
    //Agregar directiva a perfil
    function agregar_directiva(perfil_id_add, directiva_id_add)
        {                     
            var perfil_id = perfil_id_add
            var dir_id = directiva_id_add            
            $.ajax({
            		url: 'datos_directivas.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {perfil_id: perfil_id, dir_id:dir_id},
            	})
            	.done(function(respuesta){
            	   //alert(respuesta);
                    $('#dir_agregar_modal').modal('show');
                    $("#datos_dir").html(respuesta);
              })
      }
      
  function actualizar_perfiles()
        {
            var unidad_id = document.getElementById('mina_perf').value;
            var direccionar = '<?echo "\perfiles.php?unidad_id="?>'+unidad_id;                                  
            window.location.href = direccionar;  
        }
  (function($, window) {
    'use strict';

    var MultiModal = function(element) {
        this.$element = $(element);
        this.modalCount = 0;
    };

    MultiModal.BASE_ZINDEX = 1040;
      
     MultiModal.prototype.show = function(target) {
        var that = this;
        var $target = $(target);
        var modalIndex = that.modalCount++;

        $target.css('z-index', MultiModal.BASE_ZINDEX + (modalIndex * 20) + 10);

        // Bootstrap triggers the show event at the beginning of the show function and before
        // the modal backdrop element has been created. The timeout here allows the modal
        // show function to complete, after which the modal backdrop will have been created
        // and appended to the DOM.
        window.setTimeout(function() {
            // we only want one backdrop; hide any extras
            if(modalIndex > 0)
                $('.modal-backdrop').not(':first').addClass('hidden');

            that.adjustBackdrop();
        });
    };

    MultiModal.prototype.hidden = function(target) {
        this.modalCount--;

        if(this.modalCount) {
           this.adjustBackdrop();
            // bootstrap removes the modal-open class when a modal is closed; add it back
            $('body').addClass('modal-open');
        }
    };

    MultiModal.prototype.adjustBackdrop = function() {
        var modalIndex = this.modalCount - 1;
        $('.modal-backdrop:first').css('z-index', MultiModal.BASE_ZINDEX + (modalIndex * 20));
    };

    function Plugin(method, target) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data('multi-modal-plugin');

            if(!data)
                $this.data('multi-modal-plugin', (data = new MultiModal(this)));

            if(method)
                data[method](target);
        });
    }

    $.fn.multiModal = Plugin;
    $.fn.multiModal.Constructor = MultiModal;

    $(document).on('show.bs.modal', function(e) {
        $(document).multiModal('show', e.target);
    });

    $(document).on('hidden.bs.modal', function(e) {
        $(document).multiModal('hidden', e.target);
    });
}(jQuery, window));     
 </script>
 <!-- Modal Usuarios  --> 
 <div class="modal fade" id="usuarios_perfiles_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" style="max-width: 850px!important;" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">USUARIOS</h5>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                     <input type="hidden" id="mina_perf" size=40 style="width:470px; color:#996633"  value="<?echo $unidad_id;?>" disabled /> 
                     
                </button>
              </div>
              <div class="modal-body">
                    <div class="col-md-12 col-lg-12" style="font-size:8px;" id="datos_usuarios_perfiles">                  
                    </div> 
                    <div class="col-md-12 col-lg-12" style="font-size:8px;" id="datos_perfiles">                  
                    </div> 
                                 
              </div>
              <div class="modal-footer">                 
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
   </div>  
   
  <div class="modal fade" id="perfiles_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" style="max-width: 650px!important;" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">PERFILES</h5>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                     <input type="hidden" id="mina_perf" size=40 style="width:470px; color:#996633"  value="<?echo $unidad_id;?>" disabled /> 
                      
                </button>
              </div>
              <div class="modal-body">                   
                   <div class="col-md-12 col-lg-12" style="font-size:8px;" id="datos_perfiles_det"> 
                   </div>
                   <div class="col-md-12 col-lg-12" style="font-size:8px;" id="datos_perfiles">
                   </div>     
              </div>
              <div class="modal-footer">
                                    
                                 
                 <button type="button" class="btn btn-secondary" onclick="actualizar_perfiles();" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
   </div> 
   
   <div class="modal fade" id="perfiles_editar_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" style="max-width: 650px!important;" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">PERFILES editar</h5>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                     <input type="hidden" id="mina_perf" size=40 style="width:470px; color:#996633"  value="<?echo $unidad_id;?>" disabled /> 
                      
                </button>
              </div>
              <div class="modal-body">                   
                  <div class="col-md-12 col-lg-12" style="font-size:8px;" id="datos_perfiles_edi"> 
                   </div>
              </div>
              <div class="modal-footer">
                                    
                                 
                 <button type="button" class="btn btn-secondary" onclick="actualizar_perfiles();" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
   </div>    
   
   <div class="modal fade" id="dir_agregar_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" style="max-width: 650px!important;" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Directiva</h5>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                     <input type="hidden" id="mina_perf" size=40 style="width:470px; color:#996633"  value="<?echo $unidad_id;?>" disabled /> 
                      
                </button>
              </div>
              
              <div class="modal-body">                   
                      <div class="col-md-12 col-lg-12" style="font-size:8px;" id="datos_dir"> 
                       </div>
              </div>
                   
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="GuardarDirectiva()">Guardar</button>                 
                 <button type="button" class="btn btn-secondary" onclick="actualizar_perfiles();" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
   </div> 

   <div class="modal fade" id="agregar_perfilausuario_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" style="max-width: 650px!important;" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Perfil a Usuario</h5>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                     <input type="hidden" id="mina_perf" size=40 style="width:470px; color:#996633"  value="<?echo $unidad_id;?>" disabled /> 
                      
                </button>
              </div>
              
              <div class="modal-body">                   
                      <div class="col-md-12 col-lg-12" style="font-size:8px;" id="datos_agregarperfil"> 
                       </div>
              </div>
                   
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="GuardarUsuarioPerfil(0)">Guardar</button>                 
                 <button type="button" class="btn btn-secondary" onclick="actualizar_perfiles();" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
   </div> 
   
   <div class="modal fade" id="agregar_perfil_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" style="max-width: 650px!important;" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Perfil a Usuario</h5>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                     <input type="hidden" id="mina_perf" size=40 style="width:470px; color:#996633"  value="<?echo $unidad_id;?>" disabled />                      
                </button>
              </div>              
              <div class="modal-body">                   
                      <div class="col-md-12 col-lg-12" style="font-size:8px;" id="datos_agregar"> 
                       </div>
              </div>                   
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="GuardarUsuarioPerfil(1)">Guardar</button>                 
                 <button type="button" class="btn btn-secondary" onclick="actualizar_perfiles();" data-dismiss="modal">Cerrar</button>
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
        //if ($tipo == 1){
        $datos_usuarios_detalle = $mysqli->query("SELECT
	                                                us.u_id, us.codigo, us.nombre, us.email
                                                   ,(CASE WHEN us.activo = 1 THEN 'SI' ELSE 'NO' END) AS activo
                                                   ,(CASE WHEN us.division = 'empleado' THEN 'AD' ELSE 'User Local' END) AS tipo_usuario
                                                   ,us.fecha_creacion, us.fecha_fin
                                                   ,uc.nombre AS user_created
                                                FROM
                                                    `arg_usuarios` us
                                                    LEFT JOIN arg_usuarios uc
                                                        ON us.u_id_created = uc.u_id
                                                "
                                            ) or die(mysqli_error());   
        ?>
        <div class="container">
                <ul class="nav nav-tabs" id="myTab">                    
                    <li class="active"><a data-toggle="tab" href="#home">USUARIOS</a></li>
                    <li><a data-toggle="tab" href="#menu1">PERFILES</a></li>                 
                </ul>    
                
                <div class="tab-content">                
                <div id="home" class="tab-pane fade show active">
                    <div class="container" class="col-md-12 col-lg-12"> 
                            <br/>
                            <div class="container" class="col-md-2 col-lg-4">
                                        <button type='button' class='btn btn-primary' name='agregar_usuario' id='agregar_usuario' data-toggle="modal" data-target="#ModalBan" >+ AGREGAR USUARIO</button>
                                        <button type='button' class='btn btn-success' name='export' id='export' onclick="exportar_usuarios(1)">EXPORTAR
                                            <span class='fa fa-file-excel-o fa-1x'></span>
                                        </button>           
                            </div>
                            <br/><br/>
                            <?
                            $html_det = "<div class='container'>
                                        <table class='table table-striped' id='motivos'>
                                        <thead>
                                        <tr class='table-info' justify-content: center;>            
                                            <th scope='col1'>Usuario</th>
                                            <th scope='col1'>Nombre</th>
                                            <th scope='col1'>Correo</th>
                                            <th scope='col1'>Tipo</th>
                                             <th scope='col1'>Activo</th>
                                            <th scope='col1'>Creado</th>
                                            <th scope='col1'>Creación</th>
                                            <th colspan=3 align='center'>Perfiles</th>
                                            <th scope='col1'>Editar</th>";                  
                                        $html_det.="</tr>
                                        </thead>
                                        <tbody>";
                               
                                       while ($fila = $datos_usuarios_detalle->fetch_assoc()) {
                                           $num = 1;
                                           $html_det.="<tr>";
                                              $html_det.="<td>".$fila['codigo']."</td>";
                                              $html_det.="<td>".$fila['nombre']."</td>";   
                                              $html_det.="<td>".$fila['email']."</td>"; 
                                              $html_det.="<td>".$fila['tipo_usuario']."</td>";                                  
                                              $html_det.="<td>".$fila['activo']."</td>"; 
                                              $html_det.="<td>".$fila['user_created']."</td>"; 
                                              $html_det.="<td>".$fila['fecha_creacion']."</td>";
                                              $html_det.="<td> <button type='button' class='btn btn-info'";  
                                                         $html_det.="onclick = ver_usuarios(".$fila['u_id'].",".$u_id.",".$unidad_id.")";
                                                         $html_det.="><span class='fa fa-eye fa-2x'>  </span>
                                                        </button>
                                                  </td>";
                                               if($u_id == 1 or $u_id == 5 or $u_id == 6){
                                                 $html_det.="<td> <button type='button'class='btn btn-success' id='boton_save_addper' onclick='agregar_perfil_usuario(".$fila['u_id'].","."1".")' >
                                                            <span class='fa fa-plus-square fa-2x'>
                                                            </span>
                                                        </button>";
                                                 $html_det.="<td> <button type='button'class='btn btn-danger' id='boton_save_addper' onclick='agregar_perfil_usuario(".$fila['u_id'].","."0".")' >
                                                            <span class='fa fa-trash fa-2x'>
                                                            </span>
                                                        </button>";
                                                 $html_det.="<td> <button type='button' class='btn btn-warning'";  
                                                             $html_det.="onclick = agregar_perfiles_usuarios(".$fila['u_id'].",".$u_id.",".$unidad_id.")";
                                                             $html_det.="><span class='fa fa-pencil fa-2x'>  </span>
                                                            </button>
                                                      </td>";
                                                  
                                              }                                    
                                           $html_det.= "</tr>";
                               }
                              
                             $html_det.="</tbody></table></div>";
                             echo ("$html_det");
                        ?>
            </div>
            </div>
            <?//Fin primer tab usuarios?>
                
            <div id="menu1" class="tab-pane fade">
            <div class="container" class="col-md-12 col-lg-12"> 
                    <br />
                    <div class="container" class="col-md-2 col-lg-4">
                                <button type='button' class='btn btn-primary' name='agregar_voladura' id='agregar_voladura' data-toggle="modal" data-target="#ModalVol" >+ AGREGAR PERFIL</button>
                                <button type='button' class='btn btn-success' name='export' id='export' onclick="exportar_voladura(1, <?echo $unidad_id?>)">EXPORTAR
                                    <span class='fa fa-file-excel-o fa-1x'></span>
                                </button>           
                    </div>
                    <br/><br/>           
                <?
                  $datos_perfiles_detalle = $mysqli->query("SELECT
                                                                per.perfil_id,
                                                                per.descripcion,
                                                                (CASE WHEN per.activo = 1 THEN 'SI' ELSE 'NO' END) AS activo,
                                                            per.fecha_created,
                                                            uc.nombre AS user_created
                                                            FROM
                                                                `arg_perfiles` per
                                                            LEFT JOIN arg_usuarios uc ON
                                                                per.u_id_created = uc.u_id"
                                                        ) or die(mysqli_error());
                                            
                    $html_det = "<div class='container'>
                        <table class='table table-striped' id='motivos'>
                                <thead>
                                    <tr class='table-info' justify-content: center;>            
                                        <th scope='col1'>Perfil ID.</th>
                                        <th scope='col1'>Perfil</th>
                                        <th scope='col1'>Activo</th>
                                        <th scope='col1'>Creado por</th>
                                        <th scope='col1'>Fecha Creación</th>                                                                                
                                        <th scope='col1'>Accesos</th>
                                        <th scope='col1'>Editar</th>";
                                                                                                   
                                    $html_det.="</tr>
                               </thead>
                               <tbody>";
                               
                               while ($fila = $datos_perfiles_detalle->fetch_assoc()) {
                                   $num = 1;
                                   $html_det.="<tr>";
                                      $html_det.="<td>".$fila['perfil_id']."</td>";
                                      $html_det.="<td>".$fila['descripcion']."</td>";
                                      $html_det.="<td>".$fila['activo']."</td>";
                                      $html_det.="<td>".$fila['user_created']."</td>";
                                      $html_det.="<td>".$fila['fecha_created']."</td>";                                    
                                      $html_det.="<td> <button type='button' class='btn btn-info'";  
                                                         $html_det.="onclick = ver_accesos_perfil(".$fila['perfil_id'].",".$u_id.",".$unidad_id.")";
                                                         $html_det.="><span class='fa fa-eye fa-2x'> </span>
                                                        </button>
                                                  </td>";  
                                      $html_det.="<td> <button type='button' class='btn btn-warning'";  
                                                         $html_det.="onclick = editar_perfil(".$fila['perfil_id'].",".$u_id.")";
                                                         $html_det.="><span class='fa fa-pencil fa-2x'> </span>
                                                        </button>
                                                  </td>";                                     
                                   $html_det.= "</tr>";
                               }
                              
                 $html_det.="</tbody></table></div>";
                
                 echo ("$html_det");
       // }
         ?>
            
            </div>
            </div>
        
         </div> 
         <?
   }
           ?>       
<br /><br /><br /><br /><br /><br /><br /><br />    
<script type="text/javascript" src="js/jquery.min.js"></script>
<!--<script type="text/javascript" src="js/vehiculos.js"></script>-->  
          

