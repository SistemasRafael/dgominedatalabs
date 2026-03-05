<? //include "../connections/config.php";
$unidad_id = $_GET['unidad_id'];
$_SESSION['unidad_id'] = $unidad_id;
$u_id = $_SESSION['u_id'];

$unidad_mi = $mysqli->query("SELECT nombre FROM arg_empr_unidades WHERE unidad_id = " . $unidad_id) or die(mysqli_error($mysqli));
$unidad_min = $unidad_mi->fetch_assoc();
$unidad_mina = $unidad_min['nombre'];
//echo $unidad_mina;

/*$date = '2023-01-01';
$fecha_inicial = date("d-m-Y", strtotime($date));
$fecha_final = date("d-m-Y");*/
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

<div class="container-fluid">
    <br /><br /><br /><br /><br /><br />
    <?
    $fecha_minima_val = date('d-m-Y');
    $nuevafecha = strtotime($fecha_minima_val);
    $nuevafecha = date('d-m-Y', $nuevafecha);

    ?>
  

    <br /><br /><br />


    <div>
        <div class="col-md-3 col-lg-2">
            <label for="fecha_inicial_ex"><b>DESDE</b></label>
            <input type='date' class='form-control' name='fecha_inicial_ex' id='fecha_inicial_ex' value="<? echo $fecha_inicial; ?>" placeholder="">
        </div>
        <div class="col-md-3 col-lg-2">
            <label for="fecha_final_ex"><b>HASTA</b></label>
            <input type="date" class="form-control" name='fecha_final_ex' id='fecha_final_ex' value="<? echo $fecha_final; ?>" placeholder="">
        </div>
    </div>



    <div class="col-md-2 col-lg-1">
        <label for="export"></label><br /><br />
        <button type='button' class='btn btn-success' name='export' id='export' onclick="exportar_listado(<? echo $unidad_id; ?>)"> <span class="fa fa-file-excel-o fa-2x"> Exportar</span>
        </button>
    </div>
    <br /><br />
    <?php
   // if (isset($_GET['unidad_id'])) {
     //   $mysqli->set_charset("utf8");


        //$query = "CALL arg_rpt_ResultadosMuestrasGeologia('', '" . $fecha_inicial . "', '" . $fecha_final . "')";
        //mysqli_multi_query($mysqli, $query);
        //$result = $mysqli->store_result();
       $datos_geo = $mysqli->query("SELECT om.folio
        , banco_voladura(om.trn_id) AS banvol
        , DATE_FORMAT(ot.fecha, '%d-%m-%Y') AS fecha
        , IFNULL(au.resultado, -2) AS Au
        , IFNULL(ag.resultado, -2) AS Ag
        , IFNULL(DATE_FORMAT(au.fecha, '%d-%m-%Y'), 'PEND') AS fecha_res_Au
        , IFNULL(DATE_FORMAT(au.fecha, '%H:%i:%s'), 'PEND') AS hora_res_Au
        , IFNULL(DATE_FORMAT(ag.fecha, '%d-%m-%Y'), 'PEND') AS fecha_res_Ag
        , IFNULL(DATE_FORMAT(ag.fecha, '%H:%i:%s'), 'PEND') AS hora_res_Ag
         FROM `arg_ordenes_muestras` om 
         LEFT JOIN arg_ordenes_detalle AS od
          ON od.trn_id = om.trn_id_rel
           LEFT JOIN arg_ordenes AS ot
            ON ot.trn_id = od.trn_id_rel 
            AND ot.trn_id_rel = 0 
           LEFT JOIN arg_muestras_liberadas AS au
            ON om.trn_id = au.trn_id_rel 
            AND au.metodo_id = 3 
           LEFT JOIN arg_muestras_liberadas AS ag
             ON om.trn_id = ag.trn_id_rel 
             AND ag.metodo_id = 6 
        WHERE om.tipo_id = 0 
            AND od.estado <> 99 
            AND od.folio_interno NOT LIKE '%-RE%'
            AND MONTH(ot.fecha) = MONTH(NOW())
            AND ot.unidad_id = ".$unidad_id."
            AND om.trn_id NOT IN (SELECT trn_id_muestra FROM ordenes_sobrelimites)
        UNION ALL
        SELECT om.folio
            , banco_voladura(om.trn_id) AS banvol
            , DATE_FORMAT(ot.fecha, '%d-%m-%Y') AS fecha
            , IFNULL(au.resultado, '>1') AS Au            
            , IFNULL(ag.resultado, -2) AS Ag
            , IFNULL(DATE_FORMAT(au.fecha, '%d-%m-%Y'), 'PEND') AS fecha_res_Au
            , IFNULL(DATE_FORMAT(au.fecha, '%H:%i:%s'), 'PEND') AS hora_res_Au
            , IFNULL(DATE_FORMAT(ag.fecha, '%d-%m-%Y'), 'PEND') AS fecha_res_Ag
            , IFNULL(DATE_FORMAT(ag.fecha, '%H:%i:%s'), 'PEND') AS hora_res_Ag
         FROM 
              `arg_ordenes_muestras` om
                LEFT JOIN arg_ordenes_detalle AS od
                     ON od.trn_id = om.trn_id_rel
               LEFT JOIN arg_ordenes AS ot
                    ON ot.trn_id = od.trn_id_rel 
                    AND ot.trn_id_rel = 0
               LEFT JOIN arg_metodos AS met
               	     ON met.metodo_id = 1         
               LEFT JOIN arg_muestras_liberadas AS au
                    ON om.trn_id = au.trn_id_rel 
                    AND au.metodo_id = 1
               LEFT JOIN arg_muestras_liberadas AS ag
                 ON om.trn_id = ag.trn_id_rel 
                 AND ag.metodo_id = 6 
        WHERE 
            om.tipo_id = 0 
            AND od.estado <> 99 
            AND od.folio_interno NOT LIKE '%-RE%'
            AND ot.unidad_id = ".$unidad_id."
            AND om.trn_id IN (SELECT trn_id_muestra FROM ordenes_sobrelimites)
        ORDER BY folio") or die(mysqli_error());
        //$datos_in = $datos_ins->fetch_array(MYSQLI_ASSOC);

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
        while ($fila = $datos_geo->fetch_assoc()) {
            
            
            $html_det .= "<tr>";
            $html_det .= "<td>" . $num . "</td>";
            $html_det .= "<td>" . $fila['folio'] . "</td>";
            $html_det .= "<td>" . $fila['banvol'] . "</td>";
            $html_det .= "<td>" . $fila['fecha'] . "</td>";           
            $html_det .= "<td>" . $fila['Au'] . "</td>";           
            $html_det .= "<td>" . $fila['Ag'] . "</td>";            
            $html_det .= "<td>" . $fila['fecha_res_Au'] . "</td>";
            $html_det .= "<td>" . $fila['hora_res_Au'] . "</td>";
            $html_det .= "<td>" . $fila['fecha_res_Ag'] . "</td>";
            $html_det .= "<td>" . $fila['hora_res_Ag'] . "</td>";
            /*$html_det .= "<td>" . $fila[2] . "</td>";
            $html_det .= "<td>" . $fila[3] . "</td>";
            $html_det .= "<td>" . $fila[4] . "</td>";
            $html_det .= "<td>" . $fila[5] . "</td>";
            $html_det .= "<td>" . $fila[6] . "</td>";
            $html_det .= "<td>" . $fila[7] . "</td>";
            $html_det .= "<td>" . $fila[8] . "</td>";*/
            $html_det .= "</tr>";
            $num = $num + 1;
        }
        $html_det .= "</tbody></table>";
        echo ($html_det);
    ?>
</div>
<?// } ?>
<script>
    /*function updateTabla(url) {
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
*/
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
        var exportar = '<?php echo "\ exportar_reporte_geo1.php?unidad_id=" ?>' + unidad_id_ex + '&fecha_inicial=' +
            fecha_inicial_ex + '&fecha_final=' + fecha_final_ex;
        window.location.href = exportar;
    }
</script>
