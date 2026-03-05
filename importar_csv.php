<?
/**
 * facturas_visor_lista.php v0.1
 * ----------------------------------------
 * Lista del visor de facturas
 **/
include "../connections/config.php";
$unidad_id = $_GET['unidad_id'];
//echo $trn_id;
?>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style type="text/css">
	.izq{
		background-color:;
	}
	.derecha{
		background-color:;
	}
	.btnSubmit
    {
        width: 50%;
        border-radius: 1rem;
        padding: 1.5%;
        border: none;
        cursor: pointer;
    }
    .circulos{
    	padding-top: 5em;
    }
    img{
      max-width: 100%;
    }
</style>

$action='';
$user_id = $_GET['u_id'];
////  ***** BOTON EDIT ACTIVARLO *****  
//<H1 class = "miclase"> Click on V to change category costs </H1>

?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<head> 
    
    <div style="float: right; margin-right: 120px;margin-top: 600px;">
	    <a href="index.php"> <img src="http://argonaut.zairus.com:81/__pro/argonaut/store/Images/Argonaut_Logo.jpg" style="width:750px;height:337;border:1;"> </a>
    </div>    
    
    <H1 class = "miclase"> Select the Excel file to Import (.xlsx):  </H1>     
    <img src="http://argonaut.zairus.com:81/__pro/argonaut/store/images/ExcelLogo.png" width="95" height="85" /><p> 
    <form name="importa" method="post" action="<?php echo $PHP_SELF; ?>" enctype="multipart/form-data" >
    <input type="file" name="excel" />
    <input type='submit' name='enviar' value="Importar"/>
    <input type="hidden" value="upload" name="action"/> 
    </form>
    
    <p>    
    
     <form name="ver" method="post" action="<?php echo $PHP_SELF; ?>" enctype="multipart/form-data">    
        <input type='submit' name='ver' value="Ver Clasificacion" />
        <input type="hidden" value="ver" name="action" />  
    </form>
    
    
    <div class="container">
<h2>Cargar e importar archivo excel a MySQL</h2>
<form name="importa" method="post" action="" enctype="multipart/form-data" >
  <div class="col-xs-4">
    <div class="form-group">
      <input type="file" class="filestyle" data-buttonText="Seleccione archivo" name="excel">
    </div>
  </div>
  <div class="col-xs-2">
    <input class="btn btn-default btn-file" type='submit' name='enviar'  value="Importar"  />
  </div>
  <input type="hidden" value="upload" name="action" />
  <input type="hidden" value="usuarios" name="mod">
  <input type="hidden" value="masiva" name="acc">
</form>
</div>
    
        
    <br />
    <br />
    <br />
    
<style type="text/css">    
        input[type=file], input[type=submit], input[type=reset] {
        background-color: #eadb96;
        color: black;
        margin-top: 10px;
        margin-right: 10px;
        padding: 12px 10px;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
        border: 1px solid #CCC;       
    }
    
    H1.miclase {
        border-width: 1px; 
        text-align: left;
        margin-top: 20px;
        color: #8c7405;
        font-size:2em;
    }
</style>       
    
    <script> 
        function ver(sucursal_id,user_id){
             window.location.href='ver_clasificacion.php?sucursal_id='+sucursal_id+'&user_id='+user_id
        }    
    </script>
    
    <script> 
        function produccion_mensual_valida(vinculo,archivo,user_id){
             window.location.href='produccion_mensual_valida.php?vinculo='+vinculo+'&archivo='+archivo+'&user_id='+user_id
        }
    </script>
    
     <script> 
        function produccion_mensual(vinculo,archivo,user_id){
             window.location.href='produccion_mensual.php?vinculo='+vinculo+'&archivo='+archivo+'&user_id='+user_id
        }
    </script>
    
    <script> 
        function human_resources_hc(vinculo,archivo,user_id){
             window.location.href='human_resources_hc.php?vinculo='+vinculo+'&archivo='+archivo+'&user_id='+user_id
        }
    </script>
    
    <script> 
        function insertar_costos_sap(vinculo,archivo,user_id, moneda){
             window.location.href='insertar_costos_sap.php?vinculo='+vinculo+'&archivo='+archivo+'&user_id='+user_id+'&moneda='+moneda
        }
    </script>
    
    
    
    <?    
        extract($_POST);        
        if ($action == "upload") //si action tiene como valor UPLOAD haga algo (el value de este hidden es es UPLOAD iniciado desde el value
        {
        //cargamos el archivo al servidor con el mismo nombre(solo le agregue el sufijo bak_)            
            $archivo = $_FILES['excel']['name']; //captura el nombre del archivo
            $tipo = $_FILES['excel']['type']; //captura el tipo de archivo (2003 o 2007)
            $dest = 'c:\\xampp\\htdocs\\__pro\\argonaut\\VinculosKpi'.'\\ '; //lugar donde se copiara el archivo
            $desti = rtrim($dest).$archivo;      
                        
            if (copy($_FILES['excel']['tmp_name'],$desti)) //si dese copiar la variable excel (archivo).nombreTemporal a destino (bak_.archivo) (si se ha dejado copiar)
                {
                     $query = "SELECT sucursal_id, ejercicio, tipo, moneda, vinculo FROM eds_php_vinculos WHERE archivo = '".$archivo."'";
                     $datos = eds_data_query($query, $sys_link, $db_srv);
                     $sucursal_id = $datos[1]['sucursal_id'];
                     $ejercicio = $datos[1]['ejercicio'];
                     $tipo = $datos[1]['tipo'];                 
                     $vinculo = $datos[1]['vinculo'];
                     $moneda = $datos[1]['moneda'];
                     //print $vinculo;
                         
                    ?>
                        <H1 class = "miclase"> Successfully loaded file  </H1>
                    <? 
                    if ($vinculo == 1){               
                       echo "<script>";
                       $query = "DELETE FROM eds_conc_temporal";
                       $documento = eds_data_query($query, $sys_link, $db_srv);
                       echo "produccion_mensual_valida(".$vinculo.",'".$archivo."',".$user_id.");";
                       echo "</script>";                    
                    }
                     /*else{
                        $query = "EXEC _kpi_prc_DatosVinculados ".$ejercicio;
                        $documento = eds_data_query($query, $sys_link, $db_srv);
                        if (count($documento)>0){   
                           echo "<script>";
                           echo "clasificar_costos(".$sucursal_id.",".$tipo.",".$user_id.",".$ejercicio.");";
                           echo "</script>";
                        }
                        else
                            {
                                echo "<script>";
                                echo "actualizar_presupuestos(".$sucursal_id.",".$tipo.",".$user_id.",".$ejercicio.");";
                                echo "</script>";     
                            }
                        }*/
                    else {
                            if ($vinculo == 99) //Creacion de nuevos kpi
                            {
                               echo "<script>";
                               $query = "DELETE FROM eds_conc_temporal";
                               $documento = eds_data_query($query, $sys_link, $db_srv);
                               echo "produccion_mensual(1".",'".$archivo."',".$user_id.");";
                               echo "</script>";                              
                            }
                            else{ 
                                    if ($vinculo == 2) //Human Resources
                                    { 
                                       echo "<script>";
                                       $query = "DELETE FROM eds_conc_temporal";
                                       $documento = eds_data_query($query, $sys_link, $db_srv);
                                       echo "human_resources_hc(2".",'".$archivo."',".$user_id.");";
                                       echo "</script>";  
                                    }
                                    else{
                                           echo "<script>";
                                           $query = "DELETE FROM eds_conc_tempSap";
                                           $documento = eds_data_query($query, $sys_link, $db_srv);
                                           echo "insertar_costos_sap(".$vinculo.",'".$archivo."',".$user_id.",".$moneda.");";
                                           echo "</script>";
                                    }  
                                }
                    }
                }
            else
               {
                    echo "Error Al Cargar el Archivo";
                }
            } 
        else 
            {
                if ($action == "ver") 
                {       
                    $query = "SELECT [sucursal] = SUBSTRING(CONVERT(VARCHAR(10), value), 1, 1) FROM eds_sys_usuarios_directivas WHERE directiva_id = 5 AND u_id = ".$user_id;
                    $usuario = eds_data_query($query, $sys_link, $db_srv);
                    $sucursal_id = $usuario[1]['sucursal'];
                    
                    echo "<script>";                  
                    echo "ver(".$sucursal_id.",".$user_id.");";
                    echo "</script>";
                 }
        }
        
//Desconectarse al servicio de datos.
include '\xampp\htcore\scripts\\' . $db_srv . '\user_disconnect.php';
//echo <div style='span:center;colr:red;margin:0 auto;width:1000px'>ARGONAUT GOLD INC.</div>;
// <img src="http://argonaut.zairus.com:81/__pro/argonaut/store/images/ExcelLogo.png" width="60" height="60" /><p> 
?>