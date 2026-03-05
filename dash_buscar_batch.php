<?include "connections/config.php";


     $mysqli -> set_charset("utf8");

$html = '';
$q = $_POST['consulta'];
$unidad_id = $_SESSION['unidad_id'];

  //  if (isset($_POST['consulta'])) {
    	//$q = $conn->real_escape_string($_POST['consulta']);//($cons);
      //  $q = ($_POST['consulta']);
    	//$query = "SELECT * FROM busqueza_avanzada WHERE name LIKE '%$q%' ";
        $resultado = $mysqli->query("SELECT DISTINCT folio_interno, trn_id AS trn_id_batch, folio_inicial, folio_final
                                     FROM 
                                        arg_ordenes_detalle WHERE folio_interno LIKE '%".$q."%' ORDER BY folio_interno DESC"
                                     ) or die(mysqli_error());  //unidad_id = ".$unidad_id." ANDLIKE '%".$q."%'"
    // echo 'llego';
    if ($resultado->num_rows > 0) {  
         while ($row = $resultado->fetch_assoc()){
                     $resultado_metodo = $mysqli->query("SELECT DISTINCT met.nombre as metodo, om.metodo_id, met.nombre_largo as descripcion, folio_interno
                                                         FROM 
                                                            arg_ordenes_metodos AS om
                                                            LEFT JOIN arg_ordenes_detalle AS od
                                                                ON om.trn_id_rel = od.trn_id
                                                            LEFT JOIN arg_metodos AS met
                                                            	ON met.metodo_id = om.metodo_id
                                                         WHERE  od.folio_interno like '%".$row['folio_interno']."%'"
                                                         ) or die(mysqli_error());
                        
                       
                        $html .=    "<div class='card' >
                        <div class='row d-flex justify-content-between'>
                           <div class='col-8'>
                            <div class='d-flex justify-content-between'>
                                <h6>BATCH/ORDEN: <span class='text-primary font-weight-bold'>".$row['folio_interno']."</span></h6>
                                
                                <h6>Muestra Inicial: <span class='text-primary font-weight-bold'>".$row['folio_inicial']."</span> A <span class='text-primary font-weight-bold'>".$row['folio_final']."</span></h6>
                            </div>
                          </div>
                        </div>";
                        
                        while ($row_metodo = $resultado_metodo->fetch_assoc()){
                            $html .= "<div>
                                        <br/>
                                        <h6>METODO DE ANALISIS: <span class='text-primary font-weight-bold'>".$row_metodo['metodo'].' - '.$row_metodo['descripcion']."</span></h6>";
                                       $resultado_fase = $mysqli->query("SELECT
    `od`.`id` AS `id`,
    `od`.`trn_id` AS `trn_id_batch`,
    `od`.`folio_interno` AS `folio_interno`,
    `od`.`folio_inicial` AS `folio_inicial`,
    `od`.`folio_final` AS `folio_final`,
    `om`.`metodo_id` AS `metodo_id`,
    `m`.`nombre` AS `metodo`,
    `m`.`nombre_largo` AS `descripcion`,
    `mf`.`fase_id` AS `fase_id`,
    `f`.`nombre` AS `fase`,
    `od`.`estado` AS `estado`,
    `buscar_fase`(`od`.`trn_id_rel`, `om`.`metodo_id`) AS `ult_fase_id`,
    (
        CASE WHEN(
            (`mf`.`fase_id` = 1) AND(`od`.`estado` <> 0)
        ) THEN 1 ELSE(
            CASE WHEN(
                DATE_FORMAT(`bit`.`fecha`, '%d-%m-%Y') <> ''
            ) THEN 1 ELSE 0
        END
    )
END
) AS `terminado`,
`cantidad_fases`(`om`.`metodo_id`) AS `porcentaje_fase`
FROM
     `arg_ordenes_detalle` `od`
                        LEFT JOIN `arg_ordenes_metodos` `om`
                        ON
                            `om`.`trn_id_rel` = `od`.`trn_id_rel`
                    LEFT JOIN `arg_ordenes` `ord`
                    ON
                       `ord`.`trn_id` = `od`.`trn_id_rel`
                    
                LEFT JOIN `arg_metodos_fases` `mf`
                ON
                    `mf`.`metodo_id` = `om`.`metodo_id`
                
            LEFT JOIN `arg_fases` `f`
            ON
                `f`.`fase_id` = `mf`.`fase_id`
            
        LEFT JOIN `arg_metodos` `m`
        ON
            `mf`.`metodo_id` = `m`.`metodo_id`
        
    LEFT JOIN(
        SELECT
            `ob`.`trn_id_rel` AS `trn_id_rel`,
            `ob`.`fase_id` AS `fase_id`,
            `ob`.`fecha` AS `fecha`,
            `ob`.`metodo_id` AS `metodo_id`
        FROM
            `arg_ordenes_bitacora` `ob`
    ) `bit`
ON
  
            `od`.`trn_id` = `bit`.`trn_id_rel`
            AND `mf`.`metodo_id` = `bit`.`metodo_id`
            AND `mf`.`fase_id` = `bit`.`fase_id`
        
  
WHERE
    `om`.`metodo_id` = ".$row_metodo['metodo_id']."
    AND `od`.`folio_interno` = '".$row_metodo['folio_interno']."' 
    ORDER BY `mf`.`orden`"
                                                                         ) or die(mysqli_error()); 
                                                              
                                            $html .= "<div class='progress'>";
                                            while ($row_det = $resultado_fase->fetch_assoc()){
                                               // echo 'ter';//.$row_det['terminado'] ;
                                                    if($row_det['terminado'] == 1 ){
                                                        $html .= "<div class='progress-bar progress-bar-striped bg-success' role='progressbar' aria-valuenow='15' aria-valuemin='0' aria-valuemax='100' style='height:auto; width:".$row_det['porcentaje_fase']."%'>
                                                                         <a type='button' onclick='llama_datos(".$row_metodo['metodo_id'].', '.$row['trn_id_batch'].', '.$row_det['fase_id'].");'>".$row_det['fase'].' '.$row_det['porcentaje_fase'].' %'."</a><br/>
                                                                  </div>";
                                                   }
                                                    else{
                                                            $html .= " <div class='progress-bar bg-secondary' role='progressbar' aria-valuenow='10' aria-valuemin='0' aria-valuemax='100' style='height:auto; width:".$row_det['porcentaje_fase']."%'>
                                                                            <a type='button' '>".$row_det['fase'].' '.$row_det['porcentaje_fase'].' %'."</a>
                                                                        </div>";                                   
                                                    }
                                          }
                            $html .= "</div></div>";
                           
                        }
                     $html .= "</div></div></div></div>";
     }
     }
     //$mysqli -> set_charset("utf8");
     echo ($html);
?>