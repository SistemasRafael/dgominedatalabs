<!--<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>--!>
<?
$curso_id = $_GET['curso_id'];
?>
<div class="container">
<h2>Bienvenidos al curso de Inducción Básica en línea</h2>
<br />
<p>Una vez que visualice el contenido podrá realizar su evaluación</p>

<!-- Buttons -->
<div class="btn-group">

 <div class="col-md-12 col-lg-12">
    <div class="col-md-6 col-lg-6">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#videoModal" data-video="http://192.168.20.22/intranet-spa/video/BO-video1.mp4">Video Introductorio 1</button>
    </div>
    <div class="col-md-6 col-lg-6">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#videoModal" data-video="http://192.168.20.22/intranet-spa/video/BO-video2.mp4">Video Introductorio 2</button>
     </div>
     
     <br />
     <br />
     <br />
     <br />
     <br />
    <div class="col-md-6 col-lg-6">
        <button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#evaluaModal" name="evaluar" href="partials\evaluar.php?curso_id=<?echo $curso_id;?>">EVALUAR</button> 
    </div>
 </div>
</div>

<!-- Modal video -->
<div class="modal fade" id="videoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark border-dark">
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body bg-dark p-0">
                <div class="embed-responsive embed-responsive-16by9">
                  <iframe class="embed-responsive-item" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal evaluacion-->
<div class="modal fade" id="evaluaModal" tabindex="-1" role="dialog">

<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
              
              
             
            </div>
          </div>
    
   
</div>

</div>





<script>
$(document).ready(function() {
    // Set iframe attributes when the show instance method is called
    $("#videoModal").on("show.bs.modal", function(event) {
        let button = $(event.relatedTarget); // Button that triggered the modal
        let url = button.data("video");      // Extract url from data-video attribute
        $(this).find("iframe").attr({
            src : url,
            allow : "autoplay; encrypted-media; gyroscope; picture-in-picture"
        });
    });

    // Remove iframe attributes when the modal has finished being hidden from the user
    $("#videoModal").on("hidden.bs.modal", function() {
        $("#videoModal iframe").removeAttr("src allow"); 
    });
    
    
});

$(document).ready(function(){
  $("#thumbnail").click(function (e) {
        var id = e.target.id;
        alert(id); // Mostrar ID solo para verificar que sea correcto
    });
});

function GuardarEvaluacion()
        {
            var respuesta_1 = document.getElementById("exampleRadios1").checked;
            var respuesta_2 = document.getElementById("exampleRadios22").checked;
            var respuesta_31 =  document.getElementById("customCheck31").checked;
            var respuesta_42 = document.getElementById("customCheck42").checked;
            var respuesta_43 = document.getElementById("customCheck43").checked;
            //alert(imagen_sel);
            //alert(respuesta_1);
            if(respuesta_1 == true){
                alert ('La respuesta de la pregunta 1 es correcta');
            }
            else{
                 alert ('La respuesta de la pregunta 1 es incorrecta');   
                 }   
                 
            if(respuesta_2 == true){
                alert ('La respuesta de la pregunta 2 es correcta');
            }
            else{
                 alert ('La respuesta de la pregunta 2 es incorrecta');   
                 } 
                 
            if(respuesta_31 == true){
                alert ('La respuesta de la pregunta 3  es correcta');
            }
            else{
                 alert ('La respuesta de la pregunta 3  es incorrecta');   
                 }  
                 
            if(respuesta_42 == true){
                alert ('La respuesta de la pregunta 4 parte I es correcta');
            }
            else{
                 alert ('La respuesta de la pregunta 4 parte I es incorrecta');   
                 }
                
            if(respuesta_43 == true){
                alert ('La respuesta de la pregunta 4 parte II es correcta');
            }
            else{
                 alert ('La respuesta de la pregunta 4 parte II  es incorrecta');   
                 }        
            /*var marca_h  = document.getElementById("exampleRadios2").value; 
            var modelo_h = document.getElementById("exampleRadios3").value; 
            var serie_h  = document.getElementById("serie_h").value;*/

          /*  $.ajax({
            		url: 'guardar_evaluacion.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {nombre_h:nombre_h, marca_h:marca_h, modelo_h:modelo_h, serie_h:serie_h},
            	})
            	.done(function(respuesta){
            		$("#busqueda_herr0").html(respuesta);
                     //console.log(respuesta);
                    var herr_id = document.getElementById("busqueda_herr0").value;
                   // alert(veh_id);
                   // $("#marca").html(respuesta);
                    alert('Se guardó con éxito!');
                   
                     ShowSelected();
                     $('#Modalherr').hide(2);
              }) */                
      }
   
</script>
<!--
</body>
</html>   --!>
          

