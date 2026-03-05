<?php
//Configuraci�n central de sistema.
include "connections/config.php";
/*include '\xampp\htdocs\__pro\argonaut\common\phpExcel\Classes\PHPExcel.php';
include '\xampp\htdocs\__pro\argonaut\common\phpExcel\Classes\IOFactory.php';*/

require "vendor/autoload.php";

if (var_dump(class_exists('PhpSpreadsheet'))) {
    $archivo = $_GET['archivo_imp'];
    $u_id_lee = $_SESSION['u_id'];
    $dest = 'absorcion' . '/ ';
    $desti = rtrim($dest) . strtoupper($archivo);
    /**  Create a new Reader of the type defined in $inputFileType  **/
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Csv');
    $spreadsheet = $reader->load($desti);
    //$reader->load($arch);
    //$spreadsheet = $reader->load($desti);
    //$spreadsheet = $reader->loadSpreadsheetFromString($data);
}





$trn_id_bat = $mysqli->query("SELECT trn_id_rel, metodo_id, (CASE WHEN folio like '%RE%' THEN 1 ELSE 0 END) AS reensaye 
                              FROM arg_ordenes_csv WHERE folio = $archivo") or die(mysqli_error($mysqli));

$trn_id_batc = $trn_id_bat->fetch_assoc();
$trn_id_batch = $trn_id_batc['trn_id_rel'];
$met_id_b = $trn_id_batc['metodo_id'];
$reensaye = $trn_id_batc['reensaye'];


$trn_id_m = $mysqli->query("SELECT IFNULL(MAX(trn_id),0) AS trn_id FROM arg_ordenes_csv_detalle") or die(mysqli_error($mysqli));
$trn_id_ma = $trn_id_m->fetch_assoc();
$trn_id_max = $trn_id_ma['trn_id'] + 1;

//echo $trn_id_batch;

if (($fp = fopen($desti, "r")) !== FALSE) {
    //echo 'entro';
    while (!feof($fp)) {
        $data = fgetcsv($fp);
        $folio = $data[1];
        $cc = $data[2];
        echo $folio;
        //echo $cc;
        $query = "INSERT INTO arg_ordenes_csv_detalle (trn_id, trn_id_rel, folio, metodo_id, valor1, valor2 ) VALUES($trn_id_max,$trn_id_batch,$folio,$met_id_b,$cc,$cc)";
        //$mysqli->query($query);
    }
    fclose($fp);
}

if ($reensaye == 0) {
    mysqli_multi_query($mysqli, "CALL arg_prc_actualizarAbsorcion ($trn_id_batch,$met_id_b,$u_id_lee)") or die(mysqli_error($mysqli));
    $html = 'Archivo importado satisfactoriamente: ' . $archivo;
    echo ($html);
} else {
    mysqli_multi_query($mysqli, "CALL arg_prc_actualizarAbsorcion_ree ($trn_id_batch,$met_id_b,$u_id_lee)") or die(mysqli_error($mysqli));
    $html = 'Archivo importado satisfactoriamente: ' . $archivo;
    echo ($html);
}
