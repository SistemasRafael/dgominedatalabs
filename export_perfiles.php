<?

/**
 * Informe para exportar desde Bd Checadores SQL
 * Danira Romero Maldonado * 
 * ----------------------------------------
 * CSV Usuarios
 **/

include "connections/config.php";

/*include "phpExcel/Classes/PHPExcel.php";
include "phpExcel/Classes/IOFactory.php";
include "phpExcel/Classes/Writer/Excel5.php";*/
require "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$tipo = $_GET['tipo'];
$unidad_id = $_GET['unidad_id'];
$perfiles_sql = $mysqli->query("SELECT up.u_id, GROUP_CONCAT(pe.descripcion SEPARATOR ', ') as perfiles 
FROM arg_perfiles pe INNER JOIN arg_usuarios_perfiles up ON pe.perfil_id = up.perfil_id GROUP BY up.u_id;");
$perus = array();
while($resultado = $perfiles_sql->fetch_assoc()){
    $id = $resultado["u_id"];
    $perfiles = $resultado["perfiles"];
    $perus[$id] = $perfiles;
}

/*$objPHPExcel = new PHPExcel;
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);*/

$datos_at = $mysqli->query("SELECT
                                us.u_id
                               ,us.codigo
                               ,us.nombre
                               ,us.email
                               ,(CASE WHEN us.activo = 1 THEN 'SI' ELSE 'NO' END) AS activo
                               ,(CASE WHEN us.division = 'empleado' THEN 'AD' ELSE 'User Local' END) AS tipo_usuario
                               ,us.fecha_creacion
                               ,us.fecha_fin
                               ,uc.nombre AS user_created
                            FROM
                                `arg_usuarios` us
                                LEFT JOIN arg_usuarios uc
                                    ON us.u_id_created = uc.u_id
                            ORDER BY
                                us.nombre") or die(mysqli_error($mysqli));

/*$objSheet = $objPHPExcel->getActiveSheet();
$objSheet->setTitle("Usuarios");*/
$spreadsheet = new Spreadsheet(); 
$sheet = $spreadsheet->getActiveSheet();
    
$i = 1;
while ($fila = $datos_at->fetch_assoc()) {
    $sheet->setCellValue('A1', 'Usuario');
    $sheet->setCellValue('B1', 'Nombre');
    $sheet->setCellValue('C1', 'Correo');
    $sheet->setCellValue('D1', 'Tipo');
    $sheet->setCellValue('E1', 'Activo');
    $sheet->setCellValue('F1', 'Creado');
    $sheet->setCellValue('G1', 'Creación');
    $sheet->setCellValue('H1', 'Perfiles');

    $i = $i + 1;
    $sheet->setCellValue('A' . $i, utf8_encode($fila['codigo']));
    $sheet->setCellValue('B' . $i, utf8_encode($fila['nombre']));
    $sheet->setCellValue('C' . $i, utf8_encode($fila['email']));
    $sheet->setCellValue('D' . $i, utf8_encode($fila['tipo_usuario']));
    $sheet->setCellValue('E' . $i, utf8_encode($fila['activo']));
    $sheet->setCellValue('F' . $i, utf8_encode($fila['user_created']));
    $sheet->setCellValue('G' . $i, utf8_encode($fila['fecha_creacion']));
    $sheet->setCellValue('H' . $i, utf8_encode($perus[$fila["u_id"]]));
}
/*
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename=Usuarios.csv');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');*/
$writer = new Xlsx($spreadsheet);
$fileName = 'Listado de Usuarios.csv';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
$writer->save('php://output');
exit;
