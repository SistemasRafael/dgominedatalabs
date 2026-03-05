<? //include "../connections/config.php";
$unidad_id = $_GET['unidad_id'];
$_SESSION['unidad_id'] = $unidad_id;
$u_id = $_SESSION['u_id'];

$unidad_mi = $mysqli->query("SELECT nombre FROM arg_empr_unidades WHERE unidad_id = " . $unidad_id) or die(mysqli_error($mysqli));
$unidad_min = $unidad_mi->fetch_assoc();
$unidad_mina = $unidad_min['nombre'];

$date = '2023-01-01';
$fecha_inicial = date("d-m-Y", strtotime($date));
$fecha_final = date("d-m-Y");
?>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<script>
    $(document).ready(function() {
        $("#caja_orden").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#motivos tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    $(document).ready(function() {
        $("#caja_bus_mues").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#motivos tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>

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
        var unidad_id_ex = document.getElementById('unidad_id_ex').value;;
        var fecha_inicial_ex = document.getElementById('fecha_inicial_ex').value;
        var fecha_final_ex = document.getElementById('fecha_final_ex').value;
        var metodo_sel = document.getElementById('metodo_id_sel').querySelector(':checked').value;
        var orden_tr = document.getElementById('caja_orden').value;
        var muestra = document.getElementById('caja_bus_mues').value;
        var tipo_id = document.getElementById('tipo_id_sel').querySelector(':checked').value;
        var muestra = document.getElementById('caja_bus_mues').value;
        if (orden_tr == '') {
            orden_tr = 0
        };
        if (muestra == '') {
            muestra = 0
        };
        // alert(muestra);
        // alert(fecha_final_ex);
        var exportar = '<?php echo "\ listados_normal.php?unidad_id=" ?>' + unidad_id_ex + '&fecha_inicial=' +
            fecha_inicial_ex + '&fecha_final=' + fecha_final_ex + '&metodo_id=' + metodo_sel + '&orden=' + orden_tr +
            '&muestra=' + muestra + '&tipo_id=' + tipo_id;
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
            <? if ($_SESSION['unidad_acc'] == '0') {
                $datos_minas = $mysqli->query("SELECT unidad_id, nombre
                                                      FROM arg_empr_unidades") or die(mysqli_error($mysqli));
                //while ($row2 = $datos_minas->fetch_assoc()){
                while ($row2 = $datos_minas->fetch_array(MYSQLI_ASSOC)) {
                    $met_sele = $row2['nombre'];
                    $string = "";
                    if ($row2['unidad_id'] == $unidad_id) {
                        $string = "selected";
                    }
            ?>
                    <option value="<? echo $row2['unidad_id'] ?>" <? echo $string ?>>
                        <? echo $met_sele ?>
                    </option>
            <? }
            } ?>

            <? if ($_SESSION['unidad_acc'] <> '0' and $_SESSION['unidad_acc'] <> '999') {
                $datos_minas = $mysqli->query("SELECT unidad_id, nombre
                                                                    FROM arg_empr_unidades 
                                                                    WHERE unidad_id = " . $_SESSION['unidad_acc']) or die(mysqli_error($mysqli));
                //while ($row2 = $datos_minas->fetch_assoc()){
                while ($row2 = $datos_minas->fetch_array(MYSQLI_ASSOC)) {
                    $met_sele = $row2['nombre'];
                    $string = "";
                    if ($row2['unidad_id'] == $unidad_id) {
                        $string = "selected";
                    }
            ?>
                    <option value="<? echo $row2['unidad_id'] ?>" <? echo $string ?>>
                        <? echo $met_sele ?>
                    </option>
            <?
                }
            } ?>

            <?  //999=Varias unidades de mina (No Todas) 
            $cadena = $_SESSION['unidades'];
            $i = 0;
            if ($_SESSION['unidad_acc'] == '999') {
                while ($i <= strlen($cadena)) {
                    $valor = substr($cadena, $i, 1);
                    $i = $i + 1;
                    if (is_numeric($valor)) {
                        $datos_mina = $mysqli->query("SELECT unidad_id, nombre FROM arg_empr_unidades WHERE unidad_id = " . $valor) or die(mysqli_error($mysqli));
                        while ($row2 = $datos_mina->fetch_array(MYSQLI_ASSOC)) {
                            $met_sele = $row2['nombre'];
                            $string = "";
                            if ($row2['unidad_id'] == $unidad_id) {
                                $string = "selected";
                            }
            ?>
                            <option value="<? echo $row2['unidad_id'] ?>" <? echo $string ?>>
                                <? echo $met_sele ?>
                            </option>
            <? }
                    }
                }
            } ?>
        </select>
    </div>

    <br /><br /><br /><br /><br />


    <div>
        <div class="col-md-3 col-lg-2">
            <label for="fecha_inicial_ex"><b>DESDE</b></label>
            <input type='date' class='form-control' name='fecha_inicial_ex' id='fecha_inicial_ex' value="<? echo $fecha_inicial; ?>" placeholder="">
        </div>
        <div class="col-md-3 col-lg-2">
            <label for="fecha_final_ex"><b>HASTA</b></label>
            <input type="date" class="form-control" name='fecha_final_ex' id='fecha_final_ex' value="<? echo $fecha_final; ?>" placeholder="">
        </div>
        <div class="col-md-2 col-lg-1">
            <label for="filtrar"></label><br /><br />
            <button type='button' class='btn btn-success' name='filtrar' id='filtrar' onclick="updateTabla()"> <span class="fa fa-filter fa-2x"> Filtrar</span>
            </button>
        </div>
    </div>


    <div class="col-md-4 col-lg-4">
        <div class="formulario">
            <label for="caja_orden"><b>BUSCAR ORDEN</b></label>
            <input class="search_query form-control" type="text" name="caja_orden" id="caja_orden" autocomplete="off" placeholder="Buscar orden..."></input>
        </div>
    </div>


    <div class="col-md-2 col-lg-1">
        <label for="export"></label><br /><br />
        <button type='button' class='btn btn-success' name='export' id='export' onclick="exportar_listado(<? echo $unidad_id; ?>)"> <span class="fa fa-file-excel-o fa-2x"> Exportar</span>
        </button>
    </div>
    <br /><br /><br /><br /><br /><br />
    <?php
    if (isset($_GET['unidad_id'])) {
        $mysqli->set_charset("utf8");


        $query = "CALL arg_rpt_ResultadosMuestrasGeologia('', '" . $fecha_inicial . "', '" . $fecha_final . "')";
        mysqli_multi_query($mysqli, $query);
        $result = $mysqli->store_result();

        $html_det = "<table class='table table-striped' id='motivos'>
                                <thead>                                
                                     <tr class='table-info'>      
                                        <th colspan='7'>Ordenes de trabajo: " . $unidad_mina . "</th>      
                                        <th align='center' colspan='2'></th>
                                        <th></th>
                                     </tr>
                                    <tr class='table-info' justify-content: center;>
                                        <th scope='col1'>No.</th>
                                        <th scope='col1'>Muestra</th> 
                                        <th scope='col1'>BANVOL</th>
                                        <th scope='col1'>FECHA RECEPCION</th>
                                        <th scope='col1'>Au_PPM</th> 
                                        <th scope='col1'>Ag_PPM</th>
                                        <th scope='col1'>FECHA DE RESULTADO Au</th> 
                                        <th scope='col1'>HORA Au</th>
                                        <th scope='col1'>FECHA DE RESULTADO Ag</th> 
                                        <th scope='col1'>HORA Ag</th>";
        $html_det .= "</tr>
                               </thead>
                               <tbody>";

        $num = 1;
        while ($fila = $result->fetch_row()) {
            $html_det .= "<tr>";
            $html_det .= "<td>" . $num . "</td>";
            $html_det .= "<td>" . $fila[0] . "</a></td>";
            $html_det .= "<td>" . $fila[1] . "</td>";
            $html_det .= "<td>" . $fila[2] . "</td>";
            $html_det .= "<td>" . $fila[3] . "</td>";
            $html_det .= "<td>" . $fila[4] . "</td>";
            $html_det .= "<td>" . $fila[5] . "</td>";
            $html_det .= "<td>" . $fila[6] . "</td>";
            $html_det .= "<td>" . $fila[7] . "</td>";
            $html_det .= "<td>" . $fila[8] . "</td>";
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
    function updateTabla(url) {
        var fecha_inicial_ex = document.getElementById('fecha_inicial_ex').value;
        var fecha_final_ex = document.getElementById('fecha_final_ex').value;
        $.ajax({
            url: 'geo_datos.php',
            type: 'POST',
            dataType: "HTML",
            data: {
                fecha_final_ex: fecha_final_ex,
                fecha_inicial_ex: fecha_inicial_ex
            },
            success: function(html) {
                $("#motivos tbody").html(html);
            }
        });
    }

    jQuery.noConflict();

    function exportar_listado(unidad_id_e) {
        var unidad_id_ex = unidad_id_e;
        var fecha_inicial_ex = document.getElementById('fecha_inicial_ex').value;
        var fecha_final_ex = document.getElementById('fecha_final_ex').value;
        const date = new Date();

        let day = date.getDate();
        let month = date.getMonth() + 1;
        let year = date.getFullYear();

        if (fecha_inicial_ex == '') {
            fecha_inicial_ex = "01-01-2023";
        }
        if (fecha_final_ex == '') {
            fecha_final_ex = `${day}-${month}-${year}`;
        }
        var exportar = '<?php echo "\ exportar_reporte_geo.php?unidad_id=" ?>' + unidad_id_ex + '&fecha_inicial=' +
            fecha_inicial_ex + '&fecha_final=' + fecha_final_ex;
        window.location.href = exportar;
    }
</script>

<script type="text/javascript" src="js/buscar_muestra_ord.js"></script>


<script type="text/javascript" src="js/buscar_muestra.js"></script>