$(buscar_datos_mu());

function buscar_datos_mu(consulta){
	$.ajax({
		url: 'buscar_muestra.php' ,
		type: 'POST' ,
		dataType: 'html',
		data: {consulta: consulta},
	})
	.done(function(respuesta){
		$("#datos_muestra").fadeIn(1000).html(respuesta);
        $('.suggest-element').on('click', function(){
                            //Obtenemos la id unica de la sugerencia pulsada
                            var id = $(this).attr('id');
                            //Editamos el valor del input con data de la sugerencia pulsada
                            $('#caja_bus_mues').val($('#'+id).attr('data'));
                            //Hacemos desaparecer el resto de sugerencias
                            $('#datos_muestra').fadeOut(100);
                            //alert('Has seleccionado el '+id+' '+$('#'+id).attr('data'));
                           // return false;
                           
                    });
	})
	.fail(function(){
		console.log("error");
	});
}


$(document).on('keyup','#caja_bus_mues', function(){
	var valor_mu = $(this).val();
	if (valor_mu != "") {
		buscar_datos_mu(valor_mu);
	}else{
		buscar_datos_mu();
	}
});