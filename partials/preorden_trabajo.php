<? //include "../connections/config.php"; 
$unidad_id = $_GET['unidad_id'];
//$trn_id = $_GET['trn_id'];
$_SESSION['unidad_id'] = $unidad_id;
//echo $unidad_id;
?> 

<script>
    var contador=1;
</script>

<script>
 
    function verificar_seleccion(numb){          
          var validar = numb;       
          //alert(validar); 
         if(validar == 1){
            alert('Se deben seleccionar banco y voladura. Reintente por favor');
            history.go(-1)
         }
                                  
    }
       
       function verificar_existencia(unidad_m, banco, vol){
                var unidad_id_s = unidad_m; 
                var banco_s     = banco; 
                var voladura_s  = vol;
                /*alert(vol);*/
                $.ajax({
                		url: 'existe_preorden.php' ,
                		type: 'POST' ,
                		dataType: 'html',
                		data: {unidad_id_s, banco_s, voladura_s},
                	})
                	.done(function(respuesta){
                	   //alert(respuesta);
                     if (respuesta == 'existe'){
                          alert('La preorden ya existe. Reintente por favor');                              
                          var print_d = '<?php echo "\preorden_trabajo.php?unidad_id="?>'+unidad_id_s;            
                          window.location.href = print_d;
                     }
                     else{
                        $.ajax({
                    		url: 'guardar_preorden.php' ,
                    		type: 'POST' ,
                    		dataType: 'html',
                    		data: {unidad_id_s, banco_s, voladura_s},
                    	})
                    	.done(function(respuesta){
                    	  // alert(respuesta);
                         if (respuesta != ''){
                              alert('La preorden se guardó con éxito'); 
                              var print_d = '<?php echo "\preorden_trabajo_print.php?trn_id="?>'+respuesta+'&unidad_id='+unidad_id_s;            
                              window.location.href = print_d;
                         }
                         else{
                            alert('La preorden no se guardó con éxito. Reintente por favor');
                            var print_d = '<?php echo "\preorden_trabajo.php?unidad_id="?>'+unidad_id_s;            
                            window.location.href = print_d;
                         }
                      })
                     }
                  })                       
       }
     
     //Actualizar voladuras despues de seleccionar banco
     function actualiza_vol(contador){
            var cont = contador;
            //alert(cont);
            var cambia = "banco"+cont;
            var voladura = "voladura"+cont;
            //alert(voladura);
            var banco  = document.getElementById(cambia).value;
            var unidad_id = document.getElementById("mina_seleccionada").value;
            $.ajax({
            		url: 'actualizar_voladuras.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {banco, unidad_id},
            	})
            	.done(function(respuesta){
                 if (cont == 1){
                       $("#voladura1").html(respuesta);  
                    }
                    else{
                        $("#"+voladura).html(respuesta); 
                    }           		                  
              })
      }
      
      function actualiza_ultimofol(contador)
        {
            var cont = contador;
            //alert('hola');
            var cambia = "banco"+cont;
            var voladura = "voladura"+cont;            
            var banco       = document.getElementById(cambia).value;
            var voladura_id = document.getElementById(voladura).value;
            //alert(banco);
            //alert(voladura_id);
            var unidad_id = document.getElementById("mina_seleccionada").value;
            $.ajax({
            		url: 'actualizar_folios.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {banco, voladura_id, unidad_id},
            	})
            	.done(function(respuesta){
                    document.getElementById('ultimo_folio').value = respuesta;                      		                  
              })
         }
      
    
     function imprimir_pre($unidad_id,$trn_id)
            {
                 alert('Se generó la pre-orden de trabajo satisfactoriamente');
                 var trn_id = $trn_id;
                 var unidad_id = $unidad_id              
                 var print_d = '<?php echo "\preorden_trabajo_print.php?trn_id="?>'+trn_id+'&unidad_id='+$unidad_id;            
                 window.location.href = print_d;		                  
            }
    
</script>
    <br/><br/>
     <?  
        if(($_SESSION['LoggedIn']) <> '')
        {
            if (isset($_GET['trn_id'])){
                echo"<script> imprimir_pre($unidad_id,$trn_id); </script>";
            }
            else{
                 //Tomar caracter de la unidad de mina
                $caracter_mina = $mysqli->query("SELECT caracter_folio, nombre, serie
                                                      FROM `arg_empr_unidades`                                        
                                                        WHERE unidad_id = ".$unidad_id) or die(mysqli_error());
                $caracter_fol = $caracter_mina ->fetch_array(MYSQLI_ASSOC);
                $caracter_folio = $caracter_fol['caracter_folio'];
                $serie_mina = $caracter_fol['serie'];
                
                //Click en Generar Orden
                if (isset($_POST['generar_preorden'])){        
                     $fecha             = $_POST['fecha'];
                     $mina_seleccionada = $_POST['mina_seleccionada'];
                     $u_id              = $_SESSION['u_id'];
                                   
                     $banco_sel    = $_POST['banco1'];
                     $voladura_sel = $_POST['voladura1'];
                    
                     if ($banco_sel == 0 || $voladura_sel == 0){
                            echo "<script>";
                            echo "verificar_seleccion(1)";
                            echo "</script>";                            
                     }
                     else{
                         if ($banco_sel != 0 && $voladura_sel != 0){ //Validar si existe la preorden
                                echo "<script>";
                                echo "verificar_existencia($unidad_id, $banco_sel, $voladura_sel)";
                                echo "</script>"; 
                            }  
                    }                
                     
                }                
                else{            
                    ?>                             
                    <form method="post" action="preorden_trabajo.php?unidad_id=<?echo $unidad_id;?>" name="Preordenform" id="Preordenform">  
                    <fieldset>    
                    
                    <div class="container">                     
                            <div class="col-md-8 col-lg-8 bg-info text-black text-center">
                                <br />
                                <h4>PRE-ORDEN DE TRABAJO SÓLIDOS</h4>
                            </div>
                            <br/><br/><br/> <br/>
                                                                                                                                                                           
                            <div class="col-md-10 col-lg-10">
                                      
                                    <div class="col-md-1 col-lg-1">               
                                        <h5><?echo 'Fecha:'?></h5>
                                    </div> 
                                    <div class="col-md-2 col-lg-2">
                                         <input type="date" name="fecha" class="form-control" id="fecha"  disabled="1" value="<?php echo date("Y-m-d");?>"/>
                                    </div>                            
                                      
                              <div class="col-md-2 col-lg-2">                                
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
                              
                        </div>
                            
                        <!--Primer Row-->
                        <br /><br /><br />
                        
                            <div class="row">    
                          
                             <div class="col-md-6 col-lg-6">
                             <table class="table table-hover text-black" id="tablaprueba">
                                  <thead class="thead-light" align='center'>
                                    <tr>
                                      <th colspan='1'>Banco</th>
                                      <th colspan='1'>Voladura</th>
                                      <th colspan='1'>Último Folio</th>
                                     
                                    </tr>
                                  </thead>
                                  <tbody>
                                  <div class="col-md-2 col-lg-2">
                                    <td>                                
                                        <?                           
                                        $organizaciontop = $_GET['bancos'];
                                        if ($organizaciontop == ""){
                                            $nombretop = "Seleccione";
                                            $nomtop = 0;
                                        }                                  
                                        echo ("<form name=\"banco1\" id=\"banco1\">");                                   
                                        echo ("<select name=\"banco1\" id=\"banco1\" onchange=actualiza_vol(1) class=\"form-control\" > ");        
                                        echo ("<option value=$nomtop>$nombretop</option>");
                                        $result = $mysqli->query("SELECT banco_id, banco FROM arg_bancos WHERE unidad_id = ".$unidad_id) or die(mysqli_error());
                                        while( $row = $result ->fetch_array(MYSQLI_ASSOC))                                       
                                          {
                                              $nombre =($row["banco"]);
                                              $nomenclatura = $row["banco_id"];                                          
                                              echo ("<option value=$nomenclatura>$nombre</option>");
                                          }          
                                        echo ("</select>");
                                       ?>
                                    </td>
                                    </div>
                                    
                                    <div class="col-md-1 col-lg-1">
                                        <td>
                                        <?
                                        $organizaciontop = $_GET['bancos'];
                                        if ($organizaciontop == "")
                                        $nombretop = "Seleccione";                                  
                                        echo ("<form name=\"voladura1\" id=\"voladura1\">");                                   
                                        echo ("<select name=\"voladura1\" id=\"voladura1\" onchange=actualiza_ultimofol(1) class=\"form-control\" > ");        
                                        echo ("<option value=$nomtop>$nombretop</option>");
                                        $result = $mysqli->query("SELECT voladura_id FROM arg_bancos_voladuras") or die(mysqli_error());
                                        while( $row = $result ->fetch_array(MYSQLI_ASSOC))                                       
                                          {
                                              $nombre =($row["voladura_id"]);
                                              $nomenclatura = $row["voladura_id"];
                                              
                                              echo ("<option value=$nomenclatura>$nombre</option>");
                                              }          
                                        echo ("</select>");                                      
                                        ?>
                                        </td>
                                    </div>
                                     <div class="col-md-2 col-lg-2">
                                         <td>  
                                            <input type="text" disabled="" name="ultimo_folio" class="form-control" id="ultimo_folio" value=""/>
                                         </td>
                                      </div>
                                  </tbody>                                    
                                </table>
                              
                              </div> 
                        </div> 
                             </div>
                  
                   </fieldset>  
                   <div class="container">
                        <input type="submit" class="btn btn-success" name="generar_preorden" id="generar_preorden" data-toggle="modal" data-target="#guardandopre" value="GUARDAR PRE-ORDEN" />                      
               
                   </div>
                </form> 
                
                <!-- Modal -->
                <div class="modal fade" id="guardandopre" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">GENERANDO PRE-ORDEN</h5> 
                      </div>
                      <div class="modal-body">
                        <div class="text-center">
                            <h4>Please wait...</h4>        
                            <img  src="images\upload.gif">          
                        </div>
                      </div>
                     
                    </div>
                  </div>
                </div>
       <?}
    }
}?>           
<br /> <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<script type="text/javascript" src="js/jquery.min.js"></script>
 