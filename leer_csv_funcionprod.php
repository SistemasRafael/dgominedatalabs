<?php
 //Configuración central de sistema.
include "connections/config.php";
/*include '\xampp\htdocs\__pro\argonaut\common\phpExcel\Classes\PHPExcel.php';
include '\xampp\htdocs\__pro\argonaut\common\phpExcel\Classes\IOFactory.php';*/


include "phpExcel/Classes/PHPExcel.php";
//PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
include "phpExcel/Classes/IOFactory.php";
//require "phpExcel/Classes/Reader/Excel2007.php";
//include "phpExcel/Classes/Writer/Excel5.php";

$archivo = $_GET['archivo_imp'];
//$archivo = ($archivo);

//$dest = '\\xampp\\htdocs\\__pro\\argonaut\\VinculosKpi'.'\\ '; //lugar donde se copiara el archivo
//$dest = '\\absorcion'.'\\ '; //lugar donde se copiara el archivo
$dest = '/var/www/html/MineData-Labs/absorcion'.'/ ';
$desti = rtrim($dest).strtoupper($archivo);
$u_id_lee = $_SESSION['u_id'];
//echo $archivo;
//$fp = fopen ($desti,"r");

/*$trn_id_bat = $mysqli->query("SELECT trn_id_rel, metodo_id FROM arg_ordenes_csv WHERE folio = '".$archivo."'") or die(mysqli_error());
$trn_id_batc = $trn_id_bat->fetch_assoc();*/
$trn_id_batch = 2;//$trn_id_batc['trn_id_rel'];
$met_id_b = 3;//$trn_id_batc['metodo_id'];
$trn_id_max = 2;
/*
$trn_id_m = $mysqli->query("SELECT IFNULL(MAX(trn_id),0) AS trn_id FROM arg_ordenes_csv_detalle") or die(mysqli_error());
$trn_id_ma = $trn_id_m->fetch_assoc();
$trn_id_max = $trn_id_ma['trn_id']+1;*/

    //PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
  
    /*$objPHPExcel = PHPEXCEL_IOFactory::load($desti);
    //$objReader = new PHPExcel_Reader_Excel2007(); //instancio un objeto como PHPExcelReader(objeto de captura de datos de excel)
    $objPHPExcel = $objReader->load($desti); //carga en objphpExcel por medio de objReader,el nombre del archivo
    $objPHPExcel -> setActiveSheetIndex(0);
    $numRows = $objPHPExcel -> setActiveSheetIndex(0)-> getHighestRow();
                   
    $objPHPExcel->setActiveSheetIndex(0);
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $highestRow = $objWorksheet->getHighestRow(); 
    $highestColumn = $objWorksheet->getHighestColumn(); 
            
    for ($i = 4; $i <= $numRows; $i++){
        $folio = $objPHPExcel -> getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
        $cc = $objPHPExcel    -> getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
        //$ce = $objPHPExcel -> getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
        //echo $folio;*/
      //  $query = "INSERT INTO arg_ordenes_csv_detalle (trn_id, trn_id_rel, folio, metodo_id, valor1, valor2 ) ".
        //         "VALUES(".$trn_id_max.",".$trn_id_batch.", '".$folio."', ".$met_id_b.", ".$cc.",".$cc.")";
         //echo $query; die();
      /*  $mysqli->query($query);
    }*/
    /*
while ($data = fgetcsv ($fp, 1000, ";")) {
$num = count ($data);
}*/
//print "";
//foreach($data as $row) {
//$folio[1]=$row;*/
//echo $data[0].' -> '.$data[2];
//handle = fopen("test.csv", "r");
if (($fp = fopen($desti, "r")) !== FALSE) { 
    //$rows[$i] = explode("\n", $fp);
    //$length = count($rows);
   // $data = fgetcsv($fp);
    while(!feof($fp)) {
           // $data = fgetcsv($fp , 0 , ',' , '"', '"' );
            $data = fgetcsv($fp);
            $folio = $data[1];
            $cc = $data[3];
            echo $rowCount++;
            echo $folio;
            echo $ce;
            $query = "INSERT INTO arg_ordenes_csv_detalle (trn_id, trn_id_rel, folio, metodo_id, valor1, valor2 ) ".
                                                  " VALUES(".$trn_id_max.",".$trn_id_batch.", '".$folio."', ".$met_id_b.",".$cc.",".$cc.")";
            $mysqli->query($query);
     }
     fclose($fp);
}

/*$rows = shell_exec('$(/bin/which cat) SA000003_3.CSV | $(/bin/which tr) "\r" "\n" | $(which wc) -l');

echo $rows;
echo 'aqui';
$fin = 45;*/
//while ($data = fgetcsv($fp, 1000, ";") ) {
    //$fp = fgetcsv($desti);
//echo count($fp);
//$total = count($fp);

//while (($data = fgetcsv($fp)) !== FALSE) {
//while ($i < $total){
   // $data = fgetcsv($fp);
 //  echo $i;
/*$folio = $data[1];
$cc = $data[2];
$ce = $data[4];
echo $folio;
echo $cc;
echo $ce;*/
//}
/*$cc = $data[3];
$ce = $data[5];
echo $folio;*/
  /*$query = "INSERT INTO arg_ordenes_csv_detalle (trn_id, trn_id_rel, folio, metodo_id, valor1, valor2 ) ".
                                        "VALUES(".$trn_id_max.",".$trn_id_batch.", '".$folio."', ".$met_id_b.",".$cc.",".$cc.")";
$mysqli->query($query);
}*/

 //fclose ($fp);
/*
    mysqli_multi_query ($mysqli, "CALL arg_prc_actualizarAbsorcion ($trn_id_batch,$met_id_b,$u_id_lee)") OR DIE (mysqli_error($mysqli));
    $html = 'Archivo importado satisfactoriamente: '.$archivo;
    echo ($html);*/
               
?>