<!-- jQuery 
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>-->
<!-- BS JavaScript 
<script type="text/javascript" src="js/bootstrap.js"></script>-->
<!-- Have fun using Bootstrap JS -->

<?php
//include "../connections/config.php";

$ejercicio = date("Y");
$mes  = date("m");
$metodo  = 3;
$unidadmina = $_GET['unidad_id'];

switch ($mes){
    case 1: $mes_nombre = 'Enero';
    break;
    case 2: $mes_nombre = 'Febrero';
    break;
    case 3: $mes_nombre = 'Marzo';
    break;
    case 4: $mes_nombre = 'Abril';
    break;    
    case 5: $mes_nombre = 'Mayo';
    break;
    case 6: $mes_nombre = 'Junio';
    break;
    case 7: $mes_nombre = 'Julio';
    break;
    case 8: $mes_nombre = 'Agosto';
    break;
    case 9: $mes_nombre = 'Septiembre';
    break;    
    case 10: $mes_nombre = 'Octubre';
    break;
    case 11: $mes_nombre = 'Noviembre';
    break;    
    case 12: $mes_nombre = 'Diciembre';
    break;
}

switch ($metodo){
    case 3: $nombre_metodo = 'Au';
    break;
    case 6: $nombre_metodo = 'Ag';
    break;
}

?>
<script>
    $(document).ready(function() {
      
        $("#ejercicio").on("change", function() {
            var ejercicio = $("#ejercicio").val();
            var mes = $("#mes").val();
            var metodo = $("#metodo").val();
            var mina = $("#mina").val();
            alert(mina);
            
            $.ajax({
                    url: 'datos_reporte_blancos.php',
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        ejercicio: ejercicio,
                        mes: mes,
                        metodo : metodo,
                        mina: mina
                    },
                })
                .done(function(respuesta) {
                    $("#motivos").html(respuesta);
                })
        });
        
        $("#mes").on("change", function() {
            var ejercicio = $("#ejercicio").val();
            var mes = $("#mes").val();
            var metodo = $("#metodo").val();
            var mina = $("#mina").val();
            $.ajax({
                    url: 'datos_reporte_blancos.php',
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        ejercicio: ejercicio,
                        mes: mes,
                        metodo : metodo,
                        mina: mina
                    },
                })
                .done(function(respuesta) {
                    $("#motivos").html(respuesta);
                })
        });
        
         $("#metodo").on("change", function() {
            var ejercicio = $("#ejercicio").val();
            var mes = $("#mes").val();
            var metodo = $("#metodo").val();
            var mina = $("#mina").val();
            $.ajax({
                    url: 'datos_reporte_blancos.php',
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        ejercicio: ejercicio,
                        mes: mes,
                        metodo : metodo,
                        mina: mina
                    },
                })
                .done(function(respuesta) {
                    $("#motivos").html(respuesta);
                })
        });
    });



    function exportar() {
        var mina = document.getElementById("mina").value;
        var ejercicio = document.getElementById("ejercicio").value;
        var mes = document.getElementById("mes").value;
        var metodo = document.getElementById("metodo").value;
        var exportar = '<?php echo "\ export_reporte_blancos.php?unidad=" ?>' + mina + "&ejercicio=" + ejercicio+"&mes="+mes+"&metodo="+metodo;
        window.location.href = exportar;
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


$datosmina = $mysqli->query("SELECT nombre FROM arg_empr_unidades WHERE unidad_id = ".$unidadmina) or die(mysqli_error());
$mina_nombres = $datosmina ->fetch_array(MYSQLI_ASSOC);
$mina_nombre = $mina_nombres['nombre'];

$datos_bancos_detalle = $mysqli->query(
    "CALL arg_rpt_blancos($unidadmina,$ejercicio,$mes,$metodo)"
) or die(mysqli_error($mysqli));

 /*$max_mue_id = $mysqli->query("SELECT IFNULL(MAX(trn_id), 0) AS trn_id_mue FROM arg_ordenes_muestras") or die(mysqli_error());
                                         $max_mues_id = $max_mue_id ->fetch_array(MYSQLI_ASSOC);
                                         $trn_id_mue = $max_mues_id['trn_id_mue'];*/


                                      
?>

<br><br><br>
<br><br><br>
<div class="container">

    <div class="col-md-2 col-lg-2">
        <label for="mina" class="col-form-label"><b>UNIDAD DE MINA</b></label>
        <select name="mina" id="mina" class="form-control">
             <option value="<? echo $unidadmina; ?>"><? echo $mina_nombre; ?></option>
        </select>
    </div>

    <div class="col-md-2 col-lg-2">
        <label for="ejercicio" class="col-form-label"><b>AÑO</b></label>
        <select name="ejercicio" id="ejercicio" class="form-control">
            <option value="2024">2024</option>
            <option value="2023">2023</option>            
            <option value="2025">2025</option>
        </select>
    </div>
    
    
    <div class="col-md-2 col-lg-2">
        <label for="mes" class="col-form-label"><b>MES</b></label>
        <select name="mes" id="mes"  class="form-control">
            <option value="<? echo $mes; ?>"><? echo $mes_nombre; ?></option>
            <option value="1">Enero</option>
            <option value="2">Febrero</option>            
            <option value="3">Marzo</option>            
            <option value="4">Abril</option>            
            <option value="5">Mayo</option>            
            <option value="6">Junio</option>            
            <option value="7">Julio</option>            
            <option value="8">Agosto</option>            
            <option value="9">Septiembre</option> 
            <option value="10">Octubre</option>            
            <option value="11">Noviembre</option>
            <option value="12">Diciembre</option>            
        </select>  
                
    </div>
    
    <div class="col-md-2 col-lg-2">
        <label for="metodo" class="col-form-label"><b>METODO</b></label>
        <select name="metodo" id="metodo" class="form-control">
            <option value="3">EFAA30</option>
            <option value="6">VHAAAg</option>
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
    // <label for="mes" class="col-form-label"><b>MES</b></label>
    $html_det = "<div class='container'>
                        <table class='table table-striped' id='motivos'>
                                <thead>
                                    <tr class='table-info' justify-content: center;>  
                                        <th colspan='1' scope='2'></th>           
                                        <th colspan='2' scope='col2'>Blanco Reactivo</th>  
                                        <th colspan='2' scope='col2'>Muestra Precedente</th>                                        
                                        <th colspan='1' scope='col1'></th>
                                        <th colspan='2' scope='col2'  bgcolor='#bfb604'>Blanco Preparado</th>                                         
                                        <th colspan='2' scope='col2'  bgcolor='#bfb604'>Muestra Precedente</th>";
    $html_det .= "</tr>";
    $html_det .= "<tr class='table-warning' justify-content: center;> 
                            <th scope='col1'>Fecha Recepción</th>
                            <th scope='col1' bgcolor='#72bbe4'>Id Muestra</th>
                            <th scope='col1'>Ley $nombre_metodo (ppm)</th>
                            <th scope='col1'>ID Muestra</th>
                            <th scope='col1' bgcolor='#72bbe4'>Ley $nombre_metodo (ppm)</th>
                            <th scope='col1' bgcolor='#72bbe4'>Fecha Recepción</th>
                            <th scope='col1'>ID Muestra</th>
                            <th scope='col1'>Ley $nombre_metodo (ppm)</th>
                            <th scope='col1'>ID Muestra</th>
                            <th scope='col1'>Ley $nombre_metodo (ppm)</th>
                        </tr>
                       
                               </thead>
                               <tbody>";

    while ($fila = $datos_bancos_detalle->fetch_assoc()) {
        $num = 1;
       
        $html_det .= "<tr>";
        $html_det .= "<td colspan='1'>" . $fila['fecha_orden'] . "</td>";
        $html_det .= "<td colspan='1'>" . $fila['blanco_reactivo'] . "</td>";
        $html_det .= "<td colspan='1'>" . $fila['br_resultado'] . "</td>";
        $html_det .= "<td colspan='1'>" . $fila['blanco_reactivo_prec'] . "</td>";
        $html_det .= "<td colspan='1'>" . $fila['brpre_resultado'] . "</td>";
        $html_det .= "<td colspan='1'>" . $fila['fecha_orden'] . "</td>";
        $html_det .= "<td colspan='1'>" . $fila['blanco_preparado'] . "</td>";
        $html_det .= "<td colspan='1'>" . $fila['blanco_preparado_resultado'] . "</td>";        
        $html_det .= "<td colspan='1'>" . $fila['blanco_preparado_prec'] . "</td>";
        $html_det .= "<td colspan='1'>" . $fila['pre_res_preparado'] . "</td>";
        $html_det .= "</tr>";
    }

    $html_det .= "</tbody></table></div></div>";

    echo ("$html_det");



    ?>
    <br /><br /><br /><br /><br /><br /><br /><br />
    <script type="text/javascript" src="js/jquery.min.js"></script>
    
 
    <!--<script type="text/javascript" src="js/vehiculos.js"></script>-->