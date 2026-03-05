<? include "connections/config.php"; ?> 

   <!DOCTYPE html> 
     <html lang="en"> 
     <head> 
     	<meta charset="UTF-8"> 
   
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
     <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
      <link href="http://192.168.20.22/MineData-Labs/css/check.css" rel="stylesheet">
      <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> 
      
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      
<script type="text/javascript">
$("#datetime").datetimepicker({
    format: 'yyyy-mm-dd hh:ii'
})
</script>

<script>
    $(".form_datetime").datetimepicker({
        language: "zh-CN",
        format: "hh:ii:ss",
        autoclose: true,
        startView: 1,
        minuteStep: 30,
        maxView: "hour"
    });
</script>

    <br /> <br /> <br />    
     <?  
        if(($_SESSION['LoggedIn']) <> '')
        {
            $user_fir = $mysqli->query("SELECT nombre
                                        FROM `arg_usuarios`                                        
                                        WHERE u_id = ".$_SESSION['u_id']) or die(mysqli_error());
            $user_firmado = $user_fir ->fetch_array(MYSQLI_ASSOC);
            $nombre_usuario = $user_firmado['nombre'];
            ?>  
                <div class="container">
                <div class="col-12 col-md-12 col-lg-12">
                
                <form method="post" action="app.php" name="Visitaform" id="Visitaform">  
                <fieldset>  
                        <div class="container" class="col-md-12 col-lg-12">
                            <div class="col-md-12 col-lg-12 bg-info text-white text-center">
                                    <label>ORDEN DE TRABAJO GENERALES</label>
                            </div>
                            <br/>
                         </div>                                                                                                                                                    
                         <div class="col-md-8 col-lg-8">                            
                                <div class="col-md-1 col-lg-1">               
                                    <h5><?echo 'Fecha:'?></h5>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                     <input type="date" name="fecha" class="form-control" id="fecha" value="<?php echo date("Y-m-d");?>"/>
                                </div>
                              
                                <div class='col-sm-3'>
                                     <div class="form-group">
                                        <div class='input-group date' id='datetimepicker3'>
                                           <input type='text' class="form-control" />
                                           <span class="input-group-addon">
                                           <span class="glyphicon glyphicon-time"></span>
                                           </span>
                                        </div>
                                     </div>
                                </div>
                                  <script type="text/javascript">
                                     $(function () {
                                         $('#datetimepicker3').datetimepicker({
                                             format: 'LT'
                                         });
                                     });
                                  </script>                             
                          
                          <div class="col-md-4 col-lg-4">
                              <?                           
                                    $organizaciontop = $_GET['organizacion'];
                                    if ($organizaciontop == "")
                                    $nombretop = "Seleccione Mina";
                                    echo ("<form name=\"Busqueda\" id=\"Busqueda\">");
                                   
                                    echo ("<select name=\"organizacion\" id=\"organizacion\" class=\"form-control\" > ");        
                                    echo ("<option value=$nomtop>$nombretop</option>");                            
                                    
                                    $result = $mysqli->query("SELECT unidad_id, Nombre FROM arg_empr_unidades") or die(mysqli_error());
                                    while( $row = $result ->fetch_array(MYSQLI_ASSOC)) 
                                      
                                      {
                                          $nombre =($row["Nombre"]);
                                          $nomenclatura = $row["unidad_id"];                                      
                                          echo ("<option value=$nomenclatura>$nombre</option>");
                                      }          
                                          echo ("</select>");                           
                                ?>
                             </div>
                           </div>
                        
                          <br/><br/><br/><br/>                          
                         
                        <!--Segundo Row-->
                        <div class="container">
                           <div class="col-md-2 col-lg-2"> 
                           
                                    <?                           
                                    $organizaciontop = $_GET['organizacion'];
                                    if ($organizaciontop == "")
                                    $nombretop = "Seleccione";
                                  
                                    echo ("<form name=\"Busqueda\" id=\"Busqueda\">");
                                   
                                    echo ("<select name=\"organizacion\" id=\"organizacion\" class=\"form-control\" > ");        
                                    echo ("<option value=$nomtop>$nombretop</option>");                            
                                    
                                    $result = $mysqli->query("SELECT unidad_id, Nombre FROM arg_empr_unidades") or die(mysqli_error());
                                    while( $row = $result ->fetch_array(MYSQLI_ASSOC)) 
                                      
                                      {
                                          $nombre =($row["Nombre"]);
                                          $nomenclatura = $row["unidad_id"];
                                          
                                          echo ("<option value=$nomenclatura>$nombre</option>");
                                      }          
                                          echo ("</select>");                           
                                    ?>
                             </div>
                             
                             <div class='col-sm-1'>
                                     <div class="form-group">                                   
                                        <div class='input-group date' id='cantidad_muestras'>
                                           <input type='text' class="form-control" />                                          
                                        </div>
                                     </div>
                             </div>
                             <? $datos_res = $mysqli->query("SELECT metodo_id, nombre FROM arg_metodos WHERE tipo_id = 1 AND activo = 1") or die(mysqli_error());?>                             
                         
                            <div class="[ form-group ]">
                            <?while ($fila = $datos_res->fetch_assoc()) {?>
                                <input type="checkbox" name="<?echo $fila['metodo_id']?>" id="<?echo $fila['metodo_id']?>" autocomplete="off" />
                                <div class="[ btn-group col-xs-1 col-sm-1 col-md-1 col-lg-1 col-xg-1]">
                                                
                                    <label for="<?echo $fila['metodo_id']?>" class="[ btn btn-primary  align-left  col-xs-2 col-sm-2 col-md-6 col-lg-6]">
                                    <span class="[ glyphicon glyphicon-ok ]"></span>                            
                                    <span></span>
                                    </label>
                                    <label for="<?echo $fila['metodo_id']?>" class="[ btn btn-default active text-left col-xs-2 col-sm-2 col-md-8 col-lg-8]">
                                        <?echo $fila['nombre']?>
                                    </label>                              
                                </div>
                              <?}?>
                              </div>
                             
                          </div>  <!-- Fin -->
        <? 	
        }
        ?>              
                              
                         <div class="container">
                             <div class="col-md-6 col-lg-6">   
                                <br/>
                                <input type="submit" class="btn btn-primary" name="visita" id="visita" value="Generar Orden" />                            
                         </div>
                        </div>
             
               </fieldset>  
            </form> 
         
        </div>           
      <?      

?>                    
    
<script type="text/javascript" src="js/jquery.min.js"></script>
<!--<script type="text/javascript" src="js/vehiculos.js"></script>-->  
          

