<?php
    include "connections/config.php";
    include "Classes/Enums/etapas.php";
?>
<link href="http://192.168.20.22/intranet-spa/css/check.css" rel="stylesheet"> 
<?php
$html = '';
$trn_id = $_POST['trn_id'];
$etapa_id = $_POST['etapa_id'];
$u_id = $_SESSION['u_id'];

if (isset($trn_id)){
    $mysqli -> set_charset("utf8");
    $orden_tr = $mysqli->query("SELECT odet.folio_interno 
                                  FROM arg_ordenes ord
                                  LEFT JOIN arg_ordenes_detalle odet
                                        ON ord.trn_id =  odet.trn_id_rel
                                  WHERE odet.trn_id = ".$trn_id) or die(mysqli_error($mysqli));

    $orden_tra = $orden_tr->fetch_assoc();
    $orden_trabajo = $orden_tra['folio_interno'];

    if($etapa_id == Etapas::QUEBRADO){
        $existe_quebr = $mysqli->query("SELECT
                                            pul.trn_id                                       
                                        FROM 
                                            arg_muestras_quebrado pul
                                        WHERE
                                            pul.trn_id = ".$trn_id) or die(mysqli_error($mysqli)); 
        
        if ($existe_quebr->num_rows == 0) {
            $resultado_ree = $mysqli->query("SELECT 
                                                    ree.trn_id_muestra
                                                    ,ree.folio_interno AS muestra
                                            FROM 
                                                    ordenes_reensayes ree
                                            WHERE
                                                ree.trn_id_rel = ".$trn_id."
                                                AND ree.inicio_proceso = 0                                                    
                                                AND ree.tipo_id IN(3, 5)
                                            UNION ALL
                                            SELECT 
                                                    ree.trn_id_muestra
                                                    ,ree.folio_interno AS muestra
                                            FROM 
                                                    ordenes_reensayes ree
                                            WHERE
                                                ree.trn_id_rel = ".$trn_id."
                                                AND ree.inicio_proceso = 0                                                    
                                                AND ree.tipo_id = 0
                                            LIMIT 2") or die(mysqli_error($mysqli));
                
            $html =  "<table class='table text-black' id='tabla_quebrado'>
                        <thead class='table-info' align='left'>
                            <tr class='table-warning' align='center'>
                                    <th colspan='9'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                            </tr> 
                                <tr>
                                <th>Muestra</th>
                                <th>Peso</th>
                                <th>Malla</th>
                                <th>%</th>
                                <th>Comentario</th>                                    
                        </thead>
                        <tbody>";
                 
            $con = 1;      
            while ($res = $resultado_ree->fetch_assoc()) {      
                $trn_id_rel = $res['trn_id_muestra'];
                $muestra = $res['muestra'];

                $mysqli->query("INSERT INTO arg_muestras_quebrado (trn_id, trn_id_rel, peso, peso_malla, porcentaje, comentario)
                                VALUES ($trn_id, $trn_id_rel, 0, 0, 0, '')");  
                                                                
                $html.="<tr>                                   
                            <td style='display:none;'> <input type='input' id='trn_batch_q".$con."' value='".$trn_id."'/></td>  
                            <td style='display:none;'> <input type='input' id='trn_batch_relq".$con."' value='".$trn_id_rel."'/></td>                             
                            <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra."' disabled></td> 
                            <td> <input type='number' name='peso_que".$con."' id='peso_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                            <td> <input type='number' name='peso_malla_que".$con."' id='peso_malla_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                            <td> <input type='number' name='porc_que".$con."' id='porc_que".$con."' class='form-control' disabled/> </td>
                            <td> <input type='text' name='comentario_que".$con."' id='comentario_que".$con."' class='form-control' disabled /></td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save_quebrado' onclick='quebrado_guardar(".$trn_id.", ".$trn_id_rel.", ".$con.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                            </button></td>
                        </tr>";                                            
                $con = $con++;                    
            }
        }
        else {
            $html =  "<table class='table text-black' id='tabla_quebrado'>
                        <thead class='table-info' align='left'>
                            <tr class='table-warning' align='center'>
                                    <th colspan='9'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                            </tr> 
                        <tr>
                            <th>Muestra</th>
                            <th>Peso</th>
                            <th>Malla</th>
                            <th>%</th>
                            <th>Comentario</th>                                    
                        </thead>
                        <tbody>";
                                
            $con = 0;
            $existen_quebrado = $mysqli->query("SELECT
                                                    pul.trn_id,
                                                    pul.trn_id_rel,
                                                    ROUND(pul.peso, 2) AS peso,
                                                    ROUND(pul.peso_malla, 2) AS peso_malla,
                                                    ROUND(pul.porcentaje, 2) AS porcentaje,
                                                    pul.comentario,
                                                    r.folio_interno AS muestra
                                                FROM 
                                                arg_muestras_quebrado pul
                                                LEFT JOIN ordenes_reensayes r
                                                    ON r.trn_id_rel    = pul.trn_id
                                                    AND r.trn_id_muestra = pul.trn_id_rel
                                                WHERE
                                                pul.peso = 0 AND pul.peso_malla = 0 AND  pul.trn_id = ".$trn_id) or die(mysqli_error($mysqli));
            
            if ($existen_quebrado->num_rows > 0) {
                while ($res_muestras = $existen_quebrado->fetch_assoc()) {
                    $con = $con+1;    
                    $trn_id_batch = $res_muestras['trn_id'];
                    $trn_id_rel = $res_muestras['trn_id_rel'];
                    $muestra = $res_muestras['muestra'];
                    $coment_que = $res_muestras['comentario'];  
                    $html.="<tr>                                  
                                    <td style='display:none;'> <input type='input' id='trn_batch_q".$con."' value='".$trn_id_batch."'/></td>  
                                    <td style='display:none;'> <input type='input' id='trn_batch_relq".$con."' value='".$trn_id_rel."'/>".$muestra."</td>                             
                                    <td> <input type='input' name='trn_rel_que".$con."' class='form-control' id='trn_rel_que".$con."' value='".$muestra."' disabled></td> 
                                    <td> <input type='number' name='peso_que".$con."' id='peso_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                    <td> <input type='number' name='peso_malla_que".$con."' id='peso_malla_que".$con."' class='form-control' onchange='calcula_porc(".$con.")' /> </td>
                                    <td> <input type='number' name='porc_que".$con."' id='porc_que".$con."' class='form-control' disabled/> </td>
                                    <td> <input type='text' name='comentario_que".$con."' id='comentario_que".$con."' value='".$coment_que."' class='form-control' disabled /></td>
                                    <td> <button type='button'class='btn btn-primary' id='boton_save_quebrado' onclick='quebrado_guardar(".$trn_id_batch.", ".$trn_id_rel.", ".$con.")' >
                                            <span class='fa fa-cloud fa-1x'></span>
                                    </button></td>
                            </tr>"; 
                }
            }  
        }      
           
        $html .= "</tbody></table>"; 
        echo $html; 
    }
    
    if($etapa_id == Etapas::PULVERIZADO) {        
        $existen_pulv = $mysqli->query("SELECT pul.trn_id 
                                        FROM arg_muestras_pulverizado pul
                                        WHERE pul.trn_id = ".$trn_id) or die(mysqli_error($mysqli));
        
        if ($existen_pulv->num_rows == 0) {
            $total_reensayes = $mysqli->query("SELECT 
                                                    ree.trn_id_muestra
                                                ,ree.folio_interno AS muestra
                                            FROM 
                                                    ordenes_reensayes ree
                                            WHERE
                                                ree.trn_id_rel = ".$trn_id."
                                                AND ree.inicio_proceso = 0 ") or die(mysqli_error($mysqli));
            $total_ree = (mysqli_num_rows($total_reensayes));
            $porcentaje = 20;
            $limite = ROUND(($total_ree*$porcentaje)/100);
            $resultado_pul = $mysqli->query("SELECT * FROM (SELECT 
                                                    ree.trn_id_muestra
                                                ,ree.folio_interno AS muestra
                                            FROM 
                                                    ordenes_reensayes ree
                                            WHERE
                                                ree.trn_id_rel = ".$trn_id."
                                                AND ree.inicio_proceso = 0
                                            LIMIT 1) AS x 
                                            ORDER BY muestra") or die(mysqli_error($mysqli));
                
            $html =  "<table class='table text-black' id='tabla_pulverizado'>
                        <thead class='table-info' align='left'>
                            <tr class='table-warning' align='center'>
                                    <th colspan='9'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                            </tr> 
                        <tr>
                            <th>Muestra</th>
                            <th>Peso</th>
                            <th>Malla</th>
                            <th>% Pulv</th>
                            <th colspan='2'>Comentario</th>                                    
                        </thead>
                        <tbody>";
                 
            $con = 1;      
            while ($res = $resultado_pul->fetch_assoc()) {      
                $trn_id_rel = $res['trn_id_muestra'];
                $muestra = $res['muestra'];

                $mysqli->query("INSERT INTO arg_muestras_pulverizado (trn_id, trn_id_rel, peso, peso_malla, porcentaje, comentario)
                                VALUES ($trn_id, $trn_id_rel, 0, 0, 0, '')");

                $html.="<tr>                                  
                            <td style='display:none;'> <input type='input' id='trn_batch_pul".$con."' value='".$trn_id."'/></td>  
                            <td style='display:none;'> <input type='input' id='trn_rel_pul".$con."' value='".$trn_id_rel."'/></td>                             
                            <td> <input type='input' name='trn_rel_pul".$con."' class='form-control' id='trn_rel_pul".$con."' value='".$muestra."' disabled></td> 
                            <td> <input type='number' name='peso_pul".$con."' step='.01' id='peso_pul".$con."' class='form-control' onchange='calcula_porc_pulv(".$con.")' /> </td>
                            <td> <input type='number' name='peso_malla_pul".$con."' id='peso_malla_pul".$con."' class='form-control' onchange='calcula_porc_pulv(".$con.")' /> </td>
                            <td> <input type='number' name='porc_pul".$con."' id='porc_pul".$con."' class='form-control' disabled/> </td>
                            <td> <input type='text' name='comentario_pul".$con."' id='comentario_pul".$con."' class='form-control' disabled /></td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save_pulverizado' onclick='pulverizado_guardar(".$trn_id.", ".$trn_id_rel.", ".$con.")' >
                                        <span class='fa fa-cloud fa-1x'></span>
                                    </button>
                            </td>
                        </tr>";
                $cont = $cont++;                    
            }
        }
        else { 
            $html =  "<table class='table text-black' id='tabla_pulverizado'>
                        <thead class='table-info' align='left'>
                            <tr class='table-warning' align='center'>
                                    <th colspan='9'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                            </tr> 
                        <tr>
                            <th>Muestra</th>
                            <th>Peso</th>
                            <th>Malla</th>
                            <th>% Pulv</th>
                            <th colspan='2'>Comentario</th>                                    
                        </thead>
                        <tbody>";
                                
            $con = 0;
            $existen_pulverizado = $mysqli->query("SELECT
                                                        pul.trn_id,
                                                        pul.trn_id_rel,
                                                        ROUND(pul.peso, 2) AS peso,
                                                        ROUND(pul.peso_malla, 2) AS peso_malla,
                                                        ROUND(pul.porcentaje, 2) AS porcentaje,
                                                        pul.comentario,
                                                        r.folio_interno AS muestra
                                                    FROM 
                                                    arg_muestras_pulverizado pul
                                                    LEFT JOIN ordenes_reensayes r
                                                        ON r.trn_id_rel = pul.trn_id
                                                        AND r.trn_id_muestra = pul.trn_id_rel
                                                    WHERE
                                                    pul.peso = 0 AND pul.peso_malla = 0 AND  pul.trn_id = ".$trn_id) or die(mysqli_error($mysqli));
                    
            if ($existen_pulverizado->num_rows > 0) {
                while ($res_muestras = $existen_pulverizado->fetch_assoc()) {
                    $con = $con+1;  
                    $trn_id_rel = $res_muestras['trn_id_rel'];
                    $muestra = $res_muestras['muestra'];
                    $coment_que = $res_muestras['comentario'];  
                    $html.="<tr>                                  
                                <td style='display:none;'> <input type='input' id='trn_batch_pul".$con."' value='".$trn_id."'/></td>  
                                <td style='display:none;'> <input type='input' id='trn_rel_pul".$con."' value='".$trn_id_rel."'/>".$res_muestras['muestra']."</td>                             
                                <td> <input type='input' name='trn_rel_pul".$con."' class='form-control' id='trn_rel_pul".$con."' value='".$muestra."' disabled></td> 
                                <td> <input type='number' name='peso_pul".$con."' step='.01' id='peso_pul".$con."' class='form-control' onchange='calcula_porc_pulv(".$con.")' /> </td>
                                <td> <input type='number' name='peso_malla_pul".$con."' id='peso_malla_pul".$con."' class='form-control' onchange='calcula_porc_pulv(".$con.")' /> </td>
                                <td> <input type='number' name='porc_pul".$con."' id='porc_pul".$con."' class='form-control' disabled/> </td>
                                <td> <input type='text' name='comentario_pul".$con."' id='comentario_pul".$con."' class='form-control' disabled /></td>
                                <td> <button type='button'class='btn btn-primary' id='boton_save_pulverizado' onclick='pulverizado_guardar(".$trn_id.", ".$trn_id_rel.", ".$con.")' >
                                            <span class='fa fa-cloud fa-1x'></span>
                                        </button>
                                </td>
                            </tr>"; 
                }
            }
            else {
                $etapaPulverizadoId = Etapas::PULVERIZADO;
                $result = mysqli_multi_query($mysqli, "CALL arg_generar_bitacora($trn_id, $u_id)") OR DIE(mysqli_error($mysqli));
                $result = mysqli_query($mysqli,
                    "UPDATE arg_ordenes_bitacora_detalle
                        SET u_id_fin = $u_id,
                            fecha_fin = DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s')
                    WHERE trn_id_rel = $trn_id
                        AND etapa_id = $etapaPulverizadoId"
                ) OR DIE(mysqli_error($mysqli));

                $html = "<div class='alert alert-success' role='alert'>
                            <h4 class='alert-heading'>¡Proceso de pulverizado finalizado!</h4>
                            <p>Se ha registrado correctamente el proceso de pulverizado para la orden de trabajo: <strong>".$orden_trabajo."</strong>.</p>
                            <hr>
                            <p class='mb-0'>Puede continuar con el siguiente proceso.</p>
                        </div>";
            } 
        }

        echo $html;
    }
}
?>