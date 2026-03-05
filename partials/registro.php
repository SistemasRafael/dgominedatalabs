        <?$_session['nueva_empresa'] = '';?>   
        
        <script>   
            function MiGuardar()
                {  
                    var nombre = document.getElementById("nombre").value;  
                    var nombre_comercial = document.getElementById("nombre_comercial").value;                
                    var rfc = document.getElementById("rfc").value; 
                    var calle = document.getElementById("calle").value;
                    var numero = document.getElementById("numero").value;
                    var colonia = document.getElementById("colonia").value;
                    var localidad = document.getElementById("localidad").value;
                    var codigo_postal = document.getElementById("codigo_postal").value;
                    
                    //alert(nombre_local);
                    var archivo_agregar = 'nombre='+nombre+'&nombre_comercial='+nombre_comercial+'&rfc='+rfc+'&calle='+calle+'&numero='+numero+'&colonia='+colonia+'&localidad='+localidad+'&codigo_postal='+codigo_postal;
                    var agregar = '<?php echo "\agregar_empresa.php?"?>'+archivo_agregar;                
                    window.location.href = agregar;                    
               	}
            </script>
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
                     <label for="nombre_comercial" class="col-form-label">Nombre Comercial:</label>
                    <input name="nombre_comercial" id="nombre_comercial" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="rfc" class="col-form-label">RFC:</label>
                    <input name="rfc" id="rfc" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="calle" class="col-form-label">Calle:</label>
                    <input name="calle" id="calle" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="numero" class="col-form-label">Numero:</label>
                    <input name="numero" id="numero" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="colonia" class="col-form-label">Colonia:</label>
                    <input name="colonia" id="colonia" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="localidad" class="col-form-label">Localidad:</label>
                    <input name="localidad" id="localidad" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="codigo_postal" class="col-form-label">CP:</label>
                    <input name="codigo_postal" id="codigo_postal" size=40 style="width:470px; color:#996633"  value="" enabled />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="MiGuardar()">Guardar</button>
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>
        </div>
     

</head>

<body>

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

.input-group-btn
{
    width: 690px;
    height: 60px;
    cursor: pointer;
}

.input-type
{
    width: 690px;
    height: 60px;
    cursor: pointer;
}

.input-container input{
	position:relative;
	margin-bottom:25px;
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
      $licencia = $_POST['licencia'];
      $fecha_lic = $_POST['fecha_lic'];
      $ine = $_POST['ine'];
      $fecha_ine = $_POST['fecha_ine'];
      $imss = $_POST['poliza_usuario'];
      $fecha_imss = $_POST['fecha_imss'];
          
        $username = $mysqli->real_escape_string($_POST['username']);
        $password = md5($mysqli->real_escape_string($_POST['password']));
        
        $email = $mysqli->real_escape_string($_POST['email']);
        $empresa_seleccion =$_POST['caja_busqueda'];   
        
        $checkempresa = $mysqli->query("SELECT * FROM arg_organizaciones WHERE nombre = '".$empresa_seleccion."'");     
        $row_empresa = $checkempresa->fetch_array(MYSQLI_ASSOC);
        $org_id = $row_empresa['org_id']; 
        //echo $org_id;
           
        $checkusername = $mysqli->query("SELECT * FROM arg_usuarios WHERE nombre = '".$username."'");   
        $checkemail = $mysqli->query("SELECT * FROM arg_usuarios WHERE email = '".$email."'");
        
        $maximo_user = $mysqli->query("SELECT MAX(u_id) FROM arg_usuarios"); 
        $max_uid = $maximo_user->fetch_array(MYSQLI_ASSOC);
        $maximo_user = $max_uid['MAX(u_id)']; 
        $maximo_user = $maximo_user+1;
        //echo 'ya'; echo $maximo_user;
            
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
                $activo = 1;
                $division = 'proveedor';
                //$fecha_imss = '20211206';
            /*$registerquery = $mysqli->query("INSERT INTO arg_usuarios (u_id, codigo, nombre, clave, email, org_id, activo, division) 
                                            VALUES(".$maximo_user.", '".$email."', '".$username."','".$password."','".$email."', ".$org_id.",".$activo.",".$division.")");                       
            $registerLic = $mysqli->query("INSERT INTO arg_usuarios_documentos (u_id, tipo_id, nombre, fecha_expira) 
                                            VALUES(".$maximo_user.", 3, '".$licencia."','".$fecha_lic."')"); 
            $registerIne = $mysqli->query("INSERT INTO arg_usuarios_documentos (u_id, tipo_id, nombre, fecha_expira) 
                                            VALUES(".$maximo_user.", 2, '".$ine."','".$fecha_ine."')"); 
            $registerIMSS = $mysqli->query("INSERT INTO arg_usuarios_documentos (u_id, tipo_id, nombre, fecha_expira) 
                                            VALUES(".$maximo_user.", 1, '".$imss."','')");*/
            mysqli_multi_query ($mysqli, "CALL arg_registrar_user (".$maximo_user.",'".$email."','".$username."','".$password."', '".$email."',".$org_id.", ".$activo.", '".$division."', '".$licencia."','".$fecha_lic."', '".$ine."', '".$fecha_ine."', '".$imss."', '".$fecha_imss."'".")") OR DIE (mysqli_error($mysqli));
           
                                            
            //echo $mysqli;
           //(die);  
            if($mysqli)   
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
   <div id="content" class="col-lg-12">       
        <p><b>Por favor llena los siguientes datos</b></p>  
       
       <form method="post" action="register.php" name="registerform" id="registerform">  
        <fieldset>
            <div class="row">          
                <section class="principal">                
                	<div class="formulario" >
                    <div id="content" class="col-lg-12">                  
                   
                		<label for="caja_busqueda">Empresa: </label>
                        <span class="input-group-btn" >
                        <div class="input-group">
                		<input class="search_query form-control" type="text" name="caja_busqueda" id="caja_busqueda" autocomplete="off"  placeholder="Buscar empresa..." value="<?echo $_SESSION['empresa_nueva'];?>"> </input>                        
                            <a href='' name="idsele" class="btn btn-primary" data-toggle="modal" data-target="#myModal"  class="sepV_a" title="Agregar empresa">+ </a>
                        </span>
                        	</div>   
                            <br />
                      </div>
                      </div> 
                                          
                	<div class="col-md-12 col-lg-12" id="datos"></div>
                	<br />
                    <br />
                    <br />
                    </section>  
             </div> 
             <br />
             <br /> 
              <div id="content-fluid" class="col-lg-12"> 
                <label for="email"><b>Email Address:</b><br></label>               
                <input type="email" name="email" id="email" class="form-control" required="" /><br />
                <label for="password"><b>Password:</b></label><br><input type="password" name="password" class="form-control" id="password" /><br />  
                 <br />
                 <label for="username"><b>Nombre:</b></label><br><input type="text" name="username" class="form-control" id="username" /><br />            
                <br /> 
             </div>
            <div id="content" class="col-lg-3">  
                <label for="licencia"><b>Licencia:</b></label><br><input type="text" name="licencia" class="form-control" id="licencia" />
            </div>
            <div id="content" class="col-lg-3"> 
                <label for="fecha_lic"><b>Expira:</b></label><br><input type="date" name="fecha_lic" class="form-control" id="fecha_lic" />          
            </div>
            <br/>
            <div id="content" class="col-lg-3">
                 <label for="ine"><b>INE:</b></label><br><input type="text" name="ine" class="form-control" id="ine" />           
           </div>
           <div id="content" class="col-lg-3"> 
                <label for="fecha_ine"><b>Expira:</b></label><br><input type="date" name="fecha_ine" class="form-control" id="fecha_ine" />          
            </div>
            <br/>
            <br/>
            <div id="content" class="col-lg-3">
                <label for="poliza_usuario"><b>IMSS/Gastos Medicos Mayores:</b></label><br><input type="text" name="poliza_usuario" class="form-control" id="poliza_usuario" /><br />            
             
            </div>
            <div id="content" class="col-lg-3"> 
                <label for="fecha_imss"><b>Expira:</b></label><br><input type="date" name="fecha_imss" class="form-control" id="fecha_imss" />          
            </div>
            <br />  
            <br />  <br />   <br />   <br />     
       <div class="col-md-12 col-lg-12">
         <input type="submit" class="btn btn-primary" name="register" id="register" value="Register" /> 
        </div> 
         </fieldset>  
    </form> 
   </div>    
    <?php  
}  
    ?>  
    </div>
    </div>
    </div>
	</div>
	   <div class="col-12 col-md-4 derecha"> </div>
    </div>
         
 </div><!-- /.row -->

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/main.js"></script> 