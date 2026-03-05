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
    
    img{
      max-width: 100%;
    }
    
    /*.html, body, div, iframe { margin:0; padding:0; height:100%; }
    iframe { display:block; width:100%; border:none; }*/
</style>
<script>
function redireccion($u_id)
            {
                 var u_id_fir = $u_id
                 var print_d = '<?php echo "\doc_personas.php?u_id="?>';                
                 window.location.href = print_d+u_id_fir;
            }
</script>
<?php 
//$_SESSION['LoggedIn'] = 1; 
/*
function get_xmp_raw( $filename ) {
 
        $max_size = 512000;     // maximum size read
        $chunk_size = 65536;    // read 64k at a time
        $start_tag = '<x:xmpmeta';
        $end_tag = '</x:xmpmeta>';
        $cache_file = $this->cache_dir . md5( $filename ) . '.xml';
        $xmp_raw = null; 
 
        if ( $this->use_cache == true && file_exists( $cache_file ) && 
                filemtime( $cache_file ) > filemtime( $filename ) && 
                $cache_fh = fopen( $cache_file, 'rb' ) ) {
 
                $xmp_raw = fread( $cache_fh, filesize( $cache_file ) );
                fclose( $cache_fh );
 
        } elseif ( $file_fh = fopen( $filename, 'rb' ) ) {
 
                $file_size = filesize( $filename );
                while ( ( $file_pos = ftell( $file_fh ) ) < $file_size  && $file_pos < $max_size ) {
                        $chunk .= fread( $file_fh, $chunk_size );
                        if ( ( $end_pos = strpos( $chunk, $end_tag ) ) !== false ) {
                                if ( ( $start_pos = strpos( $chunk, $start_tag ) ) !== false ) {
 
                                        $xmp_raw = substr( $chunk, $start_pos, 
                                                $end_pos - $start_pos + strlen( $end_tag ) );
 
                                        if ( $this->use_cache == true && $cache_fh = fopen( $cache_file, 'wb' ) ) {
 
                                                fwrite( $cache_fh, $xmp_raw );
                                                fclose( $cache_fh );
                                        }
                                }
                                break;  // stop reading after finding the xmp data
                        }
                }
                fclose( $file_fh );
        }
        return $xmp_raw;
}

function getXmpData($filename) 
{ 
    $chunk_size = 268435; 
    $buffer = NULL; 

    if (($file_pointer = fopen($filename, 'r')) === FALSE) { 
     throw new RuntimeException('Could not open file for reading'); 
    } 

    $chunk = fread($file_pointer, $chunk_size); 
    if (($posStart = strpos($chunk, '<x:xmpmeta')) !== FALSE) { 
     $buffer = substr($chunk, $posStart); 
     $posEnd = strpos($buffer, '</x:xmpmeta>'); 
     $buffer = substr($buffer, 0, $posEnd + 1800); 
    } 
    fclose($file_pointer); 
    return $buffer; 
} */

if(($_SESSION['LoggedIn']) <> ''){
   
    $u_id = $_GET['u_id'];
    $u_id_firmado = $_SESSION['u_id'];
    $unidad_mina_sel = $_GET['unidad'];
    if ($u_id == $u_id_firmado){        
        $editar = 1;
    }else{
        $editar = 0;
    }

    $unidad_mina = $mysqli->query("SELECT um.serie
                                   FROM 
                             	        arg_usuarios_directivas ud
                                        LEFT JOIN arg_empr_unidades um
                                        	ON ud.valor = um.unidad_id
                                     WHERE 
                                     	ud.u_id = ".$u_id) or die(mysqli_error());
    $user_unidad_mina = $unidad_mina ->fetch_array(MYSQLI_ASSOC);
    $serie_mina = $user_unidad_mina['serie']; 
    
    $user_fir = $mysqli->query("SELECT arg_usuarios_documentos.nombre, arg_usuarios_documentos.fecha_expira, arg_usuarios_documentos.path
                                FROM `arg_usuarios_documentos` 
                                LEFT JOIN arg_tipo_documentos
                                    ON arg_tipo_documentos.tipo_id = arg_usuarios_documentos.tipo_id   
                                WHERE arg_tipo_documentos.tipo_id = 1 AND u_id = ".$u_id) or die(mysqli_error());
    $user_firmado = $user_fir ->fetch_array(MYSQLI_ASSOC);
    $seguro_act = $user_firmado['nombre']; 
    $seguro_act_exp = $user_firmado['fecha_expira']; 
    $seguro_act_path = $user_firmado['path']; 
            
    $user_fir1 = $mysqli->query("SELECT arg_usuarios_documentos.nombre, arg_usuarios_documentos.fecha_expira,  arg_usuarios_documentos.path 
                                 FROM `arg_usuarios_documentos` 
                                 LEFT JOIN arg_tipo_documentos
                                    ON arg_tipo_documentos.tipo_id = arg_usuarios_documentos.tipo_id   
                                 WHERE arg_tipo_documentos.tipo_id = 2 AND u_id = ".$u_id) or die(mysqli_error());
    $user_firmado1 = $user_fir1 ->fetch_array(MYSQLI_ASSOC);
    $ine_act = $user_firmado1['nombre'];
    $ine_act_exp = $user_firmado1['fecha_expira'];
    $ine_act_path = $user_firmado1['path'];
            
    $user_fir2 = $mysqli->query("SELECT arg_usuarios_documentos.nombre, arg_usuarios_documentos.fecha_expira, arg_usuarios_documentos.path
                                 FROM `arg_usuarios_documentos` 
                                 LEFT JOIN arg_tipo_documentos
                                	ON arg_tipo_documentos.tipo_id = arg_usuarios_documentos.tipo_id   
                                 WHERE arg_tipo_documentos.tipo_id = 3 AND u_id = ".$u_id) or die(mysqli_error());
    $user_firmado3 = $user_fir2 ->fetch_array(MYSQLI_ASSOC);
    $licencia_act = $user_firmado3['nombre'];
    $licencia_act_exp = $user_firmado3['fecha_expira'];
    $licencia_act_path = $user_firmado3['path'];
?>
<!--Modal INE--!>
<div class="modal fade" id="ModalImagen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">INE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                    <img src="http://192.168.20.3:81/registro/<?echo $ine_act_path;?>" class="img-rounded" alt="INE" width="370" height="236" />   
              </div>      
              <div class="modal-footer">        
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
              </div>  
            </div>
          </div>
   </div>
   
   <!--Modal Licencia--!>
<div class="modal fade" id="ModalLicencia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Licencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                    <img src="http://192.168.20.3:81/registro/<?echo $licencia_act_path;?>" class="img-rounded" alt="INE" width="370" height="236" />   
              </div>      
              <div class="modal-footer">        
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
              </div>  
            </div>
          </div>
   </div>

<!--Modal Poliza--!>
<div class="modal fade" id="ModalSeguro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Poliza GMM / IMSS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                     <div>
                       <iframe src="http://192.168.20.3:81/registro/<?echo $seguro_act_path;?>" width="450" height="350" ></iframe>
                     </div> 
              </div>      
              <div class="modal-footer">        
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
              </div>  
            </div>
          </div>
   </div>

<div class="container"  class="col-md-12 col-lg-12">  
    <br />
    <br />
    <h4><b><?echo 'Para poder ingresar a una unidad de mina es necesaria tener actualizados los siguientes documentos:'?></b></h4>
    <br />
    <div class="row">
    <div class="container"  class="col-md-12 col-lg-12"> 
        <form method="post" enctype="multipart/form-data">                     
                        <div id="content" class="col-lg-2">  
                            <label for="ine"><b>INE:</b></label><br><input type="text" name="ine" class="form-control" value="<?echo $ine_act;?>" id="ine" />
                        </div>
                        <div id="content" class="col-lg-2"> 
                            <label for="fecha_ine"><b>Expira:</b></label><br><input type="date" name="fecha_ine" value="<?echo $ine_act_exp;?>" class="form-control" id="fecha_ine" />          
                        </div>
                         <div id="content" class="col-lg-1"> 
                           </br>
                           
                            <a name="idsele" class="btn btn-success" data-toggle="modal" data-target="#ModalImagen"  class="sepV_a" title="Ine">Ver </a></td>
                         </div>
                         <? if ($editar == 1){ ?>
                            <div id="content" class="col-lg-3">                          
                                <label for="ine_img"><b>Imagen:</b></label></br>
                                <input type="hidden" name="MAX_FILE_SIZE" value="200000000">
                                <input name="ine_img" type="file" id="ine_img"> 
                             </div>
                              <div id="content" class="col-lg-1"> 
                                <label for="archivo_ant"></label><input type="hidden" name="archivo_ant" value="<?echo $ine_act_path;?>" class="form-control" id="archivo_ant" />          
                              </div>                        
                             <div id="content" class="col-lg-2">
                              <br />                           
                            <td height="26" valign="medium"><input name="upload_ine" type="submit" class="box btn btn-info" id="upload_ine" value=" Actualizar "></td>
                            <? } ?>
                       </div>
        </form>
    </div>
    </div>
    <br />
    <br />
    <div class="row">
    <div class="container"  class="col-md-12 col-lg-12"> 
        <form method="post" enctype="multipart/form-data">                     
                        <div id="content" class="col-lg-2">  
                            <label for="licencia"><b>Licencia:</b></label><br><input type="text" name="licencia" class="form-control" value="<?echo $licencia_act;?>" id="licencia" />
                        </div>
                        <div id="content" class="col-lg-2"> 
                            <label for="fecha_lic"><b>Expira:</b></label><br><input type="date" name="fecha_lic" value="<?echo $licencia_act_exp;?>" class="form-control" id="fecha_lic" />          
                        </div>
                         <div id="content" class="col-lg-1"> 
                            </br>                           
                            <a name="idlic" class="btn btn-success" data-toggle="modal" data-target="#ModalLicencia"  class="sepV_a" title="Licencia">Ver </a></td>
                         </div>
                         <? if ($editar == 1){ ?>
                                <div id="content" class="col-lg-3">                          
                                    <label for="licencia_img"><b>Imagen:</b></label></br>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="200000000">
                                    <input name="licencia_img" type="file" id="licencia_img"> 
                                 </div>
                                  <div id="content" class="col-lg-1"> 
                                    <label for="archivo_ant"></label><input type="hidden" name="licencia_ant" value="<?echo $licencia_act_path;?>" class="form-control" id="licencia_ant" />          
                                  </div>
                                 <div id="content" class="col-lg-2">                          
                                
                                  <br />                        
                                <td height="26" valign="medium"><input name="upload_lic" type="submit" class="box btn btn-info" id="upload_lic" value="Actualizar"></td>
                        <?}?> 
                           </div>
        </form>
    </div>
    </div>
    <br />
    <br />
    <div class="row"> 
    <div class="container"  class="col-md-12 col-lg-12">  
        <form method="post" enctype="multipart/form-data">                     
                        <div id="content" class="col-lg-2">  
                            <label for="seguro"><b>Poliza GMM/IMSS:</b></label><br><input type="text" name="seguro" class="form-control" value="<?echo $seguro_act;?>" id="seguro" />
                        </div>
                        <div id="content" class="col-lg-2"> 
                            <label for="fecha_seg"><b>Expira:</b></label><br><input type="date" name="fecha_seg" value="<?echo $seguro_act_exp;?>" class="form-control" id="fecha_seg" />          
                        </div>
                        <div id="content" class="col-lg-1"> 
                            </br>                           
                            <a name="idpol" class="btn btn-success" data-toggle="modal" data-target="#ModalSeguro"  class="sepV_a" title="Póliza">Ver </a></td>
                         </div>
                         <? if ($editar == 1){ ?>   
                                <div id="content" class="col-lg-3">                          
                                    <label for="poliza_img"><b>Imagen:</b></label></br>
                                    <input type="hidden" name="MAX_FILE_SIZE" value="200000000">
                                    <input name="poliza_img" type="file" id="poliza_img"> 
                                 </div>
                                  <div id="content" class="col-lg-1"> 
                                    <label for="archivo_ant"></label><input type="hidden" name="seguro_ant" value="<?echo $seguro_act_path;?>" class="form-control" id="seguro_ant" />          
                                  </div>
                                 <div id="content" class="col-lg-2">
                                  <br />
                                        
                                <td height="26" valign="medium"><input name="upload_pol" type="submit" class="box btn btn-info" id="upload_pol" value="Actualizar"></td>
                        <? } ?>
                       </div>
        </form>
    </div>
    </div>
</div> 
    <?
    //Validacion del post ine
    if(isset($_POST['upload_ine']))
    {      
       $ine = $_POST['ine'];
       $fecha_ine = $_POST['fecha_ine'];
       $archivo_eliminar = $_POST['archivo_ant'];
       
       $uploadDir = 'upload/Ine/';
                            
        if (file_exists($uploadDir) <> 'true')
            { mkdir ($uploadDir); }
            echo $uploadDir;
                             
        $filePath = $uploadDir;
                             
        $fileName = $_FILES['ine_img']['name'];
        $tmpName  = $_FILES['ine_img']['tmp_name'];
        $fileSize = $_FILES['ine_img']['size'];
        $fileType = $_FILES['ine_img']['type'];
        
        unlink ($archivo_eliminar);                        
                                
        $moverarchivo = $filePath.$fileName;                            
        $result = move_uploaded_file($tmpName, $moverarchivo);
        if (!$result) {
            echo "Error al intentar subir archivo";
            exit;
        }        
                            
        if(!get_magic_quotes_gpc())
        {
            $fileName = addslashes($fileName);
            $filePath = addslashes($filePath);
        }
        
        $archivo = $filePath.$fileName;
   
        if ($ine_act == '')
        {
            $query = "INSERT INTO arg_usuarios_documentos (u_id, tipo_id, nombre, fecha_expira, path ) ".
            "VALUES ($u_id, 2, '$ine', '$fecha_ine', '$archivo')";
                                
            $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                
            echo "<br><b>Se guardo con exito:</b><br><br>"."$fileName <br>";
            echo "<script> redireccion() </script>";
        }
       else{
            $query = "UPDATE arg_usuarios_documentos
                        SET nombre = '$ine', fecha_expira = '$fecha_ine', path = '$archivo'
                      WHERE
                          tipo_id = 2 AND u_id = ".$u_id;
            $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                
            echo "<br><b>Se guardo con exito:</b><br><br>"."$fileName <br>";
            echo "<script> redireccion(".$u_id.") </script>";
        }
    }
    
    //Validacion del post licencia
    if(isset($_POST['upload_lic']))
    {      
       $licencia = $_POST['licencia'];
       $fecha_lic = $_POST['fecha_lic'];
       $archivo_eliminar = $_POST['licencia_ant'];
       
       $uploadDir = 'upload/Licencias/';
                            
        if (file_exists($uploadDir) <> 'true')
            { mkdir ($uploadDir); }
        //echo $uploadDir;
                             
        $filePath = $uploadDir;
                             
        $fileName = $_FILES['licencia_img']['name'];
        $tmpName  = $_FILES['licencia_img']['tmp_name'];
        $fileSize = $_FILES['licencia_img']['size'];
        $fileType = $_FILES['licencia_img']['type'];
        
        unlink ($archivo_eliminar); 
        
        $moverarchivo = $filePath.$fileName;                            
        $result = move_uploaded_file($tmpName, $moverarchivo);
        if (!$result) {
            echo "Error al intentar subir archivo";
            exit;
        }
                            
        if(!get_magic_quotes_gpc())
        {
            $fileName = addslashes($fileName);
            $filePath = addslashes($filePath);
        }
        
        $archivo = $filePath.$fileName;
   
        if ($licencia_act == '')
        {
            $query = "INSERT INTO arg_usuarios_documentos (u_id, tipo_id, nombre, fecha_expira, path ) ".
            "VALUES ($u_id, 3, '$licencia', '$fecha_lic', '$archivo')";
                                
            $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                
            echo "<br><b>Se actualizó con exito:</b><br><br>"."$fileName <br>";
            echo "<script> redireccion() </script>";
        }
       else{
            $query = "UPDATE arg_usuarios_documentos
                        SET nombre = '$licencia', fecha_expira = '$fecha_lic', path = '$archivo'
                      WHERE
                          tipo_id = 3 AND u_id = ".$u_id;
            $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                
            echo "<br><b>Se actualizó con exito:</b><br><br>"."$fileName <br>";
            echo "<script> redireccion(".$u_id.") </script>";
        }
    }
    
    //Validacion del post seguro/gmm
    if(isset($_POST['upload_pol']))
    {      
       $seguro = $_POST['seguro'];
       $fecha_seg = $_POST['fecha_seg'];
       $archivo_eliminar = $_POST['seguro_ant'];
       
       $uploadDir = 'upload/Polizas/';
                            
        if (file_exists($uploadDir) <> 'true')
            { mkdir ($uploadDir); }
        //echo $uploadDir;
                             
        $filePath = $uploadDir;
                             
        $fileName = $_FILES['poliza_img']['name'];
        $tmpName  = $_FILES['poliza_img']['tmp_name'];
        $fileSize = $_FILES['poliza_img']['size'];
        $fileType = $_FILES['poliza_img']['type'];
        
        unlink ($archivo_eliminar); 
        
        $moverarchivo = $filePath.$fileName;                            
        $result = move_uploaded_file($tmpName, $moverarchivo);
        if (!$result) {
            echo "Error al intentar subir archivo";
            exit;
        }
                            
        if(!get_magic_quotes_gpc())
        {
            $fileName = addslashes($fileName);
            $filePath = addslashes($filePath);
        }
        
        $archivo = $filePath.$fileName;
   
        if ($licencia_act == '')
        {
            $query = "INSERT INTO arg_usuarios_documentos (u_id, tipo_id, nombre, fecha_expira, path ) ".
            "VALUES ($u_id, 1, '$seguro', '$fecha_seg', '$archivo')";
                                
            $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                
            echo "<br><b>Se actualizó con exito:</b><br><br>"."$fileName <br>";
            echo "<script> redireccion() </script>";
        }
       else{
            $query = "UPDATE arg_usuarios_documentos
                        SET nombre = '$seguro', fecha_expira = '$fecha_seg', path = '$archivo'
                      WHERE
                          tipo_id = 1 AND u_id = ".$u_id;
            $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                
            echo "<br><b>Se actualizó con exito:</b><br><br>"."$fileName <br>"; 
            echo "<script> redireccion(".$u_id.") </script>";
        }
    }
                            /*Era para validar y leer los datos de una imagen
                            $archivo_lee = $filePath.$fileName;
                            $filename = $filePath.$fileName;
                            preg_match('%vigencia%', $archivo_lee , $result);
                            $foo = $result[1];
                            echo $foo;
                            $content = file_get_contents($archivo_lee); 
                            $xmp_data_start = strpos($content, '<x:xmpmeta'); 
                            $xmp_data_end = strpos($content, '</x:xmpmeta>'); 
                            $xmp_length  = $xmp_data_end - $xmp_data_start; 
                            $xmp_data  = substr($content, $xmp_data_start, $xmp_length + 10); 
                            $xmp   = simplexml_load_string($xmp_data);
                            echo $xmp;
                            echo $archivo_lee;
                            $dat = getXmpData($archivo_lee);
                            echo $dat;
                            $dat = get_xmp_raw($filename);
                            echo $dat;*/
                            //echo $buffer;
                            //$query = "INSERT INTO upload (name, size, type, path ) ".
                            //"VALUES ('$fileName', '$fileSize', '$fileType', '$filePath')";
               
    }
 
    ?> 
<!--<script type="text/javascript" src="js/popper/src/popper.js"></script>-->
<!--<script type="text/javascript" src="js/vehiculos.js"></script>-->
         
          

