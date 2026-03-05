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
</style>

<script>
    function redireccion()
            {
                 var print_d = '<?php echo "\doc_vehiculos.php"?>';                
                 window.location.href = print_d;
            }
            
    function ActualizaVeh(idsele)
                {  
                    var placas = idsele;
                    var eliminar_ruta = '<?php echo "\doc_vehiculos.php?placas="?>'+placas;                
                    window.location.href = eliminar_ruta;
               	}
                
    //Agregar vehículos
     function GuardarVeh()
        {
            var placa  = document.getElementById("placa").value;                         
            var marca  = document.getElementById("marca").value; 
            var modelo = document.getElementById("modelo").value; 
            var color  = document.getElementById("color").value; 
            var poliza = document.getElementById("poliza").value;
            var expira_veh = document.getElementById("expira_veh").value;
            var path_pol = document.getElementById("file").file.name;
            //var path_type = document.getElementById("file").file.tmp_name;
           // var path_pat = document.getElementById("tem_archivo").value;
            //var path_pol_t = new FormData(document.getElementById("fileinfo"));
            //var path_pol_t = document.getElementById("userfile").files[0].tmp_name;
           
            //var file = document.getElementById("userfile").files[0].name;//path_pol.files[0];
			//var data = new FormData(document.getElementById("userfile"));//.files[0].name);
			//data.append("userfile",file);
            
			alert(path_pol);
            
            $.ajax({
            		url: 'insertar_veh.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {placa: placa, marca:marca, modelo:modelo, color:color, poliza:poliza, expira_veh:expira_veh},
            	})
            .done(function(respuesta){
                    //html(respuesta);
            		//$("#vehic").html(respuesta);
                    //var veh_id = document.getElementById("placas").value;
                    if(respuesta == 'Error: Debe capturar toda la información.' || respuesta == 'Error: Esa placa se encuentra duplicada. Reintente por favor')
                    {
                        alert(respuesta);
                    }
                    else{
                        alert('Se agrego el vehículo correctamente');
                        redireccion();
                    }
                    
              })                 
      }
</script>
<!--Modal editar Poliza
<div class="modal fade" id="ModalVeh" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Datos del Vehiculo: </h5>
                <input name="detalle" id="detalle" value="" value="" disabled/>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                    <input name="placas" id="placas"type="hidden" size=40 style="width:470px; color:#996633"  value="" disabled /> 
                    
                    <label>Placas: </label><input name="idsele" id="idsele" value="" /></br></br>
                    <label>Poliza : </label><input name="poliza" id="poliza" value="" /> 
                    <label>Expira: </label><input name="fecha_exp" id="fecha_exp" type="date" value="" />                                             
                     </br></br>       
                    <label>Importar Poliza: </label> <input type="hidden" name="MAX_FILE_SIZE" value="200000000">
                    <input name="poliza_img" type="file" id="poliza_img"> 
                  
              </div> 
              <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="ActualizaVeh(idsele)" data-dismiss="modal">Actualizar</button>
                <input name="upload_pol" type="submit" class="box btn btn-info" id="upload_pol" value=" Actualizar "></td>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
              </div>  
            </div>
          </div>
   </div>
  --!>
  
   <script type="text/javascript">
  
    $("body").on("click","#vehic a",function(event){
                                        event.preventDefault();
                                       
                                        idsele = $(this).attr("href");
                                        placas = $(this).parent().parent().children("td:eq(0)").text();
                                        marca  = $(this).parent().parent().children("td:eq(1)").text();
                                        modelo = $(this).parent().parent().children("td:eq(2)").text();
                                        poliza = $(this).parent().parent().children("td:eq(4)").text();
                                        detalle = marca+modelo;
                                        //Cargamos los datos
                                       $("#placas").val(placas);
                                       $("#idsele").val(idsele);
                                       $("#detalle").val(detalle);
                                       $("#poliza").val(poliza);
                                       
                                       ActualizaVeh(idsele);
                                      });
        
        </script>


<!-- Modal Vehículos  --> 
 <div class="modal fade" id="ModalVeh" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="ModalVeh">Agregar Vehículo. </h4>                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>               
              </div>
                <div class="modal-header">  
                  <h6 class="modal-subtitle" style="color:#996633"  id="exampleModal">IMPORTANTE: Debe importar la de poliza de seguro vigente</h6>
                </div>
              <div class="modal-body">
              
              <form enctype="multipart/form-data" id ="form1">
                    <label for="placa" class="col-form-label">Placa:</label>
                    <input name="placa" id="placa" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="marca" class="col-form-label">Marca:</label>
                    <input name="marca" id="marca" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <label for="modelo" class="col-form-label">Modelo:</label>
                    <input name="modelo" id="modelo" size=40 style="width:470px; color:#996633"  value="" enabled />
                     <label for="color" class="col-form-label">Color:</label>
                    <input name="color" id="color" size=40 style="width:470px; color:#996633"  value="" enabled />
                     <label for="poliza" class="col-form-label">Póliza:</label>
                    <input name="poliza" id="poliza" size=40 style="width:470px; color:#996633"  value="" enabled />
                     <label for="expira_veh"  class="col-form-label">Fecha Expira:</label>
                    <input type="date" name="expira_veh" id="expira_veh" size=40 style="width:470px; color:#996633"  value="" enabled />
                    <br />                 
                 
                     <table width="350" border="0" cellpadding="1" cellspacing="1" class="box">
                        <!--DWLayoutTable-->
                        <tr> 
                          <td width="246" rowspan="2">
                            <input type="hidden" name="MAX_FILE_SIZE" value="200000000">                            
                            <strong>Buscar póliza de seguro:</strong>
                            <input name="file" type="file" id="file" class="form-control">  
                          </td>
                          <td width="85" height="18"></td>
                        </tr>
                       
                      </table>
                  </form>       
                     
              </div>
              <div class="modal-footer">
                 <button type="button" class="btn btn-primary" onclick="GuardarVehi()">Guardar</button>
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>
   </div>  
   
         

<script>

function GuardarVehi(){
    
    var frm = document.getElementById('form1');
    var data = new FormData (frm);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if (this.readyState == 4){
            var msg = xhttp.responseText;
            if (msg == 'Se agregó correctamente el vehículo'){
                alert(msg);
                $('#ModalVeh').modal('hide');
                redireccion();
            }
            else{
                alert(msg);
            }
        }       
    };
    
    xhttp.open("POST", "insertar_veh_pol.php", true);
    xhttp.send(data);
    $('#form1').trigger(reset);
}

/*
                    
                    <input type="file" id="file" onchange="processSelectedFiles(this.file)">
                    
                    
                    */
function processSelectedFiles(files) {
  var d = document.getElementById("file");
  alert(d);
  if (!files.length) {
    d.innerHTML = "<p>¡No se han seleccionado archivos!</p>";
  } else {
    var list = document.createElement("ul");
    d.appendChild(list);
    var imgs = document.getElementById("fileList");
    
    alert(imgs);

    for (var i=0; i < files.length; i++) {
      var li = document.createElement("li");
      list.appendChild(li);
      
      new FileUpload(imgs[i], imgs[i].file);

      var img = document.createElement("img");
      img.src = window.createBlobURL(files[i]);;
      img.height = 60;
      img.onload = function() {
        window.revokeBlobURL(this.src);
      }
      li.appendChild(img);

      var info = document.createElement("span");
      info.innerHTML = files[i].name + ": " + files[i].size + " bytes";
      li.appendChild(info);
    }
  }
}

function FileUpload(img, file) {
  this.ctrl = createThrobber(img);
  var xhr = new XMLHttpRequest();
  this.xhr = xhr;

  var self = this;
  this.xhr.upload.addEventListener("progress", function(e) {
        if (e.lengthComputable) {
          var percentage = Math.round((e.loaded * 100) / e.total);
          self.ctrl.update(percentage);
        }
      }, false);

  xhr.upload.addEventListener("load", function(e){
          self.ctrl.update(100);
          var canvas = self.ctrl.ctx.canvas;
          canvas.parentNode.removeChild(canvas);
      }, false);

  xhr.open("POST", "http://192.168.20.3:81/registro/doc_vehiculos.php");
  xhr.overrideMimeType('text/plain; charset=x-user-defined-binary');
  xhr.sendAsBinary(file.getAsBinary());
}

/*
function processSelectedFiles(fileInput) {
  var files = fileInput.files;
  var ruta = "upload/test"
  var binaryData = [];
binaryData.push(ruta);
window.URL.createObjectURL(new Blob(binaryData, {type: "application/zip"}))
 // var path = URL.createObjectURL(ruta);//(window.URL).createObjectURL(files);///fileInput.path;
  //path = (window.URL || window.webkitURL).createObjectURL(file);///fileInput.path;
  //console.log('path', path);
 /// console.log(this.files[0].path);
  
  alert(ruta);
 /* for (var i = 0; i < filesp.length; i++) {
    alert("Filename " + filesp[i].name);
  }*/
/*
  for (var i = 0; i < files.files.length; i++) {
    alert("Filename " + files.files[i].name);
  }
}*/
</script>
  
<?
if(($_SESSION['LoggedIn']) <> ''){
   
    $u_id = $_SESSION['u_id'];    
    $org_id = $_SESSION['org_id'];                 
    $placas = $_GET['placas']; 
    //echo $placas;
    
    $empleado_vis = $mysqli->query("SELECT division
                                    FROM 
                                        arg_usuarios
                                    WHERE arg_usuarios.u_id = ".$u_id) or die(mysqli_error());
    $division_emplea = $empleado_vis ->fetch_array(MYSQLI_ASSOC);
    $division_empleado = $division_emplea['division']; 
    //echo $division_empleado;
    if($division_empleado == 'empleado'){
        $editar_veh = 'false';
    }
    else{
        $editar_veh = 'true';
    }
    if ($placas == ''){
        mysqli_multi_query ($mysqli, "CALL visor_vehiculos (".$org_id.")") OR DIE (mysqli_error($mysqli));  
    }
    else{
        $veh_edit = $mysqli->query("SELECT arg_vehiculos.poliza, arg_vehiculos.fecha_expira, arg_vehiculos.path
                                        FROM arg_vehiculos
                                        WHERE arg_vehiculos.placas = '".$placas."'") or die(mysqli_error());
            $veh_editar = $veh_edit ->fetch_array(MYSQLI_ASSOC);
            $poliza_act = $veh_editar['poliza']; 
            $poliza_act_fec = $veh_editar['fecha_expira']; 
            $poliza_act_path = $veh_editar['path']; 
           // echo $poliza_act;
    }
    
?>
<!--Modal ver Poliza--!>
<div class="modal fade" id="ModalVer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Poliza Vehículo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                     <div>
                       <iframe src="http://192.168.20.3:81/registro/<?echo $poliza_act_path;?>" width="450" height="350" ></iframe>
                     </div> 
              </div>      
              <div class="modal-footer">        
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     
              </div>  
            </div>
          </div>
   </div>
   
    <?if ($placas == ''){?>
                  <div class="container">                                                
                 <?
                 if ($editar_veh == 'true'){ ?>       
                               <td height="26" valign="medium">                                  
                                    <a href='' name="idsele" class="btn btn-primary" data-toggle="modal" data-target="#ModalVeh"  class="sepV_a" title="Nuevo vehículo">+ Agregar Vehículo</a>
                               </td>
                          <?}
        
                 $html_per = "<table class='table table-bordered' id='vehic'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Placas</th>
                                    <th scope='col'>Marca</th>
                                    <th scope='col'>Modelo</th>
                                    <th scope='col'>Color</th>
                                    <th scope='col'>Póliza</th>
                                    <th scope='col'>Expira</th>
                                    <th scope='col'>Acciones</th>
                                  </tr>
                              </thead>
                              <tbody>";
                               if ($result_per = mysqli_store_result($mysqli)) {                
                                      while ($row = mysqli_fetch_assoc($result_per)) {
                                             $html_per.="<tr>                                               
                                                <td> ".$row["placas"]."</td>
                                                <td> ".$row["marca"]."</td>
                                                <td> ".$row["modelo"]."</td>
                                                <td> ".$row["color"]."</td>
                                                <td> ".$row["poliza"]."</td>
                                                <td> ".$row["fecha_expira"]."</td>                                               
                                                <td> <a href=".$row["placas"]." name = 'idsele' class='btn btn-success' class='sepV_a' title='Poliza'>Editar</a></td>";
                                             "</tr>";
                                      }
                                      mysqli_free_result($result_per);
                                }
                $html_per.="</tbody></table>";
                
                echo ("$html_per");  //class='btn btn-info' data-toggle='modal' data-target='#ModalVer' 
                ?>
             </div>  

    <?
    }
    else{
        
    ?>        
    <div class="container"  class="col-md-12 col-lg-12">  
    <div class="row">
    <div class="container"  class="col-md-12 col-lg-12"> 
        <form method="post" enctype="multipart/form-data"> 
        <div id="content" class="col-lg-2">  
                        <label for="placas_veh"><b>Placas:</b></label><br><input type="text" name="placas_veh" class="form-control" value="<?echo $placas;?>" id="placas_veh" />
                        </div>                    
                        <div id="content" class="col-lg-2">  
                            <label for="poliza_veh"><b>Poliza:</b></label><br><input type="text" name="poliza_veh" class="form-control" value="<?echo $poliza_act;?>" id="poliza_veh" />
                        </div>
                        <div id="content" class="col-lg-2"> 
                            <label for="fecha_pol"><b>Expira:</b></label><br><input type="date" name="fecha_pol" value="<?echo $poliza_act_fec;?>" class="form-control" id="fecha_pol" />          
                        </div>
                         <div id="content" class="col-lg-1"> 
                           </br>                           
                            <a name="idsele" class="btn btn-success" data-toggle="modal" data-target="#ModalVer"  class="sepV_a" title="Poliza">Ver </a></td>
                         </div>
                        <div id="content" class="col-lg-2">                          
                            <label for="poliza_img"><b>Imagen:</b></label></br>
                            <input type="hidden" name="MAX_FILE_SIZE" value="200000000">
                            <input name="poliza_img" type="file" id="poliza_img"> 
                         </div>
                          <div id="content" class="col-lg-1"> 
                            <label for="poliza_ant"></label><input type="hidden" name="poliza_ant" value="<?echo $poliza_act_path;?>" class="form-control" id="poliza_ant" />          
                          </div>                        
                         <div id="content" class="col-lg-2">
                          <br />
                          <? if ($editar_veh == 'true'){ ?>
                               <td height="26" valign="medium"><input name="upload_pol" type="submit" class="box btn btn-info" id="upload_pol" value=" Actualizar "></td>
                          <?}?>
                       </div>
        </form>
    </div>
    </div>
    </div>  
        <?
        
      }  
        
      if(isset($_POST['upload_pol'])){
           $placas_veh = $_POST['placas_veh'];
           $poliza_veh = $_POST['poliza_veh'];
           $fecha_pol = $_POST['fecha_pol'];
           $archivo_eliminar = $_POST['poliza_ant'];
           
           $uploadDir = 'upload/vehiculos/';
                                
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
       
           
                $query = "UPDATE arg_vehiculos
                            SET placas = '$placas_veh', poliza = '$poliza_veh', fecha_expira = '$fecha_pol', path = '$archivo'
                          WHERE
                              placas = '".$placas."'";
                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                                    
                echo "<br><b>Se actualizó con exito:</b><br><br>"."$fileName <br>"; 
                echo "<script> redireccion() </script>";
            
        }
                            
               
    }
 
    ?> 
<!--<script type="text/javascript" src="js/popper/src/popper.js"></script>-->
<!--<script type="text/javascript" src="js/vehiculos.js"></script>-->
         
          

