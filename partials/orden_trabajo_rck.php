<? //include "../connections/config.php"; 
$unidad_id = $_GET['unidad_id'];
//$trn_id = $_GET['trn_id'];
$_SESSION['unidad_id'] = $unidad_id;
//echo $unidad_id;
?> 

<style>
    .multiselect {
        width: 200px;
        position:relative;
    }
 
    .selectBox {
        position: relative;
    }
 
    .selectBox select {
        width: 100%;
    }
 
    .overSelect {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
    }
 
    #checkboxes {
        display: block;
        border: 1px #dadada solid;
        position:absolute;
        width:100%;
        background-color:white;
        box-sizing: border-box;
        overflow-y:auto;
        max-height:110px;
    }
    #checkboxes.hide {display:none;}
 
    #checkboxes label {
        display: block;
    }
 
    #checkboxes label:hover {
        background-color: #1e90ff;
    }
    </style>

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
    
    function verificar_seleccion(numb){          
          var validar = numb;          
          //alert(validar); 
          if(validar == 1){
            alert('Se deben seleccionar al menos una muestra y dar click en SELECCIONAR MUESTRAS. Por favor reintente');
            history.go(-1)
          }
          if(validar == 4){
            alert('Se debe capturar al menos un método');
            history.go(-1)
          }                             
     }
    
     //Actualizar voladuras despues de seleccionar banco
     function actualiza_muestras(contador)
        {
            var cont = contador;
            //alert(cont);
            var cambia = "muestras"+cont;
            var preor  =  "preorden"+cont;
            //alert(cont);
            var preorden  = document.getElementById(preor).value;
            var unidad_id = document.getElementById("mina_seleccionada").value; 
            
            //alert(preorden);
            $.ajax({
            		url: 'actualizar_muestras.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {preorden, unidad_id},
            	})
            	.done(function(respuesta){
            	   //alert(respuesta);  
                                   
                        document.getElementById("tablaprueba").insertRow(1).innerHTML = 
                          '<div class="multiselect"><div id="checkboxes" class="hide"><select name="'+cambia+'" id="'+cambia+'" ><option value=0>SELECT</option> </select><div class="overSelect" > </div><div id="checkboxes" class="hide"><td>' 
                           +respuesta
                        +'</td></select></div></div>'                       		                  
              })
              
      }
      
function showCheckboxes() {
    var checkboxes = document.getElementById("checkboxes");
    if(checkboxes.classList.contains("hide")) {
        checkboxes.classList.remove("hide");
        calculatotal();
    } else {
        checkboxes.classList.add("hide");
        calculatotal();
    }
}

function seleccionar() {
    
var tableRows = document.getElementById("nuevaTabla");
var rowCount = tableRows.rows.length-1;
var j = rowCount;
                          
$("input[type=checkbox]:checked").each(function(){
	//cada elemento seleccionado
	//alert($(this).val());
    var muestr = $(this).val()    
    var fila = 'fila'+j;
    if(muestr == 24 || muestr == 1){
        j = j+1;/*<div class="row">*/
    }
    else{
        var muestra_folio = document.getElementById(muestr).value;
        document.getElementById("nuevaTabla").insertRow(-1).innerHTML = 
                          '<td><input type="number" id="'+j+'" name="'+j+'" value="'+j+'" class="form-control" ></td>'
                          +'<td><input type="text" id="'+fila+'" name="'+fila+'" value="'+muestra_folio+'" class="form-control" ></td>'                                   
        
                            }
    j = j+1;
});
 document.getElementById("tablaprueba").deleteRow(-1)       
 document.getElementById('total_muestras_sel').value = j;
 calculatotal();
     
}

function calculatotal()
    {    
         var table = document.getElementById("nuevaTabla");
         var total_rows = parseInt(table.rows.length)-2;
         var total_mues = parseInt(0);
         //alert(total_rows);      
        
        document.getElementById('total_muestras').value = total_rows;
        document.getElementById('total_muestras1').value = total_rows; 
    }

    
     function imprimir($unidad_id,$trn_id)
            {
                 //alert('llegoo');
                 var trn_id = $trn_id;
                 var unidad_id = $unidad_id                 
                 var print_d = '<?php echo "\orden_trabajo_print.php?unidad_id="?>'+unidad_id+'&trn_id='+trn_id;  
                // alert(print_d);              
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
            
            if (isset($_GET['trn_id'])){
                echo"<script> imprimir($unidad_id,$trn_id); </script>";
            }
            else{
                 //Tomar caracter de la unidad de mina
                $caracter_mina = $mysqli->query("SELECT caracter_folio, nombre, serie
                                                 FROM 
                                                     `arg_empr_unidades`                                        
                                                 WHERE unidad_id = ".$unidad_id) or die(mysqli_error());
                $caracter_fol = $caracter_mina ->fetch_array(MYSQLI_ASSOC);
                $caracter_folio = $caracter_fol['caracter_folio'];
                $serie_mina = $caracter_fol['serie'];
                
                //Click en Generar Orden
                if (isset($_POST['generar_orden'])){        
                     $fecha = $_POST['fecha'];
                     $hora  = $_POST['hora_sel'];
                     $mina_seleccionada = $_POST['mina_seleccionada'];
                     $u_id = $_SESSION['u_id'];
                     $total_muestras = $_POST['total_muestras1'];
                     
                     $i = 1;
                     $fin = $total_muestras;//$_POST['total_muestras_sel'];
                     $pos = 1;
                     $cons = 1;
                     $cons_det = 1;
                    
                     if ($total_muestras <> 0){  
                        //Métodos
                        $val_met = 0;
                        $metodos_validar = $mysqli->query("SELECT metodo_id FROM arg_metodos WHERE activo = 1 AND metodo_id IN(3,6)") or die(mysqli_error());
                            while ($metodos = $metodos_validar->fetch_assoc()) {
                                $metodo_id = $metodos['metodo_id'];
                                echo 'entro';
                                
                                $fila1 = 'fila0_'.$metodo_id;
                                echo $fila1;
                                $metodo_sel = $_POST[$fila1];
                                echo 'metsel'.$metodo_sel;
                                //die();
                                if ($metodo_sel  <> 0){
                                    $val_met = 1;
                                }
                        }
                        if ($val_met == 0){                            
                                    echo "<script>";
                                    echo "verificar_seleccion(4)";
                                    echo "</script>";
                                   // echo 'entrootravez'.$metodo_sel;
                                   // echo $val_met;
                                    $i = 0;      
                                }                              
                         else{
                                 $max_trn_id = $mysqli->query("SELECT ifnull(MAX(trn_id), 0) AS trn_id FROM arg_ordenes") or die(mysqli_error());
                                 $ma_trn_id = $max_trn_id ->fetch_array(MYSQLI_ASSOC);
                                 $trn_id = $ma_trn_id['trn_id'];
                                 $trn_id = $trn_id + 1;
                                         
                                 $max_fol = $mysqli->query("SELECT ifnull(MAX(folio), 0) AS folio FROM arg_ordenes WHERE unidad_id = ".$unidad_id) or die(mysqli_error());
                                 $max_foli = $max_fol ->fetch_array(MYSQLI_ASSOC);
                                 $max_folio = $max_foli['folio'];
                                 $folio_orden = $max_folio + 1;
                                 
                                 //Ordenes
                                 $query = "INSERT INTO arg_ordenes (trn_id, folio, hora, fecha_inicio, fecha_final, unidad_id, usuario_id, tipo, activo, comentario ) ".
                                          "VALUES ($trn_id, $folio_orden, '$hora', '$fecha', '', $unidad_id, $u_id, 6, 1, '')";
                                 $mysqli->query($query) ;
                                 //echo $query;
                        
                                 $max_muestras_metodo = $mysqli->query("SELECT maximo_muestras FROM arg_empr_unidades WHERE unidad_id = ".$unidad_id) or die(mysqli_error());
                                 $max_muestras  = $max_muestras_metodo ->fetch_array(MYSQLI_ASSOC);
                                 $max_muest_ord = $max_muestras['maximo_muestras'];
                                                                                    
                                 if ($total_muestras <= $max_muest_ord){                                            
                                         $total_ordenes = 1;
                                         $resto_muestras = 0;  
                                         $cant_bloque_muestras = $total_muestras;                                   
                                 }else{                                            
                                         $total_ordenes = ceil($total_muestras/$max_muest_ord);
                                         $resto_muestras = fmod($total_muestras,$max_muest_ord);//ceil($cantidad_sel/$max_muest_ord); 
                                         $cant_bloque_muestras = $max_muest_ord;
                                         //$folio_actual_fin = $folio_actual_ini+$cant_bloque_muestras;
                                         $folio_actual_fin = $folio_actual+$cant_bloque_muestras;  
                                 }
                                    
                                 $j = 1;                                    
                                 while ($j <= $total_ordenes){    
                                     
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
                                    
                                     //Ordenes_detalle    
                                     $max_trn_det = $mysqli->query("SELECT MAX(trn_id) AS trn_id FROM arg_ordenes_detalle") or die(mysqli_error());
                                     $max_trn = $max_trn_det ->fetch_array(MYSQLI_ASSOC);
                                     $tr_id_det = $max_trn['trn_id'];
                                     $tr_id_det = $tr_id_det + 1;
                                                     
                                     $max_folio_det = $mysqli->query("SELECT  
                                                                        IFNULL(MAX(od.folio), 0) AS folio_ord 
                                                                     FROM 
                                                                        arg_ordenes_detalle od
                                                                        LEFT JOIN arg_ordenes AS o
                                                                            ON od.trn_id_rel = o.trn_id
                                                                        WHERE
                                                                            o.unidad_id = ".$unidad_id
                                                                     ) or die(mysqli_error());
                                     $max_fol = $max_folio_det ->fetch_array(MYSQLI_ASSOC);
                                     $folio_det = $max_fol['folio_ord'];
                                     $folio_det = $folio_det + 1;
                                              
                                     $length = 6;                            
                                     $string_c = (string)$folio_det;
                                     $cons_c = str_pad($string_c,$length,"0", STR_PAD_LEFT);                                        
                                     $folio_interno = $serie_mina.$cons_c.'-RCK';
                                         
                                     $query = "INSERT INTO arg_ordenes_detalle (trn_id, trn_id_rel, banco_id, voladura_id, cantidad, folio_inicial, folio_final, folio, folio_interno, estado, usuario_id) ".
                                              "VALUES ($tr_id_det, $trn_id, 0, 0, $cant_bloque_muestras, '','', $folio_det, '$folio_interno', 0, $u_id)";
                                     $mysqli->query($query) ;
                                     echo $query;
                                    
                                     //ORDENES CON DETALLE DE MUESTRAS
                                     $bloc = 1;
                                     while ($bloc <= $cant_bloque_muestras){
                                                 $renglon = 'fila'.$i;
                                                 $muestra_sel = $_POST[$renglon];
                                                 
                                                 echo 'muestrsel:'.$muestra_sel;
                                                 echo '***i***'.$i;
                                                                                      
                                             if ($muestra_sel <> ''){
                                                echo 'entroa muestras sel';
                                                $trn_muestra_b = $mysqli->query("SELECT trn_id
                                                                                 FROM `arg_ordenes_muestras`                                        
                                                                                 WHERE folio = '".$muestra_sel."'"
                                                                                 ) or die(mysqli_error());
                                                $trn_muestra_bu = $trn_muestra_b ->fetch_array(MYSQLI_ASSOC);
                                                $trn_muestra_bus = $trn_muestra_bu['trn_id'];
                                                
                                                echo $trn_muestra_bus;
                                                
                                                $query = "INSERT INTO arg_ordenes_muestrasRecheck (trn_id, trn_id_rel, folio, tipo_id) ".
                                                         "VALUES ($trn_muestra_bus, $tr_id_det, '$muestra_sel', 0)";
                                                $mysqli->query($query) ;
                                                echo $query;
                                             }
                                             $bloc++;
                                             $i++;
                                      }
                             
                                     //MUESTRAS METODOS   
                                     $max_trn_id_met = $mysqli->query("SELECT IFNULL(MAX(trn_id), 0) AS trn_id FROM arg_ordenes_metodos") or die(mysqli_error());
                                     $ma_trn_id_m = $max_trn_id_met ->fetch_array(MYSQLI_ASSOC);
                                     $trn_id_met = $ma_trn_id_m['trn_id'];
                                     $trn_id_met = $trn_id_met +1;                                  
                                  
                                     $metodos_validar = $mysqli->query("SELECT metodo_id FROM arg_metodos WHERE activo = 1 AND metodo_id IN(3,6)") or die(mysqli_error());
                                     while ($metodos = $metodos_validar->fetch_assoc()) {
                                        $metodo_id = $metodos['metodo_id'];
                                        $fila1 = 'fila0_'.$metodo_id;
                                        $metodo_sel = $_POST[$fila1];
                                        //echo $metodo_id;
                                        //echo 'metsel:'.$metodo_sel;                                        
                                        if ($metodo_sel <> 0){
                                            //echo 'entro';
                                            $query = "INSERT INTO arg_ordenes_metodos (trn_id, trn_id_rel, metodo_id ) ".
                                                     "VALUES ($trn_id_met, $tr_id_det, $metodo_id)";
                                            $mysqli->query($query) ;
                                            echo $query;
                                            $trn_id_met++;
                                        }
                                    } 
                                    $j++;                               
                               }                
                        }   
                        if ($trn_id_met <> 0){
                            echo "<script>";
                            echo "imprimir(".$unidad_id.", ".$trn_id.")";
                            echo "</script>";
                        }
                     }
                     else{
                        echo "<script>";
                        echo "verificar_seleccion(1)";
                        echo "</script>";  
                     }                  
                                     
                }                
                else{            
                    ?>                     
                    <form method="post" action="app_rck.php?unidad_id=<?echo $unidad_id;?>" name="Visitaform" id="Visitaform">  
                    <div class="container">
                    <fieldset>                       
                            <div class="col-md-12 col-lg-12 bg-info text-black text-center">
                                <br />
                                <h4>ORDEN DE TRABAJO RECHECK</h4>
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
                        </div>
                            
                        <!--Primer Row-->
                        <br /><br /><br />
                                                         
                                <table class="table table-hover text-black" id="tablaprueba">
                                  <thead class="thead-secondary" align='center'>
                                
                                    
                                        <th colspan='1'>BUSCAR PREORDEN:    <?                           
                                        $organizaciontop = $_GET['bancos'];
                                        if ($organizaciontop == ""){
                                            $nombretop = "Seleccione PREORDEN";
                                            $nomtop = 0;
                                        }                                  
                                        echo ("<form class=\"col-md-1 col-lg-1\" name=\"preorden1\" id=\"preorden1\">");                                   
                                        echo ("<select name=\"preorden1\" id=\"preorden1\" onchange=actualiza_muestras(1) class=\"form-control\" > ");        
                                        echo ("<option value=$nomtop>$nombretop</option>");
                                        $result = $mysqli->query("SELECT
                                                                    CONCAT(ba.banco, LPAD(ba.voladura_id, 3, '0')) AS preorden
                                                                    
                                                                  FROM `arg_preordenes` ord
                                                                  LEFT JOIN arg_bancos_voladuras AS ba
                                                               		ON ba.banco_id = ord.banco_id
                                                                    AND ba.voladura_id = ord.voladura_id 
                                                                  WHERE unidad_id = ".$_SESSION['unidad_id']." ORDER BY preorden") or die(mysqli_error());
                                        while( $row = $result ->fetch_array(MYSQLI_ASSOC))                                       
                                          {
                                              $nombre =($row["preorden"]);
                                              $nomenclatura = $row["preorden"];                                          
                                              echo ("<option value=$nomenclatura>$nombre</option>");
                                          }          
                                        echo ("</select>");
                                        
                                       ?>
                                        <th>
                                                <div class="form-group">
                                                <button type="button" class="btn btn-warning"  onclick="seleccionar()"> SELECCIONAR
                                                <span class="fa fa-flask fa-1x"> </span>
                                                </button>
                                                </div>                        
                                        </th>
                                        
                                    </tr>
                                  </thead>                       
                            
                                <table class="table table-hover text-black" id="nuevaTabla">
                                  <thead class="thead-light" align='center'>
                                       <tr>
                                       
                                        <th colspan='1'>METODOS </th> 
                                        <th colspan='1'>
                                         <?$datos_res = $mysqli->query("SELECT metodo_id, nombre FROM arg_metodos WHERE activo = 1 AND metodo_id IN(3,6)") or die(mysqli_error());?>
                                                 <div class="[ form-group ] ">   
                                                    <?while ($fila = $datos_res->fetch_assoc()) {?>
                                                            <input type="checkbox" name="<?echo 'fila0_'.$fila['metodo_id'];?>" id="<?echo 'fila0_'.$fila['metodo_id'];?>" value="<?echo $fila['metodo_id'];?>" autocomplete="off" />
                                                            <div class="[ btn-group ]">                                                                
                                                                <label for="<?echo 'fila0_'.$fila['metodo_id'];?>" class="[ btn btn-info ]">
                                                                    <span class="[ glyphicon glyphicon-ok ]"></span>                            
                                                                    <span></span>
                                                                </label>                                                    
                                                                <label for="<?echo 'fila0_'.$fila['metodo_id'];?>" class="[ btn btn-default active ]">
                                                                    <?echo $fila['nombre']?>
                                                                </label>                              
                                                            </div>                                            
                                                 <?}?> 
                                        </th>
                                        
                                      </tr> 
                                      <tr> 
                                      
                                      <th colspan='1'  align='left'> No. </th>
                                      <th colspan='1'  align='center'> MUESTRAS </th>
                                      </tr> 
                                   </thead>
                                </table>  
                                  
                                 <table>
                                     <tfoot>
                                    <tr> 
                                      <td style="width:1%"></td>  
                                            <td style="width:12%"><strong>Total Muestras: </strong></td>    
                                            <td style="width:1%"></td>                                                                     
                                            <td style="width:15%"><input type="number" name="total_muestras" id="total_muestras" disabled="" class="form-control" /></td>
                                            <td style="width:23%"><input type="hidden" name="total_muestras_sel" id="total_muestras_sel" class="form-control" /></td>   
                                            <td style="width:25%"><input type="hidden" name="total_muestras1" id="total_muestras1" class="form-control" /></td>  
                                           
                                            <td style="width:40%"><input type="submit" class="btn btn-success" name="generar_orden" id="generar_orden" data-toggle="modal" data-target="#exampleModal" value="GUARDAR ORDEN" />  </td>                    
                                    
                                    </td>            
                                    </tr>
                                    
                                     </tfoot>
                                  
                                  </table>
                                  
                                
                              
                               
                              
                    </div>
                    </div>
                    
                     <br/>
                          
             
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
       <?}
    }
}?>           
<br /> <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<script type="text/javascript" src="js/jquery.min.js"></script>

 
 