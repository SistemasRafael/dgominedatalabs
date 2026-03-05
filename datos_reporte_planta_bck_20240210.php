<? include "connections/config.php";
$fecha = $_POST['value'];
$tipo = $_POST['tipo'];

$datos_bancos_detalle = $mysqli->query("CALL arg_rpt_reportePlanta('$fecha',$tipo)");
$html_det = "";
if ($tipo == 1) {
    $html_det .= "<div class='container'>
    <table class='table table-striped' id='motivos'>
            <thead>
                <tr class='table-info' justify-content: center;>            
                    <th colspan='2' scope='col2'>Descripcion</th>
                    <th colspan='2' scope='col2' align='center'>ORO</th>
                    <th colspan='2' scope='col2' align='center' bgcolor='#72bbe4'>PLATA</th>
                    <th colspan='2' scope='col2' align='center'>COBRE</th>
                    <th colspan='2' scope='col2' align='center' bgcolor='#72bbe4'>NaCN</th>
                    <th colspan='2' scope='col2' align='center'>pH</th>";
    $html_det .= "</tr>";
    $html_det .= "<tr class='table-info' justify-content: center;>            
        <th colspan='2' scope='col2'></th>
        <th scope='col1'>1er. Turno</th>
        <th scope='col1'>2do. Turno</th>
        <th scope='col1' bgcolor='#72bbe4'>1er. Turno</th>
        <th scope='col1' bgcolor='#72bbe4'>2do. Turno</th>
        <th scope='col1'>1er. Turno</th>
        <th scope='col1'>2do. Turno</th>
        <th scope='col1' bgcolor='#72bbe4'>1er. Turno</th>
        <th scope='col1' bgcolor='#72bbe4'>2do. Turno</th>
        <th scope='col1'>1er. Turno</th>
        <th scope='col1'>2do. Turno</th>
    </tr>
    <tr class='table-info' justify-content: center;>            
        <th colspan='2' scope='col2'></th>
        <th scope='col1'>Au (ppm)</th>
        <th scope='col1'>Au (ppm)</th>
        <th scope='col1' bgcolor='#72bbe4'>Ag (ppm)</th>
        <th scope='col1' bgcolor='#72bbe4'>Ag (ppm)</th>
        <th scope='col1'>Cu (ppm)</th>
        <th scope='col1'>Cu (ppm)</th>
        <th scope='col1' bgcolor='#72bbe4'>NaCN (ppm)</th>
        <th scope='col1' bgcolor='#72bbe4'>NaCN (ppm)</th>
        <th scope='col1'>pH</th>
        <th scope='col1'>pH</th>
    </tr>
           </thead>
           <tbody>";
} else {
    $html_det .= "<div class='container'>
    <table class='table table-striped' id='motivos'>
            <thead>
                <tr class='table-info' justify-content: center;>            
                    <th colspan='2' scope='col2'>Descripcion</th>
                    <th scope='col2' align='center'>ORO</th>
                    <th scope='col2' align='center' bgcolor='#72bbe4'>PLATA</th>
                    <th scope='col2' align='center'>COBRE</th>
                    <th scope='col2' align='center' bgcolor='#72bbe4'>NaCN</th>
                    <th scope='col2' align='center'>pH</th>";
    $html_det .= "</tr>";
    $html_det .= "
    <tr class='table-info' justify-content: center;>            
        <th colspan='2' scope='col2'></th>
        <th scope='col1'>Au (ppm)</th>
        <th scope='col1' bgcolor='#72bbe4'>Ag (ppm)</th>
        <th scope='col1'>Cu (ppm)</th>
        <th scope='col1' bgcolor='#72bbe4'>NaCN (ppm)</th>
        <th scope='col1'>pH</th>
    </tr>
           </thead>
           <tbody>";
}


while ($fila = $datos_bancos_detalle->fetch_assoc()) {
    if ($tipo == 1) {
        $au_ppm_t1 = $fila['Au_ppm_t1'] ? $fila['Au_ppm_t1'] : '0';
        $au_ppm_t2 = $fila['Au_ppm_t2'] ? $fila['Au_ppm_t2'] : '0';
        $ag_ppm_t1 = $fila['Ag_ppm_t1'] ? $fila['Ag_ppm_t1'] : '0';
        $ag_ppm_t2 = $fila['Ag_ppm_t2'] ? $fila['Ag_ppm_t2'] : '0';
        $cu_ppm_t1 = $fila['cu_ppm_t1'] ? $fila['cu_ppm_t1'] : '0';
        $cu_ppm_t2 = $fila['cu_ppm_t2'] ? $fila['cu_ppm_t2'] : '0';
        $phh_t1 = $fila['phh_t1'] ? $fila['phh_t1'] : '0';
        $phh_t2 = $fila['phh_t2'] ? $fila['phh_t2'] : '0';
        $cnl_t1 = $fila['cnl_t1'] ? $fila['cnl_t1'] : '0';
        $cnl_t2 = $fila['cnl_t2'] ? $fila['cnl_t2'] : '0';
        $cao_t1 = $fila['cao_t1'] ? $fila['cao_t1'] : '0';
        $cao_t2 = $fila['cao_t2'] ? $fila['cao_t2'] : '0';
        $html_det .= "<tr>";
        $html_det .= "<td colspan='2'>" . $fila['folio'] . "</td>";
        if ($au_ppm_t1 == 0){
            $html_det .= "<td></td>";
        }
        else {
            $html_det .= "<td>" . $au_ppm_t1 . "</td>";
        }
        if ($au_ppm_t2 == 0){
            $html_det .= "<td></td>";
        }
        else {
            $html_det .= "<td>" . $au_ppm_t2 . "</td>";
        }
        if ($ag_ppm_t1 == 0){
            $html_det .= "<td></td>";
        }
        else {        
            $html_det .= "<td>" . $ag_ppm_t1 . "</td>";
        }
        if ($ag_ppm_t2 == 0){
            $html_det .= "<td></td>";
        }
        else {
            $html_det .= "<td>" . $ag_ppm_t2 . "</td>";
        }
        if ($cu_ppm_t1 == 0){
            $html_det .= "<td></td>";
        }
        else {
            $html_det .= "<td>" . $cu_ppm_t1 . "</td>";
        }
        if ($cu_ppm_t2 == 0){
            $html_det .= "<td></td>";
        }
        else {
            $html_det .= "<td>" . $cu_ppm_t2 . "</td>";
        }
        
        if ($cnl_t1 == -2 || $cnl_t1 == 0){
            $html_det .= "<td></td>";
        }
        else {      
            $html_det .= "<td>" . $cnl_t1 . "</td>";
        }
        if ($cnl_t2 == -2 || $cnl_t2 == 0){
            $html_det .= "<td></td>";
        }
        else {
            $html_det .= "<td>" . $cnl_t2 . "</td>";
        }
        
        if ($phh_t1 == -2 || $phh_t1 == 0){
            $html_det .= "<td></td>";
        }
        else {
            $html_det .= "<td>" . $phh_t1 . "</td>";
        }
        
        if ($phh_t2 == -2 || $phh_t2 == 0){
            $html_det .= "<td></td>";
        }
        else {
            $html_det .= "<td>" . $phh_t2 . "</td>";
        }
        
        /*if ($cao_t1 == -2 || $cao_t1 == 0){
            $html_det .= "<td></td>";
        }
        else {        
            $html_det .= "<td>" . $cao_t1 . "</td>";
        }
        
        if ($cao_t2 == -2 || $cao_t2 == 0){
            $html_det .= "<td></td>";
        }
        else {  
            $html_det .= "<td>" . $cao_t2 . "</td>";
        }*/
        
        $html_det .= "</tr>";
    }else{
        $au_ppm_t1 = $fila['Au_ppm_t1'] ? $fila['Au_ppm_t1'] : '0';
        $ag_ppm_t1 = $fila['Ag_ppm_t1'] ? $fila['Ag_ppm_t1'] : '0';
        $cu_ppm_t1 = $fila['cu_ppm_t1'] ? $fila['cu_ppm_t1'] : '0';
        $phh_t1 = $fila['phh_t1'] ? $fila['phh_t1'] : '0';
        $cnl_t1 = $fila['cnl_t1'] ? $fila['cnl_t1'] : '0';
        $cao_t1 = $fila['cao_t1'] ? $fila['cao_t1'] : '0';
        $html_det .= "<tr>";
        $html_det .= "<td colspan='2'>" . $fila['folio'] . "</td>";
        $html_det .= "<td>" . $au_ppm_t1 . "</td>";
        $html_det .= "<td>" . $ag_ppm_t1 . "</td>";
        $html_det .= "<td>" . $cu_ppm_t1 . "</td>";
        $html_det .= "<td>" . $phh_t1 . "</td>";
        $html_det .= "<td>" . $cnl_t1 . "</td>";
        $html_det .= "<td>" . $cao_t1 . "</td>";
        $html_det .= "</tr>";
    }
}
$html_det .= "</tbody></table></div></div>";
echo ("$html_det");
