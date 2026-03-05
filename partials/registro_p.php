<? include "connections/config.php";?>

<?
$top = $mysqli->query("SELECT nombre, org_id FROM arg_organizaciones") or die(mysqli_error());
                   while($rowtop = $top ->fetch_array(MYSQLI_ASSOC))
                        {
                            $i = $i+1;
                            $nombre_empr[$i]["nombre"] = $rowtop["nombre"];
                        }

?>

<head>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="http://192.168.20.3:81/registro/js/JsLocalSearch.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"/>


<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js"></script>
<body>



        

<div class="container">
	<div class="row">
	<div class="col-lg-12 col-md-8 izq">
    
    
     
   <p><b>Por favor llena los siguientes datos</b></p>  
       
    <form method="post" action="register.php" name="registerform" id="registerform">  
    <fieldset>  
        <label for="email"><b>Email Address:</b><br></label>               
        <input type="email" name="email" id="email" class="form-control" required="" /><br />
        <label for="password"><b>Password:</b></label><br><input type="password" name="password" class="form-control" id="password" /><br />  
         <br />
         <label for="username"><b>Nombre:</b></label><br><input type="text" name="username" class="form-control" id="username" /><br />
       
        <br /> 
        <br />        
        <br />
         
         <div class="container mt-4">
             <input type="text" id="gsearchsimple" class="form-control input-lg" placeholder="Search.." />
             <ul class="list-group" >
             
             </ul>
             <div id="localSearchSimple"></div>
             <div id="detail"></div>   
         </div>
           
         <input type="submit" class="btn btn-primary" name="register" id="register" value="Register" />  
         </fieldset>  
    </form> 
    
    
       
</div>
</div>
 </div><!-- /.row -->
 </body>
</head>