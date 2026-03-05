<?php include "connections/config.php";  
include('partials/header.php');
$email = $_GET['email'];

echo $email;
?>
<script>
    function redireccion($email)
            {
                 var email = $email;                 
                 var print_d = '<?php echo "\cambiar_pass.php?email="?>'+email;                
                 window.location.href = print_d;
            }
            
    function inicio()
            {
                                
                 var print_d = '<?php echo "\index.php"?>';                
                 window.location.href = print_d;
            }
</script>

<div class="container">
	<div class="row">
	<div class="col-12 col-md-7 left">
         
	<html>
	<head>
	<title>Registro Argonaut Gold</title>
	<body>
     <h1>Cambia tu contrasena</h1><br />
     <h5>Crea una contraseña segura</h5>
	<form method="post" action="cambiar_pass.php?email=<?echo $email?>" name="loginform" id="loginform" style="width:198px; border:none" ><br>
		 <fieldset>
    		 <label for="clave">Contraseña:</label>
                <input type="password" class="form-control" name="clave1" id="clave1" size="15" />
                <br />
             <label for="clave">Confirmar Contraseña:</label>
                <input type="password" class="form-control" name="clave2" id="clave2" size="15" />
                <br />
    			<p>
                    <button type="submit" class="btn btn-primary">Guardar</button>
    		    </p>
		   </fieldset>
		</form>
	</body>
	</html>
 </div>
 <?
 if (isset($_POST['clave2'])){
    $clave1 = MD5($_POST['clave1']);
    $clave2 = MD5($_POST['clave2']);
    
    
    //echo $codigo_cap;
    
    if ($clave1 == $clave2){
        $query = "UPDATE arg_usuarios SET clave = '".$clave1."' WHERE email = '".$email."'";
        $mysqli->query($query) ;
        echo "Se cambio la contrasena correctamente";
        echo "<script> inicio();</script>";
    }
 else{
       echo '<div class="container">
    	<div class="row">
    	<div class="col-12 col-md-7 left">
            La contraseña no coincide
        </div>
        </div>
        </div>';
        echo "<script> redireccion('$email');</script>"; 
    }
 }
 
?>  
