<? include "connections/config.php";
$unidad_id = $_GET['unidad_id'];
$_SESSION['unidad_id'] = $unidad_id;
$u_id = $_SESSION['u_id'];
$trn_id_batch = $_GET['trn_id_batch'];
$metodo_id = $_GET['metodo_id'];
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#motivos').DataTable({
            pagingType: 'full_numbers',
        });
    });
</script>
<style>
@import url("https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css");
</style>
<script>
    jQuery.noConflict();

    function exportar_listado(unidad_id_e) {
        var unidad_id_ex = unidad_id_e;
        var trn_id_batch = document.getElementById('trn_id_batch').value;
        var metodo_id = document.getElementById('metodo_id').value;
        var fecha_inicial_ex = document.getElementById('fecha_inicial_ex').value;
        var fecha_final_ex = document.getElementById('fecha_final_ex').value;
        var exportar = "\ exportar_ultimosResultados.php?trn_id_batch=" + 0 + "&metodo_id=" + metodo_id + "&fecha_inicial=" + '<?php echo $fecha_inicial ?>' + "&fecha_final=" + '<?php echo $fecha_final ?>' + "";
        window.location.href = exportar;
    }

    function ver_listado(unidad_id_e, fecha_inicial_ex, fecha_final_ex) {
        var unidad_id_ex = unidad_id_e;
        var trn_id_batch = document.getElementById('trn_id_batch').value;
        var metodo_id = document.getElementById('metodo_id').value;
        var fecha_inicial_ex = document.getElementById('fecha_inicial_ex').value;
        var fecha_final_ex = document.getElementById('fecha_final_ex').value;
        var exportar = '\ resultados.php?trn_id_batch=' + 0 + '&metodo_id=' + metodo_id + '&unidad_id=' + unidad_id_ex + '&fecha_inicial=' +
            '<?php echo $fecha_inicial ?>' + '&fecha_final=' + '<?php echo $fecha_final ?>';
        window.location.href = exportar;
    }

    function llama_datos(consulta) {
        var consulta = consulta;
        $.ajax({
                url: 'reporte_listado_duplicado.php',
                type: 'POST',
                dataType: 'html',
                data: {
                    consulta: consulta
                },
            })
            .done(function(respuesta) {
                // alert(respuesta);
                jQuery.noConflict();
                $('#fases_modal').modal('show');
                $("#datos_listado").html(respuesta);

            })
    }
</script>

<?php
// $mysqli->set_charset("utf8");

if (is_null($fecha_inicial)) {
    $fecha_inicial = date('d-m-Y');
}

if (is_null($fecha_final)) {
    $fecha_final = date('d-m-Y');
}
$datos_metodos = $mysqli->query("SELECT nombre FROM arg_metodos WHERE tipo_id = 1") or die(mysqli_error($mysqli));
$total_metodos = (mysqli_num_rows($datos_metodos));
$unidad_mi = $mysqli->query("SELECT nombre FROM arg_empr_unidades WHERE unidad_id = " . $unidad_id) or die(mysqli_error($mysqli));
$unidad_min = $unidad_mi->fetch_assoc();
$unidad_mina = $unidad_min['nombre'];
?>
<div class="container-fluid">
    <br /><br /><br /><br /><br /><br />
    <?
    $fecha_minima_val = date('d-m-Y');
    $nuevafecha = strtotime($fecha_minima_val);
    $nuevafecha = date('d-m-Y', $nuevafecha);

    ?>
    <div class="col-md-2 col-lg-2">
        <label for="metodo_id"><b>MÉTODOS</b></label><br />
        <select name="metodo_id" id="metodo_id" class="form-control">
            <?
            $result_h = $mysqli->query("SELECT 0 AS metodo_id, 'TODOS' AS nombre
                                                         UNION ALL
                                                         SELECT metodo_id, nombre FROM `arg_metodos` ") or die(mysqli_error($mysqli));
            while ($row2 = $result_h->fetch_array(MYSQLI_ASSOC)) {
                $met_sele = $row2['nombre'];
                if ($row2['metodo_id'] == $metodo_id){
                    $string = "selected";
                }else{
                    $string = "";
                }
            ?>
                <option value="<? echo $row2['metodo_id'] ?>" <? echo $string ?>><? echo $met_sele ?></option>
            <? } ?>
        </select>
    </div>
    <div class="col-md-2 col-lg-2">
        <?
        $nuevafecha = date('d-m-Y');
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
    <div id="datos_listado">
        <br /><br /><br /><br />
        <?
        $html_det = "<table class='table table-striped' id='motivos'>
                                <thead>                                
                                     <tr class='table-info'>      
                                        <th colspan='2'>Ordenes de trabajo: " . $unidad_mina . "</th>
                                        <th align='center' colspan='1'></th>
                                        <th></th>
                                     </tr>
                                    <tr class='table-info' justify-content: center;>  
                                        <th scope='col1'>FOLIO INTERNO</th>
                                        <th scope='col1'>MUESTRA</th>
                                        <th scope='col1'>METODO</th>
                                        <th scope='col1'>ULTIMA ABSORCION</th>";
        $html_det .= "</tr>
                               </thead>
                               <tbody>";

        $num = 1;
        $datos = $mysqli->query(
            "CALL arg_rpt_ultimosResultados(" . $trn_id_batch . "," . $metodo_id . ",'" . $fecha_inicial . "', '" . $fecha_final . "')"
        ) or die(mysqli_error($mysqli));
        while ($fila = $datos->fetch_row()) {
            $html_det .= "<tr>";
            $html_det .= "<td>" . $fila[2] . "</td>";
            $html_det .= "<td>" . $fila[3] . "</td>";
            $html_det .= "<td>" . $fila[5] . "</td>";
            $html_det .= "<td>" . $fila[6] . "</td>";
            $html_det .= "</tr>";
            $num = $num + 1;
        }
        $html_det .= "</tbody></table>";
        echo ("$html_det");
        ?>
    </div>
</div>