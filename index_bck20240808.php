<?php
    include('partials/header_simple.php');
?>
         
<div class="container">
	<div class="row">
	<div class="col-12 col-md-7 left">
         
	<html>
	<head>
	<title>MineData Labs Argonaut Gold</title>
	<body>
     <h1>Iniciar Sesi&oacute;n</h1>
	<form method="post" action="control.php" name="loginform" id="loginform" style="width:198px; border:none" ><br>
		 <fieldset>
    		 <label for="usuario">Usuario:</label>
                      <input type="text" class="form-control" name="usuario" id="usuario" size="15" />
                      <br />
                    <label for="clave">Contrase&ntildea:</label>
                      <input type="password" class="form-control" name="clave" id="clave" size="15" />
                      <br />
    			<p>
                    <button type="submit" class="btn btn-primary">Iniciar</button>
    		    </p>
              
		   </fieldset>
		</form>
	</body>
	</html>
 </div> 

</div>
</div>

<?php exit(); ?>

              