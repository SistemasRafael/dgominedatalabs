<? //include "../connections/config.php"; 
$unidad_id = $_GET['unidad_id'];
$trn_id = $_GET['trn_id'];
$_SESSION['unidad_id'] = $unidad_id;
//echo $unidad_id;
?> 

   <!DOCTYPE html> 
     <html lang="en"> 
     <head> 
     	<meta charset="UTF-8"> 
   
  <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
      <link href="http://192.168.20.22/MineData-Labs/css/check.css" rel="stylesheet">
      <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> 
      
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<script>
    var contador=1;
</script>


<script>
    function cargar_orden(trn_id,unidad_id)
            {
                 var trn_id = trn_id;
                 var unidad_id = unidad_id;                
                 var print_d = '<?php echo "\mpr.php?unidad_id="?>'+unidad_id+'&trn_id='+trn_id;                
                 window.location.href = print_d;
            }
    
    
     function imprimir(unidad_id,trn_id)
            {
                   // alert('llegoo');
                 var trn_id = trn_id;
                 var unidad_id = unidad_id                 
                 var print_d = '<?php echo "\orden_trabajo_print.php?trn_id="?>'+trn_id+'&unidad_id='+$unidad_id;                
                 window.location.href = print_d;
            }
    
</script>
    <br/><br/>
     <?  
        if(($_SESSION['LoggedIn']) <> '')
        {
            $user_fir = $mysqli->query("SELECT nombre
                                        FROM `arg_usuarios`                                        
                                        WHERE u_id = ".$_SESSION['u_id']) or die(mysqli_error());
            $user_firmado = $user_fir ->fetch_array(MYSQLI_ASSOC);
            $nombre_usuario = $user_firmado['nombre'];
            
                //echo"<script> imprimir($unidad_id,$trn_id); </script>";
           
                ?>                             
                    <form method="post" action="mpr.php?unidad_id=<?echo $unidad_id.'&trn_id='.$trn_id;?>" name="Visitaform" id="Visitaform">  
                    <fieldset>    
                           <div class="col-md-2 col-lg-2">     
                           </div>
                                       
                            <div class="col-md-8 col-lg-8 bg-info text-black text-center">
                                <br />
                                <h4>PESAJE DE RECEPCIÓN</h4>
                            </div>
                            <br/><br/><br/> <br/>
                                                 
                            <div class="col-md-2 col-lg-2">     
                            </div>                                                                                                                          
                            <div class="col-md-7 col-lg-7">
                                                    
                                    <div class="col-md-2 col-lg-3 disable">               
                                        <h5><?echo 'Fecha Pesaje:'?></h5>
                                        <input type="date" name="fecha" class="form-control" id="fecha" disabled="" value="<?php echo date("Y-m-d");?>"/>
                                    </div>                            
                                   
                                    <div class="col-md-2 col-lg-2">   
                                     <h5><?echo 'Unidad de Mina:'?></h5>                               
                                                <?                           
                                                $unidad_id = $_GET['unidad_id'];
                                                if ($unidad_id == ""){
                                                    $nombretop = "Seleccione Mina";
                                                }
                                                else{
                                                    $nomtop = $unidad_id;
                                                    $result = $mysqli->query("SELECT unidad_id, Nombre FROM arg_empr_unidades WHERE unidad_id = ".$unidad_id) or die(mysqli_error());
                                                        while( $row = $result ->fetch_array(MYSQLI_ASSOC)){
                                                           $nombretop = $row['Nombre']; 
                                                        }
                                                }                                  
                                                echo ("<form name=\"Busqueda\" id=\"Busqueda\">");                                   
                                                echo ("<select name=\"mina_seleccionada\" id=\"mina_seleccionada\" disabled class=\"form-control\" > ");        
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
                                 <div class="col-md-2 col-lg-3">  
                                 <section class="principal">
                                    <h5><?echo 'Buscar orden:'?></h5>
                                        		<input class="search_query form-control" type="text" name="caja_busqueda" id="caja_busqueda" autocomplete="off"  placeholder="Search Order..."></input>
                                                   <br />          
                                        	       <div class="col-md-8 col-lg-8" id="datos">
                                                   </div> 
                                                    <br />      
                                      	 <br />   
                                    </section>  
                                 </div>
                        </div>
                        <? if ($_GET['trn_id']){
                            $limite_p = $mysqli->query("SELECT (CASE WHEN od.porcentaje = 0 THEN 1 ELSE od.porcentaje END) AS porcentaje 
                                                        FROM ordenes_metodos od 
                                                        LEFT JOIN arg_ordenes o
                                                        	ON o.trn_id = od.trn_id_rel                                          
                                                        WHERE od.trn_id =  ".$trn_id." AND o.unidad_id = ".$unidad_id) or die(mysqli_error());
                            $limite_porc = $limite_p ->fetch_array(MYSQLI_ASSOC);
                            $limite = $limite_porc['porcentaje'];
                                                        
                            $orden = $mysqli->query("SELECT date_format(o.fecha, '%d-%m-%Y') AS fecha, od.folio_inicial AS orden, om.folio as muestra 
                                                        FROM `arg_ordenes_detalle` od
                                                        LEFT JOIN arg_ordenes_muestras om
                                                        	ON om.trn_id_rel = od.trn_id
                                                        LEFT JOIN arg_ordenes o
                                                        	ON o.trn_id = od.trn_id_rel
                                                        WHERE od.trn_id =  ".$trn_id." AND o.unidad_id = ".$unidad_id." ORDER BY RAND() LIMIT ".$limite) or die(mysqli_error());
                            
                            $i = 1;
                            //$orden_muestras = $orden ->fetch_array(MYSQLI_ASSOC); 
                            ?>  
                        <!--Primer Row-->
                        <br /><br /><br /><br /><br /><br />
                     
                        <div class="container">
                        
                            <div class="col-md-10 col-lg-10">
                            
                            <div class="col-md-1 col-lg-1">                      
                            </div>
                            <div class="col-md-8 col-lg-8">
                                <table class="table text-black" id="tabla_peso">
                                      <thead class="thead-light" align='left'>
                                        <tr>
                                          <th colspan='1'>No.</th>
                                          <th colspan='1'>Fecha Orden</th>
                                          <th colspan='3'>Orden de Trabajo</th>
                                          <th colspan='1'>Muestra</th>
                                          <th colspan='1'>Peso</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      
                                      <?while ($row = $orden->fetch_assoc()) {?>
                                        <tr>
                                            <td>                                
                                                <?echo $i;?>
                                            </td>
                                            <td colspan='1'>                                
                                                <?echo $row['fecha'];?>
                                            </td>
                                            <td colspan='3'>                                
                                                <?echo $row['orden'];?>
                                            </td>
                                            <td>
                                                <?echo $row['muestra'];?>
                                             </td>
                                             <td colspan='1'>
                                                 <input type="number" name="cantidad_muestras1" id="cantidad_muestras1" class="form-control" /> 
                                             </td> 
                                        <?$i++?>
                                        </tr>                              
                                       <?}?> 
                                      </tbody>
                                </table>
                        </div>
                    </div>
                </div>
                        
                        <div class="col-md-5 col-lg-6">                      
                        </div>
                        
                        <div class="col-md-1 col-lg-1"> 
                            <input type="submit" class="btn btn-info" name="generar_orden" id="generar_orden" value="GUARDAR" />                      
                        </div>
                
                   </fieldset>  
                </form> 
             <br /><br /><br /> <br /><br /><br />
       <?}
    }?>        
       <br /><br /><br /> <br /><br /><br /> <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>         

