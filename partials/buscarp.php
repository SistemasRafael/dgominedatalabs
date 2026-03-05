<?
/**
 * buscar clasificacion.php v0.1
 * ----------------------------------------
 * Lista del visor de unicos con clasificacion
 **/

//Configuración central de sistema.


include '\xampp\htdocs\registro\connections\config.php';



?>
<head>
<body>


<?
//Variable de búsqueda

//Filtro anti-XSS
/*$caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
$caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
$consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);*/

//$consultaBusqueda = 'set';
//Variable vacía (para evitar los E_NOTICE)

//$connect = new PDO("mysql:host=192.168.20.22; dbname=arg_registroVisitas", "danira", "Danira!");

//echo $consultaBusqueda;
//Comprueba si $consultaBusqueda está seteado
//if (isset($consultaBusqueda)) {
 ///if (isset($_POST['query'])) {	
    
    //$query = "SELECT DISTINCT nombre FROM arg_organizaciones WHERE nombre LIKE '%".trim($consultaBusqueda)."%'";
    
   /* $statement = $connect->prepare($query);
    $statement -> execute();
    $result = $statement->fetchAll();*/
   /* $query = ("SELECT nombre                             
                                     FROM arg_organizaciones             
                                     WHERE nombre LIKE '%".$consultaBusqueda."%'");*/
   //$conection = mysql_connect("192.168.20.22", "root", "Axioma$3112$") or die ('no se puede conectar');
   
   $i = 0;
    //$buscar_empr = array();
     //echo ("<select name=\"documento\" id=\"documento\" class=\"form-control col-md-4\">");
    $top = $mysqli->query("SELECT nombre, org_id FROM arg_organizaciones") or die(mysqli_error());
                   while($rowtop = $top ->fetch_array(MYSQLI_ASSOC))
                        {
                            $i = $i+1;
                            $nombre_empr[$i]["nombre"] = $rowtop["nombre"];
                           // $nombre_empr[$i]["org_id"] = $rowtop["org_id"];
                           // $id_empr[$i] = $rowtop["org_id"];
                           // echo ("<option value=\"$nombre_empr\">$nombre_empr</option>");
                            //var_dump($buscar_empr);
                            //$nombretop = $rowtop["Nombre"];   
                        }
                //var_dump($nombre_empr);        
    
     //echo ("</select>");
     $total_reg = count($nombre_empr);  
     //echo $total_reg;  
     for($j=0; $j <= $total_reg; $j++){
        echo $nombre_empr[$j]['nombre'];
        //echo $nombre_empr[$j]['org_id'];
     }      
                        
    //var_dump($nombre_empr);
    //(die);
    $arreglo_php = array();
    
    //$count = count($buscar_empr);
    //echo $count;
    if($total_reg == 0){
        
        array_push($arreglo_php, 'no hay datos');
    }
    else{
        while($palabras = mysql_fetch_array($nombre_empr)){
            array_push($arreglo_php, $palabras["nombre"]);
        }
        
    }
   var_dump($nombre_empr);
   var_dump($arreglo_php);
    //(die);
    ?>
 </body>
 </head>  
