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
    function buscar_orden($unidad_id)
    {
         var trn_id = $trn_id;
         var unidad_id = $unidad_id;                
         var print_d = '<?php echo "\orden_trabajo_print.php?unidad_id="?>'+unidad_id;                
         window.location.href = print_d;
    }
  
    function calculatotal()
    {
         var j = 1;     
         var table = document.getElementById("tablaprueba");
         var total_rows = parseInt(table.rows.length)-1;
         var total_mues = parseInt(0);
         //alert(total_rows);
      
        while(j<=total_rows)
         {
            row = "cantidad_muestras"+j;
            total_cantidad = document.getElementById(row).value;
            if (total_cantidad == '') {
                total_cantidad = parseInt(0);
                 //alert(total_cantidad);
            }    
            total_mues = total_mues+parseInt(total_cantidad);            
            j++;
         }
        document.getElementById('total_muestras').value = total_mues; 
    }
    
    function verificar_seleccion(numb){          
          var validar = numb;          
          //alert(validar); 
          if(validar == 1){
            alert('Se deben capturar todos los datos');
            history.go(-1)
          }
          if(validar == 4){
            alert('Se debe capturar al menos un método');
            history.go(-1)
          } 
          /*if(validar == 7){
            alert('Warning: Se debe capturar la hora de recepción de las muestras. Reintente');
            history.go(-1)
          }*/                             
     }

     //Actualizar voladuras despues de seleccionar banco
     function actualiza_vol(contador)
        {
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
      
      function actualiza_sigfol(contador)
        {
            var cont = contador;
            //alert('hola');
            var trn_id = "preorden"+cont;           
            var trn_id_pre  = document.getElementById(trn_id).value;
            var sig_fol = "siguiente_muestra"+cont; 
            //alert(banco);
            //alert(voladura_id);
            var unidad_id = document.getElementById("mina_seleccionada").value;
            $.ajax({
            		url: 'siguiente_folio.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {trn_id_pre},
            	})
            	.done(function(respuesta){
                    document.getElementById(sig_fol).value = respuesta;                      		                  
              })
         }
         
      function eliminarFila(){
          var table = document.getElementById("tablaprueba");
          var rowCount = table.rows.length;          
          //console.log(rowCount);          
          if(rowCount <= 2)
            alert('No se puede eliminar el encabezado');
          else{
            table.deleteRow(rowCount -1);
            contador = parseInt(rowCount)-1;
            //alert(contador);
            calculatotal();
          }
    }
        
     function agregarFila(){
          //contador +=1;
          var table = document.getElementById("tablaprueba");
          var contador = table.rows.length;
          //alert(contador);          
          /*var name_banco = "banco"+contador;
          var name_voladura = "voladura"+contador;*/
          var preorden = "preorden"+contador;
          var sig_mues = "siguiente_muestra"+contador;
          var name_cantidad = "cantidad_muestras"+contador;
          var name_metodo = "fila"+contador+'_';
          document.getElementById("tablaprueba").insertRow(-1).innerHTML = 
          '<td><select name="'+preorden+'" id="'+preorden+'" onchange=actualiza_sigfol('+contador+') class="form-control">' 
                    <?$result = $mysqli->query("SELECT 0 AS trn_id_pre, 'Seleccione' AS preorden UNION ALL 
                                                SELECT pr.trn_id AS trn_id_pre, CONCAT(banco, LPAD(pr.voladura_id, 3,'0')) AS preorden
                                                    FROM arg_preordenes pr
                                                    	LEFT JOIN arg_bancos_voladuras bv
                                                        	ON pr.banco_id = bv.banco_id
                                                            AND pr.voladura_id = bv.voladura_id 
                                                WHERE unidad_id = ".$_SESSION['unidad_id']) or die(mysqli_error());                             
                              while ( $row1 = $result ->fetch_array(MYSQLI_ASSOC)) {
                                    $preor = $row1['preorden'];                                
                    ?>       
        +'<option value="<?echo $row1['trn_id_pre']?>"><?echo $preor?></option>'
        <?}?>
        +'</select></td>'
        +'<td><input type="number" name="'+sig_mues+'" id="'+sig_mues+'" disabled="1" class="form-control" /></td>'   
        +'<td><input type="number" name="'+name_cantidad+'" id="'+name_cantidad+'" onchange=calculatotal() class="form-control" /></td>'        
        +'<td><div class="[ form-group ]">' 
            <? $datos_res = $mysqli->query("SELECT metodo_id, nombre FROM arg_metodos WHERE activo = 1 AND tipo_id = 1") or die(mysqli_error());
                while ( $fila = $datos_res ->fetch_array(MYSQLI_ASSOC)) {
                    $metodo = $fila['metodo_id'];
                    $nombre = $fila['nombre'];
            ?>
                    +'<input type="checkbox" name="'+name_metodo+'<?echo $metodo;?>" id="'+name_metodo+'<?echo $metodo;?>" autocomplete="off" />'
                    +'<div class="[ btn-group col-sm-1 col-md-1 col-lg-1 col-xg-1 ]">'                                                            
                        +'<label for="'+name_metodo+'<?echo $metodo;?>" class="[ btn btn-info align-left col-xs-10 col-sm-10 col-md-10 col-lg-10 ]">'
                        +'<span class="[ glyphicon glyphicon-ok ]"></span><span></span></label>'                                                    
                        +'<label for="'+name_metodo+'<?echo $metodo;?>" class="[ btn btn-default active align-left col-xs-12 col-sm-12 col-md-12 col-lg-12 ]">'
                        +'<?echo $nombre;?>'
                        +'</label>'                          
                    +'</div>'                                            
                <?}?>                                        
        +'</div>'
        +'</td>';          
    } 
    
     function imprimir($unidad_id,$trn_id)
            {
                 alert('Se generó la orden de trabajo satisfactoriamente');
                 var trn_id = $trn_id;
                 var unidad_id = $unidad_id 
                 //var unidad_id = document.getElementById("mina_seleccionada").value;
            $.ajax({
            		url: 'notificacion_nuevaorden.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {trn_id, trn_id},
            	})
            	.done(function(respuesta){
            	   //alert(respuesta);
                        //alert(print_d);
                 var print_d = '<?php echo "\orden_trabajo_rep.php?trn_id="?>'+trn_id;
                 window.location.href = print_d;		                  
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
            
            //if (isset($_GET['trn_id'])){
               // $trn_id = $_GET['trn_id'];
               // echo"<script> imprimir($unidad_id, $trn_id); </script>";
            //}
          //  else{
                 //Tomar caracter de la unidad de mina
                
                         
                    ?>                             
                    <form method="post" action="app.php?unidad_id=<?echo $unidad_id;?>" name="Visitaform" id="Visitaform">  
                    <fieldset>                       
                            <div class="col-md-12 col-lg-12 bg-info text-black text-center">
                                <br />
                                <h4>ORDEN DE TRABAJO SÓLIDOS</h4>
                            </div>
                            <br/><br/><br/> <br/>
                                                                                                                                                                           
                            <div class="col-md-11 col-lg-11">
                                                    
                                    <div class="col-md-1 col-lg-1">               
                                        <h5><?echo 'Fecha:'?></h5>
                                    </div>
                                    <div class="col-md-2 col-lg-2">
                                         <input type="date" name="fecha" class="form-control" id="fecha" value="<?php echo date("Y-m-d");?>"/>
                                    </div>                                
                                    <div class="col-md-1 col-lg-1">
                                         <h5><?echo 'Hora:'?></h5>
                                    </div>                                
                                    <div class='col-sm-2'>
                                         <input type="hora" name="hora_sel" class="form-control" id="hora_sel" value=""/>                                         
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
                                <div class="col-md-3 col-lg-3">                                  
                                        <div class="form-group">
                                          <button type="button" class="btn btn-secondary" onclick="agregarFila()"> + FILA </button>
                                          <button type="button" class="btn btn-danger" onclick="eliminarFila()"> - FILA </button>
                                        </div> 
                                </div>
                        </div>
                            
                        <!--Primer Row-->
                        <br /><br /><br />
                        
                            <div class="row">    
                            <div class="col-md-12 col-lg-12">
                            <table class="table table-hover text-black" id="tablaprueba">
                                  <thead class="thead-light" align='center'>
                                    <tr>
                                      <th colspan='1'>PRE-ORDEN</th>
                                      <!--<th colspan='1'>Banco</th>
                                      <th colspan='1'>Voladura</th>--!>
                                      <th colspan='1'>Siguiente Muestra</th>
                                      <th colspan='1'>Cantidad</th>
                                      <th colspan='1'>Métodos</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                   <div class="col-md-1 col-lg-1">
                                    <td>                                
                                        <?                           
                                        $preord = $_GET['preorden'];
                                        if ($preord == ""){
                                            $nombretop = "Seleccione";
                                            $nomtop = 0;
                                        }                                  
                                        echo ("<form name=\"preorden1\"  id=\"preorden1\">");                                   
                                        echo ("<select name=\"preorden1\" id=\"preorden1\" onchange=actualiza_sigfol(1)  class=\"form-control\" > ");        
                                        echo ("<option value=$nomtop>$nombretop</option>");
                                        $result = $mysqli->query("SELECT pr.trn_id, CONCAT(banco, LPAD(pr.voladura_id, 3,'0')) AS preorden
                                                                  FROM 
                                                                    arg_preordenes pr
                                       	                            LEFT JOIN arg_bancos_voladuras bv
                                                                    	ON pr.banco_id = bv.banco_id
                                                                        AND pr.voladura_id = bv.voladura_id
                                                                         WHERE unidad_id = ".$unidad_id) or die(mysqli_error());
                                        while( $row = $result ->fetch_array(MYSQLI_ASSOC))                                       
                                          {
                                              $nombre =($row["preorden"]);
                                              $nomenclatura = $row["trn_id"];                                          
                                              echo ("<option value=$nomenclatura>$nombre</option>");
                                          }          
                                        echo ("</select>");
                                       ?>
                                    </td>
                                    </div>
                                  
                                  <!--<div class="col-md-1 col-lg-1">
                                    <td>                                
                                        <?                           
                                        $organizaciontop = $_GET['bancos'];
                                        if ($organizaciontop == ""){
                                            $nombretop = "SeleccioneQ";
                                            $nomtop = 0;
                                        }                                  
                                        echo ("<form name=\"banco1\"  id=\"banco1\">");                                   
                                        echo ("<select name=\"banco1\" id=\"banco1\" onchange=actualiza_vol(1)  class=\"form-control\" > ");        
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
                                        echo ("<select name=\"voladura1\" id=\"voladura1\" class=\"form-control\" > ");        
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
                                     </div>--!>
                                     <div class="col-md-1 col-lg-1">
                                     <td>
                                             <input type="text" name="siguiente_muestra1" id="siguiente_muestra1" disabled="" class="form-control" /> 
                                     </td> 
                                     <td>
                                             <input type="number" name="cantidad_muestras1" id="cantidad_muestras1" onchange="calculatotal();" class="form-control" /> 
                                     </td>
                                     
                                     </div>                               
                                     <td>
                                         <? $datos_res = $mysqli->query("SELECT metodo_id, nombre FROM arg_metodos WHERE activo = 1 AND tipo_id = 1") or die(mysqli_error());?>
                                         <div class="[ form-group ] ">   
                                            <?while ($fila = $datos_res->fetch_assoc()) {?>
                                                    <input type="checkbox" name="<?echo 'fila1_'.$fila['metodo_id']?>" id="<?echo 'fila1_'.$fila['metodo_id']?>" autocomplete="off" />
                                                    <div class="[ btn-group ]">                                                                
                                                        <label for="<?echo 'fila1_'.$fila['metodo_id']?>" class="[ btn btn-info ]">
                                                            <span class="[ glyphicon glyphicon-ok ]"></span>                            
                                                            <span></span>
                                                        </label>                                                    
                                                        <label for="<?echo 'fila1_'.$fila['metodo_id']?>" class="[ btn btn-default active ]">
                                                            <?echo $fila['nombre']?>
                                                        </label>                              
                                                    </div>                                            
                                            <?}?>                                        
                                         </div>   
                                     </td>
                                  </tbody>
                                  
                                </table>
                                <table>
                                     <tbody>
                                    <tr> 
                                      <td style="width:1%"></td>  
                                            <td style="width:6%"><strong>Total Muestras: </strong></td>    
                                            <td style="width:1%"></td>                               
                                            <td style="width:5%"><input type="number" name="total_muestras" id="total_muestras" disabled="" class="form-control" /></td> 
                                            <td style="width:50%"></td>           
                                    </tr>
                                    
                                     </tbody>
                                      
                                  </table>
                              
                    </div>
                    </div>
                    
                     <br/>
                          
                         
                    <div class="col-md-9 col-lg-9">                      
                    </div>
                    <div class="col-md-1 col-lg-1"> 
                        <input type="submit" class="btn btn-success" name="generar_orden" id="generar_orden" data-toggle="modal" data-target="#exampleModal" value="GUARDAR ORDEN" />                      
                    </div>  
                    
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
                if (isset($_POST['generar_orden'])){
                    $caracter_mina  = $mysqli->query("SELECT caracter_folio, nombre, serie
                                                      FROM `arg_empr_unidades`                                        
                                                        WHERE unidad_id = ".$unidad_id) or die(mysqli_error());
                   $caracter_fol   = $caracter_mina ->fetch_array(MYSQLI_ASSOC);
                   $caracter_folio = $caracter_fol['caracter_folio'];
                   $serie_mina = $caracter_fol['serie'];
                
                  
                     $fecha             = $_POST['fecha'];
                     $hora              = $_POST['hora_sel'];
                     $mina_seleccionada = $_POST['mina_seleccionada'];
                     $u_id              = $_SESSION['u_id'];
                     
                     $i   = 1;
                     $fin = 1;
                     $pos = 1;
                     while($i <> 0){ 
                        $preorden_sel = "preorden".$pos;
                        $preorden = $_POST[$preorden_sel];
                        $cantidad = 'cantidad_muestras'.$pos;
                        $cantidad_sel = $_POST[$cantidad];
                        
                        //Métodos
                        $val_met = 0;
                        $metodos_validar = $mysqli->query("SELECT metodo_id FROM arg_metodos WHERE activo = 1") or die(mysqli_error());
                            while ($metodos = $metodos_validar->fetch_assoc()) {
                                $metodo_id = $metodos['metodo_id'];
                                $fila1 = 'fila'.$pos.'_'.$metodo_id;
                                $metodo_sel = $_POST[$fila1];
                                if ($metodo_sel == 'on'){
                                    $val_met = 1;
                                }
                            }
                        
                        if ($preorden == 0 and $cantidad_sel == '' and $val_met == 0){
                                $i = 0;
                            }
                        elseif ($val_met == 0){                            
                                    echo "<script>";
                                    echo "verificar_seleccion(4)";
                                    echo "</script>";
                                    $i = 0;      
                                }
                       
                        elseif ($preorden == 0 || $cantidad_sel == ''){
                                    echo "<script>";
                                    echo "verificar_seleccion(1)";
                                    echo "</script>";
                                    $i = 0;      
                                }          
                         else{
                            if ($i == 1){
                                 $max_trn_id = $mysqli->query("SELECT ifnull(MAX(trn_id), 0) AS trn_id FROM arg_ordenes") or die(mysqli_error());
                                 $ma_trn_id = $max_trn_id ->fetch_array(MYSQLI_ASSOC);
                                 $trn_id = $ma_trn_id['trn_id'];
                                 $trn_id = $trn_id + 1;
                                         
                                 $max_fol = $mysqli->query("SELECT ifnull(MAX(folio), 0) AS folio FROM arg_ordenes WHERE unidad_id = ".$unidad_id) or die(mysqli_error());
                                 $max_foli = $max_fol ->fetch_array(MYSQLI_ASSOC);
                                 $max_folio = $max_foli['folio'];
                                 $folio_orden = $max_folio + 1;
                                 
                                 //Ordenes
                                 $query = "INSERT INTO arg_ordenes (trn_id, trn_id_rel, folio, hora, fecha_inicio, fecha_final, unidad_id, usuario_id, tipo, activo, comentario ) ".
                                          "VALUES ($trn_id, 0, $folio_orden, '$hora', '$fecha', '', $unidad_id, $u_id, 1, 1, '')";
                                 $mysqli->query($query) ;
                                 //echo $query;
                         }
                             //PREORDEN BANCO Y VOLADURA                                 
                             $max_con_id = $mysqli->query("SELECT bv.folio_actual, bv.banco, pr.banco_id, pr.voladura_id
                                                           FROM arg_preordenes pr
                                                           LEFT JOIN arg_bancos_voladuras bv
                                                                ON pr.banco_id = bv.banco_id
                                                                AND pr.voladura_id = bv.voladura_id
                                                           WHERE pr.trn_id = ".$preorden) or die(mysqli_error());
                             $max_cons_id = $max_con_id ->fetch_array(MYSQLI_ASSOC);
                             $folio_actual = $max_cons_id['folio_actual'];
                             $folio_actual_sig = $folio_actual+1;
                             $folio_actual_fin  = $folio_actual+$cantidad_sel; 
                             
                             $banco = $max_cons_id['banco'];    
                             $banco_sel = $max_cons_id['banco_id'];       
                             $voladura_sel = $max_cons_id['voladura_id']; 
                             
                             $query = "UPDATE arg_bancos_voladuras SET folio_actual = ".$folio_actual_fin." WHERE banco_id = ".$banco_sel." AND voladura_id = ".$voladura_sel;
                             $mysqli->query($query);
                             //echo $query;
                             
                              //MUESTRAS METODOS   
                              $max_trn_id_met = $mysqli->query("SELECT IFNULL(MAX(trn_id), 0) AS trn_id FROM arg_ordenes_metodos ") or die(mysqli_error());
                              $ma_trn_id_m = $max_trn_id_met ->fetch_array(MYSQLI_ASSOC);
                              $trn_id_met = $ma_trn_id_m['trn_id'];
                              $trn_id_met = $trn_id_met +1;                                  
                              
                              //Validamos el total de muestras de los métodos seleccionados
                              $max_muestras_metodo = $mysqli->query("SELECT maximo_muestras FROM arg_empr_unidades WHERE unidad_id = ".$unidad_id) or die(mysqli_error());
                              $max_muestras  = $max_muestras_metodo ->fetch_array(MYSQLI_ASSOC);
                              $max_muest_ord = $max_muestras['maximo_muestras'];
                                                                                    
                                    if ($cantidad_sel <= $max_muest_ord){                                            
                                         $total_ordenes = 1;
                                         $resto_muestras = 0;  
                                         $cant_bloque_muestras = $cantidad_sel;                                   
                                    }else{                                            
                                        $total_ordenes = ceil($cantidad_sel/$max_muest_ord);
                                        $resto_muestras = fmod($cantidad_sel,$max_muest_ord);//ceil($cantidad_sel/$max_muest_ord); 
                                        $cant_bloque_muestras = $max_muest_ord;
                                        $folio_actual_fin = $folio_actual_ini+$cant_bloque_muestras;  
                                    }
                                    
                                    $j = 1;                                    
                                    while ($j <= $total_ordenes){
                                        
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
                                         $folio_actual_sig = str_pad($folio_actual_sig,$length_fs,"0", STR_PAD_LEFT);
                                         $folio_actual_fin = str_pad($folio_actual_fin,$length_fs,"0", STR_PAD_LEFT);
                                         
                                         //Rellenando voladura
                                         $length_v = 3;
                                         $string_v = (string)$voladura_sel;
                                         $voladura_fill = str_pad($string_v,$length_v,"0", STR_PAD_LEFT);                                         
                                                                  
                                         $folio_interno = $serie_mina.$cons_c;
                                         
                                         if ($j == 1){
                                            if ($unidad_id == 2){                                                
                                                $folio_inicial = $caracter_folio.$banco.$voladura_fill.$folio_actual_sig;
                                                $folio_final   = $caracter_folio.$banco.$voladura_fill.$folio_actual_fin; 
                                                }
                                            else{
                                                $folio_inicial = $banco.$voladura_fill.$folio_actual_sig;
                                                $folio_final   = $banco.$voladura_fill.$folio_actual_fin;
                                            }
                                         }
                                         
                                         if ($total_ordenes > 1 && $j <> 1){
                                             if($j == $total_ordenes && $resto_muestras <> 0){
                                                $cant_bloque_muestras = $resto_muestras;
                                                $folio_actual_sig = $folio_actual_fin+1;
                                                $folio_actual_fin = $folio_actual_fin+$cant_bloque_muestras;
                                                $length_fs = 3;
                                                $folio_actual_sig = str_pad($folio_actual_sig,$length_fs,"0", STR_PAD_LEFT);
                                                $folio_actual_fin = str_pad($folio_actual_fin,$length_fs,"0", STR_PAD_LEFT);
                                                if ($unidad_id == 2){
                                                    $folio_inicial = $caracter_folio.$banco.$voladura_fill.$folio_actual_sig;
                                                    $folio_final   = $caracter_folio.$banco.$voladura_fill.$folio_actual_fin; 
                                                    }
                                                else{
                                                    $folio_inicial = $banco.$voladura_fill.$folio_actual_sig;
                                                    $folio_final   = $banco.$voladura_fill.$folio_actual_fin;
                                                }
                                             }
                                             else{
                                                $cant_bloque_muestras = $max_muest_ord;
                                                $folio_actual_sig = $folio_actual_fin+1;
                                                $folio_actual_fin = $folio_actual_fin+$cant_bloque_muestras;
                                                $length_fs = 3;
                                                $folio_actual_sig = str_pad($folio_actual_sig,$length_fs,"0", STR_PAD_LEFT);
                                                $folio_actual_fin = str_pad($folio_actual_fin,$length_fs,"0", STR_PAD_LEFT);
                                                if ($unidad_id == 2){
                                                    $folio_inicial = $caracter_folio.$banco.$voladura_fill.$folio_actual_sig;
                                                    $folio_final   = $caracter_folio.$banco.$voladura_fill.$folio_actual_fin; 
                                                    }
                                                else{
                                                    $folio_inicial = $banco.$voladura_fill.$folio_actual_sig;
                                                    $folio_final   = $banco.$voladura_fill.$folio_actual_fin;
                                                }
                                             }
                                         }
                                         
                                         $query = "INSERT INTO arg_ordenes_detalle (trn_id, trn_id_rel, banco_id, voladura_id, cantidad, folio_inicial, folio_final, folio, folio_interno, estado, usuario_id) ".
                                                  "VALUES ($tr_id_det, $trn_id, $banco_sel, $voladura_sel, $cant_bloque_muestras, '$folio_inicial','$folio_final',$folio_det , '$folio_interno', 0, $u_id)";
                                         $mysqli->query($query) ;
                                         //echo $query;
                                         $max_mue_id = $mysqli->query("SELECT IFNULL(MAX(trn_id), 0) AS trn_id_mue FROM arg_ordenes_muestras") or die(mysqli_error());
                                         $max_mues_id = $max_mue_id ->fetch_array(MYSQLI_ASSOC);
                                         $trn_id_mue = $max_mues_id['trn_id_mue'];
                                         $trn_id_mue = $trn_id_mue +1;
                                         
                                         $folio_actual_det = $folio_actual_sig;
                                         
                                         //ORDENES CON DETALLE DE MUESTRAS
                                             $c = 1;
                                             while ($c <= $cant_bloque_muestras){
                                                 $length = 6;
                                                 $string = (string)$folio_orden;
                                                 $folio_orden_int = str_pad($string,$length,"0", STR_PAD_LEFT);
                                                 $length_c = 3;
                                                 $string_c = (string)$cons_det;
                                                 $cons_deta = str_pad($string_c,$length_c,"0", STR_PAD_LEFT);
                                                 
                                                 $folio_sig = str_pad($folio_actual_det,$length_c,"0", STR_PAD_LEFT);
                                                 
                                                 //Validamos el caracter inicial de SA, las demás minas no llevan son de 10 caracteres los folios
                                                 if ($unidad_id == 2){
                                                    $folio_inicial_det = $caracter_folio.$banco.$voladura_fill.$folio_sig;                                       
                                                 }else{
                                                    $folio_inicial_det = $banco.$voladura_fill.$folio_sig;                                                                            
                                                 }
                                                 $folio_interno_det = $serie_mina.$folio_orden_int.'-'.$cons_deta;                                     
                                                 $query = "INSERT INTO arg_ordenes_muestras (trn_id, trn_id_rel, folio, tipo_id) ".
                                                          "VALUES ($trn_id_mue, $tr_id_det, '$folio_inicial_det', 0)";
                                                 $mysqli->query($query) ;
                                                echo $query;
                                                $c++;
                                                $trn_id_mue++;
                                                $cons_det++;
                                                $folio_actual_det++;
                                             }  
                                                $j++;
                                
                                $metodos_validar = $mysqli->query("SELECT metodo_id FROM arg_metodos WHERE activo = 1 AND tipo_id = 1") or die(mysqli_error());
                                while ($metodos = $metodos_validar->fetch_assoc()) {
                                        $metodo_id = $metodos['metodo_id'];
                                        $fila1 = 'fila'.$pos.'_'.$metodo_id;
                                        $metodo_sel = $_POST[$fila1];
                                        if ($metodo_sel == 'on'){
                                                    $query = "INSERT INTO arg_ordenes_metodos (trn_id, trn_id_rel, metodo_id ) ".
                                                             "VALUES ($trn_id_met, $tr_id_det, $metodo_id)";
                                                    $mysqli->query($query) ;
                                                    //echo $query;
                                                    $trn_id_met++;
                                        }  
                                    }
                                }                            
                               $i++;
                               $pos++;
                               $cons++;                   
                           }   
                    }     
                      //die();               
                     echo "<script>";
                     echo "imprimir(".$unidad_id.", ".$trn_id.")";
                     echo "</script>";
                } 
    //}
}?>           
<br /> <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<script type="text/javascript" src="js/jquery.min.js"></script>
 