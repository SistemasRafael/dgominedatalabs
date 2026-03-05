<?// include "../connections/config.php";
$unidad_id = $_GET['unidad_id'];
$fecha_i = $_GET['fecha_i'];
$fecha_f = $_GET['fecha_f'];
if (is_null($fecha_i)){
    $fecha_i = date('Y-m-d',strtotime("-30 days"));//date('d/m/y');
}
if (is_null($fecha_f)){
    $fecha_f = date('Y-m-d');
}

$_SESSION['unidad_id'] = $unidad_id;
$u_id = $_SESSION['u_id']
//echo $trn_id;
?>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    /*
    table {
  position: relative;
  border-collapse: collapse; 
}

 
th {  
  position: sticky;
  top: 0; 
}*/
</style>

<script>

  function iniciar_humedad (trn_id, unidad)
        {               
            trn_id = trn_id;
            unidad_id = unidad;
            //alert(trn_id);
           
            document.getElementById("mina_hum").value = unidad;
            $.ajax({
            		url: 'iniciar_humedad.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {trn_id: trn_id},
            	})
            	.done(function(respuesta){
            	   $('#humedad_modal').modal('show');
                    $("#datos_humedad").html(respuesta);
                        $('#humedad_modal').on('shown.bs.modal', function (e) {
                            $(this).find('#peso_hum1').focus();
                            $(this).find('#peso_sec1').focus();
                        })
              })
              //actualizar_prep(unidad_id);
        }
          
  function iniciar_batch (trn_id, unidad)
        {               
            trn_id = trn_id;
            unidad_id = unidad;
            //alert(trn_id);
            $('#iniciando_modal').modal('show');  
            document.getElementById("mina").value = unidad;
            $.ajax({
            		url: 'iniciar_batch.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {trn_id: trn_id},
            	})
            	.done(function(respuesta){
            		///$("#placas_dat").html(respuesta);                      
                       alert(respuesta);
              })
              actualizar_prep(unidad_id);
        }
  
  function iniciar_batch_ree (trn_id, unidad)
        {               
            trn_id = trn_id;
            unidad_id = unidad;
            //alert(trn_id);
            $('#iniciando_modal').modal('show');  
            document.getElementById("mina").value = unidad;
            $.ajax({
            		url: 'iniciar_batch_ree.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {trn_id: trn_id},
            	})
            	.done(function(respuesta){
            		///$("#placas_dat").html(respuesta);                      
                       alert(respuesta);
              })
              actualizar_prep(unidad_id);
        }
          
  function iniciar_etapa(trn_id, etapa, unidad)
        {               
            trn_id = trn_id;
            unidad_id = unidad;
            etapa_id = etapa;
            document.getElementById("mina").value = unidad; 
            document.getElementById("mina_secado").value = unidad; 
            //alert(etapa_id);           
            $.ajax({
            		url: 'iniciar_etapa.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {trn_id:trn_id,etapa_id:etapa_id},
            	})
            	.done(function(respuesta){ 
                   // alert(respuesta);
                    if(etapa_id == 1){
                        $('#secado_modal').modal('show');
                        $("#datos_secado").html(respuesta);
                        $('#secado_modal').on('shown.bs.modal', function (e) {
                            $(this).find('#peso1').focus();
                        })          
                    }
                    if(etapa_id == 2){
                        //alert(respuesta);
                        $('#quebrado_modal').modal('show');
                        $("#datos_quebrado").html(respuesta);
                        $('#quebrado_modal').on('shown.bs.modal', function (e) {
                            $(this).find('#peso_que1').focus();
                        })
                    }
                    if (etapa_id == 3){
                        $('#pulverizado_modal').modal('show');
                        $("#datos_pulverizado").html(respuesta);
                        $('#pulverizado_modal').on('shown.bs.modal', function (e) {
                                $(this).find('#peso_pul1').focus();
                        })
                        $('#pulverizado_modal').modal('show').trigger('shown');
                    }
             })
        }
        
   function iniciar_etapa_reen(trn_id, etapa, unidad)
        {               
            trn_id    = trn_id;
            unidad_id = unidad;
            etapa_id = etapa;
            document.getElementById("mina").value = unidad_id; 
            //alert(etapa_id);           
            $.ajax({
            		url: 'iniciar_etapa_ree.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {trn_id:trn_id,etapa_id:etapa_id},
            	})
            	.done(function(respuesta){                  
                    if(etapa_id == 2){
                        //alert(respuesta);
                        $('#quebrado_modal').modal('show');
                        $("#datos_quebrado").html(respuesta);
                        $('#quebrado_modal').on('shown.bs.modal', function (e) {
                            $(this).find('#peso_que1').focus();
                        })
                    }
                    if (etapa_id == 3){
                        $('#pulverizado_modal').modal('show');
                        $("#datos_pulverizado").html(respuesta);
                        $('#pulverizado_modal').on('shown.bs.modal', function (e) {
                                $(this).find('#peso_pul1').focus();
                        })
                        $('#pulverizado_modal').modal('show').trigger('shown');
                    }
             })
        }
  
        
   function peso_guardar(trn_bat_sec, trn_rel_sec, cont_sec)
    {
         var c = 1;
         var warn_porc = 0;
         var warning_0 = 0;
         var trn_id_sec     = trn_bat_sec;
         var trn_id_rel_sec = trn_rel_sec;
         var table = document.getElementById("tabla_secado");
         var total_rows_q = parseInt(table.rows.length)-2;
         
         if (total_rows_q == c){
             var fin = 1;
         }
         else{
             var fin = 0;
         }
         //Validar que el porcentajes sean mayores a 70  
         peso_se = "peso_seco"+c;  
                
         peso_seco = document.getElementById(peso_se).value;
         //alert(peso_se);
         if(peso_seco == 0 || peso_seco == '')
            {
                error_det = 'Error: el peso no puede ser cero. Reintente Linea 1.';
                warning_0 = 1; 
                alert(error_det);                         
            }
        else {
                $('#boton_save_secado').html('<div class="loading"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span></div>'); 
                        $.ajax({
                    		url: 'guardar_secado.php' ,
                    		type: 'POST' ,
                    		dataType: 'html',
                    		data: {trn_id_sec:trn_id_sec, trn_id_rel_sec:trn_id_rel_sec, peso_seco:peso_seco, fin:fin},
                        }).done(function(respuesta){
                            if (respuesta.match(/ha finalizado.*/)) {
                                alert('Ha finalizado la etapa de secado');
                                $("#datos_secado").html(respuesta);
                                $('#secado_modal').modal('show');
                            }
                            else
                            {                                
                                $('#secado_modal').modal('show');
                                $("#datos_secado").html(respuesta);
                                $('#secado_modal').on('shown.bs.modal', function (e) {
                                    $(this).find('#peso_seco1').focus();
                                })
                                $('#secado_modal').modal('show').trigger('shown');
                           }
                        })
              }
           }      
         
    // $('#guardando_modal_peso').modal('show');  
     //Validar que todos los pesos sean diferentes de 0
    /* while(j <= total_rows)
         {
            row = "peso"+j;            
            total_peso = document.getElementById(row).value;
            //alert(total_peso);
            if(total_peso == '' || total_peso == 0)
            {
                existe_cero = 1;
                j = total_rows+1;
            }
            j++;
         }         
     j = 1;   
     if (existe_cero == 1)
     {  
        alert('El peso debe ser diferente de 0');
     }
     else {
       while(j<=total_rows)
         {
            row = "peso_seco"+j;
            trn_id_b = "trn_batch"+j;
            trn_id_r = "trn_rel"+j;
            total_peso = document.getElementById(row).value;
            trn_id = document.getElementById(trn_id_b).value;
            trn_id_rel = document.getElementById(trn_id_r).value;
            
            //alert(total_peso);
           
            if (j == total_rows){                
                    //alert(trn_id_rel);
                    fin = 1;
                    $('#boton_save_sec').html('<div class="loading"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span></div>');
                    $.ajax({
                		url: 'guardar_secado.php' ,
                		type: 'POST' ,
                		dataType: 'html',
                		data: {trn_id:trn_id, trn_id_rel:trn_id_rel, total_peso:total_peso, fin:fin},
                    }).done(function(respuesta){
                        $('#secado_modal').modal('show');
                        $("#datos_secado").html(respuesta);   
                        $('#boton_save_sec').hide();                     
                    })
                    j++;
             }       
             else{                
                  $.ajax({
                		url: 'guardar_secado.php' ,
                		type: 'POST' ,
                		dataType: 'html',
                		data: {trn_id:trn_id, trn_id_rel:trn_id_rel, total_peso:total_peso, fin:fin},
                    }).done(function(respuesta){
                        if(respuesta == 'Hubo un error, reintente por favor.')//alert(respuesta);  
                        {
                            alert(respuesta);
                            $('#secado_modal').modal('show');
                            $("#datos_secado").html(respuesta); 
                        }
                        else
                        {
                            //$('#secado_modal').modal('hide');
                            $('#secado_modal').modal('show');
                            $("#peso_editar").html(respuesta);
                        }
                    })
                    j++;
                }
            }                         
       }      
      //$('#secado_modal').modal('show');
    }*/
    
    //Guardar peso humedo
    function humedad_peso_guardar(trnid_ph, trnid_rel_ph, contador_ph)
    {
         var co_ph = contador_ph;   
         var warn_porc_ph = 0;
         var trn_id_ph = trnid_ph;
         var trn_id_rel_ph = trnid_rel_ph;
         var table = document.getElementById("tabla_humedad");
         var total_rows_ph = parseInt(table.rows.length)-1;
         if (total_rows_ph == co_ph){
             var fin_hum = 1;
         }
         else{
             var fin_hum = 0;
         }
           // alert(fin_hum);
            peso_h = "peso_hum"+co_ph;
            
            total_ph = document.getElementById(peso_h).value;
            
            if(total_ph == 0 || total_ph == ''){
                alert('Error. El peso debe ser mayor de 0. Reintente')
            }
            else{
                $('#boton_save_hum').html('<div class="loading"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span></div>');
                    $.ajax({
                		url: 'guardar_peso_humedo.php' ,
                		type: 'POST' ,
                		dataType: 'html',
                		data: {trn_id_ph:trn_id_ph, trn_id_rel_ph:trn_id_rel_ph, total_ph:total_ph},
                    }).done(function(respuesta){                                                    
                            $('#humedad_modal').modal('show');
                            $("#datos_humedad").html(respuesta);
                            $('#humedad_modal').on('shown.bs.modal', function (e) {
                                $(this).find('#peso_hum1').focus();
                                $(this).find('#peso_sec1').focus();
                            })
                            $('#humedad_modal').modal('show').trigger('shown');
                      
                    })
          } 
    }
    
    function humedad_guardar(trnid_hum, trnid_rel_hum, contador)
    {
         var co = contador;   
         var warn_porc_hum = 0;
         var trn_id_hum = trnid_hum;
         var trn_id_rel_hum = trnid_rel_hum;
         var table = document.getElementById("tabla_humedad");
         var total_rows_h = parseInt(table.rows.length)-1;
         if (total_rows_h == co){
             var fin_hum = 1;
         }
         else{
             var fin_hum = 0;
         }
         //Validar que el porcentajes sean mayores a 70   
        
           // alert(fin_hum);
            peso_hum = "peso_hum"+co;
            peso_sec = "peso_sec"+co;
            porc_h   = "porc_hum"+co;
            
            total_peso_hum = document.getElementById(peso_hum).value;
            total_peso_sec = document.getElementById(peso_sec).value;
            total_porc_hum = document.getElementById(porc_h).value;
            
            if(total_peso_hum == 0 || total_peso_sec == 0){
                alert('Error. El peso debe ser mayor de 0. Reintente')
            }
            if(total_porc_hum < 0){
                alert('Error. El % no debe ser negativo. Reintente')
            }
            else{
                $('#boton_save_hum').html('<div class="loading"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span></div>');
                    $.ajax({
                		url: 'guardar_humedad.php' ,
                		type: 'POST' ,
                		dataType: 'html',
                		data: {trn_id_hum:trn_id_hum, trn_id_rel_hum:trn_id_rel_hum, total_peso_hum:total_peso_hum, total_peso_sec:total_peso_sec, total_porc_hum:total_porc_hum, fin_hum:fin_hum},
                    }).done(function(respuesta){
                        //if(respuesta == 'El m�todo ha finalizado.')//alert(respuesta);  
                       // {
                       //     alert(respuesta);
                      //  }
                      //  else
                     //   {                            
                            $('#humedad_modal').modal('show');
                            $("#datos_humedad").html(respuesta);
                            $('#humedad_modal').on('shown.bs.modal', function (e) {
                                $(this).find('#peso_que1').focus();
                            })
                            $('#humedad_modal').modal('show').trigger('shown');
                       // }
                    })
          } 
    }
    
    
    function quebrado_guardar(trnid_batch, trnid_rel, contador)
    {
         //var c = contador;   Esto se habilita si no queremos llevar orden en los folios de muestras
         var c = 1;
         var warn_porc = 0;
         var warning_0 = 0;
         var trn_id_que = trnid_batch;
         var trn_id_rel_que = trnid_rel;
         var error_det = "";
         var table = document.getElementById("tabla_quebrado");
         var total_rows_q = parseInt(table.rows.length)-2;
         if (total_rows_q == c){
             var fin = 1;
         }
         else{
             var fin = 0;
         }
         //Validar que el porcentajes sean mayores a 70  
         peso_ma_qu = "peso_malla_que"+c; 
         porc_que = "porc_que"+c;
         com_que  = "comentario_que"+c;
         total_porc_que = document.getElementById(porc_que).value;
         total_pesomalla_que = document.getElementById(peso_ma_qu).value;
         comentario_que = document.getElementById(com_que).value;
         
          if(total_porc_que == 0 || total_porc_que == '' || total_pesomalla_que == '' )
                        {
                            error_det = 'Error: el porcentaje no puede ser cero. Reintente L�nea 1.';
                            warning_0 = 1;                          
                        }
        
         if  (total_porc_que < 60 && comentario_que == '' && total_porc_que != '')  {   //Cambia a de 70 a 60
              warn_porc = 1;
              error_det = 'Si el porcentaje es menor que 60 debe especificar un comentario';
         }
                   
         if (warn_porc == 1 )
                 {  
                    alert(error_det);
                    document.getElementById(com_que).disabled = false;
                 }
         else{
            if (warning_0 == 1){
                alert(error_det);
            }
           
            else {
                peso_que = "peso_que"+c;
                peso_malla_que = "peso_malla_que"+c;
                porc_que = "porc_que"+c;
                comentarios_que = "comentario_que"+c;
                
                total_peso_que = document.getElementById(peso_que).value;
                total_mal_que  = document.getElementById(peso_malla_que).value;
                total_porc_que = document.getElementById(porc_que).value;
                coment_que     = document.getElementById(comentarios_que).value;
                $('#boton_save_quebrado').html('<div class="loading"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span></div>'); 
                        $.ajax({
                    		url: 'guardar_quebrado.php' ,
                    		type: 'POST' ,
                    		dataType: 'html',
                    		data: {trn_id_que:trn_id_que, trn_id_rel_que:trn_id_rel_que, total_peso_que:total_peso_que, total_mal_que:total_mal_que, total_porc_que:total_porc_que, coment_que:coment_que, fin:fin},
                        }).done(function(respuesta){
                            if (respuesta.match(/Ha finalizado.*/)) {
                                alert('Ha finalizado la etapa de quebrado');
                                $("#datos_quebrado").html(respuesta);
                                $('#quebrado_modal').modal('show');
                            }
                            else
                            {                                
                                $('#quebrado_modal').modal('show');
                                $("#datos_quebrado").html(respuesta);
                                $('#quebrado_modal').on('shown.bs.modal', function (e) {
                                    $(this).find('#peso_que1').focus();
                                })
                                $('#quebrado_modal').modal('show').trigger('shown');
                           }
                        })
              }
           }
    }
    
    function pulverizado_guardar(trnid_batch, trnid_rel, contador)
    {
         //var c = contador;   Para poder guardar cualquier renglon de la tabla habilitar esta opcion
         var c = 1;
         var warn_porc = 0;
         var warning_0 = 0;
         var error="";
         var trn_id_pul = trnid_batch;
         var trn_id_relpul = trnid_rel;
         var table = document.getElementById("tabla_pulverizado");
         var total_rows_p = parseInt(table.rows.length)-2;
         if (total_rows_p == c){
             var fin = 1;
         }
         else{
             var fin = 0;
         }
         //Validar que el porcentajes sean mayores a 85   
         porc_pul = "porc_pul"+c;
         total_pesomalla_pulv = "peso_malla_pul"+c;
         com_pul  = "comentario_pul"+c;
         total_porc_pul = document.getElementById(porc_pul).value;
         total_pesomalla_pul = document.getElementById(total_pesomalla_pulv).value;
         comentario_pul = document.getElementById(com_pul).value;
         
         if(total_porc_pul == 0 || total_porc_pul == '' || total_pesomalla_pul == '' )
                        {
                            error_det = 'Error: el porcentaje no puede ser cero. Reintente Linea 1.';
                            warning_0 = 1;                          
                        }
        
         
         if(total_porc_pul < 85 && comentario_pul == '' && total_porc_pul != '')
            {
                warn_porc = 1;
                error_det = 'Si el porcentaje es menor q<strong></strong>ue 85 debe especificar un comentario.';
            }      
      
         if (warn_porc == 1)
         {  
            alert(error_det);
            document.getElementById(com_pul).disabled = false;
         }
         else{
            if (warning_0 == 1){
                alert(error_det);
            }
            else {
                peso_pul = "peso_pul"+c;
                peso_malla_pul = "peso_malla_pul"+c;
                porc_pul = "porc_pul"+c;
                comentarios_pul = "comentario_pul"+c;
                
                total_peso_pul = document.getElementById(peso_pul).value;
                total_mal_pul = document.getElementById(peso_malla_pul).value;
                total_porc_pul = document.getElementById(porc_pul).value;
                coment_pul = document.getElementById(comentarios_pul).value; 
                //alert(total_porc_pul);           
                //alert(trn_id_pul);alert(trn_id_relpul);alert(total_peso_pul);  alert(total_mal_pul);alert(total_porc_pul);  alert(coment_pul);  alert(fin);
                $('#boton_save_pulverizado').html('<div class="loading"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span></div>'); 
                     $.ajax({
                    		url: 'guardar_pulverizado.php' ,
                    		type: 'POST' ,
                    		dataType: 'html',
                    		data: {trn_id_pul:trn_id_pul, trn_id_relpul:trn_id_relpul, total_peso_pul:total_peso_pul, total_mal_pul:total_mal_pul, total_porc_pul:total_porc_pul, coment_pul:coment_pul, fin:fin},
                        }).done(function(respuesta){
                            //alert(respuesta);
                            if (respuesta.match(/Ha finalizado.*/)) {
                                alert('Ha finalizado la etapa de Pulverizado');
                                $("#datos_pulverizado").html(respuesta);
                                $('#pulverizado_modal').modal('show');
                            }
                            else
                            {
                                $("#datos_pulverizado").html(respuesta);
                                $('#pulverizado_modal').modal('show');
                                $('#pulverizado_modal').on('shown.bs.modal', function (e) {
                                    $(this).find('#peso_pul1').focus();
                                })
                                $('#pulverizado_modal').modal('show').trigger('shown');
                            }
                        })
                }
          }
    }
    
     function actualizar_prep(unidad_prep)
    {
        var unidad_id = unidad_prep;
        var direccionar = '<?echo "\seguimiento_ordenes.php?unidad_id="?>'+unidad_id;                                  
        window.location.href = direccionar;  
    }
    
    function actualizar()
    {
        var unidad_id = document.getElementById('mina_secado').value;
        var unidad_id = document.getElementById('mina').value;
        var direccionar = '<?echo "\seguimiento_ordenes.php?unidad_id="?>'+unidad_id;                                  
        window.location.href = direccionar;  
    }
    
     function actualizar_ree()
    {
        var unidad_id = document.getElementById('mina_que_ree').value;
        var direccionar = '<?echo "\seguimiento_ordenes.php?unidad_id="?>'+unidad_id;                                  
        window.location.href = direccionar;  
    }
        
     function actualizar_hum()
    {
        var unidad_hum = document.getElementById('mina_hum').value;
        var direccionar = '<?echo "\seguimiento_ordenes.php?unidad_id="?>'+unidad_hum;                                  
        window.location.href = direccionar;  
    }
    
     function actualizar_importar()
    {
        var unidad_id = document.getElementById('mina_abs_esp').value;
        var direccionar = '<?echo "\seguimiento_ordenes.php?unidad_id="?>'+unidad_id;                                  
        window.location.href = direccionar;  
    }
    
     function actualizar_met()
    {
        var unidad_id = document.getElementById('mina_met').value;
        var direccionar = '<?echo "\seguimiento_ordenes.php?unidad_id="?>'+unidad_id;                                  
        window.location.href = direccionar;  
    }
    
    function calcula_porc(cont)
    {
         var j = 1;
         var cont = cont;
         //alert(cont);
         var peso_mu  = "peso_que"+cont;
         var peso_ma  = "peso_malla_que"+cont;
         var porc_pos = "porc_que"+cont;
         var porcentaje = 0;
         peso_muestra = document.getElementById(peso_mu).value;
         peso_malla   = document.getElementById(peso_ma).value;
         porcentaje = ((peso_muestra-peso_malla)/peso_muestra)*100;  
         document.getElementById(porc_pos).value = porcentaje;
    }
    //% de humedad
    function calcula_porc_hum(cont)
    {
         var j = 1;
         var cont = cont;
         //alert(cont);
         var peso_hum  = "peso_hum"+cont;
         var peso_sec  = "peso_sec"+cont;
         var porc_hum = "porc_hum"+cont;
         var porcentaje_hum = 0;
         
         peso_humedo    = document.getElementById(peso_hum).value;
         peso_seco      = document.getElementById(peso_sec).value;
         porcentaje_hum = ((peso_humedo-peso_seco)/peso_humedo)*100;  
         document.getElementById(porc_hum).value = porcentaje_hum;
    }
    
    function calcula_porc_pulv(con)
    {
         var j = 2;
         var con = con;
         //alert(cont);
         var peso_mu_p    = "peso_pul"+con;
         var peso_ma_p    = "peso_malla_pul"+con;
         var porc_pos_p   = "porc_pul"+con;
         var porcentaje_p = 0;
         peso_muestra_p   = document.getElementById(peso_mu_p).value;
         peso_malla_p     = document.getElementById(peso_ma_p).value;
         porcentaje_p     = ((peso_muestra_p-peso_malla_p)/peso_muestra_p)*100;  
         document.getElementById(porc_pos_p).value = porcentaje_p;
    }
    
    //Metodos
    function iniciar_metodo(trn_id, metodo, fase, etapa, unidad)
    {
            trn_id = trn_id;
            metodo = metodo;
            fase   = fase;
            etapa  = etapa;
            //unidad = unidad;
            unidad_tem = unidad;
            document.getElementById("mina_met").value = unidad_tem;
            //document.getElementById("mina_temp").value = unidad_tem;
            //alert(metodo);
            $.ajax({
            		url: 'iniciar_metodo.php',
            		type: 'POST' ,
            		dataType: 'html',
            		data: {trn_id:trn_id, metodo:metodo, fase:fase, etapa:etapa, unidad_tem:unidad_tem},
            }).done(function(respuesta){
            //alert(respuesta);
            if (etapa == 5){//Pesaje muestra
                //alert('ok');
                $('#metodo_modal').modal('show');
                $("#datos_metodo").html(respuesta); 
                $('#metodo_modal').on('shown.bs.modal', function (e) {
                    $(this).find('#peso_met1').focus();
                })                
                $('#metodo_modal').modal('show').trigger('shown');
                /* setTimeout(function(){
                    $('#peso_met0').focus();
                }, 10);*/
                 //$('input:visible:first').focus();                                   
                //$('#peso_met0').focus();
            }
            if (etapa == 8){//Fundicion
                $('#temperatura_modal').modal('show');
                $("#datos_temperatura").html(respuesta);
                $('#temperatura_modal').on('shown.bs.modal', function (e) {
                    $(this).find('#cantidad_tem').focus();
                })                
                $('#temperatura_modal').modal('show').trigger('shown');
            }
            if (etapa == 6){//Pesaje Pay�n
                $('#payon_modal').modal('show');
                $("#datos_payon").html(respuesta);
                $('#payon_modal').on('shown.bs.modal', function (e) {
                    $(this).find('#peso_pay1').focus();
                })                
                $('#payon_modal').modal('show').trigger('shown');
            }
            if (etapa == 9){//Copelado
                $('#copelado_modal').modal('show');
                $("#datos_copelado").html(respuesta);
                $('#copelado_modal').on('shown.bs.modal', function (e) {
                    $(this).find('#cantidad_cop').focus();
                })                
                $('#copelado_modal').modal('show').trigger('shown');
            }
            if (etapa == 4){//Digesti�n
                $('#digestion_modal').modal('show');
                $("#datos_digestion").html(respuesta);
                $('#digestion_modal').on('shown.bs.modal', function (e) {
                    $(this).find('#cantidad_dig').focus();
                })                
                $('#digestion_modal').modal('show').trigger('shown');
            }            
            if (etapa == 7){//Lectura de absorcion: exportar-importar CSV
                $('#importar_modal').modal('show');
                $("#datos_importar").html(respuesta);
            }
            if (etapa == 10){
               revision_abs(trn_id, metodo, unidad);
            }
            if (etapa == 11){
               enviar_resultados(trn_id, metodo, unidad, 1);
            }
            if (etapa == 12){
               enviar_resultados(trn_id, metodo, unidad, 0);
            }
            if (etapa == 16){//Cianuracion
                $('#temperatura_modal').modal('show');
                $("#datos_temperatura").html(respuesta);
                $('#temperatura_modal').on('shown.bs.modal', function (e) {
                    $(this).find('#cantidad_tem_cia').focus();
                })                
                $('#temperatura_modal').modal('show').trigger('shown');
            }
            if (etapa == 17){//Agitacion
                $('#temperatura_modal').modal('show');
                $("#datos_temperatura").html(respuesta);
                $('#temperatura_modal').on('shown.bs.modal', function (e) {
                    $(this).find('#hora_inicio').focus();
                })                
                $('#temperatura_modal').modal('show').trigger('shown');
            }
            if (etapa == 18){//Centrifugado
                $('#temperatura_modal').modal('show');
                $("#datos_temperatura").html(respuesta);
                $('#temperatura_modal').on('shown.bs.modal', function (e) {
                    $(this).find('#hora_final_cen').focus();
                })                
                $('#temperatura_modal').modal('show').trigger('shown');
            }
        })
    }
    
    function exportar_absorcion(trn_id_abs, metodo_abs, u_id_exp)
            {
                 var trn_id_a = trn_id_abs;
                 var metodo_id_a = metodo_abs;
                 var uid_exp = u_id_exp;
                 var exportar = '<?php echo "\ exportar_absatomica.php?trn_id_a="?>'+trn_id_a+'&metodo_id_a='+metodo_id_a+'&u_id_a='+uid_exp;                                  
                 window.location.href = exportar;
            }
    function actualizar_fechas()
            {
                 var fecha_i = document.getElementById('fecha_inicial').value;
                 var fecha_f = document.getElementById('fecha_final').value;
                 //alert(fecha_i);
                 var unid = <?echo $unidad_id;?>;
                 var exportars = '<?php echo "\ seguimiento_ordenes.php?unidad_id="?>'+unid+'&fecha_i='+fecha_i+'&fecha_f='+fecha_f;                                  
                 window.location.href = exportars;
            }
            
    function revision_abs(trn_id_abso, metodo_abso, unidad)
            {
                 var trn_id_absat    = trn_id_abso;
                 var metodo_id_absat = metodo_abso;
                 var unidad_id = unidad;
                     var exportar = '<?php echo "\ revision_absatomica.php?trn_id_abs="?>'+trn_id_absat+'&metodo_id_abs='+metodo_id_absat+'&unidad_id='+unidad_id;                                  
                     window.location.href = exportar;
            }
    
    function met_peso_guardar(trnid_batch, trnid_rel, metodo, fase, etapa, contador, unidad)
    {
         var contador = contador;   
         var warn_met = 0;
         var trnid_orden   = trnid_batch;
         var trnid_muestra = trnid_rel;
         var metodo_id = metodo;
         var fase = fase;
         var etapa = etapa;
         var unidad = unidad;
         var table = document.getElementById("tabla_pesaje_met");
         var total_rows_m = parseInt(table.rows.length)-3;
         //alert(contador);
         //$('#guardando_modal_peso').modal('show');
         //alert(total_rows_m);
         if (total_rows_m == contador){
             var fin_met = 1;
         }
         else{
             var fin_met = 0;
         }
         //Validar que el porcentajes sean mayores a 70   
         cantidad_met    = "peso_met"+contador;
         cantidad_metodo = document.getElementById(cantidad_met).value;
         //alert(cantidad_metodo);
         if(cantidad_metodo == 0 || cantidad_metodo == '' || cantidad_metodo == 0.00)
            {
                warn_met = 1;
                alert('El valor debe ser diferente de cero:');
            }
         else {
            cantidad_met = "peso_met"+contador;            
            cantidad_metodo = document.getElementById(cantidad_met).value;   
            //$('#boton_save').html('<div class="loading"><img src="images/upload.gif" alt="loading" /></div>');   
            //$('#peso_met1') = '<img src="images/upload.gif" alt="loading" />';
            //alert(trnid_orden);alert(trnid_muestra);alert(metodo_id);  alert(fase);alert(etapa);  alert(cantidad_metodo);  
            $('#boton_save').html('<div class="loading"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span></div>');
            //alert(fin_met); 
                    $.ajax({
                		url: 'guardar_met_peso.php' ,
                		type: 'POST' ,
                		dataType: 'html',
                		data: {trnid_orden:trnid_orden, trnid_muestra:trnid_muestra, metodo_id:metodo_id, fase:fase, etapa:etapa, cantidad_metodo:cantidad_metodo, fin_met:fin_met, unidad:unidad},
                    }).done(function(respuesta){
                        if(respuesta == 'Ha finalizado la etapa.')//alert(respuesta);  
                        {
                            alert(respuesta);
                        }
                        //else
                        //{
                            $('#metodo_modal').modal('show');
                            $("#datos_metodo").html(respuesta); 
                            $('#metodo_modal').on('shown.bs.modal', function (e) {
                                $(this).find('#peso_met1').focus();
                            })                
                            $('#metodo_modal').modal('show').trigger('shown');
                })
          }
    }
    
    function temperatura_guardar(trn_id_tem, metodo_tem)
        {               
            var trn_id_tem    = trn_id_tem;
            var metodo_tem    = metodo_tem;
            var cantidad_tem  = document.getElementById("cantidad_tem").value;
            var ins_id_tem    = document.getElementById("ins_id").value;
            if (cantidad_tem == 0 || cantidad_tem == ''){
                alert('La temperatura no puede ser 0');
            }
            else{
                if(cantidad_tem < 1000 || cantidad_tem > 1100 ){
                    alert('Error: Temperatura fuera de rango, reintente por favor.');
                }        
                else{
                    $('#boton_save_fun').html('<div class="loading"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span></div>'); 
                    $.ajax({
                    		url: 'guardar_temperatura.php' ,
                    		type: 'POST' ,
                    		dataType: 'html',
                    		data: {trn_id_tem:trn_id_tem, metodo_tem:metodo_tem, cantidad_tem:cantidad_tem, ins_id_tem:ins_id_tem },
                    	})
                    	.done(function(respuesta){
                    		///$("#placas_dat").html(respuesta);  
                               alert(respuesta);
                               $('#boton_save_fun').html('<div class="loading" disabled><i class="fa fa-cloud fa-1x"></i><span class="sr-only">Loading...</span></div>'); 
                      })
                     // actualizar(unidad_id);
                }
            }
        }
    
    //Guardar agitacion cianuro      
    function agitacion_guardar(trn_id_ci, metodo_ci)
        {               
            var trn_id_agi    = trn_id_ci;
            var metodo_agi    = metodo_ci;
            var hora_ini  = document.getElementById("hora_inicio").value;
            var hora_fin  = document.getElementById("hora_final").value;
            var fase_id_agi = 7;
            var etapa_id_agi = 17;
            
            if (hora_ini == '' || hora_fin == ''){
                alert('La hora no puede ser vacio');
            }
          // alert(fase_id_agi);
           else{
               
                    $('#boton_save_fun').html('<div class="loading"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span></div>'); 
                    $.ajax({
                    		url: 'guardar_cianurado.php' ,
                    		type: 'POST' ,
                    		dataType: 'html',
                    		data: {trn_id_tem:trn_id_agi, metodo_tem:metodo_agi, hora_ini:hora_ini, hora_fin:hora_fin, fase_id_agi:fase_id_agi, etapa_id_agi:etapa_id_agi},
                    	})
                    	.done(function(respuesta){
                    		///$("#placas_dat").html(respuesta);  
                               alert(respuesta);
                               $('#boton_save_fun').html('<div class="loading" disabled><i class="fa fa-cloud fa-1x"></i><span class="sr-only">Loading...</span></div>'); 
                      })
                     // actualizar(unidad_id);
               }
           // }
        }
        
        //Guardar centrifugado cianuro      
    function centrifugado_guardar(trn_id_ce, metodo_ce)
        {               
            var trn_id_cen    = trn_id_ce;
            var metodo_cen    = metodo_ce;
            var hora_fin_cen  = document.getElementById("hora_final_cen").value;
            var hora_ini_ce = '';
            var fase_id_agi = 7;
            var etapa_id_agi = 18;
            
            if (hora_fin_cen == ''){
                alert('La hora no puede ser vacio');
            }
          // alert(fase_id_agi);
           else{
               
                    $('#boton_save_fun').html('<div class="loading"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span></div>'); 
                    $.ajax({
                    		url: 'guardar_centrifugado.php' ,
                    		type: 'POST' ,
                    		dataType: 'html',
                    		data: {trn_id_tem:trn_id_cen, metodo_tem:metodo_cen, hora_ini:hora_ini_ce, hora_fin:hora_fin_cen, fase_id_agi:fase_id_agi, etapa_id_agi:etapa_id_agi},
                    	})
                    	.done(function(respuesta){
                    		///$("#placas_dat").html(respuesta);  
                               alert(respuesta);
                               $('#boton_save_fun').html('<div class="loading" disabled><i class="fa fa-cloud fa-1x"></i><span class="sr-only">Loading...</span></div>'); 
                      })
                     // actualizar(unidad_id);
               }
           // }
        }
        
    //Temperatura guardar cianuro
    function temperatura_guardar_cianuro(trn_id_cian, metodo_cian)
        {               
            var trn_id_cia    = trn_id_cian;
            var metodo_cia    = metodo_cian;
            var cantidad_tem_cia  = document.getElementById("cantidad_tem_cia").value;
            if (cantidad_tem_cia == 0 || cantidad_tem_cia == ''){
                alert('La temperatura no puede ser 0');
            }
            else{
                if(cantidad_tem_cia < 68 || cantidad_tem_cia > 72 ){
                    alert('Error: Temperatura fuera de rango, reintente por favor.');
                }        
                else{
                    $('#boton_save_fun').html('<div class="loading"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span></div>'); 
                    $.ajax({
                    		url: 'guardar_temperatura_cia.php' ,
                    		type: 'POST' ,
                    		dataType: 'html',
                    		data: {trn_id_tem:trn_id_cia, metodo_tem:metodo_cia, cantidad_tem:cantidad_tem_cia },
                    	})
                    	.done(function(respuesta){
                    		///$("#placas_dat").html(respuesta);  
                               alert(respuesta);
                               $('#boton_save_fun').html('<div class="loading" disabled><i class="fa fa-cloud fa-1x"></i><span class="sr-only">Loading...</span></div>'); 
                      })
                     // actualizar(unidad_id);
                }
            }
        }
        
    //Guardar peso payon
    function met_payon_guardar(trnid_batch, trnid_rel, metodo, fase, etapa, contador, unidad)
    {
         //var con_pay  = contador;  Si quiero tomar en cuenta el contador del renglon esto permite guardar sin orden de tabla...preguntar a nancy
         var con_pay = 1;   
         var warn_pay = 0;
         var trnid_pay   = trnid_batch;
         var trnid_muestra_pay = trnid_rel;
         var metodo_id_pay = metodo;
         var fase_pay = fase;
         var etapa_pay = etapa;
         var unidad_pay = unidad;
         var table = document.getElementById("tab_datos_payon");
         var total_rows_pay = parseInt(table.rows.length)-4;
         //alert(total_rows_pay);alert(con_pay);
         if (total_rows_pay == con_pay){
             var fin_met_pay = 1;
         }
         else{
             var fin_met_pay = 0;
         }
         //Validar que el peso se encuentre entre 25 y 45 gramos   
         cantidad_pay        = "peso_pay"+con_pay;
         cantidad_met_pay = document.getElementById(cantidad_pay).value;
         if(cantidad_met_pay == 0 && cantidad_met_pay == '')
            {
                warn_pay = 1;
                alert('El valor debe ser diferente de cero l\u00EDnea: '+con_pay);
            }
         else {
            //alert(cantidad_met_pay);
            if (cantidad_met_pay < 25 || cantidad_met_pay > 45){
                alert('Error: Peso fuera de rango, se envia la muestra a REENSAYE');
                //Crear proceso para enviar a REE
            }
                cantidad_pay = "peso_pay"+con_pay;            
                cantidad_met_pay = document.getElementById(cantidad_pay).value;  
                //alert(fin_met_pay);       
            //alert(trnid_orden);alert(trnid_muestra);alert(metodo_id);  alert(fase);alert(etapa);  alert(cantidad_metodo);  
            //alert(fin_met);
                $('#boton_save_pay').html('<div class="loading"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span></div>'); 
                    $.ajax({
                		url: 'guardar_payon_peso.php' ,
                		type: 'POST' ,
                		dataType: 'html',
                	    data: {trnid_pay:trnid_pay, trnid_muestra_pay:trnid_muestra_pay, metodo_id_pay:metodo_id_pay, fase_pay:fase_pay, etapa_pay:etapa_pay, cantidad_met_pay:cantidad_met_pay, fin_met_pay:fin_met_pay},
                    }).done(function(respuesta){
                        if(respuesta == 'La etapa ha finalizado.')//alert(respuesta);  
                         {
                            alert(respuesta);
                         }
                      //  else
                    //    {
                            $('#payon_modal').modal('show');
                            $("#datos_payon").html(respuesta); 
                            $('#payon_modal').on('shown.bs.modal', function (e) {
                                $(this).find('#peso_pay1').focus();
                            })                
                            $('#payon_modal').modal('show').trigger('shown');
                        //}
                    })
          }
    }
    //Copelado
    function copelado_guardar(trn_id_cop, metodo_cop)
        {               
            var trn_id_cp    = trn_id_cop;
            var metodo_cp    = metodo_cop
            var cantidad_cp  = document.getElementById("cantidad_cop").value;
            var ins_id_cp    = document.getElementById("ins_id_cop").value;
            if (cantidad_cp == 0 || cantidad_cp == ''){
                alert('La temperatura no puede ser 0');
            }
            else 
            {
                if(cantidad_cp < 900 || cantidad_cp > 940){
                    alert('Error: Temperatura fuera de rango, reintente por favor');
                }
                else{
                    $('#boton_save_cop').html('<div class="loading"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span></div>'); 
                    $.ajax({
                    		url: 'guardar_copelado.php' ,
                    		type: 'POST' ,
                    		dataType: 'html',
                    		data: {trn_id_cp:trn_id_cp, metodo_cp:metodo_cp, cantidad_cp:cantidad_cp, ins_id_cp:ins_id_cp},
                    	})
                    	.done(function(respuesta){
                    		///$("#placas_dat").html(respuesta);  
                            alert(respuesta);
                            $('#boton_save_cop').html('<div class="loading" ><i  disabled="disabled" class="fa fa-cloud fa-1x"  disabled="disabled" ></i><span class="sr-only">Loading...</span></div>'); 
                      })
                }
           }            
        }
        
    //Digestion
    function digestion_guardar(trn_id_dig, metodo_dig)
        {         
            var trn_id_dg    = trn_id_dig;
            var metodo_dg    = metodo_dig
            var cantidad_dg  = document.getElementById("cantidad_dig").value;
            var error_tem = 0;
            var error_desc = '';
            if (cantidad_dg == 0 || cantidad_dg == ''){
                //alert('La temperatura no puede ser 0');
                error_tem = 1;
                error_desc = 'La temperatura no puede ser 0';
            }
            else 
            {
                if(metodo_dg == 3 && (cantidad_dg < 65 || cantidad_dg > 85)) {
                         error_desc = 'Error: Temperatura fuera de rango, reintente por favor';
                         error_tem = 1;
                }else{
                    if ((metodo_dg == 6 || metodo_dg == 7) && (cantidad_dg < 100 || cantidad_dg > 140)){                        
                         error_desc = 'Error: Temperatura fuera de rango, reintente por favor';
                         error_tem = 1;
                    }
                }
            }
             /*   else{
                    (metodo_dg == 6 || metodo_dg == 7){
                    if(cantidad_dg < 100 || cantidad_dg > 140){
                        alert('Error: Temperatura fuera de rango, reintente por favor');
                    } */  
            if (error_tem == 1){
                alert(error_desc);
            }
                else{
                    $('#boton_save_dig').html('<div class="loading"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span></div>'); 
                    $.ajax({
                    		url: 'guardar_digestion.php' ,
                    		type: 'POST' ,
                    		dataType: 'html',
                    		data: {trn_id_dg:trn_id_dg, metodo_dg:metodo_dg, cantidad_dg:cantidad_dg},
                    	})
                    	.done(function(respuesta){
                    		///$("#placas_dat").html(respuesta);  
                               alert(respuesta);
                               $('#boton_save_dig').html('<div class="loading" disabled><i class="fa fa-cloud fa-1x"></i><span class="sr-only">Loading...</span></div>'); 
                    })
                }
           // }
    }
    
    //Importar satisfactoriamente
    function ver_csv(archivo, metodid_importar)
        {         
               var archivo_imp = archivo;
               var unidad_id_abs =  document.getElementById("mina_absimp").value;  
               document.getElementById("mina_abs_esp").value = unidad_id_abs;  
               //$('#progress_modal').modal('show');
               //alert('El archivo se import� satisfactoriamente: '+archivo_imp);
               $.ajax({
                		url: 'leer_csv.php' ,
                		type: 'POST' ,
                		dataType: 'html',
                		data: {archivo_imp:archivo_imp},
                	})
                	.done(function(respuesta){                	   
                           alert(respuesta);   
                           actualizar_importar();                        
                })
        }
        
    //Importar error
    function error_csv(archivo)
        {         
               var archivo_imp = archivo;  
               alert('Error: Al importar el archivo, revise que el documento sea el correcto y reintente por favor: '+archivo_imp);            
        }
  
    function enviar_resultados(trn_id_env, metodo_id_env, unidad_id, pree)
        {         
               var trn_id_env = trn_id_env;
               var metodo_id_env = metodo_id_env;
               var pre = pree;               
               
               enviar_notificacion(trn_id_env, metodo_id_env);
               //Llamar funcion para enviar correo, pendiente
               //alert('Se ha enviado la alerta de liberaci\u00F3n al cliente, a continuaci\u00F3n se descargar\u00E1n los resultados.');               
              /* $.ajax({
                		url: 'liberar_resultados.php' ,
                		type: 'POST' ,
                		dataType: 'html',
                		data:{trn_id_env:trn_id_env, metodo_id_env:metodo_id_env,pre:pre},
                    }).done(function(respuesta){                        
                            alert(respuesta);
                    })
                    */
               var print_d = '<?php echo "\liberar_orden.php?trn_id_a="?>'+trn_id_env+'&metodo_id_a='+metodo_id_env+'&pree='+pre;                
               window.location.href = print_d;        
        }
    
   /* function enviar_resultados(trn_id_env, metodo_id_env)
        {         
               var trn_id_env = trn_id_env;
               var metodo_id_env = metodo_id_env;
               
               enviar_notificacion(trn_id_env, metodo_id_env);
               //Llamar funcion para enviar correo, pendiente
               //alert('Se ha enviado la alerta de liberaci\u00F3n al cliente, a continuaci\u00F3n se descargar\u00E1n los resultados.');               
               $.ajax({
                		url: 'liberar_resultados_ree.php' ,
                		type: 'POST' ,
                		dataType: 'html',
                		data:{trn_id_env:trn_id_env, metodo_id_env:metodo_id_env},
                    }).done(function(respuesta){                        
                            alert(respuesta);
                    })
                    
               var print_d = '<?php echo "\liberar_orden.php?trn_id_a="?>'+trn_id_env+'&metodo_id_a='+metodo_id_env;                
               window.location.href = print_d;        
        }*/
  
   function enviar_notificacion(trn_id_li, metodo_id_li)
        {    
                var trn_id_l = trn_id_li;
                var metodo_id_l  = metodo_id_li;
                $.ajax({
            		url: 'enviar_notificacion_liberacion.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {trn_id_l, trn_id_l, metodo_id_l,metodo_id_l},
            	})
            	.done(function(respuesta){
            	   
                })
        }
     
</script>
<!-- Modal PROGRESS-->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="progress_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Importar en progreso</h5> 
        <input type="text"  name="mina_abs_esp" id="mina_abs_esp" size=20 style="width:125px; color:#996633"  disabled />  
      </div>
      <div class="modal-body">
        <div class="text-center">
            <h4>Importando archivo CSV, por favor espere...</h4>        
            <img  src="images\upload.gif">          
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Iniciando PROGRESS-->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="iniciando_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Iniciando Orden...</h5>
        <input type="hidden"  name="mina_abs_esp" id="mina_abs_esp" size=20 style="width:125px; color:#996633"  disabled /> 
      </div>
      <div class="modal-body">
        <div class="text-center">
            <h4>Se esta iniciando la orden y sus controles, por favor espere...</h4>
            <input type="hidden" id="mina_prep" size=20 style="width:125px; color:#996633" value="<?php echo $unidad_id;?>" disabled />        
            <img  src="images\upload.gif">          
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" data-backdrop="static" data-keyboard="false" data-backdrop="static" data-keyboard="false"  id="secado_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable"  style="max-width: 650px!important;" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="reserva_modal_test">ETAPA SECADO</h5>                   
              </div>
              <div class="modal-body"> 
                     <div class="col-md-1 col-lg-1"></div>                  
                    <label for="fecha_pesaje" class="col-form-label">Fecha:</label>
                    <input type="date"  name="fecha_pesaje" id="fecha_pesaje" size=20 style="width:125px; color:#996633" value="<?php echo date("Y-m-d");?>" min="<?php echo date("Y-m-d");?>" disabled />
                    <!--<label for="mina" class="col-form-label">Mina:</label>  --!>
                    <input type="hidden"  name="mina_secado" id="mina_secado" size=20 style="width:125px; color:#996633"  disabled />              
              </div>
              <div class="modal-body">                    
                    <div class="col-md-12 col-lg-12" style="font-size:6px;" id="datos_secado">                  
                    </div>
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="actualizar();" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
    </div>              
</div>

<div class="modal fade" data-backdrop="static" data-keyboard="false" id="secado_modal_editar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"  style="max-width: 850px!important;" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="reserva_modal_test">Etapa secado</h5>                   
              </div>
              <div class="modal-body">                    
                    <label for="fecha_pesaje" class="col-form-label">Fecha:</label>
                    <input type="date"  name="fecha_pesaje" id="fecha_pesaje" size=20 style="width:125px; color:#996633" value="<?php echo date("Y-m-d");?>" min="<?php echo date("Y-m-d");?>" disabled />
              </div>
               <div class="modal-body">                    
                    <div class="col-md-12 col-lg-12" style="font-size:8px;" id="peso_editar">                  
                    </div>
               </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="actualizar();" data-dismiss="modal">Cerrar</button>
                   <!-- <button type="button" class="btn btn-primary" id="peso_btn" onclick="peso_editar()">Editar</button>--!>
              </div>
            </div>
    </div>              
</div>

<!--Modal humedad--!>
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="humedad_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable" style="max-width: 650px!important;" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="humedad">CALCULAR % DE HUMEDAD</h5>                   
              </div>
              <div class="modal-body">                    
                    <label for="fecha_humedad" class="col-form-label">Fecha:</label>
                    <input type="date"  name="fecha_pesaje" id="fecha_pesaje" size=20 style="width:125px; color:#996633" value="<?php echo date("Y-m-d");?>" min="<?php echo date("Y-m-d");?>" disabled />
                    <input type="hidden"  name="mina_hum" id="mina_hum" size=20 style="width:125px; color:#996633"  disabled />          
              </div>
              <div class="modal-body" style="font-size:5px;" id="datos_humedad">
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="actualizar_hum();" data-dismiss="modal">Cerrar</button>
                    <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" id="quebrado_btn" onclick="quebrado_guardar()">Guardar</button>--!>
              </div>
            </div>
    </div>              
</div>

<!--Modal quebrado--!>
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="quebrado_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable" style="max-width: 650px!important;" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="quebrado">ETAPA QUEBRADO</h5>                   
              </div>
              <div class="modal-body">                    
                    <label for="fecha_quebrado" class="col-form-label">Fecha:</label>
                    <input type="date"  name="fecha_pesaje" id="fecha_pesaje" size=20 style="width:125px; color:#996633" value="<?php echo date("Y-m-d");?>" min="<?php echo date("Y-m-d");?>" disabled />
                   <!-- <label for="mina" class="col-form-label">Mina:</label>-->
                    <input type="hidden"  name="mina" id="mina" size=20 style="width:125px; color:#996633"  disabled />          
              </div>
               <div class="modal-body" class="col-md-12 col-lg-12" style="font-size:5px;" id="datos_quebrado">
               </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="actualizar();" data-dismiss="modal">Cerrar</button>
                    <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" id="quebrado_btn" onclick="quebrado_guardar()">Guardar</button>--!>
              </div>
            </div>
    </div>              
</div>

<div class="modal fade" data-backdrop="static" data-keyboard="false" id="pulverizado_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable" style="max-width: 650px!important;" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="quebrado">ETAPA PULVERIZADO</h5>                   
              </div>
              <div class="modal-body">                    
                    <label for="fecha_quebrado" class="col-form-label">Fecha:</label>
                    <input type="date"  name="fecha_pesaje" id="fecha_pesaje" size=20 style="width:125px; color:#996633" value="<?php echo date("Y-m-d");?>" min="<?php echo date("Y-m-d");?>" disabled />
                   <!-- <label for="mina" class="col-form-label">Mina:</label>-->
                    <input type="hidden"  name="mina" id="mina" size=20 style="width:125px; color:#996633"  disabled />          
              </div>
               <div class="modal-body"  style="font-size:4px;" class="col-md-12 col-lg-12" id="datos_pulverizado">                    
                   <div  style="font-size:4px;" >                  
                    </div>
               </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="actualizar();" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
    </div>              
</div>

<div class="modal fade" data-backdrop="static" data-keyboard="false" id="metodo_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable" style="max-width: 750px!important;" role="document">
            <div class="modal-content">
              <div class="modal-header" >
                <h5 class="modal-title" id="nombre_etapa">METODOS</h5>                             
                <div class="col-md-1 col-lg-1">                
                    <input type="date" id="fecha_metodo"  value="<?php echo date("Y-m-d");?>" min="<?php echo date("Y-m-d");?>" disabled />                    
                    <input type="hidden" id="mina_met" size=20 style="width:125px; color:#996633"  disabled /> 
                </div>
              </div> 
              <div class="modal-body"  style="font-size:4px;" class="col-md-12 col-lg-12" id="datos_metodo">
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="actualizar_met();" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
    </div>              
</div>

<div class="modal fade" data-backdrop="static" data-keyboard="false" id="temperatura_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
              <div class="modal-header" >
                <h5 class="modal-title" id="nombre_etapa">METODOS</h5>                             
                <div class="col-md-1 col-lg-1">                
                    <input type="date" id="fecha_metodo"  value="<?php echo date("Y-m-d");?>" min="<?php echo date("Y-m-d");?>" disabled />                    
                    <input type="hidden" id="mina_tem" size=20 style="width:125px; color:#996633" value="<?php echo $unidad_id;?>" disabled /> 
                </div>
              </div> 
              <div class="modal-body"  style="font-size:4px;" class="col-md-12 col-lg-12" id="datos_temperatura">
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="actualizar_met();" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
    </div>              
</div>

<div class="modal fade" data-backdrop="static" data-keyboard="false" id="payon_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable" style="max-width: 750px!important;"  role="document">
            <div class="modal-content">
              <div class="modal-header" >
                <h5 class="modal-title" id="nombre_etapa">METODOS</h5>                             
                <div class="col-md-1 col-lg-1">                
                    <input type="date" id="fecha_metodo"  value="<?php echo date("Y-m-d");?>" min="<?php echo date("Y-m-d");?>" disabled />                    
                    <input type="hidden" id="mina_payon" size=20 style="width:200px; color:#996633" value="<?php echo $unidad_id;?>" disabled /> 
                </div>
              </div> 
              <div class="modal-body"  style="font-size:4px;" class="col-md-12 col-lg-12" id="datos_payon">
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="actualizar_met();" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
    </div>              
</div>

<div class="modal fade" data-backdrop="static" data-keyboard="false" id="copelado_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
              <div class="modal-header" >
                <h5 class="modal-title" id="nombre_etapa">METODOS</h5>                             
                <div class="col-md-1 col-lg-1">                
                    <input type="date" id="fecha_metodo"  value="<?php echo date("Y-m-d");?>" min="<?php echo date("Y-m-d");?>" disabled />                    
                    <input type="hidden" id="mina_cop" size=20 style="width:125px; color:#996633" value="<?php echo $unidad_id;?>" disabled /> 
                </div>
              </div> 
              <div class="modal-body"  style="font-size:4px;" class="col-md-12 col-lg-12" id="datos_copelado">
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="actualizar_met();" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
    </div>              
</div>

<div class="modal fade" data-backdrop="static" data-keyboard="false" id="digestion_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
              <div class="modal-header" >
                <h5 class="modal-title" id="nombre_etapa">METODOS</h5>                             
                <div class="col-md-1 col-lg-1">                
                    <input type="date" id="fecha_metodo"  value="<?php echo date("Y-m-d");?>" min="<?php echo date("Y-m-d");?>" disabled />                    
                    <input type="hidden" id="mina_dig" size=20 style="width:125px; color:#996633" value="<?php echo $unidad_id;?>" disabled /> 
                </div>
              </div> 
              <div class="modal-body"  style="font-size:4px;" class="col-md-12 col-lg-12" id="datos_digestion">
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="actualizar_met();" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
    </div>              
</div>

<div class="modal fade" data-backdrop="static" data-keyboard="false" id="importar_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
              <div class="modal-header" >
                <h5 class="modal-title" id="nombre_etapa">METODOS</h5>                             
                <div class="col-md-1 col-lg-1">                
                    <input type="date" id="fecha_metodo"  value="<?php echo date("Y-m-d");?>" min="<?php echo date("Y-m-d");?>" disabled />                    
                    <input type="hidden" id="mina_absimp" values="mina_absimp" size=20 style="width:125px; color:#996633" value="<?php echo $unidad_id;?>" disabled /> 
                </div>
              </div> 
              <div class="modal-body"  style="font-size:4px;" class="col-md-12 col-lg-12" id="datos_importar">
              </div>
              <?
              extract($_POST);        
              if ($action == "upload") //si action tiene como valor UPLOAD haga algo (el value de este hidden es es UPLOAD iniciado desde el value
                {
                //cargamos el archivo al servidor con el mismo nombre(solo le agregue el sufijo bak_)            
                    $archivo = $_FILES['excel']['name']; //captura el nombre del archivo
                    $archivo = strtoupper($archivo);
                    $tipo  = $_FILES['excel']['type']; //captura el tipo de archivo (2003 o 2007)
                    ///$dest  = 'c:\\xampp\\htdocs\\__pro\\argonaut\\VinculosKpi'.'\\ '; //lugar donde se copiara el archivo
                    //$dest  = '\\absorcion'.'\\ '; //lugar donde se copiara el archivo
                    $dest  = '/var/www/html/dgominedatalabs/absorcion'.'/ '; //lugar donde se copiara el archivo
                    $desti = rtrim($dest).$archivo;                        
                    copy($_FILES['excel']['tmp_name'],$desti);
                   /// echo $archivo_corto;
                    $archivo_exis = $mysqli->query("SELECT folio FROM arg_ordenes_csv WHERE folio = '".$archivo."'") or die(mysqli_error());             
                    $archivo_exist = $archivo_exis->fetch_assoc();
                    $archivo_ex = $archivo_exist['folio'];                   
                    if($archivo_ex == $archivo){
                        mysqli_multi_query ($mysqli, "UPDATE arg_ordenes_csv SET archivo_csv = '".$archivo."' WHERE folio = '".$archivo."'") OR DIE (mysqli_error($mysqli));
                        
                        echo "<script> ver_csv('$archivo');
                        $('#progress_modal').modal('show');
                        </script>";
                    }              
                    else{
                        echo "<script> error_csv('$archivo');</script>";
                    }
                    
                    ///mysqli_multi_query ($mysqli, "CALL arg_prc_ordenPulverizado(".$trn_id.", ".$trn_id_rel.", ".$peso.", ".$peso_malla.", ".$porc_que.", ".$u_id.",".$final.", '".$coment."')") OR DIE (mysqli_error($mysqli)); 
                   $action = '';
                }
                ?>
              <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="actualizar_met();" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
    </div>              
</div>
<?php 
if (isset($_GET['unidad_id'])){
    $mysqli -> set_charset("utf8");
   /* echo $fecha_i; 
    echo $fecha_f; */
             $datos_orden_detalle = $mysqli->query("SELECT 
                                                        ord.folio, DATE_FORMAT(ord.fecha, '%d-%m-%Y') AS fecha, ord.hora, us.nombre AS usuario, ord.trn_id
                                                       ,om.trn_id AS trn_id_batch, om.folio_interno, om.folio_inicial
                                                       ,om.folio_final, om.cantidad, om.estado as estado_id
                                                       ,(CASE om.estado WHEN 0 THEN 'Pendiente' WHEN 1 THEN 'Iniciada' WHEN 2 THEN 'Finalizada' END) AS estado
                                                       ,buscar_fase(om.trn_id,0) AS fase_id
                                                       ,buscar_etapa(om.trn_id,0) AS etapa_id
                                                       ,buscar_etapa_acceso (om.trn_id,0,".$u_id.") AS boton_acceso
                                                       ,buscar_etapa_nombre(om.trn_id,0) AS etapa
                                                       ,buscar_metodo(om.trn_id) AS metodo_id                                                                                                 
                                                       ,(CASE WHEN met1 = 1 THEN 'X' ELSE '' END) AS met1
                                                       ,(CASE WHEN met2 = 2 THEN 'X' ELSE '' END) AS met2
                                                       ,(CASE WHEN ord.trn_id_rel <> 0 THEN 1 ELSE 0 END) AS reensaye
                                                       ,buscar_humedad(om.trn_id) AS humedad
                                                       ,acceso_preparacion(".$u_id.") AS prepara
                                                       ,(CASE WHEN om.estado = 0 AND ord.trn_id_rel <> 0 THEN iniciar_reensayeOrden(ord.trn_id) ELSE 0 END) AS iniciar_reen
                                                    FROM
                                                     `ordenes_metodos` om
                                                    LEFT JOIN `arg_ordenes` ord
                                                        ON ord.trn_id = om.trn_id_rel
                                                    LEFT JOIN arg_usuarios us
                                            	       ON us.u_id = ord.usuario_id                                              
                                                    WHERE estado <> 99 AND ord.unidad_id = ".$unidad_id." 
                                                    AND DATE_FORMAT(ord.fecha, '%Y-%m-%d') BETWEEN '$fecha_i' AND '$fecha_f'
                                                    ORDER BY ord.fecha DESC, om.folio_interno DESC"
                                            ) or die(mysqli_error()); 
                                            
             $datos_metodos = $mysqli->query("SELECT nombre FROM arg_metodos WHERE tipo_id = 1") or die(mysqli_error());             
             $total_metodos = (mysqli_num_rows($datos_metodos));
             
             $unidad_mi = $mysqli->query("SELECT nombre FROM arg_empr_unidades WHERE unidad_id = ".$unidad_id) or die(mysqli_error());             
             $unidad_min = $unidad_mi->fetch_assoc();
             $unidad_mina = $unidad_min['nombre'];
             
             $imprime_etiq = $mysqli->query("SELECT acceso_preparacion(".$u_id.") AS etiquetas") or die(mysqli_error());             
             $imprime_etiqu = $imprime_etiq->fetch_assoc();
             $imprime_etiquetas = $imprime_etiqu['etiquetas'];
             
            ?>
             <div class="container-fluid">
             <br/><br/><br/><br/><br/>
              
                <div class="col-md-2 col-lg-2">                     
                      <label for="fecha_inicial"><b>DESDE:</b></label>
                      <input type="date" name="fecha_inicial" class="form-control" id="fecha_inicial" value="<?echo $fecha_i;?>" />
                </div>
                <div class="col-md-2 col-lg-2">
                      <label for="fecha_final"><b>HASTA:</b></label><br/>
                      <input type="date" name="fecha_final" class="form-control" id="fecha_final" value="<?echo $fecha_f;?>" />                                
                </div>                
                <div class="col-md-2 col-lg-4">
                    <label for="print"></label><br/><br/>
                    <button type='button' class='btn btn-success' onclick='actualizar_fechas();' name='print' id='print' >VER</button>                      
                </div>
                <br/><br/><br/><br/><br/>
                
                 <? 
                  $html_det = "<table class='table table-striped' id='motivos'>
                                <thead>                                
                                     <tr class='table-info'>      
                                        <th colspan='4'>Ordenes de trabajo: ".$unidad_mina."</th>      
                                        <th align='center' colspan='3'>Batch</th>
                                        <th colspan='2' center>Seguimiento</th>
                                        <th colspan='2' center>Listado</th>";
                                     /*   if ($imprime_etiquetas <> 0){
                                            $html_det = "<th scope='col1'>Controles</th>"; 
                                            $html_det = "<th scope='col1'>Etiquetas</th>";      
                                        } */
                                        $html_det.="<th></th>
                                     </tr>
                                    <tr class='table-info' justify-content: center;>            
                                        <th scope='col1'>Folio</th>
                                        <th scope='col1'>Batch</th>
                                        <th scope='col1'>Fecha</th>
                                        <th scope='col1'>Hora</th>
                                        <th scope='col1'>Total muestras</th>
                                        <th scope='col1'>De la muestra</th>
                                        <th scope='col1'>A la muestra</th>                                        
                                        <th scope='col1'>Estado</th>
                                        <th scope='col1'>Etapa</th>
                                        <th colspan='3'>Muestras</th>";
                                   /*     if ($imprime_etiquetas <> 0){
                                            $html_det = "<th scope='col1'>Controles</th>"; 
                                            $html_det = "<th scope='col1'>Etiquetas</th>";      
                                        }   */    
                                    $html_det.="</tr>
                               </thead>
                               <tbody>";
                               
                               while ($fila = $datos_orden_detalle->fetch_assoc()) {
                                   $num = 1;
                                   $variable_img = $fila['etapa_img'];
                                   $html_det.="<tr>";
                                      $html_det.="<td> <a href='orden_trabajo_rep.php?trn_id=".$fila['trn_id']."' target='_blank'>".$fila['folio']."</td>";
                                      $html_det.="<td> <a href='orden_trabajo_rep.php?trn_id=".$fila['trn_id']."' target='_blank'>".$fila['folio_interno']."</a></td>";
                                      $html_det.="<td>".$fila['fecha']."</td>";                                     
                                      $html_det.="<td>".$fila['hora']."</td>";
                                      $html_det.="<td>".$fila['cantidad']."</td>";                                 
                                      $html_det.="<td>".$fila['folio_inicial']."</td>";
                                      $html_det.="<td>".$fila['folio_final']."</td>";                                     
                                      $html_det.="<td>".$fila['estado']."</td>";                                      
                                    
                                      if ($fila['estado_id'] == 0){
                                            if ($fila['humedad'] <> 0){
                                                $html_det.="<td><a type='button' class='btn btn-warning' name='print' id='print'";
                                                if ( $fila['boton_acceso'] == 1){
                                                       $html_det.="onclick = iniciar_humedad(".$fila['trn_id_batch'].",".$unidad_id.")";
                                                }               
                                                $html_det.="><span class='fa fa-percent fa-2x'> Humedad </span>
                                                                </a>
                                                            </td>";
                                            }
                                            else{
                                                $html_det.="<td><a type='button' class='btn btn-warning' name='prinT' id='print'";
                                                                if($fila['reensaye'] == 0){
                                                                    if ($fila['prepara'] > 0){
                                                                        $html_det.="onclick = iniciar_batch(".$fila['trn_id_batch'].",".$unidad_id.")";
                                                                    }
                                                                 }
                                                                 else{
                                                                       
                                                                        if ($fila['prepara'] > 0 and $fila['iniciar_reen'] == 1){
                                                                            $html_det.="onclick = iniciar_batch_ree(".$fila['trn_id_batch'].",".$unidad_id.")";
                                                                        }   
                                                                 }                                                
                                                                 $html_det.="><span class='fa fa-check-circle-o fa-2x'>Preparar</span>
                                                                </a>
                                                            </td>";
                                            }                                        
                                      }
                                      if ($fila['estado_id'] == 1 or $fila['estado_id'] == 2){
                                            if($fila['fase_id'] == 1){                                                
                                                if($fila['reensaye'] == 1 ){
                                                    $html_det.="<td> <button type='button' class='btn btn-info'";
                                                        if ($fila['boton_acceso'] <> 0){
                                                            $html_det.="onclick = iniciar_etapa_reen(".$fila['trn_id_batch'].",".$fila['etapa_id'].",".$unidad_id.")";
                                                        }
                                                        
                                                            $html_det.="><span class='fa fa-hourglass-start fa-2x'>Iniciar ".$fila['etapa']." </span>
                                                                    </button></td>";
                                                        
                                                }
                                                else{
                                                    $html_det.="<td> <button type='button' class='btn btn-info'";
                                                                if ($fila['boton_acceso'] <> 0){    
                                                                    $html_det.="onclick = iniciar_etapa(".$fila['trn_id_batch'].",".$fila['etapa_id'].",".$unidad_id.")";
                                                                }
                                                                $html_det.="><span class='fa fa-hourglass-start fa-2x'>Iniciar ".$fila['etapa']." </span>
                                                                </button></td>";  
                                                }                                                                                              
                                            }else{
                                                $html_det.="<td>";
                                                  $metodos_lista = $mysqli->query("SELECT  nombre
                                                                                          ,om.metodo_id
                                                                                          ,m.color
                                                                                          ,buscar_fase(".$fila['trn_id_batch'].", om.metodo_id) AS fase_id
                                                                                          ,buscar_etapa(".$fila['trn_id_batch'].", om.metodo_id) AS etapa_id
                                                                                          ,buscar_etapa_acceso(".$fila['trn_id_batch'].", om.metodo_id, ".$u_id.") AS boton_acceso
                                                                                          ,buscar_etapa_nombre(om.trn_id_rel,om.metodo_id) AS etapa
                                                                                          ,buscar_etapa_img(om.trn_id_rel,om.metodo_id) AS etapa_img
                                                                                   FROM arg_metodos m
                                                                                   LEFT JOIN arg_ordenes_metodos om
                                                                                    ON m.metodo_id = om.metodo_id
                                                                                   WHERE m.metodo_id <> 4 AND om.trn_id_rel = ".$fila['trn_id_batch']) or die(mysqli_error());
                                                  while ($fila_met = $metodos_lista->fetch_assoc()) {
                                                        
                                                        if ($fila['estado_id'] == 2){
                                                            $variable_color = 'btn btn-success';
                                                        }
                                                        else{
                                                            $variable_color = $fila_met['color'];
                                                        }
                                                        $variable_img = $fila_met['etapa_img'];
                                                        $html_det.="<button type='button' class='".$variable_color."'";
                                                                    if ($fila_met['boton_acceso'] <> 0){
                                                                        $html_det.="onclick = iniciar_metodo(".$fila['trn_id_batch'].",".$fila_met['metodo_id'].",".$fila_met['fase_id'].",".$fila_met['etapa_id'].",".$unidad_id.")";
                                                                     }
                                                                    $html_det.="><span class='".$variable_img."'>".$fila_met['nombre']."  ".$fila_met['etapa']." </span>
                                                                    </button>";                                                                  
                                                  }
                                                  $html_det.="</td>";
                                              }
                                      }
                                     
                                      if ($fila['reensaye'] == 1){
                                            $html_det.="<td> <a button type='button'class='btn btn-success' href='orden_trabajo_muestras_ree.php?trn_id=".$fila['trn_id_batch']."&metodo_id=0&unidad_id=".$unidad_id."&ree=1' target='_blank'>
                                                                    <span class='fa fa-flask fa-2x'></span>
                                                                 </button></td>";
                                      }
                                          elseif ($fila['estado_id'] <> 0 ){
                                                $html_det.="<td> <a button type='button'class='btn btn-success' href='orden_trabajo_muestras.php?trn_id=".$fila['trn_id_batch']."&metodo_id=".$fila['metodo_id']."&unidad_id=".$unidad_id."' target='_blank'>
                                                                        <span class='fa fa-flask fa-2x'></span>
                                                                     </button></td>";
                                          }
                                          else{
                                                $html_det.="<td> <a button type='button'class='btn btn-success' href='orden_trabajo_muestras.php?trn_id=".$fila['trn_id_batch']."&metodo_id=0&unidad_id=".$unidad_id."' target='_blank'>
                                                                        <span class='fa fa-flask fa-2x'></span>
                                                                     </button></td>";
                                          }                                      
                                    
                                    if ($fila['prepara'] > 0){                                        
                                        $html_det.="<td> <a button type='button'class='btn btn-warning' href='orden_trabajo_muestrasControl.php?trn_id=".$fila['trn_id_batch']."&metodo_id=".$fila['metodo_id']."'&unidad_id=".$unidad_id."' target='_blank'>
                                                                        <span class='fa fa-flask fa-2x'></span>
                                                                     </button></td>";                                       
                                                         
                                        $html_det.="<td> <a button type='button'class='btn btn-secondary' href='etiquetas_muestras.php?trn_id=".$fila['trn_id_batch']."&metodo_id=".$fila['metodo_id']."' target='_blank'>
                                                                    <span class='fa fa-tags fa-2x'></span>
                                                                 </button></td>";
                                    }
                                                                 
                               }                              
                  $html_det.="</tbody></table>";
                  
                 echo ("$html_en");
                 echo ("$html_det");
                ?>
        </div>
            <?
    }
?>                    
 

