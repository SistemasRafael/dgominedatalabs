<? //include "../connections/config.php";

$u_id = $_SESSION['u_id'];
$fecha_i = $_GET['fecha_i'];
$fecha_f = $_GET['fecha_f'];
if (is_null($fecha_i)){
    $fecha_i = date('Y-m-d',strtotime("-3 days"));//date('d/m/y');
}
if (is_null($fecha_f)){
    $fecha_f = date('Y-m-d');
}


?>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<script>
    $(document).ready(function() {
        $("#caja_orden").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#motivos tbody tr ").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    $(document).ready(function() {

        $("#metodo_id_sel").on("change", function() {
            var value = $("#metodo_id_sel option:selected").text().toLowerCase();

            if (value == "todos") {
                value = "";
            }
            console.log(value);
            $("#motivos tbody tr").filter(function() {
                $(this).toggle($("#motivos tbody tr").text().toLowerCase().indexOf(value) > -1)
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


<div class="container-fluid">
    <br /><br /><br /><br /><br /><br />
    <div class="col-md-1 col-lg-1">
        <label for="metodo_id_sel" class="col-form-label"><b>MÉTODOS</b></label>
        <select name="metodo_id_sel" id="metodo_id_sel" class="form-control">
            <? $result_h = $mysqli->query("SELECT 0 AS metodo_id, 'TODOS' AS nombre
                                                         UNION ALL
                                                         SELECT metodo_id, nombre FROM `arg_metodos` ") or die(mysqli_error($mysqli));
            while ($row2 = $result_h->fetch_array(MYSQLI_ASSOC)) {
                $met_sele = $row2['nombre'];
                $stringm = "";
                if ($row2['metodo_id'] == $metodo_id) {
                    $stringm = "selected";
                }
            ?>
                <option value="<? echo $row2['metodo_id'] ?>" <? echo $stringm ?>><? echo $met_sele ?></option>
            <? } ?>
        </select>
    </div>
    <div class="col-md-2 col-lg-2">
        <label for="tipo_id_sel" class="col-form-label"><b>TIPO DE MUESTRAS</b></label>
        <select name="tipo_id_sel" id="tipo_id_sel" class="form-control">
            <? $result_h = $mysqli->query("SELECT 0 AS tipo_id, 'Muestras de Geología' AS nombre 
                                                          UNION ALL 
                                                          SELECT 1 AS tipo_id, 'Muestras Geo y Controles de Calidad' AS nombre") or die(mysqli_error($mysqli));
            while ($row2 = $result_h->fetch_array(MYSQLI_ASSOC)) {
                $met_sele = $row2['nombre'];
                $stringt = "";
                if ($row2['tipo_id'] == $tipo_id) {
                    $stringt = "selected";
                }
            ?>
                <option value="<? echo $row2['tipo_id'] ?>" <? echo $stringt ?>><? echo $met_sele ?></option>
            <? } ?>
        </select>
    </div>
    <br /><br /><br /><br /><br />


    <div class="col-md-4 col-lg-4">
        <div class="formulario">
            <label for="caja_orden"><b>BUSCAR ORDEN</b></label>
            <input class="search_query form-control" type="text" name="caja_orden" id="caja_orden" autocomplete="off" placeholder="Buscar orden..."></input>

        </div>
    </div>
    
    <div class="col-md-2 col-lg-2">                     
                      <label for="fecha_inicial"><b>DESDE:</b></label>
                      <input type="date" name="fecha_inicial" class="form-control" id="fecha_inicial" value="<?echo $fecha_i;?>" />
                </div>
                <div class="col-md-2 col-lg-2">
                      <label for="fecha_final"><b>HASTA:</b></label><br/>
                      <input type="date" name="fecha_final" class="form-control" id="fecha_final" value="<?echo $fecha_f;?>" />                                
                </div>                

    <div class="col-md-2 col-lg-3">
        <label for="print"></label><br /><br />
        <button type='button' class='btn btn-info' name='print' id='print' onclick="llama_datos();"> <span class="fa fa-eye fa-2x"> Ejecutar</span> </button>

        <button type='button' class='btn btn-success' name='export' id='export' onclick="exportar_listado(<? echo $unidad_id; ?>)"> <span class="fa fa-file-excel-o fa-2x"> Exportar</span>
        </button>
    </div>
    <div>
        <br /><br /><br /><br /><br /><br />
        <?php
        $mysqli->set_charset("utf8");
        echo $fecha_i; echo $fecha_f;
        $query = "CALL arg_rpt_pesajesMuestras(0, 0, 0, '".$fecha_i."','".$fecha_f."')";
        mysqli_multi_query($mysqli, $query);
        $result = $mysqli->store_result();

        $html_det = "<table class='table table-striped' id='motivos'>
                                <thead>
                                    <tr class='table-info' justify-content: center;>
                                        <th scope='col1'>No.</th>
                                        <th scope='col1'>ORDEN</th>
                                        <th scope='col1'>FOLIO INTERNO</th>
                                        <th scope='col1'>MUESTRA</th> 
                                        <th scope='col1'>METODO</th>
                                        <th scope='col1'>SECADO</th>
                                        <th scope='col1'>PESO QUE</th>
                                        <th scope='col1'>PESO MALLA QUE</th>
                                        <th scope='col1'>PORC QUE</th> 
                                        <th scope='col1'>PESO PUL</th> 
                                        <th scope='col1'>PESO MALLA PUL</th> 
                                        <th scope='col1'>PORC PUL</th> 
                                        <th scope='col1'>PESO METODO</th>                                      
                                     
                                        <th scope='col1'>PESO PAYON</th>
                                        <th scope='col1'>PESO DORE</th>
                                        <th scope='col1'>Au_PPM</th>";
        $html_det .= "</tr>
                               </thead>
                               <tbody>";
                               
                               //   <th scope='col1'>INCUARTE</th>   <th scope='col1'>Ag_PPM</th>

        $num = 1;
        while ($fila = $result->fetch_row()) {
            $html_det .= "<tr>";
            $html_det .= "<td>" . $num . "</td>";
            $html_det .= "<td>" . $fila[0] . "</td>";
            $html_det .= "<td>" . $fila[1] . "</td>";
            $html_det .= "<td>" . $fila[2] . "</td>";
            //$html_det .= "<td>" . $fila[3] . "</td>";
            $html_det .= "<td>" . $fila[4] . "</td>";
            $html_det .= "<td>" . $fila[5] . "</td>";
            $html_det .= "<td>" . $fila[6] . "</td>";
            $html_det .= "<td>" . $fila[7] . "</td>";
            $html_det .= "<td>" . $fila[8] . "</td>";
            $html_det .= "<td>" . $fila[9] . "</td>";
            $html_det .= "<td>" . $fila[10] . "</td>";
            $html_det .= "<td>" . $fila[11] . "</td>";
            $html_det .= "<td>" . $fila[12] . "</td>";
            $html_det .= "<td>" . $fila[13] . "</td>";
            $html_det .= "<td>" . $fila[14] . "</td>";
            $html_det .= "<td>" . $fila[15] . "</td>";
            //$html_det .= "<td>" . $fila[16] . "</td>";
            //$html_det .= "<td>" . $fila[17] . "</td>";
            $html_det .= "</tr>";
            $num = $num + 1;
        }
        $html_det .= "</tbody></table>";
        echo ("$html_det");
        ?>
    </div>
</div>
<script>
    jQuery.noConflict();

    function exportar_listado() {
        var metodo_sel = document.getElementById('metodo_id_sel').querySelector(':checked').value;
        var tipo_mues = document.getElementById('tipo_id_sel').querySelector(':checked').value;
        var orden = document.getElementById('caja_orden').value;        
        var fecha_i = document.getElementById('fecha_inicial').value;
        var fecha_f = document.getElementById('fecha_final').value;

        orden = 0
        alert(fecha_i);
        
        alert(fecha_f);
        var exportar = '<?php echo "\ exportar_pesajes_muestras.php?metodo_sel=" ?>' + metodo_sel + '&tipo_mues=' + tipo_mues + '&orden=' + orden + '&fecha_i=' + fecha_i+ '&fecha_f=' + fecha_f;
        window.location.href = exportar;
    }

    function llama_datos() {
        var metodo_sel = document.getElementById('metodo_id_sel').querySelector(':checked').value;
        var tipo_mues = document.getElementById('tipo_id_sel').querySelector(':checked').value;
        var orden = document.getElementById('caja_orden').value;
        var fecha_i = document.getElementById('fecha_inicial').value;
        var fecha_f = document.getElementById('fecha_final').value;
        orden = 0;
        $.ajax({
                url: 'reporte_pesajes_muestras.php',
                type: 'POST',
                dataType: 'html',
                data: {
                    metodo_sel: metodo_sel,
                    tipo_mues: tipo_mues,
                    orden: orden,
                    fecha_i: fecha_i,
                    fecha_f: fecha_f
                },
            })
            .done(function(respuesta) {
                jQuery.noConflict();
                $("#motivos tbody").html(respuesta);
            })
    }
</script>

<script type="text/javascript" src="js/buscar_muestra_ord.js"></script>


<script type="text/javascript" src="js/buscar_muestra.js"></script>