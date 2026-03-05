<?php include("seguridad.php"); ?>
	<html>
	<head>
		<title>Argonaut Gold</title>
         <input type="submit" class="btn btn-primary" name="visita" id="visita" onclick="agregarFila();" value="Agregar" />  
		</head>
		<body>
		
           

<script>            
function agregarFila(Id, Nombres, ApellidoP, AppellidoM) {
   
   var htmlTags = '<tr>'+
        '<td>' + Id + '</td>'+
        '<td>' + Nombres + '</td>'+
        '<td>' + ApellidoP + '</td>'+
        '<td>' + AppellidoM + '</td>'+
      '</tr>';
      
   $('#tabla tbody').append(htmlTags);

}
</script>
            
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<table id="tablaprueba">
  <thead>
    <tr>
      <th>Id</th>
      <th>Nombres</th>
      <th>Apellido Paterno</th>
      <th>Apellido Materno</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>


            
		</body>
		</html>
