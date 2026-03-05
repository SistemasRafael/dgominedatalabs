$(insertar_datos());

function insertar_datos(placa){
	$.ajax({
		url: 'insertar_veh.php' ,
		type: 'POST' ,
		dataType: 'html',
		data: {placa: placa},
	})
	.done(function(respuesta){
		$("#placas_dat").html(respuesta);
     //   alert(html(respuesta));
       // console.log(respuesta);
        //$("#placas").fadeIn(1000).html(respuesta);        
       // $('.suggest-element').on('click', function(){
                            //Obtenemos la id unica de la sugerencia pulsada
                          //  var id = $(this).attr('id');
                            //Editamos el valor del input con data de la sugerencia pulsada
                          //  $('#placas').val($('#'+id).attr('data'));
                            //Hacemos desaparecer el resto de sugerencias
                            //$('#datos').fadeOut(100);
                            //alert('Has seleccionado el '+id+' '+$('#'+id).attr('data'));
                            //return false;
                           
                    //});
        
	})
	.fail(function(){
		console.log("erroresss");
	});
}


