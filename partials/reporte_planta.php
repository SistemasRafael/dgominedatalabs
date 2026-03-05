<!-- jQuery 
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>-->
<!-- BS JavaScript 
<script type="text/javascript" src="js/bootstrap.js"></script>-->
<!-- Have fun using Bootstrap JS -->

<?php
//include "../connections/config.php";
$fecha = date("Y-m-d");
$unidad_id = $_GET['unidad_id'];

?>
<script>
    $(document).ready(function() {
        $("#folio").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#motivos tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });


        $("#fecha_ex").on("change", function() {
            var value = $("#fecha_ex").val();
            var tipo = $("#area_sel").val();
            var unidadid = <?php echo $unidad_id;?>

            $.ajax({
                    url: 'datos_reporte_planta.php',
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        value: value,
                        tipo: tipo,
                        unidadid:unidadid
                    },
                })
                .done(function(respuesta) {
                    $("#motivos").html(respuesta);
                })
        });
        $("#area_sel").on("change", function() {
            var value = $("#fecha_ex").val();
            var tipo = $("#area_sel").val();
            $.ajax({
                    url: 'datos_reporte_planta.php',
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        value: value,
                        tipo: tipo,
                        unidadid:unidadid
                    },
                })
                .done(function(respuesta) {
                    $("#motivos").html(respuesta);
                })
        });
    });



    function exportar() {
        var fecha = document.getElementById("fecha_ex").value;
        var tipo = document.getElementById("area_sel").value;        
        var unidadid = <?php echo $unidad_id;?>

        var exportar = '<?php echo "\ export_reporte_planta.php?fecha=" ?>' + fecha + "&tipo=" + tipo + '&unidad=' + unidadid;
        window.location.href = exportar;
    }

    //Crear Bancos
    function GuardarMuestra(unidad_id) {
        var unidad_id = unidad_id
        var folio = document.getElementById("folio_ex").value;
        var area = document.getElementById("area").value;
        $.ajax({
                url: 'agregar_muestra.php',
                type: 'POST',
                dataType: 'html',
                data: {
                    unidad_id: unidad_id,
                    folio: folio,
                    area: area
                },
            })
            .done(function(respuesta) {
                ///$("#placas_dat").html(respuesta);
                if (respuesta == 'Se registro exitosamente.') {
                    alert('Se guardó con éxito');
                    var direccionar = '<?php echo "\ muestras_soluciones.php?tipo=2&unidad_id=" ?>' + unidad_id;
                    window.location.href = direccionar;
                } else {
                    alert(respuesta);
                }
            })
    }
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

<?php

$datos_bancos_detalle = $mysqli->query(
    "CALL arg_rpt_reportePlanta('$fecha',1, $unidad_id)"
) or die(mysqli_error($mysqli));

?>

<br><br><br>
<br><br><br>
<div class="container">

    <div class="col-md-2 col-lg-2">
        <label for="fecha_ex" class="col-form-label"><b>FECHA</b></label>
        <input type='date' class='form-control' name='fecha_ex' id='fecha_ex' value="<? echo $fecha; ?>" placeholder="<? echo $fecha ?>">
    </div>
    <div class="col-md-2 col-lg-2">
        <label for="folio" class="col-form-label"><b>BUSQUEDA</b></label>
        <input class="form-control" type="text" name="folio" id="folio" autocomplete="off" placeholder="Buscar folio..."></input>
    </div>

    <div class="col-md-2 col-lg-2">
        <label for="area_sel" class="col-form-label"><b>Tipo</b></label>
        <select name="area_sel" id="area_sel" class="form-control">
            <option value="1">Planta</option>
            <option value="2">Metalurgia</option>
        </select>
    </div>

    <div class="col-md-2 col-lg-2" style="margin-top:24;">
        <button type='button' class='btn btn-success' name='export' id='export' onclick="exportar('<? echo $fecha ?>', 1)"> EXPORTAR
            <span class='fa fa-file-excel-o fa-1x'></span>
        </button>
    </div>



    <br>
    <br>
    <br>
    <br>
    <?
    $html_det = "<div class='container'>
                        <table class='table table-striped' id='motivos'>
                                <thead>
                                    <tr class='table-info' justify-content: center;>            
                                        <th colspan='2' scope='col2'>Descripcion</th>
                                        <th colspan='2' scope='col2' align='center'>ORO</th>
                                        <th colspan='2' scope='col2' align='center' bgcolor='#72bbe4'>PLATA</th>
                                        <th colspan='2' scope='col2' align='center'>COBRE</th>
                                        <th colspan='2' scope='col2' align='center' bgcolor='#72bbe4'>NaCN</th>
                                        <th colspan='2' scope='col2' align='center'>pH</th>";
    $html_det .= "</tr>";
    $html_det .= "<tr class='table-info' justify-content: center;>            
                            <th colspan='2' scope='col2'></th>
                            <th scope='col1'>1er. Turno</th>
                            <th scope='col1'>2do. Turno</th>
                            <th scope='col1' bgcolor='#72bbe4'>1er. Turno</th>
                            <th scope='col1' bgcolor='#72bbe4'>2do. Turno</th>
                            <th scope='col1'>1er. Turno</th>
                            <th scope='col1'>2do. Turno</th>
                            <th scope='col1' bgcolor='#72bbe4'>1er. Turno</th>
                            <th scope='col1' bgcolor='#72bbe4'>2do. Turno</th>
                            <th scope='col1'>1er. Turno</th>
                            <th scope='col1'>2do. Turno</th>
                        </tr>
                        <tr class='table-info' justify-content: center;>            
                            <th colspan='2' scope='col2'></th>
                            <th scope='col1'>Au (ppm)</th>
                            <th scope='col1'>Au (ppm)</th>
                            <th scope='col1' bgcolor='#72bbe4'>Ag (ppm)</th>
                            <th scope='col1' bgcolor='#72bbe4'>Ag (ppm)</th>
                            <th scope='col1'>Cu (ppm)</th>
                            <th scope='col1'>Cu (ppm)</th>
                            <th scope='col1' bgcolor='#72bbe4'>NaCN (ppm)</th>
                            <th scope='col1' bgcolor='#72bbe4'>NaCN (ppm)</th>
                            <th scope='col1'>pH</th>
                            <th scope='col1'>pH</th>
                        </tr>
                               </thead>
                               <tbody>";

    while ($fila = $datos_bancos_detalle->fetch_assoc()) {
        $num = 1;
        $au_ppm_t1 = $fila['Au_ppm_t1'] ? $fila['Au_ppm_t1'] : '0';
        $au_ppm_t2 = $fila['Au_ppm_t2'] ? $fila['Au_ppm_t2'] : '0';
        $ag_ppm_t1 = $fila['Ag_ppm_t1'] ? $fila['Ag_ppm_t1'] : '0';
        $ag_ppm_t2 = $fila['Ag_ppm_t2'] ? $fila['Ag_ppm_t2'] : '0';
        $cu_ppm_t1 = $fila['cu_ppm_t1'] ? $fila['cu_ppm_t1'] : '0';
        $cu_ppm_t2 = $fila['cu_ppm_t2'] ? $fila['cu_ppm_t2'] : '0';
        $phh_t1 = $fila['phh_t1'] ? $fila['phh_t1'] : '0';
        $phh_t2 = $fila['phh_t2'] ? $fila['phh_t2'] : '0';
        $cnl_t1 = $fila['cnl_t1'] ? $fila['cnl_t1'] : '0';
        $cnl_t2 = $fila['cnl_t2'] ? $fila['cnl_t2'] : '0';
        $cao_t1 = $fila['cao_t1'] ? $fila['cao_t1'] : '0';
        $cao_t2 = $fila['cao_t2'] ? $fila['cao_t2'] : '0';
        $html_det .= "<tr>";
        $html_det .= "<td colspan='2'>" . $fila['folio'] . "</td>";
        $html_det .= "<td>" . $au_ppm_t1 . "</td>";
        $html_det .= "<td>" . $au_ppm_t2 . "</td>";
        $html_det .= "<td>" . $ag_ppm_t1 . "</td>";
        $html_det .= "<td>" . $ag_ppm_t2 . "</td>";
        $html_det .= "<td>" . $cu_ppm_t1 . "</td>";
        $html_det .= "<td>" . $cu_ppm_t2 . "</td>";
        $html_det .= "<td>" . $cnl_t1 . "</td>";
        $html_det .= "<td>" . $cnl_t2 . "</td>";
        $html_det .= "<td>" . $phh_t1 . "</td>";
        $html_det .= "<td>" . $phh_t2 . "</td>";
        $html_det .= "</tr>";
    }

    $html_det .= "</tbody></table></div></div>";

    echo ("$html_det");



    ?>
    <br /><br /><br /><br /><br /><br /><br /><br />
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <!--<script type="text/javascript" src="js/vehiculos.js"></script>-->