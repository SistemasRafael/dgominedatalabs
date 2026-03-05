
<? include "connections/config.php";
    header('Content-Type: text/html; charset=UTF-8');
    $unidad_id = $_GET['unidad_id'];
    $_SESSION['unidad_id'] = $unidad_id;
?> 
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>MineData-Labs Argonaut Gold</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Argonaut Gold">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        
  <!-- Custom fonts for this template  --> 
  
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
   <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
 <!--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">  -->
   <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>   
   
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>   
   
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
   
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
   
<style type="text/css">
   body {
  padding-bottom: 10px;
}
.navbar {
  margin-bottom: 0px;
}
.bg-blue{
  background-color: #152c52;
}
.navbar-dark .navbar-nav .nav-link {
  color: white;
}
.navbar-brand {    
    height: 150px;
}
img{
  max-width: 20%;
}
.barra {
  width: 100%;
  padding: 5px;
  height: 60px;
  background-color: #cecece;
  text-align: left;
  color: blue;
  }
  .barra a{
    color: #152c52;
  }
  .nav-item a{
    font-size: 18px;
    font-weight: lighter;
    font-family: tahoma;
  }
 
 </style>

<div class="barra">
  <div class="container-fluid">
    <ul class="nav nav-pills">
        
           
          <li class="nav-item">
            <a class="navbar-brand logos" href="seguimiento_ordenes.php?unidad_id=<?echo $_SESSION['unidad_id'];?>">
                <img src="images/Minedata_lab_hs.png" alt="ArgonautGold Logo">
            </a>
           
          </li>   
       
  
          <?if ($_SESSION['LoggedIn'] == 1) {?>
         <li>       
            
                <div class="container">
                  
                        <a class="dropdown-item" href="">Hola <?echo $_SESSION['nombre']?></a>
                    
                </div>
             </li>       
           <li>          
                <a class="nav-item dropdown-item" href="logout.php">Cerra sesión</a>
                 
          </li>     

        <?}?> 
        
  </ul>  
  </div>
</div>


<? $unidad_mina_sel = $_GET['unidad'];
  if ($unidad_mina_sel == ''){
    $unidad_mina_sel = $_SESSION['unidad_def'];
  }
?>
