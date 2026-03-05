<?php

include "connections/config.php";
		require("ldap.php");
       // require("user_bd.php");
		header("Content-Type: text/html; charset=utf-8");
		$usr = $_POST["usuario"];
        $passw = $_POST["clave"];
        if ($passw == ''){
            
            echo 'La contraseña no puede estar vacía';
           	echo"<script>window.location.href='index.php'</script>";
        }
        else{
        $pass = MD5($_POST["clave"]);       
		$usuario = mailboxpowerloginrd($usr, $_POST["clave"]);        
		if($usuario == "0" || $usuario == ''){
		
		      $existe_ext = $mysqli->query("SELECT u.u_id, u.codigo, u.nombre,u.email
                                             FROM arg_usuarios u
                                             WHERE u.codigo = '".$usr."'
                                             AND u.clave = '".$pass."'");
              $existe_extu = $existe_ext->fetch_array(MYSQLI_ASSOC);
              
              //var_dump($existe_extu);
              //die();
              if ($existe_extu <> ''){
                  session_start();
                  
                 $_SESSION["LoggedIn"] = 1;
			     $_SESSION["user"] = $usr;
			     $_SESSION["autentica"] = "SIP";  
                 $cuenta_usuario = $existe_extu['codigo'];
                 
                  $user_local = $mysqli->query("SELECT u.u_id, u.codigo, u.nombre, u.email
                                                  ,(CASE WHEN uni.valor = '0' THEN '1' WHEN uni.valor LIKE '%,%' THEN SUBSTRING(uni.valor, 1, 1)  ELSE uni.valor END) AS unidad_def
                                                  ,(CASE WHEN uni.valor = '0' THEN '0' WHEN uni.valor LIKE '%,%' THEN 999 ELSE uni.valor END) AS unidad_acc
                                                  ,uni.valor AS unidades                       
                                             FROM arg_usuarios u
                                                                                          
                                             LEFT JOIN arg_usuarios_directivas uni
                                             	ON uni.u_id = u.u_id            
                                             WHERE u.codigo = '".$usr."'");
                 $user_local_ex = $user_local->fetch_array(MYSQLI_ASSOC);
                 $_SESSION['nombre'] = $existe_extu['nombre']; 
                 $_SESSION['email']  = $existe_extu['email'];                 
                 $_SESSION['u_id'] = $existe_extu['u_id']; 
                 $_SESSION["unidad_def"] = $user_local_ex['unidad_def'];
                 $_SESSION["unidad_acc"] = $user_local_ex['unidad_acc'];
                 $_SESSION['unidades']   = $user_local_ex['unidades'];
                 
                 echo"<script>window.location.href='app.php'; </script>";
                } 
                else { 
			     echo"<script> alert('Usuario o clave incorrecta. Vuelva a digitarlos por favor.'); window.location.href='index.php'; </script>";
                }
                
        }else{
			session_start();
            $_SESSION["LoggedIn"] = 1;
			$_SESSION["user"] = $usuario;
			$_SESSION["autentica"] = "SIP";  
            $cuenta_usuario = $usuario['cuenta'];
             
            $existe_id = $mysqli->query("SELECT u_id, codigo FROM arg_usuarios WHERE codigo = '".$cuenta_usuario."'");
		    $existe = $existe_id->fetch_array(MYSQLI_ASSOC);
		    $existe_usuario = $existe["codigo"];
            
            $_SESSION['nombre'] = $usuario['nombre']." ".$usuario['last'];; 
            $_SESSION['email']  = $usuario['correo'];
            $_SESSION['u_id']   = $existe['u_id'];
            $_SESSION['empleado'] = 1;
            
               $existe_ext2 = $mysqli->query("SELECT u.u_id, u.codigo, u.nombre, u.email
                                                  ,(CASE WHEN uni.valor = '0' THEN '1' WHEN uni.valor LIKE '%,%' THEN SUBSTRING(uni.valor, 1, 1)  ELSE uni.valor END) AS unidad_def
                                                  ,(CASE WHEN uni.valor = '0' THEN '0' WHEN uni.valor LIKE '%,%' THEN 999 ELSE uni.valor END) AS unidad_acc
                                                  ,uni.valor AS unidades                       
                                             FROM arg_usuarios u
                                                                                          
                                             LEFT JOIN arg_usuarios_directivas uni
                                             	ON uni.u_id = u.u_id            
                                             WHERE u.codigo = '".$usr."'");
                 $existe_extu2 = $existe_ext2->fetch_array(MYSQLI_ASSOC);
                 $_SESSION["unidad_def"] = $existe_extu2['unidad_def'];
                 $_SESSION["unidad_acc"] = $existe_extu2['unidad_acc'];
                 $_SESSION['unidades']   = $existe_extu2['unidades'];
                 
                 if($_SESSION['unidad_def'] <> ''){
                     $unidad_defa = $mysqli->query("SELECT serie
                             FROM arg_empr_unidades 
                             WHERE unidad_id = ".$_SESSION["unidad_def"]);
                     $unidad_def = $unidad_defa->fetch_array(MYSQLI_ASSOC);
                     $serie_def = $unidad_def['serie'];
                 }
                 //echo $_SESSION["unidad_acc"];
                 //echo $_SESSION["unidad_def"];
                // echo $serie_def;
                 //$serie_def_car = "%27".$serie_def."%27";
                 //echo $_SESSION["unidades"];
                 //die();
         
           if($existe_usuario == ''){
            //echo 'llego';
                $id_usuarios = $mysqli->query("SELECT max(id) FROM arg_usuarios");
    		    $row_id = $id_usuarios->fetch_array(MYSQLI_ASSOC);
    		    $id_max = $row_id['max(id)'];
                $id_max = $id_max+1;
                $codigo = $usuario;
              
                $email_usuario  = $usuario['correo'];
                $nombre_usuario = $usuario['nombre']." ".$usuario['last'];
                $grupos_usuario = $usuario['member'];                
                
                $query = "INSERT INTO arg_usuarios (u_id, codigo, nombre, email, org_id, division ) ".
                         "VALUES ($id_max, '$cuenta_usuario', '$nombre_usuario', '$email_usuario', 0, 'empleado')";
                            
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                $query1 = "INSERT INTO arg_usuarios_perfiles (u_id, perfil_id) ".
                          "VALUES ($id_max, 2)";
                            
                $mysqli->query($query1) or die('Error, query failed : ' . mysqli_error($mysqli));
                            
                echo "<br><b>Se guardó con exito:</b><br><br>"."$fileName <br>"; 
                
                $_SESSION['u_id'] = $id_max;
                $_SESSION["LoggedIn"] = 1;
	     		$_SESSION["user"] = $cuenta_usuario;
                $_SESSION['empleado'] = 1;
           }
           
           
			echo"<script>window.location.href='app.php?unidad_id=".$_SESSION["unidad_def"]."'; </script>";
		}
  }
?>
