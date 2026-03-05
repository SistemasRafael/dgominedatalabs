<style type="text/css">
	.izq{
		background-color:;
	}
	.derecha{
		background-color:;
	}

	.btnSubmit
{
    width: 50%;
    border-radius: 1rem;
    padding: 1.5%;
    border: none;
    cursor: pointer;
}

.circulos{
	padding-top: 5em;
}

</style>



<div class="container">
	<div class="row">
	<div class="col-lg-12 col-md-8 izq">
    

           
             
        
    
    
    
    <div id="main">  
    <?php 
    if(isset($_POST['register']))
    { 
    if(!empty($_POST['username']) && !empty($_POST['password']))   
    {  
      $nivel = $_POST['level']; 
          
        $username = $mysqli->real_escape_string($_POST['username']);
        $password = md5($mysqli->real_escape_string($_POST['password']));
        
        $email = $mysqli->real_escape_string($_POST['email']);
        $iddept2 =$_POST['departamento'];   
           
         $checkusername = $mysqli->query("SELECT * FROM users WHERE Username = '".$username."'");   
         $checkemail = $mysqli->query("SELECT * FROM users WHERE Username = '".$email."'");
            
         if(mysqli_num_rows($checkusername) == 1)   
         {   
            echo "<h1>Error</h1>";   
            echo "<p>Lo siento, ese usuario ya existe. Por favor <a href=\"register.php\">regresa</a> e intenta de nuevo.</p>";
           
         }   
         elseif(mysqli_num_rows($checkemail) == 1)   
             {   
                echo "<h1>Error</h1>";   
                echo "<p>Lo siento, ese correo ya existe. Por favor <a href=\"register.php\">regresa</a> e intenta de nuevo.</p>";
               
             } 
          else  
         {   
            $registerquery = $mysqli->query("INSERT INTO arg_usuarios (u_id, codigo, nombre, clave, email) 
                                            VALUES(3, '".$username."', '".$username."','".$password."', '".$email."')");   
            if($registerquery)   
            {   
                echo "<h1>Listo!</h1>";   
                echo "<p>La cuenta a sido creada. Por favor <a href=\"index.php\">click aqui para loguear</a>.</p>";   
              echo "<p>Presiona <a href=\"register.php\">aqui</a> si deseas crear un nuevo usuario</p>";
        }   
            else   
            {   
                echo "<h1>Error</h1>";   
                echo "<p>Lo siento, ese registro falló. Por favor regresa e intenta de nuevo.</p>";       
        die("MySQL Error: " . mysqli_error());
            }          
         }   
    }
    }   
    else   
    {   
    ?> 
       
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
             <input type="text" id="gsearchsimple" class="form-control my-2" placeholder="Search.." />
             <ul class="list-group" >
             
             </ul>
             <div id="localSearchSimple"></div>
             <div id="detail"></div>
             
            
             
         </div>
          <br />
           <br />
            <br />
             <br />
         <br />
         
          <script type="text/javascript">
            
            $('#gsearchsimple').keyup(function(){
                var textoBusqueda = document.getElementById("gsearchsimple").value;
                //alert(textoBusqueda);
                 if (textoBusqueda != "") {
                    $.post("buscar.php", {valorBusqueda: textoBusqueda}, function(output) {
                         //alert(textoBusqueda);
                        //$("#resultadoBusqueda").html(output);
                       $('.list-group').html(output);
                     }); 
                 } else { 
                    $('.list-group').html('<p>BUSQUEDA </p>');
                    };
            });
            
            /*$('#detail').html('');
                $('.list-group').css('display', 'block');
                if(query.length == 2){
                    $.ajax({
                        url:"buscar.php",
                        method:"POST",
                        data:{query:query},
                        success:function(data){
                            $('.list-group').html(data);
                        }
                    })
                }*/
    </script>
        
         <script>
            $('#gsearchsimple').keyup(function(){
                var query = $('#gsearchsimple').val();
                $('#detail').html('');
                $('.list-group').css('display', 'block');
                if(query.length == 2){
                    $.ajax({
                        url:"buscar.php",
                        method:"POST",
                        data:{query:query},
                        success:function(data){
                            $('.list-group').html(data);
                        }
                    })
                }
                if(query.length == 0){
                    $('.list-group').css('display', 'None');
                }
            
            });
            $('#localSearchSimple').jsLocalSearch({
                action:"Show",
                html_search:true,
                mark_text:"marktext"
            });
            
        </script>
        
        <?
        
        
        
        

//Menu de listado de Empresas
/*
        $organizaciontop = $_GET['organizacion'];
        if ($organizaciontop == "")
        $nombretop = "Seleccione Organización ...";
      
        echo ("<form name=\"Busqueda\" id=\"Busqueda\">");
        echo ("<b><br>A que organización pertenece?</b><br>");
        echo ("<select name=\"organizacion\" id=\"organizacion\" class=\"form-control\" > ");        
        echo ("<option value=$nomtop>$nombretop</option>");
        
        
        $result = $mysqli->query("select id, Nombre from arg_organizaciones ORDER BY Nombre ASC ") or die(mysqli_error());
        while( $row = $result ->fetch_array(MYSQLI_ASSOC)) 
          
          {
          $nombre =($row["Nombre"]);
          $nomenclatura = $row["Id"];
          
          echo ("<option value=$nomenclatura>$nombre</option>");
          }          
                  echo ("</select><br />"); */     
    
//termina menu ...        
    ?>    
        
         <input type="submit" class="btn btn-primary" name="register" id="register" value="Register" />  
         </fieldset>  
    </form> 
       
    <?php  
    }  
    ?>  
</div>

	</div>
	   <div class="col-12 col-md-4 derecha"> </div>
    </div>
         
 </div><!-- /.row -->
        
