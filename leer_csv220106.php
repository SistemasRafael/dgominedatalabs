<?php
 //Configuración central de sistema.
include "connections/config.php";
/*include '\xampp\htdocs\__pro\argonaut\common\phpExcel\Classes\PHPExcel.php';
include '\xampp\htdocs\__pro\argonaut\common\phpExcel\Classes\IOFactory.php';*/
require_once 'vendor/autoload.php';
include "phpoffice/phpspreadsheet/src/PhpSpreadsheet/IOFactory.php";
use PhpOffice\PhpSpreadsheet\IOFactory;

$archivo = $_POST['archivo_imp'];
$dest = 'absorcion'.'/ ';
$desti = rtrim($dest).strtoupper($archivo);
$u_id_lee = $_SESSION['u_id'];

$trn_id_bat = $mysqli->query("SELECT trn_id_rel, metodo_id, (CASE WHEN folio like '%RE%' THEN 1 ELSE 0 END) AS reensaye 
                              FROM arg_ordenes_csv WHERE folio = '".$archivo."'") or die(mysqli_error());
$trn_id_batc = $trn_id_bat->fetch_assoc();
$trn_id_batch = $trn_id_batc['trn_id_rel'];
$met_id_b = $trn_id_batc['metodo_id'];
$reensaye = $trn_id_batc['reensaye'];

$trn_id_m = $mysqli->query("SELECT IFNULL(MAX(trn_id),0) AS trn_id FROM arg_ordenes_csv_detalle") or die(mysqli_error());
$trn_id_ma = $trn_id_m->fetch_assoc();
$trn_id_max = $trn_id_ma['trn_id']+1;

    $documento = IOFactory::load($desti);
    $hojaActual = $documento->getSheet(0);
    
    $numeroMayorDeFila = $hojaActual->getHighestRow(); // Numérico
    for ($indiceFila = 9; $indiceFila <= $numeroMayorDeFila; $indiceFila++) {
        $coordenadas_b = "B".$indiceFila;
        $coordenadas_c = "C".$indiceFila;

        $celda_b = $hojaActual->getCell($coordenadas_b);
        $folio = $celda_b->getValue();
        //echo $folio;    
        $celda_c = $hojaActual->getCell($coordenadas_c);
        $abs = $celda_c->getValue();
        //echo $abs."<br/>";
        $query = "INSERT INTO arg_ordenes_csv_detalle (trn_id, trn_id_rel, folio, metodo_id, valor1, valor2 ) ".
                                              " VALUES(".$trn_id_max.",".$trn_id_batch.", '".$folio."', ".$met_id_b.",".$abs.",".$abs.")";
        $mysqli->query($query);        
    }
    
if ($reensaye == 0){
    mysqli_multi_query ($mysqli, "CALL arg_prc_actualizarAbsorcion ($trn_id_batch,$met_id_b,$u_id_lee)") OR DIE (mysqli_error($mysqli));
    $html = 'Archivo importado satisfactoriamente: '.$archivo;
    echo ($html);
}
else{
    mysqli_multi_query ($mysqli, "CALL arg_prc_actualizarAbsorcion_ree ($trn_id_batch,$met_id_b,$u_id_lee)") OR DIE (mysqli_error($mysqli));
    $html = 'Archivo importado satisfactoriamente: '.$archivo;
    echo ($html);
}
?>