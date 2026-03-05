<script>
    
       function redireccion($trn_id)
            {
                 var trn_id = $trn_id;
                 var print_d = '<?php echo "\app.php?trn_id="?>'+trn_id;                
                    window.location.href = print_d;
            }
    
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
    
    img{
      max-width: 100%;
    }
</style>
<?php 
$_SESSION['LoggedIn'] = 1; 

if(($_SESSION['LoggedIn']) <> ''){
    ?>
    
        <?            echo 'aqui';
            $u_id = 55;//$_GET['u_id'];
            echo $u_id;
               echo 'entro';
            
            /*if (!$mysqli->multi_query("CALL visor_visitas(2)")) {
                echo "Falló CALL: (" . $mysqli->errno . ") " . $mysqli->error;
            }*/
            
          // $datos_e = $mysqli->query("CALL visor_visitas (2)");
          // $user_firmado = $datos_e ->fetch_array(MYSQLI_ASSOC);
          // $entrada = $datos_e->fetch_assoc();
           // var_dump($user_firmado);
           // var_dump($resultado->fetch_assoc());
          // $res =  mysqli_multi_query ($mysqli, "CALL visor_visitas (2)") OR DIE (mysqli_error($mysqli));
            mysqli_multi_query ($mysqli, "CALL visor_visitas (2)") OR DIE (mysqli_error($mysqli));
            //var_dump($res);
           // echo $res;
            //while (mysqli_more_results($mysqli)) {
               
            if ($result = mysqli_store_result($mysqli)) {
                
              while ($row = mysqli_fetch_assoc($result)) {

                     // i.e.: DBTableFieldName="userID"
                     echo 'entro';
                     echo "row = ".$row["nombre"]."<br />";
                     

              }
              mysqli_free_result($result);
       }
      // mysqli_next_result($conn);

        //}
                    
            ?>
             <div class="container">
                                                
                 <? 
                 
                 $html_en = "<table class='table table-bordered' id='encabezado'>
                             <thead>
                                 <tr class='table-secondary'>   
                                    <th scope='col'>Unidad de Mina: ".$entrada['unidad']."</th>
                                    <th scope='col'>Desde: ".$entrada['fecha_inicio']."</th>
                                    <th scope='col'>Hasta: ".$entrada['fecha_final']."</th>
                                  </tr>;
                              </thead></tbody></table>";
                    
                 
                  /*$html_v = "<table class='table table-bordered' id='visitantes'>
                                <thead>
                                <tr class='bg-info'>            
                                    <th scope='col'>Visitantes</th>
                                    <th scope='col'>INE</th>
                                </tr>
                            </thead>
                            <tbody>";                    
                            	while ($fila_v = $visitantes->fetch_assoc()) {
                            		$html_v.="<tr>
                                                <td>".$fila_v['visitante']."</td>
                                                <td>".$fila_v['ine']."</td>
                                             </tr>";
                            	}
                  $html_v.="</tbody></table>";*/
                 
                      echo ("$html_en");
                     
                ?>
                
             <form method="post" action="print_doc.php?trn_id=<?echo $trn_id_rel;?>" name="Printform" id="Printform">  
                <fieldset>  
                    <input type="submit" class="btn btn-success" name="print" id="print" value="Ver PDF" />                
               </fieldset>  
            </form> 
          
          <form method="post" action="cancelar.php" name="newform" id="newform">  
                <fieldset>  
                    <input type="submit" class="btn btn-danger" name="cancelar_visita" id="cancelar_visita" value="Cancelar" />                
               </fieldset>  
            </form> 
          
             </div>
            <?
           
            
        }


    ?>
    <script>
        redireccion($trn_id);
    </script>
             
    
<script type="text/javascript" src="js/jquery.min.js"></script>
<!--<script type="text/javascript" src="js/vehiculos.js"></script>


-->
         
          

