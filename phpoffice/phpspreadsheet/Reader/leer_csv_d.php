<?php
 //Configuración central de sistema.
include "connections/config.php";
/*include '\xampp\htdocs\__pro\argonaut\common\phpExcel\Classes\PHPExcel.php';
include '\xampp\htdocs\__pro\argonaut\common\phpExcel\Classes\IOFactory.php';*/



require_once 'vendor/autoload.php';
//require_once 'config.php';
 
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Reader\Csv;
//use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

//echo 'llego';
//include "phpExcel/Classes/PHPExcel.php";
//PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
//include "phpExcel/Classes/IOFactory.php";
//require "phpExcel/Classes/Reader/Excel2007.php";
//include "phpExcel/Classes/Writer/Excel5.php";


include "phpoffice/phpspreadsheet/src/PhpSpreadsheet/Reader/IReader.php";

include "phpoffice/phpspreadsheet/src/PhpSpreadsheet/Reader/Csv.php";
//$reader = new vendor\PhpOffice\PhpSpreadsheet\Reader\Csv();

$archivo = $_GET['archivo_imp'];
//$archivo = ($archivo);
//echo $archivo;

//$dest = '\\xampp\\htdocs\\__pro\\argonaut\\VinculosKpi'.'\\ '; //lugar donde se copiara el archivo
$dest = 'absorcion'.'/ ';
$desti = rtrim($dest).strtoupper($archivo);
$u_id_lee = $_SESSION['u_id'];
//echo $desti;

 //$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      
   // if(isset($desti, $file_mimes)) {
      
       // $arr_file = explode('.', $desti);
      //  $extension = end($arr_file);
      
       // if('csv' == $extension) {
            $reader = new phpOffice\PhpSpreadsheet\Reader\Csv();
        /*} else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }*/
  
        $spreadsheet = $reader->load($desti);
  
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
  
        if (!empty($sheetData)) {
            for ($i=1; $i<count($sheetData); $i++) { //skipping first row
                //var_dump($sheetData);
                $name = $sheetData[$i][0];
                $email = $sheetData[$i][1];
                $company = $sheetData[$i][2];
                echo $name;
                echo $email;
                echo $company;
                //$db->query("INSERT INTO USERS(name, email, company) VALUES('$name', '$email', '$company')");
            }
        }
       // echo "Records inserted successfully.";
  


//$spreadsheet = $reader->load($desti);
//$spreadsheet = $reader->loadSpreadsheetFromString($data);

/*
$trn_id_bat = $mysqli->query("SELECT trn_id_rel, metodo_id, (CASE WHEN folio like '%RE%' THEN 1 ELSE 0 END) AS reensaye 
                              FROM arg_ordenes_csv WHERE folio = '".$archivo."'") or die(mysqli_error());
$trn_id_batc = $trn_id_bat->fetch_assoc();
$trn_id_batch = $trn_id_batc['trn_id_rel'];
$met_id_b = $trn_id_batc['metodo_id'];
$reensaye = $trn_id_batc['reensaye'];


$trn_id_m = $mysqli->query("SELECT IFNULL(MAX(trn_id),0) AS trn_id FROM arg_ordenes_csv_detalle") or die(mysqli_error());
$trn_id_ma = $trn_id_m->fetch_assoc();
$trn_id_max = $trn_id_ma['trn_id']+1;*/

//echo $trn_id_batch;

//if (($fp = fopen($desti, "r")) !== FALSE) {
  /*  $fp = fopen($desti, "r");
    while(!feof($fp)) {
        //echo 'entro';
            $data = fgetcsv($fp);
            echo $data[1];
            $folio = $data[1];
            $cc = $data[2];
            echo $folio;
            echo $cc;
            $query = "INSERT INTO arg_ordenes_csv_detalle (trn_id, trn_id_rel, folio, metodo_id, valor1, valor2 ) ".
                                                  " VALUES(".$trn_id_max.",".$trn_id_batch.", '".$folio."', ".$met_id_b.",".$cc.",".$cc.")";
            //$mysqli->query($query);
     }
     fclose($fp);*/
//}
/*
if ($reensaye == 0){
    mysqli_multi_query ($mysqli, "CALL arg_prc_actualizarAbsorcion ($trn_id_batch,$met_id_b,$u_id_lee)") OR DIE (mysqli_error($mysqli));
    $html = 'Archivo importado satisfactoriamente: '.$archivo;
    echo ($html);
}
else{
    mysqli_multi_query ($mysqli, "CALL arg_prc_actualizarAbsorcion_ree ($trn_id_batch,$met_id_b,$u_id_lee)") OR DIE (mysqli_error($mysqli));
    $html = 'Archivo importado satisfactoriamente: '.$archivo;
    echo ($html);
}*/
?>