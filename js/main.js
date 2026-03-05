$(buscar_datos());

function buscar_datos(consulta){
	$.ajax({
		url: 'buscar_orden.php' ,
		type: 'POST' ,
		dataType: 'html',
		data: {consulta: consulta},
	})
	.done(function(respuesta){
		//$("#datos").html(respuesta);
        $("#datos").fadeIn(1000).html(respuesta);        
        $('.suggest-element').on('click', function(){
                            //Obtenemos la id unica de la sugerencia pulsada
                            var id = $(this).attr('id');
                            //Editamos el valor del input con data de la sugerencia pulsada
                            $('#caja_busqueda').val($('#'+id).attr('data'));
                            //Hacemos desaparecer el resto de sugerencias
                            $('#datos').fadeOut(100);
                            //alert('Has seleccionado el '+id+' '+$('#'+id).attr('data'));
                           // return false;
                           
                    });
        
	})
	.fail(function(){
		console.log("error");
	});
}


$(document).on('keyup','#caja_busqueda', function(){
	var valor = $(this).val();
	if (valor != "") {
		buscar_datos(valor);
	}else{
		buscar_datos();
	}
});

