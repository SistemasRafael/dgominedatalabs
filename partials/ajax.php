<?php
include '\xampp\htdocs\registro\connections\config.php';

$html = '';
$key = $_POST['key'];

$i = 0;

$top = $mysqli->query("SELECT org_id, nombre FROM arg_organizaciones WHERE nombre LIKE '%".strip_tags($key)."%'") or die(mysqli_error());
                 /*  while($rowtop = $top ->fetch_array(MYSQLI_ASSOC))
                        {
                            //$i = $i+1;
                            //$nombre_empr[$i]["nombre"] = $rowtop["nombre"];
                            $nombre_empr = $rowtop["nombre"];
                        }*/
/*
$result = $connexion->query(
    'SELECT * FROM ps_product p 
    LEFT JOIN ps_product_lang pl ON (pl.id_product = p.id_product AND pl.id_lang = 1) 
    WHERE active = 1 
    AND pl.name LIKE "%'.strip_tags($key).'%"
    ORDER BY date_upd DESC LIMIT 0,5'
);*/

//var_dump($nombre_empr);
/*$total_reg = count($nombre_empr);
echo $total_reg;*/

/*foreach($nombre_empr as $row){
    $html .= '<div><a class="suggest-element" data="'.utf8_encode($row['nombre']).'"></a></div>';
}*/

if ($top->num_rows > 0) {
  // echo 'entro';
   while ($row = $top->fetch_assoc()) {
  // while ($row = $result->fetch_assoc()) {  
        //echo 'entro';
        $html .= '<div><a class="suggest-element" data="'.utf8_encode($row['nombre']).'" id="id'.$row['org_id'].'">'.utf8_encode($row['nombre']).'</a></div>';
        //'<div><a class="suggest-element" data="'.utf8_encode($row['nombre']).'">'.utf8_encode($row['nombre']).'</a></div>';
   //     $html .= '<div><a class="suggest-element" data="'.utf8_encode($nombre_empr['nombre']).'"></a></div>';
    
    }
}
echo $html;
?>