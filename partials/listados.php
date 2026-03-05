<? // include "../connections/config.php";
$unidad_id = $_GET['unidad_id'];
$_SESSION['unidad_id'] = $unidad_id;
$u_id = $_SESSION['u_id'];
$fecha_inicial = $_GET['fecha_inicial'];
$fecha_final = $_GET['fecha_final'];

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
    jQuery.noConflict();

    function exportar_listado(unidad_id_e) {
        var unidad_id_ex = unidad_id_e;
        var fecha_inicial_ex = document.getElementById('fecha_inicial_ex').value;
        var fecha_final_ex = document.getElementById('fecha_final_ex').value;
        // alert(fecha_inicial_ex);
        // alert(fecha_final_ex);
        //document.getElementById("metodo_id_sel").value;
        var exportar = '<?php echo "\ exportar_listados.php?unidad_id=" ?>' + unidad_id_ex + '&fecha_inicial=' +
            '<?php echo $fecha_inicial ?>' + '&fecha_final=' + '<?php echo $fecha_final ?>';
        window.location.href = exportar;
    }

    function ver_listado(unidad_id_e, fecha_inicial_ex, fecha_final_ex) {
        var unidad_id_ex = unidad_id_e;
        var fecha_inicial_ex = document.getElementById('fecha_inicial_ex').value;
        var fecha_final_ex = document.getElementById('fecha_final_ex').value;
        // alert(fecha_inicial_ex);
        // alert(fecha_final_ex);

        //document.getElementById("metodo_id_sel").value;
        var exportar = '<?php echo "\ listados.php?unidad_id=" ?>' + unidad_id_ex + '&fecha_inicial=' +
            fecha_inicial_ex + '&fecha_final=' + fecha_final_ex;
        window.location.href = exportar;
    }
    function llama_datos(consulta){
        var consulta = consulta;
        $.ajax({
            		url: 'reporte_listado_duplicado.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {consulta:consulta},
            	})
            	.done(function(respuesta){
            	  // alert(respuesta);
                   jQuery.noConflict();
            	   $('#fases_modal').modal('show');
                   $("#datos_listado").html(respuesta);
                       
              })   
    }
</script>

<?php
if (isset($_GET['unidad_id'])) {
   // $mysqli->set_charset("utf8");

    if (is_null($fecha_inicial)) {
        $fecha_inicial = date('d-m-Y');
    }

    if (is_null($fecha_final)) {
        $fecha_final = date('d-m-Y');
    }



   

    $datos_metodos = $mysqli->query("SELECT nombre FROM arg_metodos WHERE tipo_id = 1") or die(mysqli_error($mysqli));
    $total_metodos = (mysqli_num_rows($datos_metodos));
    $fecha_inicial = date("d-m-Y", strtotime($fecha_inicial));
    $fecha_final = date("d-m-Y", strtotime($fecha_final));
    $unidad_mi = $mysqli->query("SELECT nombre FROM arg_empr_unidades WHERE unidad_id = " . $unidad_id) or die(mysqli_error($mysqli));
    $unidad_min = $unidad_mi->fetch_assoc();
    $unidad_mina = $unidad_min['nombre'];
    $query = "CALL arg_rpt_listadoDuplicados(" . $unidad_id . ", '" . $fecha_inicial . "', '" . $fecha_final . "', " . "0" . ", " . "0)";
    mysqli_multi_query($mysqli, $query);
    $result = $mysqli->store_result();
?>
    <div class="container-fluid">
        <br /><br /><br /><br /><br /><br />
        <?
        $fecha_minima_val = date('d-m-Y');
        $nuevafecha = strtotime($fecha_minima_val);
        $nuevafecha = date('d-m-Y', $nuevafecha);

        ?>

        <div class="col-md-2 col-lg-2">
            <?
            //$fecha_minima_val = date('Y-m-j');
            // $fecha_minima_val = strtotime ( $fecha_minima_val);
            $nuevafecha = date('d-m-Y');
            //echo $nuevafecha; //2020-12-31
            ?>
            <label for="fecha_inicial_ex"><b>DESDE:</b></label>
            <input type='date' class='form-control' name='fecha_inicial_ex' id='fecha_inicial_ex' value="<? echo $fecha_inicial; ?>">

        </div>
        <div class="col-md-2 col-lg-2">
            <label for="fecha_final_ex"><b>HASTA:</b></label><br />
            <input type="date" class="form-control" name='fecha_final_ex' id='fecha_final_ex' value="<? echo $fecha_final; ?>">
        </div>
        <div class="col-md-2 col-lg-4">
            <label for="print"></label><br /><br />
            <button type='button' class='btn btn-info' name='print' id='print' onclick="ver_listado(<? echo $unidad_id . ', ' . $fecha_inicial . ', ' . $fecha_final; ?>)"> <span class="fa fa-eye fa-2x"> Ejecutar</span> </button>


            <button type='button' class='btn btn-success' name='export' id='export' onclick="exportar_listado(<? echo $unidad_id; ?>)"> <span class="fa fa-file-excel-o fa-2x"> Exportar</span>
            </button>

        </div>
        <!--<div class="col-md-2 col-lg-4">
            <div class="formulario">
                <div id="content" class="col-md-12 col-lg-12">

                    <label for="caja_busqueda"></label>
                    <input class="search_query form-control" type="text" name="caja_busqueda" id="caja_busqueda" placeholder="Buscar orden..."></input>

                </div>
            </div>
        </div>--!>
        <div id="datos_listado">
            <br /><br /><br /><br />

            <?
            $html_det = "<table class='table table-striped' id='motivos'>
                                <thead>                                
                                     <tr class='table-info'>      
                                        <th colspan='6'>Ordenes de trabajo: " . $unidad_mina . "</th>      
                                        <th align='center' colspan='2'></th>
                                        <th></th>
                                     </tr>
                                    <tr class='table-info' justify-content: center;>            
                                        <th scope='col1'>FOLIO</th>
                                        <th scope='col1'>ORDEN TRABAJO</th>
                                        <th scope='col1'>FECHA</th>
                                        <th scope='col1'>METODO</th>
                                        <th scope='col1'>MUESTRA ORIGINAL</th>
                                        <th scope='col1'>ABSORCION ORIGINAL</th>
                                        <th scope='col1'>MUESTRA DUPLICADA</th>                                        
                                        <th scope='col1'>CONTROL</th>
                                        <th scope='col1'>ABSORCION DUPLICADO</th>";
            $html_det .= "</tr>
                               </thead>
                               <tbody>";

            $num = 1;
            while ($fila = $result->fetch_row()) {
                $html_det .= "<tr>";
                $html_det .= "<td> <a href='orden_trabajo_lis.php?trn_id=" . $fila[4] . "' target='_blank'>" . $num . "</td>";
                $html_det .= "<td> <a href='orden_trabajo_lis.php?trn_id=" . $fila[4] . "' target='_blank'>" . $fila[1] . "</a></td>";
                $html_det .= "<td>" . $fila[0] . "</td>";
                $html_det .= "<td>" . $fila[3] . "</td>";
                $html_det .= "<td>" . $fila[5] . "</td>";
                 $html_det .= "<td>" . $fila[6] . "</td>";
                 
                $html_det .= "<td>" . $fila[8] . "</td>";
                $html_det .= "<td>" . $fila[9] . "</td>";
                $html_det .= "<td>" . $fila[10] . "</td>";
                $html_det .= "</tr>";
                $num = $num + 1;
            }
            $html_det .= "</tbody></table>";
            echo ("$html_en");
            echo ("$html_det");
            ?>
        </div>
    </div>
<? } ?>