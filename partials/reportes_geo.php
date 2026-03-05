<? //include "../connections/config.php";

$date = date('Y-m-d');
$fecha_inicial = date("Y-m-d", strtotime($date));
$fecha_final = date("Y-m-d");
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
</script>
<style type="text/css">
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

<div class="container-fluid">
    <br /><br /><br /><br /><br /><br />
    <?
        $fecha_minima_val = date('Y-m-d');
        $nuevafecha = strtotime($fecha_minima_val);
        $nuevafecha = date('Y-m-d', $nuevafecha);
        $tipo_id = 0;
    ?>
    <br /><br /><br />
    <div>
        <div class="col-md-3 col-lg-2">
            <label for="fecha_inicial_ex"><b>DESDE</b></label>
            <input type='date' class='form-control' name='fecha_inicial_ex' id='fecha_inicial_ex' placeholder="">
        </div>
        <div class="col-md-3 col-lg-2">
            <label for="fecha_final_ex"><b>HASTA</b></label>
            <input type="date" class="form-control" name='fecha_final_ex' id='fecha_final_ex' placeholder="">
        </div>
        <div class="col-md-3 col-lg-2">
            <label for="tipo_id_sel"><b>TIPO DE MUESTRAS</b></label>
            <select name="tipo_id_sel" id="tipo_id_sel" class="form-control">
                <option value="2" selected>Seleccione</option>
                <? $result_h = $mysqli->query("SELECT 0 AS tipo_id, 'Muestras de Geología' AS nombre 
                                                          UNION ALL 
                                                          SELECT 1 AS tipo_id, 'Muestras Geo y Controles de Calidad' AS nombre") or die(mysqli_error($mysqli));
                while ($row2 = $result_h->fetch_array(MYSQLI_ASSOC)) {
                    $met_sele = $row2['nombre'];
                ?>
                    <option value="<? echo $row2['tipo_id'] ?>"><? echo $met_sele ?></option>
                <? } ?>
            </select>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="formulario">
                <label for="caja_orden"><b>BUSCAR MUESTRA</b></label>
                <input class="search_query form-control" type="text" name="caja_orden" id="caja_orden" autocomplete="off" placeholder="Buscar muestra..."></input>
            </div>
        </div>
    </div>

    <div class="col-md-1">
        <label for="submit"></label><br /><br />
        <button type='button' class='btn btn-info' name='submit' id='submit' onclick="updateTabla()"> <span class="fa fa-eye fa-2x"> Ejecutar</span>
        </button>
    </div>
    <div class="col-md-1">
        <label for="export"></label><br /><br />
        <button type='button' class='btn btn-success' name='export' id='export' onclick="exportar_listado()"> <span class="fa fa-file-excel-o fa-2x"> Exportar</span>
        </button>
    </div>
    <br /><br/><br><br>
    <?php

    $datos_geo = $mysqli->query("CALL arg_rpt_reporteGeosql('$fecha_inicial','$fecha_final', '0')") or die(mysqli_error($mysqli));
    //$datos_in = $datos_ins->fetch_array(MYSQLI_ASSOC);

    $html_det = "<table class='table table-striped' id='motivos'>
                                <thead>                                
                                     <tr class='table-info'>      
                                        <th colspan='4'>Ordenes de trabajo</th>
                                     </tr>
                                    <tr class='table-info' justify-content: center;>
                                        <th scope='col1'>No.</th>
                                        <th scope='col1'>MUESTRA</th> 
                                        <th scope='col1'>Au_PPM</th> 
                                        <th scope='col1'>Ag_PPM</th>";
    $html_det .= "</tr></thead><tbody>";

    $num = 1;
    while ($fila = $datos_geo->fetch_assoc()) {
        $html_det .= "<tr>";
        $html_det .= "<td>" . $num . "</td>";
        $html_det .= "<td>" . $fila['muestra_geologia'] . "</td>";
        $html_det .= "<td>" . $fila['au_ppm'] . "</td>";
        $html_det .= "<td>" . $fila['ag_ppm'] . "</td>";
        $html_det .= "</tr>";
        $num = $num + 1;
    }
    $html_det .= "</tbody></table>";
    echo ($html_det);
    ?>
</div>
<?
?>
<script>
    function updateTabla(url) {
        var fecha_inicial_ex = document.getElementById('fecha_inicial_ex').value;
        var fecha_final_ex = document.getElementById('fecha_final_ex').value;
        var tipo_id = document.getElementById('tipo_id_sel').querySelector(':checked').value;
        if (fecha_inicial_ex == '') {
            fecha_inicial_ex = '<?php echo $fecha_inicial ?>';
        }
        if (fecha_final_ex == '') {
            fecha_final_ex = '<?php echo $fecha_final ?>';
        }
        $.ajax({
            url: 'reporte_datos.php',
            type: 'POST',
            dataType: "html",
            data: {
                fecha_final_ex: fecha_final_ex,
                fecha_inicial_ex: fecha_inicial_ex,
                tipo_id: tipo_id
            },
            success: function(html) {
                $("#motivos tbody").html(html);
            }
        });
    }

    jQuery.noConflict();
    function exportar_listado() {
        var fecha_inicial_ex = document.getElementById('fecha_inicial_ex').value;
        var fecha_final_ex = document.getElementById('fecha_final_ex').value;
        var tipo_id = document.getElementById('tipo_id_sel').querySelector(':checked').value;
        if (fecha_inicial_ex == '') {
            fecha_inicial_ex = '<?php echo $fecha_inicial ?>';
        }
        if (fecha_final_ex == '') {
            fecha_final_ex = '<?php echo $fecha_final ?>';
        }
        var exportar = '<?php echo "\ exportar_reportes_geoVarios.php?fecha_inicial=" ?>' + fecha_inicial_ex + '&fecha_final=' + fecha_final_ex + '&tipo_id=' + tipo_id;
        window.location.href = exportar;
    }
</script>