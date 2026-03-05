<? //include "../connections/config.php";
$unidad_id = $_GET['unidad_id'];
$_SESSION['unidad_id'] = $unidad_id;
$u_id = $_SESSION['u_id'];

$fecha_inicial = $_GET['fecha_inicial'];
$fecha_final = $_GET['fecha_final'];
$metodo_id = $_GET['metodo_id'];
$orden = $_GET['orden'];
$muestra = $_GET['muestra'];
$tipo_id = $_GET['tipo_id'];

$unidad_mi = $mysqli->query("SELECT nombre FROM arg_empr_unidades WHERE unidad_id = " . $unidad_id) or die(mysqli_error($mysqli));
$unidad_min = $unidad_mi->fetch_assoc();
$unidad_mina = $unidad_min['nombre'];
?>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style type="text/css">
    .izq {
        background-color: ;
    }

    .derecha {
        background-color: ;
    }

    .btnSubmit {
        width: 50%;
        border-radius: 1rem;
        padding: 1.5%;
        border: none;
        cursor: pointer;
    }

    .circulos {
        padding-top: 5em;
    }

    img {
        max-width: 100%;
    }
</style>
<script>
function ver_listado() {
        var unidad_id_ex     = document.getElementById('unidad_id_ex').value;;
        var fecha_inicial_ex = document.getElementById('fecha_inicial_ex').value;
        var fecha_final_ex   = document.getElementById('fecha_final_ex').value;
        var metodo_sel       = document.getElementById("metodo_id_sel").value;
        var orden_tr         = document.getElementById('caja_orden').value;
        var muestra          = document.getElementById('caja_bus_mues').value;
        var tipo_id          = document.getElementById("tipo_id_sel").value;
        var muestra = document.getElementById('caja_bus_mues').value;
        if(orden_tr == ''){
            orden_tr = 0
        };
         if(muestra == ''){
            muestra = 0
        };
        // alert(muestra);
        // alert(fecha_final_ex);
          var exportar = '<?php echo "\ listados_normal.php?unidad_id=" ?>' + unidad_id_ex + '&fecha_inicial=' +
            fecha_inicial_ex + '&fecha_final=' + fecha_final_ex +'&metodo_id='+metodo_sel+ '&orden='+orden_tr+'&muestra='+muestra+'&tipo_id='+tipo_id;
        window.location.href = exportar;
    }
</script>

<div class="container-fluid">
        <br /><br /><br /><br /><br /><br />
        <?
        $fecha_minima_val = date('d-m-Y');
        $nuevafecha = strtotime($fecha_minima_val);
        $nuevafecha = date('d-m-Y', $nuevafecha);

        ?>
        <div class="col-md-1 col-lg-1">
            <label for="unidad_id_ex" class="col-form-label"><b>UNIDAD DE MINA</b></label>  
                       
                <select name="unidad_id_ex" id="unidad_id_ex" class="form-control"> 
                        <?     if ($_SESSION['unidad_acc'] == '0'){
                                    $datos_minas = $mysqli->query ("SELECT unidad_id, nombre
                                                      FROM arg_empr_unidades") or die(mysqli_error());
                                    //while ($row2 = $datos_minas->fetch_assoc()){
                                          while ( $row2 = $datos_minas ->fetch_array(MYSQLI_ASSOC)) {
                                                        $met_sele = $row2['nombre'];                                
                            ?>       
                                <option value="<?echo $row2['unidad_id']?>"><?echo $met_sele?></option>
                            <?}}?>
                        
                         <? if ($_SESSION['unidad_acc'] <> '0' AND $_SESSION['unidad_acc'] <> '999'){
                                    $datos_minas = $mysqli->query ("SELECT unidad_id, nombre
                                                                    FROM arg_empr_unidades 
                                                                    WHERE unidad_id = ".$_SESSION['unidad_acc']) or die(mysqli_error());
                                    //while ($row2 = $datos_minas->fetch_assoc()){
                                    while ( $row2 = $datos_minas ->fetch_array(MYSQLI_ASSOC)) {
                                        $met_sele = $row2['nombre'];                                
                                        ?>       
                                            <option value="<?echo $row2['unidad_id']?>"><?echo $met_sele?></option>
                                        <?
                                    }
                            }?>    
                                
                         <?  //999=Varias unidades de mina (No Todas) 
                           $cadena = $_SESSION['unidades'];
                           $i = 0;
                           if ($_SESSION['unidad_acc'] == '999'){
                                while ($i<=strlen($cadena)){
                                    $valor = substr($cadena, $i, 1);
                                    $i=$i+1;
                                    if (is_numeric($valor)){
                                         $datos_mina = $mysqli->query ("SELECT unidad_id, nombre FROM arg_empr_unidades WHERE unidad_id = ".$valor) or die(mysqli_error());   
                                          while ( $row2 = $datos_mina ->fetch_array(MYSQLI_ASSOC)) {
                                                        $met_sele = $row2['nombre'];                                
                                                ?>       
                                                    <option value="<?echo $row2['unidad_id']?>"><?echo $met_sele?></option>
                                          <?}
                                    }
                                }
                          }?>                                                 
            </select>
        </div>
        
        <div class="col-md-1 col-lg-1">
            <label for="metodo_id_sel" class="col-form-label"><b>MÉTODOS</b></label>  
                        <select name="metodo_id_sel" id="metodo_id_sel" class="form-control"> 
                            <?$result_h = $mysqli->query("SELECT 0 AS metodo_id, 'TODOS' AS nombre
                                                         UNION ALL
                                                         SELECT metodo_id, nombre FROM `arg_metodos` ") or die(mysqli_error());                             
                                                  while ( $row2 = $result_h ->fetch_array(MYSQLI_ASSOC)) {
                                                    $met_sele = $row2['nombre'];                                
                                                  ?>       
                            <option value="<?echo $row2['metodo_id']?>"><?echo $met_sele?></option>
                            <?}?>
            </select>
        </div>
        
        <div class="col-md-2 col-lg-2">
            <label for="tipo_id_sel" class="col-form-label"><b>TIPO DE MUESTRAS</b></label>  
                        <select name="tipo_id_sel" id="tipo_id_sel" class="form-control"> 
                            <?$result_h = $mysqli->query("SELECT 0 AS tipo_id, 'Muestras de Geología' AS nombre 
                                                          UNION ALL 
                                                          SELECT 1 AS tipo_id, 'Muestras Geo y Controles de Calidad' AS nombre") or die(mysqli_error());                             
                                                  while ( $row2 = $result_h ->fetch_array(MYSQLI_ASSOC)) {
                                                    $met_sele = $row2['nombre'];                                
                                                  ?>       
                            <option value="<?echo $row2['tipo_id']?>"><?echo $met_sele?></option>
                            <?}?>
            </select>
        </div>
        <br /><br /><br /><br /><br />
        
        <div class="col-md-1 col-lg-1">
            <?
            //$fecha_minima_val = date('Y-m-j');
            // $fecha_minima_val = strtotime ( $fecha_minima_val);
            $nuevafecha = date('d-m-Y');
            //echo $nuevafecha; //2020-12-31
            ?>
            <label for="fecha_inicial_ex"><b>DESDE</b></label>
            <input type='date' class='form-control' name='fecha_inicial_ex' id='fecha_inicial_ex' value="<? echo $fecha_inicial; ?>" placeholder="<? echo $fecha_final ?>">

        </div>
        <div class="col-md-1 col-lg-1">
            <label for="fecha_final_ex"><b>HASTA</b></label>
            <input type="date" class="form-control" name='fecha_final_ex' id='fecha_final_ex' value="<? echo $fecha_final; ?>" placeholder="<? echo $fecha_final ?>">
        </div>
        
               <div class="col-md-4 col-lg-4">
               <div class="formulario">
                      <label for="caja_orden"><b>BUSCAR ORDEN</b></label>
                        <input class="search_query form-control" type="text" name="caja_orden" id="caja_orden" autocomplete="off" placeholder="Buscar orden..."></input>
    
                    <div id="ordenes">
                       
                    </div>
                </div>
                </div>
               
                <div class="col-md-3 col-lg-3">
                  
                        <label for="caja_bus_mues"><b>BUSCAR MUESTRA GEOLOGIA</b></label>
                        <input class="search_query form-control" type="text" name="caja_bus_mues" id="caja_bus_mues" autocomplete="off" placeholder="Buscar muestra..."></input>
                  
                    <div id="datos_muestra">
                       
                    </div>
                </div>
          
        
        <div class="col-md-2 col-lg-3">
            <label for="print"></label><br /><br />
            <button type='button' class='btn btn-info' name='print' id='print' onclick="ver_listado(<? echo $unidad_id; ?>);"> <span class="fa fa-eye fa-2x"> Ejecutar</span> </button>

            <button type='button' class='btn btn-success' name='export' id='export' onclick="exportar_listado(<? echo $unidad_id; ?>)"> <span class="fa fa-file-excel-o fa-2x"> Exportar</span>
            </button>
        </div>
        <br/><br/><br/><br/><br/><br/>
<?php
if (isset($_GET['unidad_id'])) {
    $mysqli->set_charset("utf8");

    if (is_null($fecha_inicial)) {
        $fecha_inicial = date('Y-m-d');
    }

    if (is_null($fecha_final)) {
        $fecha_final = date('Y-m-d');
    }
    
    if (is_null($metodo_id)) {
        $metodo_id = 3;
    }
    if (is_null($tipo_id)) {
        $tipo_id = 0;
    }
    
    $fecha_inicial = date("Y-m-d", strtotime($fecha_inicial));
    $fecha_final = date("Y-m-d", strtotime($fecha_final));
    $query = "CALL arg_rpt_listadoNormal(" . $unidad_id . ", '" . $fecha_inicial . "', '" . $fecha_final . "', " .$metodo_id. ", '".$orden."', '".$muestra."' ,".$tipo_id.")";
    mysqli_multi_query($mysqli, $query);
    $result = $mysqli->store_result();
    
            $html_det = "<table class='table table-striped' id='motivos'>
                                <thead>                                
                                     <tr class='table-info'>      
                                        <th colspan='8'>Ordenes de trabajo: " . $unidad_mina . "</th>      
                                        <th align='center' colspan='2'></th>
                                        <th></th>
                                     </tr>
                                    <tr class='table-info' justify-content: center;>
                                        <th scope='col1'>No.</th>
                                         <th scope='col1'>ORDEN TRABAJO</th> 
                                        <th scope='col1'>FECHA</th>
                                        <th scope='col1'>FECHA LIBERACION</th>
                                        <th scope='col1'>HORA LIBERACION</th> 
                                        <th scope='col1'>METODO</th>
                                        <th scope='col1'>FOLIO INTERNO</th> 
                                        <th scope='col1'>MUESTRA GEOLOGIA</th>                                      
                                        <th scope='col1'>CONTROL</th>
                                        <th scope='col1'>ABSORCION</th>
                                        <th scope='col1'>REENSAYE</th>";
            $html_det .= "</tr>
                               </thead>
                               <tbody>";

            $num = 1;
            while ($fila = $result->fetch_row()) {
                $html_det .= "<tr>";
                $html_det .= "<td>" . $num . "</td>";
                $html_det .= "<td> <a href='orden_trabajo_lis.php?trn_id=" . $fila[5] . "' target='_blank'>" . $fila[3] . "</a></td>";
                $html_det .= "<td>" . $fila[0] . "</td>";                             
                $html_det .= "<td>" . $fila[1] . "</td>";
                $html_det .= "<td>" . $fila[2] . "</td>";
                $html_det .= "<td>" . $fila[5] . "</td>";
                $html_det .= "<td>" . $fila[7] . "</td>";
                
                $html_det .= "<td>" . $fila[8] . "</td>"; 
                $html_det .= "<td>" . $fila[11] . "</td>";
                $html_det .= "<td>" . $fila[9] . "</td>";               
                
                $html_det .= "<td>" . $fila[12] . "</td>";
                $html_det .= "</tr>";
                $num = $num + 1;
            }
            $html_det .= "</tbody></table>";
            echo ("$html_det");
            ?>
        </div>
    </div>
<? } ?>
<script>
    jQuery.noConflict();

    function exportar_listado(unidad_id_e) {
        var unidad_id_ex = unidad_id_e;
        var fecha_inicial_ex = document.getElementById('fecha_inicial_ex').value;
        var fecha_final_ex = document.getElementById('fecha_final_ex').value;
        /*alert(fecha_inicial_ex);
         alert(fecha_final_ex);*/
        
        var metodo_sel = document.getElementById("metodo_id_sel").value;
        var tipo_mues = document.getElementById("tipo_id_sel").value;
        var orden = document.getElementById('caja_orden').value;
        var muestra = document.getElementById('caja_bus_mues').value;
        if(orden == ''){
            orden = 0
        };
         if(muestra == ''){
            muestra = 0
        };
       /* alert(metodo_sel);
        alert(tipo_mues);
        alert(orden);
        alert(muestra);*/
        var exportar = '<?php echo "\ exportar_listadoNormal.php?unidad_id=" ?>' + unidad_id_ex + '&fecha_inicial=' +
            fecha_inicial_ex + '&fecha_final=' + fecha_final_ex+'&metodo='+metodo_sel+'&orden='+orden+'&muestra='+muestra+'&tipo_muestra='+tipo_mues;
        window.location.href = exportar;
    }

    /*function llama_datos(fecha_inicial, fecha_final) {
        var consulta = document.getElementById("caja_busqueda").value;
        var nombre = 'San Agustin';
        var unidad_id = <? echo $unidad_id ?>;
        console.log(consulta);
        console.log("entró");
        $.ajax({
                url: 'reporte_listado_normal.php',
                type: 'POST',
                dataType: 'html',
                data: {
                    consulta: consulta,
                    nombre: nombre,
                    fecha_inicial: fecha_inicial,
                    fecha_final: fecha_final,
                    unidad_id: unidad_id
                },
            })
            .done(function(respuesta) {
                console.log(respuesta);
                jQuery.noConflict();
                $('#fases_modal').modal('show');
                $("#datos_listado").html(respuesta);

            })
    }*/

</script>

<script type="text/javascript" src="js/buscar_muestra_ord.js"></script>


<script type="text/javascript" src="js/buscar_muestra.js"></script>