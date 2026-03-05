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
$consultaBusqueda = $_POST['query'];
$nombre = $_POST['nombre'];

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
    if (isset($consultaBusqueda)) {	
    //$query = "SELECT DISTINCT nombre FROM arg_organizaciones WHERE nombre LIKE '%".trim($consultaBusqueda)."%'";
    
   /* $statement = $connect->prepare($query);
    $statement -> execute();
    $result = $statement->fetchAll();*/
   /* $query = ("SELECT nombre                             
                                     FROM arg_organizaciones             
                                     WHERE nombre LIKE '%".$consultaBusqueda."%'");*/
    $buscar_empresa = $mysqli->query("SELECT nombre                             
                                     FROM arg_organizaciones             
                                     WHERE nombre LIKE '%".$consultaBusqueda."%'");
    $buscar_empr = $buscar_empresa->fetch_array(MYSQLI_ASSOC);
   //$buscar_empr = $buscar_empresa->fetch_array(MYSQLI_ASSOC);
    
    //$query("SELECT nombre FROM arg_organizaciones WHERE nombre LIKE '%".($_POST['query'])."%'");
    
  //  $statement = $connect->prepare($query);
    //$statement -> execute();
    //$result = $statement->fetchAll();
 
   // var_dump($buscar_empr);
  //  var_dump($result);
    //(die);
    $output = '';
   // $num = 0;
    foreach($buscar_empr as $row){
        //echo $num;
        $output .= '
          <li class="list-group-item contsearch">
                <a href="javascript:void()" class="gsearch">'.$buscar_empr["nombre"].'</a>
          </li>  
        ';
        //$num++;
        
    }
    echo $output;
 }
 
 
 
 //echo $nombre;
 
 //if (isset($_POST['nombre'])){
 if (isset($nombre)){
     $buscar_empresa = $mysqli->query("SELECT nombre                             
                                     FROM arg_organizaciones             
                                     WHERE nombre = '".trim($nombre)."' LIMIT 1");
     $buscar_empr = $buscar_empresa->fetch_array(MYSQLI_ASSOC);
     //var_dump($buscar_empr);
    // (die);
     foreach($buscar_empr as $row){
        //echo $num;
        $output .= $buscar_empr["nombre"]; //'<a>'.$buscar_empr["nombre"].'</a>';
        //$num++;
        
    }
    echo $output;
    
 }
    /*
 IF ($_POST['valorBusqueda']){
    $query = str_replace("TOP 20", "TOP 100 PERCENT", $query);    
   
    $query .= "WHERE unidad_id = ".$sucursal." AND buscar LIKE '%".$consultaBusqueda."%'";
    
 }*/ 
 
    /*$buscar_empresa = $mysqli->query("SELECT org_id, nombre                             
                                     FROM arg_organizaciones             
                                     WHERE nombre LIKE '%".$consultaBusqueda."%'");
    $buscar_empr = $buscar_empresa->fetch_array(MYSQLI_ASSOC);*/
 
   // var_dump($buscar_empr);
    //(die);
	//Obtiene la cantidad de filas que hay en la consulta
	/*$filas = count($buscar_empr);
    $mensaje = $buscar_empr['nombre'];*/
    //echo $mensaje;
	//Si no existe ninguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
	/*if ($filas == 0) {
		$mensaje = "<p>No hay ninguna organización con ese nombre. Favor de crearla</p>";
        //echo $mensaje;
	} */
 
    ?>
 </body>
 </head>  
