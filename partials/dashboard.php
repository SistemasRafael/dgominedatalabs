<? //include "connections/config.php";

$unidad_id = $_GET['unidad_id'];
$_SESSION['unidad_id'] = $unidad_id;

$datos_preparacion = $mysqli->query(
    "SELECT 
                                                    tm.fase_id, etapa_id AS fase,  etapa, SUM(cantidad) AS total
                                                FROM
                                                   total_muestras_preparacion tm
                                                   LEFT JOIN arg_fases fa
                                                   		ON tm.fase_id = fa.fase_id
                                                GROUP BY
                                                	tm.etapa, etapa_id, tm.fase_id
                                                ORDER BY
                                                    tm.fase_id"
) or die(mysqli_error($mysqli));

$datos_preparacion_tot = $mysqli->query(
    "SELECT (SELECT SUM(cantidad)
                                                FROM
                                                   total_muestras_preparacion) + 
            (SELECT SUM(cantidad)
                                                FROM
                                                   total_muestras_preparacionree) 
            as total_fase"
) or die(mysqli_error($mysqli));
$total_prep = $datos_preparacion_tot->fetch_assoc();
$total_prepa  = $total_prep['total_fase'];
$datos_fases = $mysqli->query(
    "SELECT 
                                                    nombre, fase_id
                                                FROM
                                                   arg_fases
                                                   GROUP BY
                                                   nombre, fase_id"
) or die(mysqli_error($mysqli));
$datos_fases_re = $mysqli->query(
    "SELECT 
                                                    nombre, fase_id
                                                FROM
                                                   arg_fases
                                                GROUP BY
                                                	nombre, fase_id"
) or die(mysqli_error($mysqli));

$datos_preparacion_normal = $mysqli->query(
    "SELECT 
                                                tm.fase_id, etapa_id, fa.nombre AS fase,  etapa, SUM(cantidad) AS total_fase
                                                FROM
                                                   total_muestras_preparacion tm
                                                   LEFT JOIN arg_fases fa
                                                   		ON tm.fase_id = fa.fase_id
                                                GROUP BY
                                                	tm.etapa, etapa_id, fa.nombre, tm.fase_id"
) or die(mysqli_error($mysqli));
$total_prepa_no      = $datos_preparacion_normal->fetch_assoc();
$total_prepa_normal  = $total_prepa_no['total_fase'];

$datos_preparacion_ree = $mysqli->query(
    "SELECT 
                                                    tm.fase_id, etapa_id  AS fase,  etapa, SUM(cantidad) AS total
                                                FROM
                                                   total_muestras_preparacionree tm
                                                   LEFT JOIN arg_fases fa
                                                   		ON tm.fase_id = fa.fase_id
                                                GROUP BY
                                                	etapa_id, etapa, tm.fase_id
                                                ORDER BY
                                                    tm.fase_id"
) or die(mysqli_error($mysqli));
$datos_preparacion_reensaye = $mysqli->query(
    "SELECT 
                                                tm.fase_id, etapa_id, fa.nombre AS fase,  etapa, SUM(cantidad) AS total_fase
                                                FROM
                                                   total_muestras_preparacionree tm
                                                   LEFT JOIN arg_fases fa
                                                   		ON tm.fase_id = fa.fase_id
                                                GROUP BY
                                                	etapa, etapa_id, fa.nombre, tm.fase_id"
) or die(mysqli_error($mysqli));

$total_prepa_re   = $datos_preparacion_reensaye->fetch_assoc();
$total_prepa_ree  = $total_prepa_re['total_fase'];

$muestras_unidad2  = $mysqli->query("SELECT nombre, cantidad, tipo_nombre FROM `dash_unidades_muestras` WHERE tipo = 0 AND unidad_id = 2") or die(mysqli_error($mysqli));
$total_mues_sa     = $muestras_unidad2->fetch_assoc();
$total_muestras_sa = $total_mues_sa['cantidad'];



?>

<body>

    <!-- BS JavaScript -->
    <!-- Have fun using Bootstrap JS -->

    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">

    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">

    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">--!>
<!-- 2. GOOGLE JQUERY JS v3.2.1  JS --!>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- 3. BOOTSTRAP v4.0.0         JS --!>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js">


<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">  -->


    <style type="text/css">
        .izq {
            background-color: #455A64;
        }

        .derecha {
            background-color: #455A64;
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

        .float-container {
            padding: 20px;
        }

        .float-child-left {
            width: 50%;
            float: left;
            padding: 20px;
        }

        img {
            max-width: 20%;
        }

        body {
            height: 100%;
            overflow-x: hidden;
        }

        .card {
            z-index: 0;
            padding-bottom: 20px;
            margin-top: 10px;
            margin-bottom: 30px;
            border-radius: 10px;
        }

        .top {
            padding-top: 10px;
            padding-left: 1% !important;
            padding-right: 1% !important;
        }

        /*Icon progressbar*/
        #progressbar {
            margin-bottom: 60px;
            overflow: hidden;
            color: #455A64;
            padding-left: 0px;
            margin-top: 30px;
        }

        #progressbar li {
            list-style-type: none;
            font-size: 13px;
            width: 25%;
            float: left;
            position: relative;
            font-weight: 400;
        }

        #progressbar .step0:before {
            font-family: FontAwesome;
            content: "\f10c";
            color: #fff;
        }

        #progressbar li:before {
            width: 40px;
            height: 40px;
            line-height: 45px;
            display: block;
            font-size: 20px;
            background: #C5CAE9;
            border-radius: 50%;
            margin: auto;
            padding: 0px;
        }

        /*ProgressBar connectors*/
        #progressbar li:after {
            content: '';
            width: 100%;
            height: 12px;
            background: #C5CAE9;
            position: absolute;
            left: 0;
            top: 16px;
            z-index: -1;
        }

        /*Color number of the step and the connector before it*/


        .icon {
            width: 60px;
            height: 60px;
            margin-right: 45px;
        }

        .icon-content {
            padding-bottom: 20px;
        }

        @media screen and (max-width: 992px) {
            .icon-content {
                width: 50%;
            }
        }

        .preparacion {
            background-color: #C0C0C0;
            filter: saturate(75%);
            border-radius: 8px;
            padding: 5px;
            padding-left: 1%;
            margin-top: 10px;
            color: black;
            border: 1px solid #808080;
        }

        .ensayefuego {
            background-color: #FA8072;
            filter: saturate(75%);
            border-radius: 8px;
            padding: 5px;
            padding-left: 1%;
            margin-top: 10px;
            color: black;
            border: 1px solid #808080;
        }

        .absorcion {
            background-color: #ADFF2F;
            filter: saturate(75%);
            border-radius: 8px;
            padding: 5px;
            padding-left: 1%;
            margin-top: 10px;
            color: black;
            border: 1px solid #808080;
        }


        .control {
            background-color: #FFC0CB;
            filter: saturate(75%);
            border-radius: 8px;
            padding: 5px;
            padding-left: 1%;
            margin-top: 10px;
            color: black;
            border: 1px solid #808080;
        }

        .mecanica {
            background-color: #CD853F;
            filter: saturate(75%);
            border-radius: 8px;
            padding: 5px;
            padding-left: 1%;
            margin-top: 10px;
            color: black;
            border: 1px solid #808080;
        }


        .viahumeda {
            background-color: #6495ED;
            filter: saturate(75%);
            border-radius: 8px;
            padding: 5px;
            padding-left: 1%;
            margin-top: 10px;
            color: black;
            border: 1px solid #808080;
        }


        .cianuracion {
            background-color: #FFA500;
            filter: saturate(75%);
            border-radius: 8px;
            padding: 5px;
            padding-left: 1%;
            margin-top: 10px;
            color: black;
            border: 1px solid #808080;
        }

        .default {
            background-color: #887dc4;
            filter: saturate(75%);
            border-radius: 8px;
            padding: 5px;
            padding-left: 1%;
            margin-top: 10px;
            color: black;
            border: 1px solid #808080;
        }


        .right-child {
            float: right;
        }
    </style>

    <script>
        function llama_datos(metodo_id, trn_id_batch, fase) {
            var metodo_id = metodo_id;
            var trn_batch = trn_id_batch;
            var fase_id = fase;
            $.ajax({
                    url: 'datos_fases_muestras.php',
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        metodo_id: metodo_id,
                        trn_batch: trn_batch,
                        fase_id: fase_id
                    },
                })
                .done(function(respuesta) {
                    // alert(respuesta);
                    jQuery.noConflict();
                    $('#fases_modal').modal('show');
                    $("#datos_fases").html(respuesta);

                })
        }

        function grafico_widgetlc() {
            $.ajax({
                url: 'grafico_mensual.php',
                type: 'POST'
            }).done(function(resp) {
                //alert(resp);
                var titulo = [];
                var cantidad = [];
                // var items = json_decode(resp, true);
                //lista = items['mes']['cantidad_muestras'];
                //   alert(titulo);
                var mydata = JSON.parse(resp);
                //alert(mydata[0].mes);
                //alert(mydata[0].cantidad_muestras);

                for (var l = 0; l < mydata.length; l++) {
                    titulo.push(mydata[l]['mes']);
                    cantidad.push(mydata[l]['cantidad_muestras']);
                }

                var ctxwid = document.getElementById("widgetChart1s");
                ctxwid.height = 150;
                var myChartWid = new Chart(ctxwid, {
                    type: 'line',
                    data: {
                        labels: titulo,
                        type: 'line',
                        datasets: [{
                            data: cantidad,
                            label: 'Muestras',
                            backgroundColor: 'transparent',
                            borderColor: 'rgba(255,255,255,.55)',
                        }]
                    },
                    options: {

                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        responsive: true,
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    color: 'transparent',
                                    zeroLineColor: 'transparent'
                                },
                                ticks: {
                                    fontSize: 2,
                                    fontColor: 'transparent'
                                }
                            }],
                            yAxes: [{
                                display: false,
                                ticks: {
                                    display: false,
                                }
                            }]
                        },
                        title: {
                            display: false,
                        },
                        elements: {
                            line: {
                                borderWidth: 1
                            },
                            point: {
                                radius: 12,
                                hitRadius: 5,
                                hoverRadius: 12
                            }
                        }
                    }
                })
            })
        };
    </script>

    
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="fases_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable" style="max-width:850px!important;" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="humedad">DETALLE DE FASE Y ETAPAS</h5>                   
              </div>
            
               <div class="modal-body" style="font-size:5px;" id="datos_fases">
               </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button></div>
            </div>
    </div>              
</div>
 
    
   <?
    $muestraspien = array();
    $muestraspiec = array();

    $muestras_unidad1  = $mysqli->query("SELECT nombre, cantidad, tipo_nombre FROM `dash_unidades_muestras` WHERE tipo = 0 AND unidad_id = 1") or die(mysqli_error($mysqli));
    $total_mues_lc     = $muestras_unidad1->fetch_assoc();
    $total_muestras_lc = $total_mues_lc['cantidad'];

    $muestras_unidad2  = $mysqli->query("SELECT nombre, cantidad, tipo_nombre FROM `dash_unidades_muestras` WHERE tipo = 0 AND unidad_id = 2") or die(mysqli_error($mysqli));
    $total_mues_sa     = $muestras_unidad2->fetch_assoc();
    $total_muestras_sa = $total_mues_sa['cantidad'];

    $muestras_unidad3  = $mysqli->query("SELECT nombre, cantidad, tipo_nombre FROM `dash_unidades_muestras` WHERE tipo = 0 AND unidad_id = 3") or die(mysqli_error($mysqli));
    $total_mues_ec     = $muestras_unidad3->fetch_assoc();
    $total_muestras_ec = $total_mues_ec['cantidad'];

    $muestras_unidad0   = $mysqli->query("SELECT nombre, cantidad, tipo_nombre FROM `dash_unidades_muestras` WHERE tipo = 0 AND unidad_id = 0") or die(mysqli_error($mysqli));
    $total_mues_all     = $muestras_unidad0->fetch_assoc();
    $total_muestras_all = $total_mues_all['cantidad'];

    $muestras_unidad2_m  = $mysqli->query("SELECT SUM(cantidad) AS total_sa_mes FROM `dash_recibidas_dia` WHERE MONTH(STR_TO_DATE(fecha, '%d-%m-%Y')) = MONTH(NOW())") or die(mysqli_error($mysqli));
    $muestras_unidad2_me     = $muestras_unidad2_m->fetch_assoc();
    $muestras_unidad2_mes = $muestras_unidad2_me['total_sa_mes'];



    $metodoEFAA30u1 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 3 AND ord.unidad_id = 1") or die(mysqli_error($mysqli));
    $metodoEFAA30u2 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 3 AND ord.unidad_id = 2") or die(mysqli_error($mysqli));
    $metodoEFAA30u3 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 3 AND ord.unidad_id = 3") or die(mysqli_error($mysqli));
    $metodoEFAA30u1_ec = $metodoEFAA30u1->fetch_assoc();
    $metodoEFAA30u1_all = $metodoEFAA30u1_ec['cant'];

    $metodoEFAA30u2_ec = $metodoEFAA30u2->fetch_assoc();
    $metodoEFAA30u2_all = $metodoEFAA30u2_ec['cant'];
    $metodoEFAA30u3_ec = $metodoEFAA30u3->fetch_assoc();
    $metodoEFAA30u3_all = $metodoEFAA30u3_ec['cant'];
    //if ($metodoEFAA30u1_all > 0) {
    //    $muestraspiec[] = $metodoEFAA30u1_all;
    //    $muestraspien[] = "EFAA30 La Colorada";
    //}
    //if ($metodoEFAA30u2_all > 0) {
    //    $muestraspiec[] = $dia_pro;
    //    $muestraspien[] = "EFAA30 San Agustin";
    //}
    //if ($metodoEFAA30u3_all > 0) {
    //    $muestraspiec[] = $metodoEFAA30u3_all;
    //    $muestraspien[] = "EFAA30 El Castillo";
    //}

    $metodoHUMu1 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 4 AND ord.unidad_id = 1") or die(mysqli_error($mysqli));
    $metodoHUMu2 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 4 AND ord.unidad_id = 2") or die(mysqli_error($mysqli));
    $metodoHUMu3 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 4 AND ord.unidad_id = 3") or die(mysqli_error($mysqli));
    $metodoHUMu1_ec = $metodoHUMu1->fetch_assoc();
    $metodoHUMu1_all = $metodoHUMu1_ec['cant'];
    $metodoHUMu2_ec = $metodoHUMu2->fetch_assoc();
    $metodoHUMu2_all = $metodoHUMu2_ec['cant'];
    $metodoHUMu3_ec = $metodoHUMu3->fetch_assoc();
    $metodoHUMu3_all = $metodoHUMu3_ec['cant'];
    //if ($metodoHUMu1_all > 0) {
    //    $muestraspiec[] = $metodoHUMu1_all;
    //    $muestraspien[] = "HUM La Colorada ";
    //}
    //if ($metodoHUMu2_all > 0) {
    //    $muestraspiec[] = $metodoHUMu2_all;
    //    $muestraspien[] = "HUM San Agustin ";
    //}
    //if ($metodoHUMu2_all > 0) {
    //    $muestraspiec[] = $metodoHUMu2_all;
    //    $muestraspien[] = "HUM El Castillo ";
    //}

    $metodoVHAAAgu1 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 6 AND ord.unidad_id = 1") or die(mysqli_error($mysqli));
    $metodoVHAAAgu2 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 6 AND ord.unidad_id = 2") or die(mysqli_error($mysqli));
    $metodoVHAAAgu3 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 6 AND ord.unidad_id = 3") or die(mysqli_error($mysqli));
    $metodoVHAAAgu1_ec = $metodoVHAAAgu1->fetch_assoc();
    $metodoVHAAAgu1_all = $metodoVHAAAgu1_ec['cant'];
    $metodoVHAAAgu2_ec = $metodoVHAAAgu2->fetch_assoc();
    $metodoVHAAAgu2_all = $metodoVHAAAgu2_ec['cant'];
    $metodoVHAAAgu3_ec = $metodoVHAAAgu3->fetch_assoc();
    $metodoVHAAAgu3_all = $metodoVHAAAgu3_ec['cant'];
    //if ($metodoVHAAAgu1_all > 0) {
    //    $muestraspiec[] = $metodoVHAAAgu1_all;
    //    $muestraspien[] = "VHAAAg La Colorada";
    //}
    //if ($metodoVHAAAgu2_all > 0) {
    //    $muestraspiec[] = $metodoVHAAAgu2_all;
    //    $muestraspien[] = "VHAAAg San Agustin";
    //}
    //if ($metodoVHAAAgu3_all > 0) {
    //    $muestraspiec[] = $metodoVHAAAgu3_all;
    //    $muestraspien[] = "VHAAAg El Castillo";
    //}

    $metodoVHAACuu1 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 7 AND ord.unidad_id = 1") or die(mysqli_error($mysqli));
    $metodoVHAACuu2 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 7 AND ord.unidad_id = 2") or die(mysqli_error($mysqli));
    $metodoVHAACuu3 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 7 AND ord.unidad_id = 3") or die(mysqli_error($mysqli));
    $metodoVHAACuu1_ec = $metodoVHAACuu1->fetch_assoc();
    $metodoVHAACuu1_all = $metodoVHAACuu1_ec['cant'];
    $metodoVHAACuu2_ec = $metodoVHAACuu2->fetch_assoc();
    $metodoVHAACuu2_all = $metodoVHAACuu2_ec['cant'];
    $metodoVHAACuu3_ec = $metodoVHAACuu3->fetch_assoc();
    $metodoVHAACuu3_all = $metodoVHAACuu3_ec['cant'];
    //if ($metodoVHAACuu1_all > 0) {
    //    $muestraspiec[] = $metodoVHAACuu1_all;
    //    $muestraspien[] = "VHAACu La Colorada";
    //}
    //if ($metodoVHAACuu2_all > 0) {
    //    $muestraspiec[] = $metodoVHAACuu2_all;
    //    $muestraspien[] = "VHAACu San Agustin";
    //}
    //if ($metodoVHAACuu3_all > 0) {
    //    $muestraspiec[] = $metodoVHAACuu3_all;
    //    $muestraspien[] = "VHAACu El Castillo";
    //}

    $metodoCNAAAuu1 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 11 AND ord.unidad_id = 1") or die(mysqli_error($mysqli));
    $metodoCNAAAuu2 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 11 AND ord.unidad_id = 2") or die(mysqli_error($mysqli));
    $metodoCNAAAuu3 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 11 AND ord.unidad_id = 3") or die(mysqli_error($mysqli));
    $metodoCNAAAuu1_ec = $metodoCNAAAuu1->fetch_assoc();
    $metodoCNAAAuu1_all = $metodoCNAAAuu1_ec['cant'];
    $metodoCNAAAuu2_ec = $metodoCNAAAuu2->fetch_assoc();
    $metodoCNAAAuu2_all = $metodoCNAAAuu2_ec['cant'];
    $metodoCNAAAuu3_ec = $metodoCNAAAuu3->fetch_assoc();
    $metodoCNAAAuu3_all = $metodoCNAAAuu3_ec['cant'];
    //if ($metodoCNAAAuu1_all > 0) {
    //    $muestraspiec[] = $metodoCNAAAuu1_all;
    //    $muestraspien[] = "CNAAAu La Colorada";
    //}
    //if ($metodoCNAAAuu2_all > 0) {
    //    $muestraspiec[] = $metodoCNAAAuu2_all;
    //    $muestraspien[] = "CNAAAu San Agustin";
    //}
    //if ($metodoCNAAAuu3_all > 0) {
    //    $muestraspiec[] = $metodoCNAAAuu3_all;
    //    $muestraspien[] = "CNAAAu El Castillo";
    //}

    $metodoCNAACuu1 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 13 AND ord.unidad_id = 1") or die(mysqli_error($mysqli));
    $metodoCNAACuu2 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 13 AND ord.unidad_id = 2") or die(mysqli_error($mysqli));
    $metodoCNAACuu3 = $mysqli->query("SELECT COUNT(metodo_id) as cant FROM arg_ordenes_bitacora orb JOIN arg_ordenes ord ON orb.trn_id_rel = ord.trn_id_rel WHERE orb.metodo_id = 13 AND ord.unidad_id = 3") or die(mysqli_error($mysqli));
    $metodoCNAACuu1_ec = $metodoCNAACuu1->fetch_assoc();
    $metodoCNAACuu1_all = $metodoCNAACuu1_ec['cant'];
    $metodoCNAACuu2_ec = $metodoCNAACuu2->fetch_assoc();
    $metodoCNAACuu2_all = $metodoCNAACuu2_ec['cant'];
    $metodoCNAACuu3_ec = $metodoCNAACuu3->fetch_assoc();
    $metodoCNAACuu3_all = $metodoCNAACuu3_ec['cant'];
    if ($metodoCNAACuu1_all > 0) {
        $muestraspieu1c[] = $metodoCNAACuu1_all;
        $muestraspieu1n[] = "CNAACu La Colorada";
    }
    if ($metodoCNAACuu2_all > 0) {
        $muestraspieu2c[] = $metodoCNAACuu2_all;
        $muestraspieu2n[] = "CNAACu San Agustin";
    }
    if ($metodoCNAACuu3_all > 0) {
        $muestraspieu3c[] = $metodoCNAACuu3_all;
        $muestraspieu3n[] = "CNAACu El Castillo";
    }

    $lacoloradaensayes =  $mysqli->query("SELECT COUNT(*) as cant FROM arg_ordenes WHERE trn_id_rel = 0 AND unidad_id = 1") or die(mysqli_error($mysqli));
    $lacoloradaensayes_ec = $lacoloradaensayes->fetch_assoc();
    $lacoloradaensayes_all = $lacoloradaensayes_ec['cant'];
    $sanagustinensayes =  $mysqli->query("SELECT SUM(od.cantidad) as cant 
    FROM arg_ordenes o
    LEFT JOIN arg_ordenes_detalle od
      ON o.trn_id = od.trn_id_rel
    WHERE 
      o.trn_id_rel = 0 
      AND od.estado <> 99                                            
      AND YEAR(o.fecha) = YEAR(CURDATE())
      AND unidad_id = 2 group by MONTH(o.fecha)") or die(mysqli_error($mysqli));
    $elcastilloensayes =  $mysqli->query("SELECT COUNT(*) as cant FROM arg_ordenes WHERE trn_id_rel = 0 AND unidad_id = 3") or die(mysqli_error($mysqli));
    $elcastilloensayes_ec = $elcastilloensayes->fetch_assoc();
    $elcastilloensayes_all = $elcastilloensayes_ec['cant'];
    $lacoloradareensayes =  $mysqli->query("SELECT COUNT(*) as cant FROM arg_ordenes WHERE trn_id_rel <> 0 AND unidad_id = 1") or die(mysqli_error($mysqli));
    $lacoloradareensayes_ec = $lacoloradareensayes->fetch_assoc();
    $lacoloradareensayes_all = $lacoloradareensayes_ec['cant'];
    $sanagustinreensayes =  $mysqli->query("SELECT SUM(od.cantidad) as cant 
    FROM arg_ordenes o
    LEFT JOIN arg_ordenes_detalle od
      ON o.trn_id = od.trn_id_rel
    WHERE 
      o.trn_id_rel <> 0 
      AND od.estado <> 99
      
      AND YEAR(o.fecha) = YEAR(CURDATE())
      AND unidad_id = 2 
      group by MONTH(o.fecha)") or die(mysqli_error($mysqli));
    $elcastilloreensayes =  $mysqli->query("SELECT COUNT(*) as cant FROM arg_ordenes WHERE trn_id_rel <> 0 AND unidad_id = 3") or die(mysqli_error($mysqli));
    $elcastilloreensayes_ec = $elcastilloreensayes->fetch_assoc();
    $elcastilloreensayes_all = $elcastilloreensayes_ec['cant'];

    $multitip = $mysqli->query("SELECT 
    fecha, SUM(muestras_recibidas) AS muestras_procesadas, SUM(reens_oro) AS reensayes_oro, SUM(reens_plata) AS reensayes_plata
    FROM `resultado_semanal`
    GROUP BY fecha;") or die(mysqli_error($mysqli));

    $procesos_mensual_sa = array();

    $multitip_n = array();
    $multitip_c = array();

    while ($sanagustinensayes_ec = $sanagustinensayes->fetch_assoc()) {
        $sanagustinensayes_all = $sanagustinensayes_ec['cant'];
        $procesos_mensual_sa[] = $sanagustinensayes_all;
    }

    while ($sanagustinreensayes_ec = $sanagustinreensayes->fetch_assoc()) {
        $sanagustinreensayes_all = $sanagustinreensayes_ec['cant'];
        $procesos_mensual_sa_ree[] = $sanagustinreensayes_all;
    }

    while ($multi = $multitip->fetch_assoc()) {
        $multitip_n[] = $multi['fecha'];
        $multitip_n[] = "Reensayes oro";
        $multitip_n[] = "Reensayes plata";
        $multitip_c[] = $multi['muestras_procesadas'];
        $multitip_c[] = $multi['reensayes_oro'];
        $multitip_c[] = $multi['reensayes_plata'];
    }

    //Diario
    $dia    = $mysqli->query("SELECT SUM(cantidad) total_diario FROM `dash_recibidas_dia` WHERE  fecha = (date_format(curdate(), '%d-%m-%Y'))") or die(mysqli_error($mysqli));
    $dia_to = $dia->fetch_assoc();
    $dia_total_rec = $dia_to['total_diario'];
    $fecha = "04-07-2022";
    $dia_l    = $mysqli->query("SELECT SUM(total_lib) AS total_dia_liberadas FROM `liberadas_mes` WHERE date_format(fecha, '%d-%m-%Y')  = DATE_FORMAT(now(), '%d-%m-%Y')") or die(mysqli_error($mysqli));
    $dia_li = $dia_l->fetch_assoc();
    $dia_liberadas = $dia_li['total_dia_liberadas'];

    $dia_reen    = $mysqli->query("SELECT SUM(od.cantidad) as total_dia_reensayes 
                                          FROM arg_ordenes o
                                          LEFT JOIN arg_ordenes_detalle od
                                            ON o.trn_id = od.trn_id_rel
                                          WHERE 
                                            o.trn_id_rel <> 0 
                                            AND od.estado <> 99
                                            AND date_format(o.fecha, '%d-%m-%Y') = date_format(curdate(), '%d-%m-%Y')
                                            AND unidad_id = 2") or die(mysqli_error($mysqli));
    $dia_reens = $dia_reen->fetch_assoc();
    $dia_reens_hoy = $dia_reens['total_dia_reensayes'];
    
    $dia_reen_mensual    = $mysqli->query("SELECT SUM(od.cantidad) as total_mensual_reensayes 
                                          FROM arg_ordenes o
                                          LEFT JOIN arg_ordenes_detalle od
                                            ON o.trn_id = od.trn_id_rel
                                          WHERE 
                                            o.trn_id_rel <> 0 
                                            AND od.estado <> 99
                                            
                                            AND YEAR(o.fecha) = YEAR(CURDATE())
                                            AND MONTH(o.fecha) =   MONTH(CURDATE())
                                            AND unidad_id = 2") or die(mysqli_error($mysqli));
    $dia_reen_mens = $dia_reen_mensual->fetch_assoc();
    $total_reen_mensu = $dia_reen_mens['total_mensual_reensayes'];

    $dia_pro    = $mysqli->query("SELECT SUM(od.cantidad) as total_dia_proceso_oro
                                              FROM
                                                   ordenes_metodos od
                                                   
                                              WHERE 
                                                    buscar_etapa(od.trn_id,3) < 11 
                                                    and  estado <> 99") or die(mysqli_error($mysqli));
    $dia_proc = $dia_pro->fetch_assoc();
    $dia_proceso = $dia_proc['total_dia_proceso_oro'];

    $dia_pro_pl    = $mysqli->query("SELECT SUM(od.cantidad) as total_dia_proceso_plata
                                              FROM
                                                   ordenes_metodos od
                                                   
                                              WHERE 
                                                    buscar_etapa(od.trn_id,6) < 11 
                                                    and  estado <> 99") or die(mysqli_error($mysqli));
    $dia_proc_pl = $dia_pro_pl->fetch_assoc();
    $dia_proceso_pla = $dia_proc_pl['total_dia_proceso_plata'];

    if ($dia_proceso > 0) {
        $muestraspiec[] = $dia_proceso;
        $muestraspien[] = "Total Proceso Oro";
    }
    if ($dia_proceso_pla > 0) {
        $muestraspiec[] = $dia_proceso_pla;
        $muestraspien[] = "Total Proceso Plata";
    }
    setlocale(LC_ALL, "es_ES");

    $hoy = date('d/m/Y');
    $mes = (strftime("%B"));
    ?>
    <!-->
    <nav class="nav nav-tabs">
        <a class="nav-item nav-link active" data-toggle="tab" href="#home">Dashboard</a>
        <a class="nav-item nav-link" data-toggle="tab" href="#menu1">Batchs / Ordenes de Trabajo</a>
        <a class="nav-item nav-link" data-toggle="tab" href="#menu3">Fases y Etapas</a>
    </nav>

    <div class="tab-content">

        <div id="home" class="tab-pane fade show active">

            <div class="breadcrumbs">

                <div class="page-header float-left">
                    <div class="page-title">
                        <h3>Muestras de Hoy:
                            <? echo $hoy; ?>
                        </h3>
                    </div>
                </div>

                <div class="col-sm-1">
                </div>

                <div class="page-header float-center">
                    <div class="page-title">
                        <h3>Muestras del Mes:
                            <? echo $mes; ?>
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <br />
                <div class="row">
                    <br /> <br /> <br /> <br />
                    <div class="col-xl-3 col-lg-3">
                        <div class="card bg-info">
                            <div class="card-body" class="text-secondary success-black">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="fa fa-flask text-warning border-warning"></i>
                                    </div>
                                    <div class="stat-content dib">
                                        <div class="stat-text text-warning stat-digit text-bg">MUESTRAS RECIBIDAS</div>
                                        <div class="count stat-digit text-warning text-bg">
                                            <? echo $dia_total_rec; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-success">
                            <div class="card-body">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="fa fa-check text-white border-white"></i></div>
                                    <div class="stat-content dib">
                                        <div class="stat-text stat-digit text-white text-bg">MUESTRAS LIBERADAS</div>
                                        <div class="count stat-digit text-white text-bg">
                                            <? echo $dia_liberadas ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="fa fa-repeat text-danger border-danger"></i>
                                    </div>
                                    <div class="stat-content dib">
                                        <div class="stat-text stat-digit text-danger text-bg"> REENSAYES</div>
                                        <div class="count stat-digit text-danger text-bg">
                                            <? echo $dia_reens_hoy ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-warning">
                            <div class="card-body">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="fa fa-refresh text-secondary border-secondary"></i></div>
                                    <div class="stat-content dib">
                                        <div class="stat-text stat-digit text-secondary"> MUESTRAS EN PROCESO</div>
                                        <div class="count stat-digit text-secondary text-bg">
                                            <? echo $dia_proceso + $dia_proceso_pla; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--Fin Tarjetas Dia --!>
                    
        <?
        $total_reensayes_glo = $mysqli->query("SELECT cantidad AS total_reensayes FROM `dash_unidades_muestras` WHERE unidad_id = 0 AND tipo = 1") or die(mysqli_error($mysqli));
        $total_reensayes_mes = $total_reensayes_glo->fetch_array(MYSQLI_ASSOC);
        $total_reensayes = $total_reensayes_mes['total_reensayes'];

        $total_liberadas_mes = $mysqli->query("SELECT COUNT(*) as total_liberadas
                                                    FROM
                                                            `arg_ordenes_bitacora_detalle` bd
                                                            LEFT JOIN arg_muestras_resultados mr
                                                                ON bd.trn_id_rel = mr.trn_id
                                                                AND bd.metodo_id = mr.metodo_id
                                                    WHERE
                                                            etapa_id = 11
                                                            AND MONTH(bd.fecha) = MONTH(CURDATE()) ") or die(mysqli_error($mysqli));
        $total_liber_mes = $total_liberadas_mes->fetch_array(MYSQLI_ASSOC);
        $total_lib_mes = $total_liber_mes['total_liberadas'];

        $total_proceso_mes = $mysqli->query("SELECT COUNT(*) as total_proceso
                                                    FROM
                                                            `arg_ordenes_bitacora_detalle` bd
                                                    WHERE
                                                            etapa_id <> 11
                                                            AND etapa_id > 3
                                                            AND MONTH(bd.fecha) = MONTH(CURDATE()) ") or die(mysqli_error($mysqli));
        $total_proc_mes = $total_proceso_mes->fetch_array(MYSQLI_ASSOC);
        $total_pro_mes = $total_proc_mes['total_proceso'];


        ?>   
        <!-->
                    <div class="col-xl-3 col-lg-3">
                        <div class="card bg-info">
                            <div class="card-body">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="fa fa-flask text-warning border-warning"></i>
                                    </div>
                                    <div class="stat-content dib">
                                        <div class="stat-text stat-digit text-warning text-bg">MUESTRAS RECIBIDASS </div>
                                        <div class="count stat-digit text-warning text-bg">
                                            <? echo $muestras_unidad2_mes; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-success">
                            <div class="card-body">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="fa fa-check text-white border-white"></i></div>
                                    <div class="stat-content dib">
                                        <div class="stat-text stat-digit text-white text-bg">MUESTRAS LIBERADAS</div>
                                        <h1>
                                            <div class="count stat-digit text-white text-bg">
                                                <? echo $total_lib_mes; ?>
                                            </div>
                                        </h1>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="fa fa-repeat text-danger border-danger"></i>
                                    </div>
                                    <div class="stat-content dib">
                                        <div class="stat-text stat-digit text-danger text-bg"> REENSAYESS </div>
                                        <div class="count stat-digit text-danger text-bg">
                                            <? echo $total_reen_mensu;//$total_reensayes; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-warning">
                            <div class="card-body ">
                                <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="fa fa-refresh text-secondary border-secondary"></i></div>
                                    <div class="stat-content dib">
                                        <div class="stat-text stat-digit text-secondary text-bg"> MUESTRAS EN PROCESO
                                        </div>
                                        <div class="count stat-digit text-secondary text-bg">
                                            <? echo $dia_proceso + $dia_proceso_pla; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Fin de mes tarjetas del mes --!>
                 
                 
            <!-- Grafico --!>    
                <div class="col-sm-5 col-lg-5">   
                    <div class="chart-wrapper px-1">  
                        <canvas id="myChartDia" width="400" height="400"></canvas>
                    </div>
                 </div>      
                    
        <!-->
                    <div class="col-sm-9">

                        <div class="col-sm-12">
                            <div class="page-header float-left">
                                <div class="page-title">
                                    <h3>Muestras Procesadas en el Mes por Unidad de Mina</h3>
                                </div>
                            </div>
                        </div>
                        <br />
                        <br />
                        <br />

                        <?
                        $t_ens_lc = $mysqli->query("SELECT cantidad AS total_ensayes_lc FROM `dash_unidades_muestras` WHERE unidad_id = 1 AnD tipo = 0") or die(mysqli_error($mysqli));
                        $to_ens_lc = $t_ens_lc->fetch_array(MYSQLI_ASSOC);
                        $tot_ens_lc = $to_ens_lc['total_ensayes_lc'];

                        $t_rens_lc = $mysqli->query("SELECT cantidad AS total_reensayes_lc FROM `dash_unidades_muestras` WHERE unidad_id = 1 AnD tipo = 1") or die(mysqli_error($mysqli));
                        $to_reens_lc = $t_rens_lc->fetch_array(MYSQLI_ASSOC);
                        $to_reensaye_lc = $to_reens_lc['total_reensayes_lc'];

                        $t_lc = $mysqli->query("SELECT cantidad AS total_lc FROM `dash_unidades_muestras` WHERE unidad_id = 1 AnD tipo = -1") or die(mysqli_error($mysqli));
                        $to_lc = $t_lc->fetch_array(MYSQLI_ASSOC);
                        $total_lc = $to_lc['total_lc'];

                        $t_ens_sa = $mysqli->query("SELECT cantidad AS total_ensayes_sa FROM `dash_unidades_muestras` WHERE unidad_id = 2 AnD tipo = 0") or die(mysqli_error($mysqli));
                        $t_ens_san = $t_ens_sa->fetch_array(MYSQLI_ASSOC);
                        $tot_ens_sa = $t_ens_san['total_ensayes_sa'];

                        $t_rens_sa = $mysqli->query("SELECT cantidad AS total_reensayes_sa FROM `dash_unidades_muestras` WHERE unidad_id = 2 AnD tipo = 1") or die(mysqli_error($mysqli));
                        $to_reens_sa = $t_rens_sa->fetch_array(MYSQLI_ASSOC);
                        $to_reensaye_sa = $to_reens_sa['total_reensayes_sa'];

                        $t_sa = $mysqli->query("SELECT cantidad AS total_sa FROM `dash_unidades_muestras` WHERE unidad_id = 2 AnD tipo = -1") or die(mysqli_error($mysqli));
                        $to_sa = $t_sa->fetch_array(MYSQLI_ASSOC);
                        $total_sa = $to_sa['total_sa'];

                        $t_ens_ec = $mysqli->query("SELECT cantidad AS total_ensayes_ec FROM `dash_unidades_muestras` WHERE unidad_id = 3 AnD tipo = 0") or die(mysqli_error($mysqli));
                        $to_ens_ec = $t_ens_ec->fetch_array(MYSQLI_ASSOC);
                        $tot_ens_ec = $to_ens_ec['total_ensayes_ec'];

                        $t_rens_ec = $mysqli->query("SELECT cantidad AS total_reensayes_ec FROM `dash_unidades_muestras` WHERE unidad_id = 3 AnD tipo = 1") or die(mysqli_error($mysqli));
                        $to_reens_ec = $t_rens_ec->fetch_array(MYSQLI_ASSOC);
                        $to_reensaye_ec = $to_reens_ec['total_reensayes_ec'];

                        $t_ec = $mysqli->query("SELECT cantidad AS total_ec FROM `dash_unidades_muestras` WHERE unidad_id = 3 AnD tipo = -1") or die(mysqli_error($mysqli));
                        $to_ec = $t_ec->fetch_array(MYSQLI_ASSOC);
                        $total_ec = $to_ec['total_ec'];

                        $t_ens_gl = $mysqli->query("SELECT cantidad AS total_ensayes FROM `dash_unidades_muestras` WHERE tipo = 0 and unidad_id = 0") or die(mysqli_error($mysqli));
                        $t_ens_glo = $t_ens_gl->fetch_array(MYSQLI_ASSOC);
                        $t_ens_glob = $t_ens_glo['total_ensayes'];

                        $t_rens_gl = $mysqli->query("SELECT cantidad AS total_reensayes FROM `dash_unidades_muestras` WHERE tipo = 1 and unidad_id = 0") or die(mysqli_error($mysqli));
                        $to_reens_global = $t_rens_gl->fetch_array(MYSQLI_ASSOC);
                        $total_reensaye_gl = $to_reens_global['total_reensayes'];

                        $t_glo = $mysqli->query("SELECT cantidad AS total FROM `dash_unidades_muestras` WHERE tipo = -1 and unidad_id = 0") or die(mysqli_error($mysqli));
                        $t_globa = $t_glo->fetch_array(MYSQLI_ASSOC);
                        $t_global = $t_globa['total'];
                        ?>


                        <div class="col-lg-16 col-md-16">

                            <div class="col-lg-3 col-md-4">
                                <div class="social-box facebook">
                                    <i>
                                        <? echo 'San Agustin' . ' Anual'; ?>
                                    </i>
                                    <div class="weather-category twt-category">
                                        <ul>
                                            <li>
                                                <span class="count">
                                                    <? echo $tot_ens_sa; ?>
                                                </span> <br />
                                                <? echo 'ENSAYES'; ?>
                                            </li>
                                            <li>
                                                <span class="count">
                                                    <? echo $to_reensaye_sa; ?>
                                                </span> <br />
                                                <? echo 'REENSAYES'; ?>
                                            </li>
                                            <li>
                                                <span class="count">
                                                    <? echo $total_sa; ?>
                                                </span> <br />
                                                <? echo 'TOTAL'; ?>
                                            </li>
                                        </ul>
                                    </div>
                                    <!--/Total de muestras LC box-->
                                </div>
                            </div>
                            <!--/Total de todas las unidades box-->


                            <div style="opacity:0; height:0px;">
                                <div class="chart-wrapper">
                                    <canvas id="myChart" width="0" height="0"></canvas>
                                </div>
                            </div>

                            <div style="opacity:0; height:0px;">
                                <div class="chart-wrapper">
                                    <canvas id="myChartEC"></canvas>
                                </div>
                            </div>

                            <div style="opacity:0; height:0px;">
                                <div class="chart-wrapper px-1">
                                    <canvas id="myChartSA" width="0" height="0"></canvas>
                                </div>
                            </div>

                            <div class="col-sm-4 col-lg-9">
                                <div class="card text-white bg-flat-color-3">
                                    <div class="card-body pb-0">
                                        <div class="dropdown float-right">
                                            <button class="btn bg-transparent text-light" type="button" id="dropdownMenuButton2" data-toggle="dropdown">
                                                <i class="fa fa-flask"></i>
                                            </button>

                                        </div>
                                        <h4 class="mb-0">
                                            <span class="count">
                                                <? echo $total_muestras_sa; ?>
                                            </span>
                                            Muestras
                                        </h4>
                                        <p class="text-light">Unidad San Agustin</p>

                                        <div class="chart-wrapper px-0">
                                            <canvas id="widgetChart2"></canvas>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-11">
                                <br />
                                <br />
                                <br />
                                <div class="row">


                                    <div class="col-sm-12 col-lg-25">
                                        <div class="chart-wrapper px-1">
                                            <canvas id="myChartMultip"></canvas>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!--Fin row--!>         
      <!-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin primer tab = Home --!>   
                   
        <!-->
        <div id="menu1" class="tab-pane fade">
            <br />
            <div class="row">
                <div class="container col-md-4 col-lg-4">
                    <h4> <span class="text-primary text-center font-weight-bold">TRAZABILIDAD NIVEL FASE</span></h4>
                </div>
                <div class="formulario">
                    <div id="content" class="col-md-12 col-lg-12">

                        <label for="caja_busqueda"></label>
                        <input class="search_query form-control" type="text" name="caja_busqueda" id="caja_busqueda" placeholder="Buscar BATCH..."></input>

                    </div>
                </div>

            </div>
            <div class="col-md-12 col-lg-12" id="datos_batchs"></div>
        </div>
        <div id="menu2" class="tab-pane fade">

            <div class="formulario">
                <div id="content" class="col-md-6 col-lg-6">

                    <h5>Buscar muestra:</h5>

                    <label for="caja_busqueda"></label>
                    <input class="search_query form-control" type="text" name="caja_busqueda" id="caja_busqueda" placeholder="Buscar..."></input>
                    <br />
                    <br />
                </div>
            </div>
            <div class="col-md-12 col-lg-12" id="datos2"></div>
        </div>
        <!--Fin tercer tab = Muestras --!> 
        <!-->
        <div id="menu3" class="tab-pane fade">

            <div class="float-container">
                <div class="page-header">
                    <div class="page-title" style="position:center;">
                        <h3>TRAZABILIDAD DE BATCHS EN PROCESO: Fases y Etapas</h3>
                    </div>
                </div>

                <?
                while ($fila = $datos_fases->fetch_assoc()) {
                    $faseid   =  $fila['fase_id'];
                    $datos_etapas = $mysqli->query("SELECT DISTINCT etapa as nombre, etapa_id as etapaid from metodos_fases_etapas where fase_id = " . $faseid . "");
                    $datos_etapasree = $mysqli->query("SELECT DISTINCT etapa as nombre, etapa_id as etapaid from metodos_fases_etapas where fase_id = " . $faseid . "");
                    $total_normal = $mysqli->query("SELECT SUM(cantidad) as cantidad FROM total_muestras_preparacion WHERE fase_id =  " . $faseid . "");
                    $total_ree = $mysqli->query("SELECT SUM(cantidad) as cantidad FROM total_muestras_preparacionree WHERE fase_id = " . $faseid . "");
                    $total_normal2 = $total_normal->fetch_assoc();
                    $total_prepa_normal = $total_normal2['cantidad'];
                    $total_ree2 = $total_ree->fetch_assoc();
                    $total_prepa_ree = $total_ree2['cantidad'];
                    $total_prepa = intval($total_prepa_normal) + intval($total_prepa_ree);
                    switch ($faseid) {
                        case '1':
                            $clase = 'preparacion';
                            $fase = 'Preparación';
                            $logo = "fa fa-flask wtt-mark";
                            $color = "background-color: #C0C0C0;";
                            break;
                        case '2':
                            $clase = 'ensayefuego';
                            $fase = 'Ensaye a fuego';
                            $logo = "fa fa-fire wtt-mark";
                            $color = "background-color: #FA8072;";
                            break;
                        case '3':
                            $clase = 'absorcion';
                            $fase = 'Absorción Atómica';
                            $logo = "fa fa-plus-circle wtt-mark";
                            $color = "background-color: #ADFF2F;";
                            break;
                        case '4':
                            $clase = 'control';
                            $fase = 'Control de Calidad';
                            $logo = "fa fa-spinner wtt-mark";
                            $color = "background-color: #FFC0CB;";
                            break;
                        case '5':
                            $clase = 'mecanica';
                            $fase = 'Mecánica de suelos';
                            $logo = "fa fa-thermometer-quarter wtt-mark";
                            $color = "background-color: #CD853F;";
                            break;
                        case '6':
                            $clase = 'viahumeda';
                            $fase = 'Via Humeda';
                            $logo = "fa fa-check-square wtt-mark";
                            $color = "background-color: #6495ED;";
                            break;
                        case '7':
                            $clase = 'cianuracion';
                            $fase = 'Cianuracion';
                            $logo = "fa fa-flask wtt-mark";
                            $color = "background-color: #FFA500;";
                            break;

                        default:
                            $clase = "default";
                            $fase = $fila['nombre'];
                            $logo = "fa fa-vial-circle-check wtt-mark";
                            $color = "background-color: #887dc4;";
                    }

                ?>
                    <div class="float-child-left">
                        <section class="card">
                            <div class="twt-feed blue-bg" style="opacity:0.7; background-blend-mode: lighten; <? echo $color; ?>">
                                <div class="<? echo $logo; ?>" style="position:center; background-blend-mode: darken;"></div>
                                <h3 style="text-shadow: 1px 1px 2px black;"><? echo $fase; ?></h3>
                            </div>
                            <div class="weather-category twt-category">
                                <ul>
                                    <li>
                                        <span class="count">
                                            <? echo $total_prepa_normal; ?>
                                        </span> <br />
                                        <? echo 'ENSAYES'; ?>
                                    </li>
                                    <li>
                                        <span class="count">
                                            <? echo $total_prepa_ree; ?>
                                        </span> <br />
                                        <? echo 'REENSAYES'; ?>
                                    </li>
                                    <li>
                                        <span class="count">
                                            <? echo $total_prepa; ?>
                                        </span> <br />
                                        <? echo 'TOTAL'; ?>
                                    </li>
                                </ul>
                            </div>
                            <footer class="twt-footer">
                                <div class="col-12">
                                    <div class="col-6">
                                        <h4 style="text-shadow: 1px 1px 2px black;">Ensayes</h4>
                                        <div>
                                            <div class="<? echo $clase ?>">
                                                <tr>
                                                    <?
                                                    while ($etapa = $datos_etapas->fetch_assoc()) {
                                                        $nombre = $etapa['nombre'];
                                                        $etapaid = $etapa['etapaid'];
                                                        $datos = $mysqli->query("SELECT SUM(cantidad) as cantidad FROM `total_muestras_preparacion` WHERE fase_id = " . $faseid . " AND etapa_id = " . $etapaid . "");
                                                        $datos_f = $datos->fetch_assoc();
                                                        $cantidad = $datos_f['cantidad'];
                                                    ?>
                                                        <span class="count">
                                                            <? echo $cantidad; ?>
                                                        </span>
                                                        <? echo $nombre; ?>
                                                        </br>
                                                        </td>
                                                    <? } ?>

                                                </tr>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <h4 style="text-shadow: 1px 1px 2px black;">Reensayes</h4>
                                        <div>
                                            <div class="<? echo $clase ?>">
                                                <tr>
                                                    <?
                                                    while ($etapa = $datos_etapasree->fetch_assoc()) {
                                                        $nombre = $etapa['nombre'];
                                                        $etapaid = $etapa['etapaid'];
                                                        $datos = $mysqli->query("SELECT SUM(cantidad) as cantidad FROM `total_muestras_preparacionree` WHERE fase_id = " . $faseid . " AND etapa_id = " . $etapaid . "");
                                                        $datos_f = $datos->fetch_assoc();
                                                        $cantidad = $datos_f['cantidad'];
                                                    ?>
                                                        <span class="count">
                                                            <? echo $cantidad; ?>
                                                        </span>
                                                        <? echo $nombre; ?>
                                                        </br>
                                                        </td>
                                                    <? } ?>

                                                </tr>
                                            </div>
                                        </div>
                                    </div>

                            </footer>
                        </section>
                    </div>
                <? } ?>

            </div>
        </div>
    </div>
    </footer>
    </section>
    </div>
    </div>
    <!--Fin Tab 4 Fases y etapas-->

    </div><!-- Fin tab - content -->
    </div>
    </div>
    </div>
    </div>
    </div>
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/widget.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    
<script type="text/javascript" src="js/buscar_orden.js"></script>

    <!--Graficos--!>
    <!-->
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['EFAA30', 'HUM', 'VHAAAg', 'VHAACu', 'CNAAAu', 'CNAACu'],
                datasets: [{
                    label: 'Número de ordenes',
                    data: [<? echo $metodoEFAA30u1_all; ?>, <? echo $metodoHUMu1_all; ?>, <? echo $metodoVHAAAgu1_all; ?>, <? echo $metodoVHAACuu1_all; ?>, <? echo $metodoCNAAAuu1_all; ?>, <? echo $metodoCNAACuu1_all; ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },

            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


        const ctxe = document.getElementById('myChartSA').getContext('2d');
        var datosnombre = <?php echo json_encode($muestraspien); ?>;
        var datoscantidad = <?php echo json_encode($muestraspiec); ?>;
        const myChartSA = new Chart(ctxe, {
            type: 'doughnut',
            data: {
                labels: ['Oro', 'Plata'],
                datasets: [{
                    label: datosnombre,
                    data: datoscantidad,
                    backgroundColor: [
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 206, 86)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },

            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                title: {
                    display: true,
                    text: 'Datos de San Agustin en <? echo $mes ?>',
                    fontSize: 12,
                    position: 'bottom'
                }
            }
        });
        const ctxs = document.getElementById('myChartEC').getContext('2d');
        const myChartEC = new Chart(ctxs, {
            type: 'doughnut',
            data: {
                labels: ['EFAA30', 'HUM', 'VHAAAg', 'VHAACu', 'CNAAAu', 'CNAACu'],
                datasets: [{
                    label: 'Número de ordenes',
                    data: [<? echo $metodoEFAA30u3_all; ?>, <? echo $metodoHUMu3_all; ?>, <? echo $metodoVHAAAgu3_all; ?>, <? echo $metodoVHAACuu3_all; ?>, <? echo $metodoCNAAAuu3_all; ?>, <? echo $metodoCNAACuu3_all; ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },

            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                title: {
                    display: true,
                    text: 'Datos de El Castillo en <? echo $mes ?>',
                    fontSize: 12,
                    position: 'bottom'
                }
            }
        });
        const ctxd = document.getElementById('myChartDia').getContext('2d');
        var datosnombre = <?php echo json_encode($muestraspien); ?>;
        var datoscantidad = <?php echo json_encode($muestraspiec); ?>;
        const myChartDia = new Chart(ctxd, {
            type: 'pie',
            data: {
                title: "Muestras en Proceso para mes <? echo $hoy; ?>",
                labels: datosnombre,
                datasets: [{
                    label: '# of Votes',
                    data: datoscantidad,
                    backgroundColor: [
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 159, 64, 0.8)'
                    ],
                    borderColor: [
                        'rgba(255, 206, 86)',
                        'rgba(255, 99, 132)',
                        'rgba(54, 162, 235)',
                        'rgba(75, 192, 192)',
                        'rgba(153, 102, 255)',
                        'rgba(255, 159, 64)',
                        'rgba(255, 99, 132)',
                        'rgba(54, 162, 235)',
                        'rgba(255, 206, 86)',
                        'rgba(75, 192, 192)',
                        'rgba(153, 102, 255)',
                        'rgba(255, 159, 64)'
                    ],
                    borderWidth: 1
                }]
            },

            options: {
                legend: {
                    position: 'right',
                },
                title: {
                    display: true,
                    text: 'Muestras en Proceso para <? echo $hoy ?>',
                    fontSize: 25
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const ctxmult = document.getElementById('myChartMultip').getContext('2d');
        var datosnombre = <?php echo json_encode($multitip_n); ?>;
        var datoscantidad = <?php echo json_encode($multitip_c); ?>;
        const myChartMultip = new Chart(ctxmult, {
            type: 'bar',
            data: {
                labels: datosnombre,
                datasets: [{
                    label: 'San Agustin',
                    data: datoscantidad,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderWidth: 2,
                    borderColor: 'rgba(255,80,50,0.8)',
                    borderRadius: Number.MAX_VALUE,
                    borderSkipped: false,
                }]
            },
            options: {
                legend: {
                    position: 'right',
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var ctxch5 = document.getElementById("widgetChart2");
        var datos = <?php echo json_encode($procesos_mensual_sa); ?>;
        var datos_ree = <?php echo json_encode($procesos_mensual_sa_ree); ?>;
        var myChartCh4 = new Chart(ctxch5, {
            type: 'bar',
            data: {
                labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                datasets: [{
                        label: "Ensayes",
                        data: datos,
                        borderColor: "rgba(0, 123, 255, 0.9)",
                        backgroundColor: "rgb(107, 255, 122,0.7)"
                    },
                    {
                        label: "Reensayes",
                        data: datos_ree,
                        borderColor: "rgba(255, 145, 105, 0.9)",
                        backgroundColor: "rgb(240, 107, 108,0.7)"
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        categoryPercentage: 1,
                        barPercentage: 0.5
                    }],
                    yAxes: [{
                        display: true
                    }]
                }
            }
        });

        var ctxch6 = document.getElementById("widgetChart3");
        var myChartCh4 = new Chart(ctxch6, {
            type: 'bar',
            data: {
                labels: ["<? echo $mes; ?>"],
                datasets: [{
                        label: "Ensayes",
                        data: [<? echo $elcastilloensayes_all; ?>],
                        borderColor: "rgba(0, 123, 255, 0.9)",
                        backgroundColor: "rgb(107, 255, 122,0.7)"
                    },
                    {
                        label: "Reensayes",
                        data: [<? echo $elcastilloreensayes_all; ?>],
                        borderColor: "rgba(0, 145, 255, 0.9)",
                        backgroundColor: "rgb(122, 107, 255,0.7)"
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        display: false,
                        categoryPercentage: 1,
                        barPercentage: 0.5
                    }],
                    yAxes: [{
                        display: false
                    }]
                }
            }
        });

        //WidgetChart 4
        var ctxch4 = document.getElementById("widgetChart4");
        ctxch4.height = 70;
        var myChartCh4 = new Chart(ctxch4, {
            type: 'bar',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                datasets: [{
                        label: "Ensayes",
                        data: [78, 81, 80, 45, 34, 12, 40, 75, 34, 89, 32, 68],
                        borderColor: "rgba(0, 123, 255, 0.9)",
                        //borderWidth: "0",
                        backgroundColor: "rgba(153,101,21)"
                    },
                    {
                        label: "Reensayes",
                        data: [18, 22, 20, 26, 22, 14, 40, 25, 22, 20, 12, 18],
                        borderColor: "rgba(0, 145, 255, 0.9)",
                        //borderWidth: "0",
                        backgroundColor: "rgba(207, 197, 48)"
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        display: false,
                        categoryPercentage: 1,
                        barPercentage: 0.5
                    }],
                    yAxes: [{
                        display: false
                    }]
                }
            }
        });

        <? echo ("grafico_widgetlc();"); ?>
    </script>
</body>

</html>


<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/buscar_orden.js"></script>