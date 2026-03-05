<? //include "../connections/config.php"; 
$unidad_id = $_GET['unidad_id'];
$area_muestras = $_GET['area_id'];
$_SESSION['unidad_id'] = $unidad_id;
//echo $unidad_id;
?> 

<script>
    var contador=1;
</script>

<script>
    function buscar_orden($unidad_id)
    {
         var trn_id = $trn_id;
         var unidad_id = $unidad_id;                
         var print_d = '<?php echo "\orden_trabajo_print.php?unidad_id="?>'+unidad_id;                
         window.location.href = print_d;
    }
    
     function cargar_muestras($unidad_id)
    {
         var area_id = document.getElementById('area_id').value;
         var unidad_id = $unidad_id;                
         //alert(area_id);
         var print_d = '<?php echo "\app_sln.php?unidad_id="?>'+unidad_id+'&area_id='+area_id;                
         window.location.href = print_d;
    }
  
    function calculatotal()
    {
         var j = 3;     
         var table = document.getElementById("tablaprueba");
         var total_rows = parseInt(table.rows.length);
         var total_mues = parseInt(0);
         var fila_validar = '';
         var act = '';
         //alert(total_rows);
         
         var activos = 0;
         while (j <  total_rows){
            //alert('entr');
            fila_validar = "fila"+j;
            act = document.getElementById(fila_validar).checked;
            //alert(act);
            //alert(act == "true");
            if (act == true){
                activos = activos+1;
                //alert('entroo');
                j = j+1;
            }
            else{
                j = j+1;    
            }
            
         }
         document.getElementById('total_muestras_1').value = activos;         
         document.getElementById('total_muestras1').value  = activos; 
    }
    
    function calculatotal_met(contador)
    {
         
         var table = document.getElementById("tablaprueba");
         var activos = parseInt(table.rows.length-3);        
         
         document.getElementById('total_muestras_1').value = activos;         
         document.getElementById('total_muestras1').value  = activos; 
    }
        
    function verificar_seleccion(numb){          
          var validar = numb;          
          //alert(validar); 
        /*  if(validar == 1){
            alert('Se deben capturar todos los datos');
            history.go(-1)
          }*/
          if(validar == 4){
            alert('Se debe capturar al menos un método');
            history.go(-1)
          } 
          /*if(validar == 7){
            alert('Warning: Se debe capturar la hora de recepción de las muestras. Reintente');
            history.go(-1)
          }*/                             
     }

      function eliminarFila(fila){
          var filaEl = fila;
          var table = document.getElementById("tablaprueba");
          var rowCount = table.rows.length;   
         // var filaEliminar = table.row.filaEl; 
         // alert(filaEliminar);
          //console.log(rowCount);          
         // if(rowCount <= 3)
         if (filaEl == 0)
            alert('No se puede eliminar el encabezado');
          else{
            table.deleteRow(filaEl);
            ///contador = parseInt(rowCount)-2;
            //alert(contador);
            calculatotal();
          }
    }
    
     function imprimir($unidad_id,$trn_id)
            {
                 alert('Se generó la orden de trabajo satisfactoriamente');
                 var trn_id = $trn_id;
                 var unidad_id = $unidad_id 
                 //var unidad_id = document.getElementById("mina_seleccionada").value;
            /*$.ajax({
            		url: 'notificacion_nuevaorden.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {trn_id, trn_id},
            	})
            	.done(function(respuesta){*/
            	   //alert(respuesta);
                        //alert(print_d);
                 var print_d = '<?php echo "\orden_trabajo_rep.php?trn_id="?>'+trn_id;
                 window.location.href = print_d;		                  
            //  }) 
            }
            
      function agregar_muestra(){
          var table = document.getElementById("tablaprueba");
          var contador = (table.rows.length-2);
          //alert(contador);
          var sig_mues = "muestra"+contador;
          var muestra_sele = document.getElementById("muestra_sel").value;
          //alert(muestra_sele);
          $.ajax({
            		url: 'obtener_muestra_folio.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {muestra_sele, muestra_sele},
            	})
            	.done(function(respuesta){
            	  document.getElementById("tablaprueba").insertRow(-1).innerHTML = '<td><input type="hidden" name="fila" id="fila" value = "'+contador+'" class="form-control" />'+contador+'</td>'
        +'<td><input type="hidden" name="'+sig_mues+'" id="'+sig_mues+'" value = "'+muestra_sele+'" class="form-control" />'+respuesta+'</td>'; 
          	  calculatotal_met();                
              })   
    } 
    
</script>
    <br/><br/>
     <?  
        if(($_SESSION['LoggedIn']) <> '')
        {
            $user_fir       = $mysqli->query("SELECT nombre
                                              FROM `arg_usuarios`                                        
                                              WHERE u_id = ".$_SESSION['u_id']) or die(mysqli_error());
            $user_firmado   = $user_fir ->fetch_array(MYSQLI_ASSOC);
            $nombre_usuario = $user_firmado['nombre'];
                        
     ?>                             
                    <form method="post" action="app_sln.php?unidad_id=<?echo $unidad_id."&area_id=".$area_muestras;?>" name="Visitaform" id="Visitaform">  
                    <fieldset>                       
                            <div class="col-md-12 col-lg-12 bg-info text-black text-center">
                                <br />
                                <h4>ORDEN DE TRABAJO SOLUCIONES</h4>
                            </div>
                            <br/><br/><br/> <br/>
                            <div class="container">                                                                                                                                                 
                            <div class="col-md-11 col-lg-11">
                                
                                    
                                  <div class="col-md-3 col-lg-3">  
                                                          
                                        <?     
                                        $area_muestras = $_GET['area_id'];  
                                        if ($area_muestras == ""){
                                            $nombretop = "Seleccione Tipo de Muestras";
                                        }
                                        else{
                                           if ($area_muestras == 1){
                                                $nombretop = "Planta";
                                           }else{
                                                $nombretop = "Metalurgia";
                                           }
                                        }                                  
                                        echo ("<form name=\"area\" id=\"area\">");                                   
                                        echo ("<select name=\"area_id\" id=\"area_id\" onchange=\"cargar_muestras($unidad_id)\" class=\"form-control\" > ");        
                                        echo ("<option value=$nomtop>$nombretop</option>");
                                        $result = $mysqli->query("SELECT 1 AS area_id, 'Planta' AS area UNION ALL SELECT 2 AS area_id, 'Metalurgia' AS area") or die(mysqli_error());
                                        while( $row = $result ->fetch_array(MYSQLI_ASSOC))                                      
                                          {
                                              $nombre =($row["area"]);
                                              $nomenclatura = $row["area_id"];                                          
                                              echo ("<option value=$nomenclatura>$nombre</option>");
                                          }          
                                        echo ("</select>");                           
                                        ?> 
                                </div> 
                                                    
                                    <div class="col-md-1 col-lg-1">               
                                        <h5><?echo 'Fecha:'?></h5>
                                    </div>
                                    <div class="col-md-2 col-lg-2">
                                         <input type="date" name="fecha" class="form-control" id="fecha" value="<?php echo date("Y-m-d");?>"/>
                                    </div>                                
                                    <div class="col-md-2 col-lg-2">                                                                                                         
                                    <?                           
                                                                            
                                        echo ("<form name=\"turno\" id=\"turno\">");                                   
                                        echo ("<select name=\"turno\" id=\"turno\" class=\"form-control\" > ");        
                                        echo ("<option value=$nomtop>Seleccione Turno</option>");
                                        $result = $mysqli->query("SELECT '07:00' AS turno, '1 T' AS etiqueta UNION ALL SELECT '19:00' AS turno, '2 T' AS etiqueta") or die(mysqli_error());
                                        while( $row = $result ->fetch_array(MYSQLI_ASSOC))                                      
                                          {
                                              $nombre =($row["etiqueta"]);
                                              $nomenclatura = $row["turno"];                                          
                                              echo ("<option value=$nomenclatura>$nombre</option>");
                                          }          
                                        echo ("</select>");                           
                                        ?>                                     
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
                            
                        <br /><br /><br />
                        
                   
                            <div class="col-md-8 col-lg-9">
                            <table class="table table-hover text-black" id="tablaprueba">
                                  <thead class="thead-light" align='center'>
                                    <tr>
                                      <th colspan='4'>METODOS</th>
                                       <div class="row">  
                                        <div class="col-md-10 col-lg-10">                      
                                        </div>
                                        <div class="col-md-1 col-lg-1"> 
                                            <input type="submit" class="btn btn-success" name="generar_ordenSln" id="generar_ordenSln" data-toggle="modal" data-target="#exampleModal" value="GUARDAR ORDEN" />                      
                                        </div>    
                                    </tr>
                                    <tr>
                                       <th colspan='4'>
                                              <? $datos_res = $mysqli->query("SELECT metodo_id, nombre FROM arg_metodos WHERE activo = 1 AND tipo_id = 2") or die(mysqli_error());?>
                                                 <div class="[ form-group ] ">   
                                                    <?while ($fila = $datos_res->fetch_assoc()) {?>
                                                            <input type="checkbox" name="<?echo 'fila2_'.$fila['metodo_id']?>" id="<?echo 'fila2_'.$fila['metodo_id']?>" autocomplete="off" />
                                                            <div class="[ btn-group ]">                                                                
                                                                <label for="<?echo 'fila2_'.$fila['metodo_id']?>" class="[ btn btn-warning ]">
                                                                    <span class="[ glyphicon glyphicon-ok ]"></span>                            
                                                                    <span></span>
                                                                </label>                                                    
                                                                <label for="<?echo 'fila2_'.$fila['metodo_id']?>" class="[ btn btn-default active ]">
                                                                    <?echo $fila['nombre']?>
                                                                </label>                              
                                                            </div>                                            
                                                    <?}?>                                        
                                                 </div>   
                                         </th>
                                    </tr>
                                    <?if ($area_muestras == 2){ ?> 
                                            <th colspan='1'>No.</th>
                                            <th colspan='1'>MUESTRAS <?                                      
                                      $result = $mysqli->query("SELECT trn_id, folio   
                                                                FROM arg_ordenes_muestrasSoluciones 
                                                                WHERE activo = 1 AND tipo_id = 2 AND area_id = ".$area_muestras." AND unidad_id = ".$unidad_id
                                                                ) or die(mysqli_error());
                                      $i = 3;
                                       
                                             $muestra = 'muestra'.$i;
                                             $posicion_nombre = 'p'.$muestra;
                                             $muestra_sol = $row['folio'];
                                             $muestra_trn = $row['trn_id']; 
                                             echo ("<td>"); 
                                               echo ("<form name=\"muestra\" id=\"muestra\">");                                   
                                               echo ("<select name=\"muestra_sel\" id=\"muestra_sel\"  class=\"form-control\" > ");        
                                               echo ("<option value=0>Seleccione Muestras</option>");
                                                while( $row = $result ->fetch_array(MYSQLI_ASSOC))                                       
                                                {    
                                                        $muestra_trn = $row['trn_id'];
                                                        $muestra_folio_e = $row['folio_envia'];                                                        
                                                        $muestra_folio = $row['folio'];                                                                                                        
                                                        echo ("<option value=$muestra_trn>$muestra_folio</option>");
                                                
                                                }
                                                  echo ("</select>"); 
                                                  echo ("</td>");
                                              ?>     
                                              </th>  
                                                <th colspan='1'>  <input type="button" class="btn btn-info" name="add_muestra" id="add_muestra" onclick="agregar_muestra()"  value="AGREGAR A LA ORDEN" />                      
                                              </th> 
                                    <?} 
                                    else{?>    
                                        <tr>
                                            <th colspan='2'>MUESTRAS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      
                                     <div class="col-md-1 col-lg-1">                                     
                                      <?                                      
                                      /*$result = $mysqli->query("SELECT trn_id, folio   
                                                                FROM arg_ordenes_muestrasSoluciones 
                                                                WHERE activo = 1 AND tipo_id = 2 AND area_id = ".$area_muestras) or die(mysqli_error());*/
                                      $query = "CALL arg_prc_muestrasSoluciones($unidad_id,1)";
                                      mysqli_multi_query($mysqli, $query);
                                      $result = $mysqli->store_result();
                                      $i = 3;
                                      while( $row = $result ->fetch_array(MYSQLI_ASSOC)){                                   
                                    //  while ($row = $result->fetch_row())   {                                                
                                             $muestra = 'muestra'.$i;                                             
                                             $muestra_trn = $row['trn_id'];  
                                             $muestra_sol = $row['folio'];                                             
                                             echo ("<tr> <input type='hidden' name='$muestra' id='$muestra' value='$muestra_trn' class='form-control' />                                                        
                                                   <td> <input type='text' disabled='1' id='$muestra_sol' name='$muestra_sol' value='$muestra_sol' class='form-control' /> </td>"
                                                   );                                                                                   
                                              ?>                             
                                                   <td> <input type="checkbox" name="<?echo 'fila'.$i;?>" id="<?echo 'fila'.$i;?>" onchange="calculatotal()" class='form-control' autocomplete="off" /> </td>
                                                   </tr>
                                        <?                                       
                                            $i = $i+1;
                                          }
                                          $total_muestras = (mysqli_num_rows($result));  
                                      }
                                       if($result = mysqli_store_result($mysqli)){
        		mysqli_free_result($result);
            } while(mysqli_more_results($mysqli) && mysqli_next_result($mysqli));?> 
                                    </tbody>                                  
                                </table>
                                
                                <table>
                                <tbody>
                                    <tr> 
                                      <td style="width:10%"><strong>Total Muestras: </strong></td>    
                                      <td style="width:1%"></td>                               
                                      <td style="width:5%"><input type="input" name="total_muestras_1" id="total_muestras_1" disabled="1"  value="" class="form-control" /></td> 
                                      <input type="hidden" name="total_muestras1" id="total_muestras1" value="" class="form-control" />
                                      <input type="hidden" name="total_muestras_lista" id="total_muestras_lista" value="<?echo  $total_muestras;?>" class="form-control" />
                                      <td style="width:20%"></td>     
                                       </tr>                                    
                                </tbody>                                      
                                </table>
                              
                    </div>
                    </div>
                    </div>
                    <br/>
                   </fieldset>  
                   </form>
                
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">GENERANDO ORDEN</h5> 
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
       <?
              //Click en Generar Orden
                if (isset($_POST['generar_ordenSln'])){
                     $caracter_mina  = $mysqli->query("SELECT caracter_folio, nombre, serie
                                                      FROM `arg_empr_unidades`                                        
                                                        WHERE unidad_id = ".$unidad_id) or die(mysqli_error());
                     $caracter_fol   = $caracter_mina ->fetch_array(MYSQLI_ASSOC);
                     $caracter_folio = $caracter_fol['caracter_folio'];
                     $serie_mina     = $caracter_fol['serie'];
                     
                     $fecha             = $_POST['fecha'];
                     $hora              = $_POST['turno'];
                     $mina_seleccionada = $_POST['mina_seleccionada'];
                     $u_id              = $_SESSION['u_id'];
                     $total_muest       = $_POST['total_muestras1']; 
                     $total_muest_list  = $_POST['total_muestras_lista'];  
                     
                     echo $total_muest_list.'Total listado';
                     
                     //echo 'areasel:'.$area_muestras;
                     
                     if ($area_muestras == 2){
                        $fin = $total_muest;
                     }
                     else{
                        $fin = $total_muest_list;
                     }
                     
                     echo 'fin'.$fin;
                     $j = 1;
                     $pos = 1;
                     $cons_det = 1;                     
                     $crear = 1;          
                     //die();           
                                
                                //Métodos
                                $val_met = 0;
                                $metodos_validar = $mysqli->query("SELECT metodo_id FROM arg_metodos WHERE activo = 1 AND tipo_id = 2") or die(mysqli_error());
                                    while ($metodos = $metodos_validar->fetch_assoc()) {
                                        $metodo_id = $metodos['metodo_id'];
                                        $fila1 = 'fila2_'.$metodo_id;
                                        $metodo_sel = $_POST[$fila1];
                                        if ($metodo_sel == 'on'){
                                            $val_met = 1;
                                        }
                                    }
                                
                                if ($val_met == 0){                            
                                            echo "<script>";
                                            echo "verificar_seleccion(4)";
                                            echo "</script>";
                                            $i = 0;      
                                        }     
                                 else{
                                    if ($crear == 1){
                                         $max_trn_id = $mysqli->query("SELECT ifnull(MAX(trn_id), 0) AS trn_id FROM arg_ordenes") or die(mysqli_error());
                                         $ma_trn_id = $max_trn_id ->fetch_array(MYSQLI_ASSOC);
                                         $trn_id = $ma_trn_id['trn_id'];
                                         $trn_id = $trn_id + 1;
                                                 
                                         $max_fol = $mysqli->query("SELECT ifnull(MAX(folio), 0) AS folio FROM arg_ordenes WHERE unidad_id = ".$unidad_id) or die(mysqli_error());
                                         $max_foli = $max_fol ->fetch_array(MYSQLI_ASSOC);
                                         $max_folio = $max_foli['folio'];
                                         $folio_orden = $max_folio + 1;
                                         //echo 'fechaayer:'.$fecha;
                                         
                                         //Ordenes
                                         $query = "INSERT INTO arg_ordenes (trn_id, trn_id_rel, folio, hora, fecha, fecha_inicio, fecha_final, unidad_id, usuario_id, tipo, activo, comentario ) ".
                                                  "VALUES ($trn_id, 0, $folio_orden, '$hora', '$fecha', '', '', $unidad_id, $u_id, 2, 1, '')";
                                         $mysqli->query($query) ;
                                         //echo $query;
                                         
                                         //Ordenes_detalle
                                          $max_trn_det = $mysqli->query("SELECT MAX(trn_id) AS trn_id FROM arg_ordenes_detalle") or die(mysqli_error());
                                          $max_trn = $max_trn_det ->fetch_array(MYSQLI_ASSOC);
                                          $tr_id_det = $max_trn['trn_id'];
                                          $tr_id_det = $tr_id_det + 1;
                                                     
                                          $max_folio_det = $mysqli->query("SELECT  IFNULL(MAX(od.folio), 0) AS folio_ord 
                                                                           FROM arg_ordenes_detalle od
                                                                           LEFT JOIN arg_ordenes AS o
                                                                                ON od.trn_id_rel = o.trn_id
                                                                           WHERE
                                                                                o.unidad_id = ".$unidad_id) or die(mysqli_error());
                                          $max_fol = $max_folio_det ->fetch_array(MYSQLI_ASSOC);
                                          $folio_det = $max_fol['folio_ord'];
                                          $folio_det = $folio_det + 1;
                                                     
                                          $length = 6;                            
                                          $string_c = (string)$folio_det;
                                          $cons_c = str_pad($string_c,$length,"0", STR_PAD_LEFT);
                                                     
                                          $length_fs = 3;
                                                     
                                          $folio_interno = $serie_mina.$cons_c;
                                                     
                                          $query = "INSERT INTO arg_ordenes_detalle (trn_id, trn_id_rel, banco_id, voladura_id, cantidad, folio_inicial, folio_final, folio, folio_interno, estado, usuario_id) ".
                                                              "VALUES ($tr_id_det, $trn_id, 0, 0, $total_muest, '','',$folio_det , '$folio_interno', 1, $u_id)";
                                          $mysqli->query($query) ;
                                          //echo $query;
                                          $crear = $crear++;
                                    }
                                     
                                      //MUESTRAS METODOS   
                                      $max_trn_id_met = $mysqli->query("SELECT IFNULL(MAX(trn_id), 0) AS trn_id FROM arg_ordenes_metodos ") or die(mysqli_error());
                                      $ma_trn_id_m = $max_trn_id_met ->fetch_array(MYSQLI_ASSOC);
                                      $trn_id_met = $ma_trn_id_m['trn_id'];
                                      $trn_id_met = $trn_id_met+1;   
                                        
                                      $metodos_validar = $mysqli->query("SELECT metodo_id FROM arg_metodos WHERE activo = 1 AND tipo_id = 2") or die(mysqli_error());
                                      while ($metodos = $metodos_validar->fetch_assoc()) {
                                                echo 'entro'.$metodo_id;
                                                $metodo_id = $metodos['metodo_id'];
                                                $fila1 = 'fila2_'.$metodo_id;
                                                $metodo_sel = $_POST[$fila1];
                                                //echo $metodo_sel;
                                                //echo 'fila: '.$fila1;
                                                if ($metodo_sel == 'on'){
                                                            $query = "INSERT INTO arg_ordenes_metodos (trn_id, trn_id_rel, metodo_id ) ".
                                                                     "VALUES ($trn_id_met, $tr_id_det, $metodo_id)";
                                                            $mysqli->query($query) ;
                                                            echo $query;
                                                             
                                                             $cons_det = 1;
                                                             $pos = 1;
                                                             $j = 1;
                                                             
                                                                if ($area_muestras == 2){
                                                                    $i = 1;
                                                                    
                                                                    while($j <= $fin){ 
                                                                        
                                                                        $muestra_sel = "muestra".$i;                                                                    
                                                                        $muestra_sln = $_POST[$muestra_sel];
                                                                        
                                                                        if ($muestra_sln <> 0 AND $muestra_sln <> ''){
                                                                            //echo 'ENTROarea2'.$i.'</br>';
                                                                             $length = 6;                                                       
                                                                             $string = (string)$folio_interno;
                                                                             $folio_orden_int = str_pad($string,$length,"0", STR_PAD_LEFT);
                                                                             $length_c = 3;
                                                                             $string_c = (string)$cons_det;
                                                                             $cons_deta = str_pad($string_c,$length_c,"0", STR_PAD_LEFT);                                                       
                                                                             
                                                                             $folio_interno_det = $folio_orden_int.'-'.$cons_deta;   
                                                                    
                                                                             $query = "INSERT INTO arg_ordenes_soluciones (trn_id_batch, trn_id_rel, metodo_id, posicion, folio_interno, resultado, fecha, u_id, lectura) ".
                                                                                                                "VALUES  ($tr_id_det, $muestra_sln, $metodo_id, $pos, '$folio_interno_det', 0, '', $u_id, 0)";                                                    
                                                                             $mysqli->query($query);
                                                                             //echo $query.'</br>';
                                                                             $i++;  
                                                                             $j++;
                                                                             $pos=$pos+1;
                                                                             $cons_det++;
                                                                       }
                                                                       else{
                                                                        $i++;
                                                                        $j++;
                                                                       }  
                                                                }
                                                            }
                                                            else{
                                                                $i = 3;
                                                            
                                                                while($j <= $fin){   
                                                                    $muestra_sel = "muestra".$i;
                                                                    $fila_act = "fila".$i;
                                                                    $fila_on = $_POST[$fila_act];
                                                                    $muestra_sln = $_POST[$muestra_sel];
                                                                    if ($muestra_sln <> 0 AND $muestra_sln <> '' AND $fila_on == 'on'){
                                                                    //echo 'ENTROarea1'.$i.'</br>';
                                                                         $length = 6;                                                       
                                                                         $string = (string)$folio_interno;
                                                                         $folio_orden_int = str_pad($string,$length,"0", STR_PAD_LEFT);
                                                                         $length_c = 3;
                                                                         $string_c = (string)$cons_det;
                                                                         $cons_deta = str_pad($string_c,$length_c,"0", STR_PAD_LEFT);                                                       
                                                                         
                                                                         $folio_interno_det = $folio_orden_int.'-'.$cons_deta;   
                                                                
                                                                         $query = "INSERT INTO arg_ordenes_soluciones (trn_id_batch, trn_id_rel, metodo_id, posicion, folio_interno, resultado, fecha, u_id, lectura) ".
                                                                                                            "VALUES  ($tr_id_det, $muestra_sln, $metodo_id, $pos, '$folio_interno_det', 0, '', $u_id, 0)";                                                    
                                                                         $mysqli->query($query);
                                                                         echo $query.'</br>';
                                                                         $i++;  
                                                                         $j++;
                                                                         $pos=$pos+1;
                                                                         $cons_det++;
                                                                   }
                                                                   else{
                                                                    $i++;
                                                                    $j++;
                                                                   }  
                                                                
                                                            }   
                                                        }//FIN DEL WHILE QUE RECORRE TODAS LAS MUESTRAS
                                                            
                                                            if($metodo_id == 14 || $metodo_id == 15 || $metodo_id == 16){
                                                                $query = "INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id)
                                                                                                     VALUES ($tr_id_det, $metodo_id, 3, $u_id )";
                                                                $mysqli->query($query) ;
                                                                
                                                                $query = "INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin)
                                                                                                    VALUES ($tr_id_det, $metodo_id, 3, 7, $u_id, '', $u_id )";                                                    
                                                                $mysqli->query($query);
                                                            }
                                                            elseif ($metodo_id == 18){
                                                                $query = "INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id)
                                                                                                     VALUES ($tr_id_det, $metodo_id, 15, $u_id )";
                                                                $mysqli->query($query);
                                                                
                                                                $query = "INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin)
                                                                                                    VALUES ($tr_id_det, $metodo_id, 15, 25, $u_id, '', $u_id )";                                                    
                                                                $mysqli->query($query);
                                                                
                                                            }
                                                            elseif($metodo_id == 19){
                                                                $query = "INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id)
                                                                                                     VALUES ($tr_id_det, $metodo_id, 13, $u_id )";
                                                                $mysqli->query($query);
                                                                
                                                                $query = "INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin)
                                                                                                    VALUES ($tr_id_det, $metodo_id, 13, 24, $u_id, '', $u_id )";                                                    
                                                                $mysqli->query($query);
                                                                
                                                            }
                                                            elseif($metodo_id == 26){
                                                                $query = "INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id)
                                                                                                     VALUES ($tr_id_det, $metodo_id, 17, $u_id )";
                                                                $mysqli->query($query);
                                                                
                                                                $query = "INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin)
                                                                                                    VALUES ($tr_id_det, $metodo_id, 17, 26, $u_id, '', $u_id )";                                                    
                                                                $mysqli->query($query);                                                                
                                                            }
                                                            echo $query;
                                                            $trn_id_met++;
                                                }  
                                            }
                                                
                                   }// Fin de creación de orden
                    }//Fin del post
                    if ($trn_id <> 0){
                        echo "<script>";
                        echo "imprimir(".$unidad_id.", ".$trn_id.")";
                        echo "</script>"; 
                    }
        }   
                      
    
?>           
<br /> <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<script type="text/javascript" src="js/jquery.min.js"></script>
 