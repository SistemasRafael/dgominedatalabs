<?php include "connections/config.php";  
include('partials/header.php');
$email = $_GET['email'];
?>

<script>
 function redireccion_cambio($email)
            {
                 var email = $email;
                 
                 var print_d = '<?php echo "\cambiar_pass.php?email="?>'+email;                
                 window.location.href = print_d;
            }
            
  function redireccion($email)
            {
                 var email = $email;
                 
                 var print_d = '<?php echo "\confirma_pass.php?email="?>'+email;                
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
     <h1>Codigo de Recuperacion</h1><br />
     <h5>Se ha enviado un codigo a su correo: <?echo $email;?>, favor de introducirlo:</h5>
	<form method="post" action="confirma_pass.php?email=<?echo $email?>" name="loginform" id="loginform" style="width:198px; border:none" ><br>
		 <fieldset>
    		 <label for="usuario">Codigo:</label>
                <input type="text" class="form-control" name="codigo" id="codigo" size="15" />
                <br />
                    
    			<p>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
    		    </p>
              
		   </fieldset>
		</form>
	</body>
	</html>
 </div>
 <?
 
 if (isset($_POST['codigo'])){
    $codigo_cap = $_POST['codigo'];
    //echo $codigo_cap;
 
    $codigo_user = $mysqli->query("SELECT codigo_reset FROM arg_usuarios WHERE email = '".$email."'") or die(mysqli_error());
    $codigo_user1 = $codigo_user ->fetch_array(MYSQLI_ASSOC);
    $codigo_reset = $codigo_user1['codigo_reset'];
    
    //echo $codigo_reset;
 
    if ($codigo_reset == $codigo_cap){
        echo "<script> redireccion_cambio('$email');</script>";
    }
 else{
       echo '<div class="container">
    	<div class="row">
    	<div class="col-12 col-md-7 left">
        Codigo Erroneo, reintenta...
        </div>
        </div>
        </div>';
        echo "<script> redireccion('$email');</script>"; 
    }
 }
 
 
?>  
