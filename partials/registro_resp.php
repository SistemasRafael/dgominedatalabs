

<script>

/* $(document).ready(function() {
        $('#key').on('keyup', function() {
            var key = $(this).val();		
            var dataString = 'key='+key;
    	$.ajax({
                type: "POST",
                url: "buscar.php",
                data: dataString,
                success: function(data) {
                    //Escribimos las sugerencias que nos manda la consulta
                    $('#suggestions').fadeIn(1000).html(data);
                    //Al hacer click en algua de las sugerencias
                    $('.suggest-element').on('click', function(){
                            //Obtenemos la id unica de la sugerencia pulsada
                            var id = $(this).attr('id');
                            //Editamos el valor del input con data de la sugerencia pulsada
                            $('#key').val($('#'+id).attr('data'));
                            //Hacemos desaparecer el resto de sugerencias
                            $('#suggestions').fadeOut(1000);
                            alert('Has seleccionado el '+id+' '+$('#'+id).attr('data'));
                            return false;
                    });
                }
            });
        });
    });*/

            function MiGuardar(nombre, rfc, calle, numero)
                {  
                    var nombre = nombre.val();
                    var rfc = rfc;
                    var calle = calle;
                    var numero = numero;
                    alert(nombre.val());
                    var archivo_agregar = 'nombre='+nombre+'&rfc='+rfc+'&calle='+calle+'&numero='+numero;
                    //var agregar = '<?php echo "\agregar_empresa.php?"?>'+archivo_agregar;                
                   // window.location.href = agregar;
               	}
        
            function goBack() {
                window.history.back();
            }
            
            
</script>
        
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Empresa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">      
                   
                    <label for="nombre" class="col-form-label">Nombre:</label>
                    <input name="nombre" id="nombre" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="rfc" class="col-form-label">RFC:</label>
                    <input name="rfc" id="rfc" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="calle" class="col-form-label">Calle:</label>
                    <input name="calle" id="calle" size=40 style="width:470px; color:#996633"  value="" enabled />
                     <label for="numero" class="col-form-label">Numero:</label>
                    <input name="numero" id="numero" size=40 style="width:470px; color:#996633"  value="" enabled />
                     <label for="colonia" class="col-form-label">Colonia:</label>
                    <input name="colonia" id="colonia" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="localidad" class="col-form-label">Localidad:</label>
                    <input name="localidad" id="colonia" size=40 style="width:470px; color:#996633"  value="" enabled />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="MiGuardar(nombre, rfc, calle,numero)">Guardar</button>
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>
        </div>
        
        <script type="text/javascript">
         $("body").on("click","#tbdoctor a",function(event){
                                        event.preventDefault();
                                        idsele = $(this).attr("href");
                                        nombre = $(this).parent().parent().children("td:eq(0)").text();
                                        
                                        //Cargamos los datos
                                       $("#nombre").val(nombre);
                                       $("#idsele").val(idsele);
                                      });
        </script>


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
       
       <!-- <label for="username"><b>Empresa:</b></label><br>
        <div class="row">
        <div id="content" class="col-lg-12">
            <form class="form-inline" method="post" action="#">
              <div class="input-group">
                <div class="input-group input-group-sm">
                    <input class="search_query form-control" type="text" name="key" id="key" placeholder="Buscar...">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button> 
                       
                        <a href='' name="idsele" class="btn btn-primary" data-toggle="modal" data-target="#myModal"  class="sepV_a" title="Agregar empresa">+ </a></td>
                                          
                    </span>
                </div>
              </div>
            </form>
            
            <div id="suggestions"></div>
            </div>
        </div>-->
         <br />
         <br />
         
        
                
         
          
            <div id="content" class="col-md-12 col-lg-12">            
                <br />
                <br />    
                <section class="principal">
                
                	<div class="formulario" >
                    <div id="content" class="col-md-6 col-lg-6"> 
                     
                    <h5>Document Name:</h5>
                   
                		<label for="caja_busqueda"></label>
                		<input class="search_query form-control" type="text" name="caja_busqueda" id="caja_busqueda"  placeholder="Buscar..."></input>
                           <br />
                           <br />
                	</div>	
                    </div>                       
                	<div class="col-md-12 col-lg-12" id="datos"></div>
                	
                </section>  
         
            </div>                       
      
         
        
         
        
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
</div>
	</div>
	   <div class="col-12 col-md-4 derecha"> </div>
    </div>
         
        
