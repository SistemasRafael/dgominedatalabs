<? //include "../connections/config.php";
$u_id = $_SESSION['u_id'];

$date = date('Y-m-d');
$fecha_inicial = date("Y-m-d", strtotime("-1 month"));
$fecha_final = date("Y-m-d");

$orden_recheck = 0;
$muestra = 0;
?>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<script>
    $(document).ready(function() {
        $("#metodo_id_sel").on("change", function() {
            var value = $("#metodo_id_sel option:selected").text().toLowerCase();

            if (value == "todos") {
                value = "";
            }
            console.log(value);
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

<div class="container-fluid">
    <br /><br /><br /><br /><br /><br />
    <?
    $fecha_minima_val = date('d-m-Y');
    $nuevafecha = strtotime($fecha_minima_val);
    $nuevafecha = date('d-m-Y', $nuevafecha);

    ?>

    <div class="col-md-2 col-lg-2">
        <label for="caja_bus_mues"><b>BUSCAR</b></label>
        <input class="search_query form-control" type="text" name="caja_bus_mues" id="caja_bus_mues" autocomplete="off" placeholder="Buscar..."></input>
    </div>
    <br /><br /><br /><br /><br />

    <div class="col-md-1 col-lg-1">
        <label for="fecha_inicial_ex"><b>DESDE</b></label>
        <input type='date' class='form-control' name='fecha_inicial_ex' id='fecha_inicial_ex' value="<? echo $fecha_inicial; ?>" placeholder="<? echo $fecha_final ?>">

    </div>
    <div class="col-md-1 col-lg-1">
        <label for="fecha_final_ex"><b>HASTA</b></label>
        <input type="date" class="form-control" name='fecha_final_ex' id='fecha_final_ex' value="<? echo $fecha_final; ?>" placeholder="<? echo $fecha_final ?>">
    </div>

    <div class="col-md-2 col-lg-2">
        <div class="formulario">
            <label for="caja_orden"><b>ORDEN</b></label>
            <input class="search_query form-control" type="text" name="caja_orden" id="caja_orden" autocomplete="off" placeholder="Orden..."></input>
        </div>
    </div>

    <div class="col-md-2 col-lg-2">
        <label for="caja_muestra"><b>MUESTRA</b></label>
        <input class="search_query form-control" type="text" name="caja_muestra" id="caja_muestra" autocomplete="off" placeholder="Muestra..."></input>
    </div>


    <div class="col-md-2 col-lg-3">
        <label for="print"></label><br /><br />
        <button type='button' class='btn btn-info' name='print' id='print' onclick="llama_datos(<? echo $unidad_id; ?>);"> <span class="fa fa-filter fa-2x"> Filtrar</span> </button>
        <button type='button' class='btn btn-success' name='export' id='export' onclick="exportar_listado(<? echo $unidad_id; ?>)"> <span class="fa fa-file-excel-o fa-2x"> Exportar</span>
        </button>
    </div>
    <br /><br /><br /><br /><br /><br />
    <?php
    $mysqli->set_charset("utf8");

    $query = "CALL arg_rpt_ordenesRecheck('" . $fecha_inicial . "', '" . $fecha_final . "', " . $orden_recheck . ", '" . $muestra . "')";
    mysqli_multi_query($mysqli, $query);

    $result = $mysqli->store_result();

    $html_det = "<table class='table table-striped' id='motivos'>
                                <thead>
                                    <tr class='table-info' justify-content: center;>
                                        <th scope='col1'>No.</th>
                                        <th scope='col1'>FECHA DE ENTREGA ORIG</th> 
                                        <th scope='col1'>FECHA DE RESULTADO ORIG</th> 
                                        <th scope='col1'>MUESTRA</th>
                                        <th scope='col1'>Au ORIG</th> 
                                        <th scope='col1'>Ag ORIG</th>
                                        <th scope='col1'>Au RECH</th> 
                                        <th scope='col1'>Ag RECH</th>                                      
                                        <th scope='col1'>FECHA DE SOLICITUD RECH</th>
                                        <th scope='col1'>FECHA DE RESULTADO RECH</th>";
    $html_det .= "</tr>
                               </thead>
                               <tbody>";

    $num = 1;
    while ($fila = $result->fetch_row()) {
        $html_det .= "<tr>";
        $html_det .= "<td>" . $num . "</td>";
        $html_det .= "<td>" . $fila[0] . "</td>";
        $html_det .= "<td>" . $fila[1] . "</td>";
        $html_det .= "<td>" . $fila[3] . "</td>";
        $html_det .= "<td>" . $fila[4] . "</td>";
        $html_det .= "<td>" . $fila[5] . "</td>";

        $html_det .= "<td>" . $fila[6] . "</td>";
        $html_det .= "<td>" . $fila[7] . "</td>";
        $html_det .= "<td>" . $fila[8] . "</td>";

        $html_det .= "<td>" . $fila[9] . "</td>";
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
        var fecha_inicial_ex = document.getElementById('fecha_inicial_ex').value;
        var fecha_final_ex = document.getElementById('fecha_final_ex').value;
        var tipo_mues = document.getElementById('caja_muestra').value;
        var orden = document.getElementById('caja_orden').value;
        if (orden == '') {
            orden = 0
        };
        if (tipo_mues == '') {
            tipo_mues = "";
        };
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
        var exportar = '<?php echo "\ export_ordenes_recheck.php?fecha_inicial_ex=" ?>' + fecha_inicial_ex + '&fecha_final_ex=' +
            fecha_final_ex + '&orden=' + orden + '&tipo_muestra=' + tipo_mues;
        window.location.href = exportar;
    }

    function llama_datos() {
        var fecha_inicial_ex = document.getElementById('fecha_inicial_ex').value;
        var fecha_final_ex = document.getElementById('fecha_final_ex').value;
        var tipo_mues = document.getElementById('caja_muestra').value;
        var orden = document.getElementById('caja_orden').value;
        if (orden == '') {
            orden = 0
        };
        if (tipo_mues == '') {
            tipo_mues = ""
        };
        const date = new Date();

        let day = date.getDate();
        let month = date.getMonth() + 1;
        let year = date.getFullYear();

        if (fecha_inicial_ex == '') {
            fecha_inicial_ex = "2023-01-01";
        }
        if (fecha_final_ex == '') {
            fecha_final_ex = `${year}-${month}-${day}`;
        }
        $.ajax({
            url: 'datos_ordenes_recheck.php',
            type: 'POST',
            dataType: 'html',
            data: {
                fecha_inicial_ex: fecha_inicial_ex,
                fecha_final_ex: fecha_final_ex,
                tipo_mues: tipo_mues,
                orden: orden
            },
            success: function(html) {
                $("#motivos tbody").html(html);
            }
        })
    }
</script>

<script type="text/javascript" src="js/buscar_muestra_ord.js"></script>


<script type="text/javascript" src="js/buscar_muestra.js"></script>