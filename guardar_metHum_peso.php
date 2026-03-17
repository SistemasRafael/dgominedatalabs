<?php
include "connections/config.php";

$html = '';
$trnid_m    = $_POST['trnid_orden'];
$trnidrel_m = $_POST['trnid_muestra'];
$metodo_sel = $_POST['metodo_id'];
$fase_sel   = $_POST['fase'];
$etapa_sel  = $_POST['etapa'];
$cantidad   = $_POST['cantidad_metodo'];
$final      = $_POST['fin_met'];
$unidad_id  = $_POST['unidad'];
$u_id       = $_SESSION['u_id'];

if (isset($trnid_m)){
    mysqli_multi_query ($mysqli, "CALL arg_prc_ordenMetodoPesoHum(".$trnid_m.", ".$trnidrel_m.", ".$metodo_sel.", ".$fase_sel.", ".$etapa_sel.", ".$cantidad.", ".$u_id.",".$final.")") OR DIE (mysqli_error($mysqli));
    $resultado_efaa = $mysqli->query(" SELECT metodo, fase, etapa
                                        FROM ordenes_fases_etapas
                                        WHERE trn_id_rel = ".$trnid_m." AND fase_id = ".$fase_sel." AND etapa_id = ".$etapa_sel
                                        ) or die(mysqli_error($mysqli));
    $tipo_orden = $mysqli->query("SELECT 
                                        (CASE WHEN ord.tipo = 0 THEN 1 ELSE 0 END) AS reensaye 
                                        ,odet.folio_interno
                                    FROM arg_ordenes ord
                                    LEFT JOIN arg_ordenes_detalle odet
                                        ON ord.trn_id =  odet.trn_id_rel
                                    WHERE odet.trn_id = ".$trnid_m) or die(mysqli_error($mysqli));             
    $tipo_ord = $tipo_orden->fetch_assoc();
    $reensaye = $tipo_ord['reensaye'];
    $orden_trabajo = $tipo_ord['folio_interno'];
      
    if ($metodo_sel == 9) {   
        $resultado = $mysqli->query("SELECT
                                            mc.trn_id AS trn_id_batch
                                        ,mc.trn_id_rel
                                        ,mm.folio_interno AS muestra
                                        ,(CASE WHEN mm.tipo_id = 0 THEN muestra_geologia ELSE mm.control END) AS control
                                    FROM 
                                        `arg_muestras_cianurado` mc
                                        LEFT JOIN ordenes_metalurgia AS mm
                                            ON  mc.trn_id     = mm.trn_id_batch
                                            AND mc.trn_id_rel = mm.trn_id_rel
                                            AND mc.metodo_id = mm.metodo_id                                                
                                    WHERE 
                                        mc.trn_id = ".$trnid_m."
                                        AND mc.metodo_id = ".$metodo_sel."
                                        AND mc.peso = 0
                                    ORDER BY 
                                        mm.bloque, mm.folio_interno") 
                                        or die(mysqli_error($mysqli));
    }

    if ($metodo_sel == 30) {       
        $resultado = $mysqli->query("SELECT
                                        hum.trn_id_batch
                                        ,hum.trn_id_rel
                                        ,mm.folio_interno AS muestra
                                    FROM 
                                        `arg_ordenes_humedad` hum
                                        LEFT JOIN ordenes_metalurgia AS mm
                                            ON hum.trn_id_batch = mm.trn_id_batch
                                            AND hum.trn_id_rel = mm.trn_id_rel                                                
                                    WHERE 
                                        hum.trn_id_batch = ".$trnid_m."
                                        AND mm.metodo_id = ".$metodo_sel."
                                        AND (CASE WHEN ".$etapa_sel." = 27 THEN hum.peso_charola = 0 
                                                    WHEN ".$etapa_sel." = 28 THEN hum.peso_humedo = 0 
                                                    WHEN ".$etapa_sel." = 1 THEN hum.peso_seco = 0 
                                        END)") 
                                            or die(mysqli_error($mysqli));
    }

    if ($metodo_sel == 31) {       
        if ($etapa_sel == 5) {
            $peso_all_ori = $mysqli->query("SELECT COUNT(*) AS peso_ori                                                    
                                            FROM 
                                            `arg_muestras_cianurado` mc
                                                LEFT JOIN ordenes_metalurgia ol
                                                ON mc.trn_id = ol.trn_id_batch
                                                AND mc.trn_id_rel = ol.trn_id_rel
                                                AND mc.metodo_id = ol.metodo_id
                                            WHERE 
                                            mc.trn_id  = ".$trnid_m."
                                            AND mc.metodo_id = ".$metodo_sel."
                                            AND ol.tipo_id = 0
                                            AND mc.peso_original = 0") or die(mysqli_error($mysqli)); 
            $elpeso_orig = $peso_all_ori->fetch_assoc();
            $peso_or = $elpeso_orig['peso_ori'];
                        
            if ($peso_or > 0) {    
                $nombrecampo  = 'Peso Original';
                $peso_sig_fil = 'peso_original = 0 AND tipo_id = 0';     
            }
            else {
                $peso_all_actu = $mysqli->query("SELECT COUNT(*) AS peso200                                                    
                                        FROM 
                                            `arg_muestras_cianurado` mc
                                                LEFT JOIN ordenes_metalurgia ol
                                                ON mc.trn_id = ol.trn_id_batch
                                                AND mc.trn_id_rel = ol.trn_id_rel
                                                AND mc.metodo_id = ol.metodo_id                                                
                                        WHERE 
                                            mc.trn_id  = ".$trnid_m."
                                            AND mc.metodo_id = ".$metodo_sel."
                                            AND ol.tipo_id = 0
                                            AND mc.peso = 0") or die(mysqli_error($mysqli));             
                $elpeso = $peso_all_actu->fetch_assoc();
                $peso_sig2 = $elpeso['peso200'];
                        
                if($peso_sig2 > 0) {
                    $nombrecampo = 'Peso malla+200 g';
                    $peso_sig_fil = 'peso = 0 AND tipo_id = 0';
                }
                else {
                    $nombrecampo = 'Peso de muestra g';
                    $peso_sig_fil = 'peso = 0 AND tipo_id IN (99, 1, 2)';
                }
            }
            
            $resultado = $mysqli->query("SELECT 
                                        mq.trn_id_batch
                                    ,mq.trn_id_rel
                                    ,mq.metodo_id
                                    ,mq.folio_interno AS muestra
                                    ,(CASE WHEN mq.tipo_id = 0 THEN ''
                                        ELSE mq.control END) AS control
                                FROM 
                                    arg_muestras_cianurado mc
                                    LEFT JOIN ordenes_metalurgia mq
                                        ON mc.trn_id = mq.trn_id_batch
                                        AND mc.trn_id_rel = mq.trn_id_rel
                                        AND mc.metodo_id = mq.metodo_id
                                WHERE
                                    mq.trn_id_batch  = ".$trnid_m."  
                                    AND mq.metodo_id = ".$metodo_sel."
                                    AND ".$peso_sig_fil."
                                ORDER BY mq.bloque, mq.folio_interno") 
                                or die(mysqli_error($mysqli));
        }
        
        if ($etapa_sel == 19) {
            $resultado = $mysqli->query("SELECT 
                                            mq.trn_id_batch
                                        ,mq.trn_id_rel
                                        ,mq.metodo_id
                                        ,mq.folio_interno AS muestra
                                        ,(CASE WHEN mq.tipo_id = 0 THEN '' ELSE mq.control END) AS control
                                    FROM 
                                        arg_muestras_cianurado mc
                                        LEFT JOIN ordenes_metalurgia mq
                                            ON mc.trn_id = mq.trn_id_batch
                                            AND mc.trn_id_rel = mq.trn_id_rel
                                            AND mc.metodo_id = mq.metodo_id
                                    WHERE
                                        mq.trn_id_batch  = ".$trnid_m."  
                                        AND mq.metodo_id = ".$metodo_sel."
                                        AND mc.incuarte = 0
                                    ORDER BY
                                        mq.bloque, mq.posicion") 
                                    or die(mysqli_error($mysqli));
        }
    
        if ($etapa_sel == 6) {
            $resultado = $mysqli->query("SELECT 
                                            mq.trn_id_batch
                                        ,mq.trn_id_rel
                                        ,mq.metodo_id
                                        ,mq.folio_interno AS muestra
                                        ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                    FROM 
                                        arg_muestras_cianurado mc
                                        LEFT JOIN ordenes_metalurgia mq
                                            ON mc.trn_id = mq.trn_id_batch
                                            AND mc.trn_id_rel = mq.trn_id_rel
                                            AND mc.metodo_id = mq.metodo_id
                                    WHERE
                                        mq.trn_id_batch  = ".$trnid_m."  
                                        AND mq.metodo_id = ".$metodo_sel."
                                        AND mc.peso_payon = 0
                                    ORDER BY
                                        mq.bloque, mq.posicion") 
                                    or die(mysqli_error($mysqli));
        }

        if ($etapa_sel == 20) {
            $resultado = $mysqli->query("SELECT 
                                            mq.trn_id_batch
                                        ,mq.trn_id_rel
                                        ,mq.metodo_id
                                        ,mq.folio_interno AS muestra
                                        ,(CASE WHEN mq.tipo_id = 0 THEN '' ELSE mq.control END) AS control
                                    FROM 
                                        arg_muestras_cianurado mc
                                        LEFT JOIN ordenes_metalurgia mq
                                            ON mc.trn_id = mq.trn_id_batch
                                            AND mc.trn_id_rel = mq.trn_id_rel
                                            AND mc.metodo_id = mq.metodo_id
                                    WHERE
                                        mq.trn_id_batch  = ".$trnid_m."  
                                        AND mq.metodo_id = ".$metodo_sel."
                                        AND mc.peso_dore = 0
                                    ORDER BY
                                        mq.bloque, mq.posicion") 
                                    or die(mysqli_error($mysqli));
        }

        if ($etapa_sel == 21) {
            $resultado = $mysqli->query("SELECT 
                                            mq.trn_id_batch
                                        ,mq.trn_id_rel
                                        ,mq.metodo_id
                                        ,mq.folio_interno AS muestra
                                        ,(CASE WHEN mq.tipo_id = 0 THEN '' ELSE mq.control END) AS control
                                    FROM 
                                        arg_muestras_cianurado mc
                                        LEFT JOIN ordenes_metalurgia mq
                                            ON mc.trn_id = mq.trn_id_batch
                                            AND mc.trn_id_rel = mq.trn_id_rel
                                            AND mc.metodo_id = mq.metodo_id
                                    WHERE
                                        mq.trn_id_batch  = ".$trnid_m."  
                                        AND mq.metodo_id = ".$metodo_sel."
                                        AND mc.peso_oro = 0
                                    ORDER BY
                                        mq.bloque, mq.posicion") 
                                    or die(mysqli_error($mysqli));
        }  
    }
    elseif ($metodo_sel == 28) {
        $resultado = $mysqli->query("SELECT
                                        hum.trn_id
                                        ,hum.trn_id_rel
                                        ,mm.folio_interno  AS muestra
                                    FROM 
                                        `arg_muestras_impurezas` hum
                                        LEFT JOIN ordenes_metalurgia AS mm
                                            ON  hum.trn_id = mm.trn_id_batch
                                            AND hum.trn_id_rel = mm.trn_id_rel
                                            AND hum.metodo_id = mm.metodo_id
                                    WHERE 
                                        hum.trn_id = ".$trnid_m."
                                        AND hum.metodo_id = ".$metodo_sel."
                                        AND peso = 0") 
                                            or die(mysqli_error($mysqli));
    }
    elseif ($metodo_sel == 29) {
        $resultado = $mysqli->query("SELECT
                                        hum.trn_id_batch
                                        ,hum.trn_id_rel
                                        ,mm.folio  AS muestra
                                    FROM 
                                        `arg_ordenes_densidad` hum
                                        LEFT JOIN arg_ordenes_muestrasMetalurgia AS mm
                                            ON hum.trn_id_batch = mm.trn_id_rel
                                            AND hum.trn_id_rel = mm.trn_id
                                    WHERE 
                                        hum.trn_id_batch = ".$trnid_m."
                                        AND densidad = 0") 
                                            or die(mysqli_error($mysqli));
    }
    elseif ($metodo_sel == 33) { //Cianurado 
        if ($reensaye == 0){
            $resultado = $mysqli->query("SELECT 
                                        mq.trn_id_batch
                                    ,mq.trn_id_rel
                                    ,mq.metodo_id
                                    ,mq.folio_interno AS muestra
                                    ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                FROM 
                                    arg_muestras_cianurado mc
                                    LEFT JOIN ordenes_metalurgia mq
                                        ON mc.trn_id = mq.trn_id_batch
                                        AND mc.trn_id_rel = mq.trn_id_rel
                                        AND mc.metodo_id = mq.metodo_id
                                WHERE
                                    mq.trn_id_batch  = ".$trnid_m."  
                                    AND mq.metodo_id = ".$metodo_sel."
                                    AND mc.peso = 0
                                ORDER BY mq.folio_interno") 
                                or die(mysqli_error($mysqli));
        }                
        else{
            $resultado = $mysqli->query("SELECT 
                                        mq.trn_id_rel AS trn_id_batch
                                    ,mq.trn_id_muestra AS trn_id_rel
                                    ,mq.metodo_id
                                    ,mq.folio_interno AS muestra
                                    ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                FROM 
                                    arg_muestras_cianurado mc
                                    LEFT JOIN ordenes_reensayes_metal mq
                                        ON mc.trn_id = mq.trn_id_rel
                                        AND mc.trn_id_rel = mq.trn_id_muestra
                                        AND mc.metodo_id = mq.metodo_id
                                WHERE
                                    mq.trn_id_rel    = ".$trnid_m."  
                                    AND mq.metodo_id = ".$metodo_sel."
                                    AND mc.peso = 0
                                ORDER BY mq.folio_interno") 
                                or die(mysqli_error($mysqli));
        }
    }
    elseif ($metodo_sel == 35) { //CB_DIGAg 
        if ($reensaye == 0) {
            $resultado = $mysqli->query("SELECT 
                                                mq.trn_id_batch
                                            ,mq.trn_id_rel
                                            ,mq.metodo_id
                                            ,mq.folio_interno AS muestra
                                            ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                        FROM 
                                            arg_muestras_cianurado mc
                                            LEFT JOIN ordenes_metalurgia mq
                                                ON mc.trn_id = mq.trn_id_batch
                                                AND mc.trn_id_rel = mq.trn_id_rel
                                                AND mc.metodo_id = mq.metodo_id
                                        WHERE
                                            mq.trn_id_batch  = ".$trnid_m."  
                                            AND mq.metodo_id = ".$metodo_sel."
                                            AND mc.peso = 0
                                        ORDER BY mq.folio_interno") 
                                        or die(mysqli_error($mysqli));
        }                
        else{
            $resultado = $mysqli->query("SELECT 
                                        mq.trn_id_rel AS trn_id_batch
                                    ,mq.trn_id_muestra AS trn_id_rel
                                    ,mq.metodo_id
                                    ,mq.folio_interno AS muestra
                                    ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                FROM 
                                    arg_muestras_cianurado mc
                                    LEFT JOIN ordenes_reensayes_metal mq
                                        ON mc.trn_id = mq.trn_id_rel
                                        AND mc.trn_id_rel = mq.trn_id_muestra
                                        AND mc.metodo_id = mq.metodo_id
                                WHERE
                                    mq.trn_id_rel    = ".$trnid_m."  
                                    AND mq.metodo_id = ".$metodo_sel."
                                    AND mc.peso = 0
                                ORDER BY mq.folio_interno") 
                                or die(mysqli_error($mysqli));
        }
    }
    elseif ($metodo_sel == 2) { //EF-Grav2 2
        switch($etapa_sel) {
            case 5: $concat = 'mc.peso = 0';
            break;
            case 19: $concat = 'mc.incuarte = 0';
            break;
            case 6:  $concat = 'mc.peso_payon = 0';
            break;   
            case 20:  $concat = 'mc.peso_dore = 0';
            break;        
            case 21:  $concat = 'mc.capturado = 0';
            break;              
        }
        
        if($reensaye == 0){
            $resultado = $mysqli->query("SELECT 
                                            mq.trn_id_batch
                                        ,mq.trn_id_rel
                                        ,mq.metodo_id
                                        ,mq.folio_interno AS muestra
                                        ,(CASE WHEN mq.tipo_id = 0 THEN '' WHEN mq.tipo_id = 1 THEN mq.folio_interno ELSE mq.control END) AS control
                                    FROM 
                                        arg_muestras_cianurado mc
                                        LEFT JOIN ordenes_metalurgia mq
                                            ON mc.trn_id = mq.trn_id_batch
                                            AND mc.trn_id_rel = mq.trn_id_rel
                                            AND mc.metodo_id = mq.metodo_id
                                    WHERE
                                        mq.trn_id_batch  = ".$trnid_m."  
                                        AND mq.metodo_id = ".$metodo_sel."
                                        AND mc.reensaye = 0
                                        AND ".$concat."
                                    ORDER BY mq.posicion") 
                                    or die(mysqli_error($mysqli));
        }
        else{
            $resultado = $mysqli->query("SELECT 
                                            mq.trn_id_rel  AS trn_id_batch
                                        ,mq.trn_id_muestra AS trn_id_rel
                                        ,mq.metodo_id
                                        ,mq.folio_interno AS muestra
                                        ,(CASE WHEN mq.tipo_id = 0 THEN '' WHEN mq.tipo_id = 1 THEN mq.folio_interno ELSE mq.control END) AS control
                                    FROM 
                                        arg_muestras_cianurado mc
                                        LEFT JOIN ordenes_reensayes_metal mq
                                            ON mc.trn_id = mq.trn_id_rel
                                            AND mc.trn_id_rel = mq.trn_id_muestra
                                            AND mc.metodo_id = mq.metodo_id
                                    WHERE
                                        mq.trn_id_rel  = ".$trnid_m."  
                                        AND mq.metodo_id = ".$metodo_sel."
                                        AND ".$concat."
                                        AND mc.reensaye = 0
                                    ORDER BY mq.posicion") 
                                    or die(mysqli_error($mysqli));
        }
    }
    elseif ($metodo_sel == 27) { //Au_LibreAA
        if ($etapa_sel == 5) {
            $peso_all_act = $mysqli->query("SELECT IFNULL(COUNT(*), 0) AS peso                                                    
                                            FROM 
                                                `arg_muestras_cianurado` mc
                                                    LEFT JOIN ordenes_metalurgia ol
                                                    ON mc.trn_id = ol.trn_id_batch
                                                    AND mc.trn_id_rel = ol.trn_id_rel
                                                    AND mc.metodo_id = ol.metodo_id                                                
                                            WHERE 
                                                mc.trn_id  = ".$trnid_m."
                                                AND mc.metodo_id = ".$metodo_sel."
                                                AND ol.tipo_id IN (0, 4)
                                                AND mc.peso = 0") or die(mysqli_error($mysqli));             
            $elpeso = $peso_all_act->fetch_assoc();
            $peso_sig = $elpeso['peso'];
                            
            if($peso_sig > 0) {
                $nombrecampo = 'Peso de muestra g';
                $peso_sig_fil = 'peso = 0 AND tipo_id IN (0,4)';
            }
            elseif($peso_sig == 0) {
                $peso_all_ori = $mysqli->query("SELECT COUNT(*) AS peso_ori                                                    
                                        FROM 
                                        `arg_muestras_cianurado` mc
                                            LEFT JOIN ordenes_metalurgia ol
                                            ON mc.trn_id = ol.trn_id_batch
                                            AND mc.trn_id_rel = ol.trn_id_rel
                                            AND mc.metodo_id = ol.metodo_id
                                        WHERE 
                                        mc.trn_id  = ".$trnid_m."
                                        AND mc.metodo_id = ".$metodo_sel."
                                        AND ol.tipo_id = 0
                                        AND mc.peso_original = 0") or die(mysqli_error($mysqli));
                $elpeso_orig = $peso_all_ori->fetch_assoc();
                $peso_or = $elpeso_orig['peso_ori'];
                            
                if ($peso_or > 0) {    
                    $nombrecampo  = 'Peso Original';
                    $peso_sig_fil = 'peso_original=0 AND tipo_id = 0';     
                }
                else {
                    $peso_all_actu = $mysqli->query("SELECT COUNT(*) AS peso200                                                    
                                        FROM 
                                            `arg_muestras_cianurado` mc
                                                LEFT JOIN ordenes_metalurgia ol
                                                ON mc.trn_id = ol.trn_id_batch
                                                AND mc.trn_id_rel = ol.trn_id_rel
                                                AND mc.metodo_id = ol.metodo_id                                                
                                        WHERE 
                                            mc.trn_id  = ".$trnid_m."
                                            AND mc.metodo_id = ".$metodo_sel."
                                            AND ol.tipo_id = 200
                                            AND mc.peso_malla200 is null") or die(mysqli_error($mysqli));             
                    $elpeso = $peso_all_actu->fetch_assoc();
                    $peso_sig2 = $elpeso['peso200'];
                        
                    if($peso_sig2 > 0) {
                        $nombrecampo = 'Peso malla+200 g';
                        $peso_sig_fil = 'peso_malla200 is null AND tipo_id = 200';
                    }
                    else {
                        $nombrecampo = 'Peso de muestra g';
                        $peso_sig_fil = 'peso = 0 AND tipo_id IN (99, 1, 2)';
                    }
                }
            }                    
                    
            $resultado = $mysqli->query("SELECT 
                                            mq.trn_id_batch
                                        ,mq.trn_id_rel
                                        ,mq.metodo_id
                                        ,mq.folio_interno AS muestra
                                        ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                    FROM 
                                        arg_muestras_cianurado mc
                                        LEFT JOIN ordenes_metalurgia mq
                                            ON mc.trn_id = mq.trn_id_batch
                                            AND mc.trn_id_rel = mq.trn_id_rel
                                            AND mc.metodo_id = mq.metodo_id
                                    WHERE
                                        mq.trn_id_batch  = ".$trnid_m."  
                                        AND mq.metodo_id = ".$metodo_sel."
                                        AND ".$peso_sig_fil."
                                    ORDER BY mq.bloque, mq.folio_interno") 
                                    or die(mysqli_error($mysqli));
        }
        
        if ($etapa_sel == 19){
            $resultado = $mysqli->query("SELECT 
                                            mq.trn_id_batch
                                        ,mq.trn_id_rel
                                        ,mq.metodo_id
                                        ,mq.folio_interno AS muestra
                                        ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                    FROM 
                                        arg_muestras_cianurado mc
                                        LEFT JOIN ordenes_metalurgia mq
                                            ON mc.trn_id = mq.trn_id_batch
                                            AND mc.trn_id_rel = mq.trn_id_rel
                                            AND mc.metodo_id = mq.metodo_id
                                    WHERE
                                        mq.trn_id_batch  = ".$trnid_m."  
                                        AND mq.metodo_id = ".$metodo_sel."
                                        AND mc.incuarte = 0
                                        AND mc.reensaye = 0
                                    ORDER BY
                                        mq.bloque, mq.posicion") 
                                    or die(mysqli_error($mysqli));
        }
        
        if ($etapa_sel == 6){
            $resultado = $mysqli->query("SELECT 
                                            mq.trn_id_batch
                                        ,mq.trn_id_rel
                                        ,mq.metodo_id
                                        ,mq.folio_interno AS muestra
                                        ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                    FROM 
                                        arg_muestras_cianurado mc
                                        LEFT JOIN ordenes_metalurgia mq
                                            ON mc.trn_id = mq.trn_id_batch
                                            AND mc.trn_id_rel = mq.trn_id_rel
                                            AND mc.metodo_id = mq.metodo_id
                                    WHERE
                                        mq.trn_id_batch  = ".$trnid_m."  
                                        AND mq.metodo_id = ".$metodo_sel."
                                        AND mc.peso_payon = 0
                                    ORDER BY
                                        mq.bloque, mq.posicion") 
                                    or die(mysqli_error($mysqli));
        }
        
        if ($etapa_sel == 20){
            $resultado = $mysqli->query("SELECT 
                                            mq.trn_id_batch
                                        ,mq.trn_id_rel
                                        ,mq.metodo_id
                                        ,mq.folio_interno AS muestra
                                        ,(CASE WHEN mq.tipo_id = 0 THEN muestra_geologia ELSE mq.control END) AS control
                                    FROM 
                                        arg_muestras_cianurado mc
                                        LEFT JOIN ordenes_metalurgia mq
                                            ON mc.trn_id = mq.trn_id_batch
                                            AND mc.trn_id_rel = mq.trn_id_rel
                                            AND mc.metodo_id = mq.metodo_id
                                    WHERE
                                        mq.trn_id_batch  = ".$trnid_m."  
                                        AND mq.metodo_id = ".$metodo_sel."
                                        AND mc.peso_dore = 0
                                        AND mc.reensaye = 0
                                    ORDER BY
                                        mq.bloque, mq.posicion") 
                                    or die(mysqli_error($mysqli));
        }
    }
    elseif ($metodo_sel == 5) { //Gravimetria
        $peso_all_act = $mysqli->query("SELECT
                                            (CASE                                                      
                                                    WHEN `p_malla12`   = 0 THEN 'p_malla12=0'                                                      
                                                    WHEN `p_malla38`   = 0 THEN 'p_malla38=0'                                                      
                                                    WHEN `p_malla14`   = 0 THEN 'p_malla14=0'                                                      
                                                    WHEN `p_malla10`   = 0 THEN 'p_malla10=0'
                                                    WHEN `p_malla50`   = 0 THEN 'p_malla50=0'
                                                    WHEN `p_malla100`  = 0 THEN 'p_malla100=0'                                                      
                                                    WHEN `p_mallamenos100`  = 0 THEN 'p_mallamenos100=0'
                                            ELSE 'p_mallamenos100=0' END) AS peso_malla                                                      
                                    FROM 
                                        `arg_ordenes_granulometria` hum
                                        LEFT JOIN arg_ordenes_muestrasMetalurgia AS mm
                                            ON hum.trn_id_batch = mm.trn_id_rel
                                            AND hum.trn_id_rel = mm.trn_id
                                    WHERE 
                                        hum.trn_id_batch  = ".$trnid_m) or die(mysqli_error($mysqli));             
        $elpeso = $peso_all_act->fetch_assoc();
        $elpeso1 = $elpeso['peso_malla']; 
        
        switch ($elpeso1) {
            case 'p_malla12=0': $nombrecampo = 'Malla +1/2';
            break;
            case 'p_malla38=0': $nombrecampo = 'Malla +3/8';
            break;
            case 'p_malla14=0': $nombrecampo = 'Malla +1/4';
            break;
            case 'p_malla10=0': $nombrecampo = 'Malla +10';
            break;  
            case 'p_malla50=0': $nombrecampo = 'Malla +50';
            break;         
            case 'p_malla100=0': $nombrecampo = 'Malla +100';                                
            break; 
            case 'p_mallamenos100=0': $nombrecampo = 'Malla -100';
            break; 
        }           
        
        $resultado = $mysqli->query("SELECT
                                        hum.trn_id_batch AS trnid_batch_met
                                        ,hum.trn_id_rel AS trnid_rel_met
                                        ,mm.folio  AS muestra_met
                                    FROM 
                                        `arg_ordenes_granulometria` hum
                                        LEFT JOIN arg_ordenes_muestrasMetalurgia AS mm
                                            ON hum.trn_id_batch = mm.trn_id_rel
                                            AND hum.trn_id_rel = mm.trn_id
                                    WHERE 
                                        hum.trn_id_batch  = ".$trnid_m."
                                        AND hum.".$elpeso1) 
                                            or die(mysqli_error($mysqli));
    }
                
    if ($resultado->num_rows > 0) {
        $datos_gen = $resultado_efaa ->fetch_array(MYSQLI_ASSOC);
        $metodo_codigo = $datos_gen['metodo'];
        $metodo_fase   = $datos_gen['fase'];
        $metodo_etapa  = $datos_gen['etapa'];
        $html =  "<table class='table text-black' id='tabla_pesaje_met'>
                        <thead class='thead-info' align='center'>
                            <tr class='table-info'>
                                <th colspan='5'>".$metodo_codigo." Fase: ".$metodo_fase." Etapa: ".$metodo_etapa."</th>
                            </tr>
                            <tr class='table-warning' align='center'>
                                <th colspan='11'>ORDEN DE TRABAJO: ".$orden_trabajo."</th>
                            </tr>"; 
             
        if ($etapa_sel == 27) {
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Muestra</th>
                                <th>Peso g</th>
                                <th></th>                       
                        </thead>
                        <tbody>";

            $cont = 0;                          
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                             
                            <td>".$muestra_met."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }
            
        if ($etapa_sel == 1){
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Muestra</th>
                                <th>Peso seco g</th>
                                <th></th>                       
                        </thead>
                        <tbody>";

            $cont = 0;                             
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra']; 
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                             
                            <td>".$muestra_met."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 5 pesaje de metodo humedad
            
        //Pesaje del metodo IMPUREZAS
        if ($fase_sel == 6 & $etapa_sel == 5 & $metodo_sel == 28) {
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Muestra</th>   
                                <th>Peso g</th>
                                <th></th>                       
                        </thead>
                        <tbody>";

            $cont = 0;                    
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                             
                            <td>".$muestra_met."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 5 pesaje
            
        //Pesaje del metodo Densidad
        if ($fase_sel == 20 & $etapa_sel == 5 & $metodo_sel == 29){
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Muestra</th>                                        
                                <th>Volumen</th>
                                <th>Peso g</th>
                                <th></th>                       
                        </thead>
                        <tbody>";

            $cont = 0;              
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trnid_batch_met'];
                $trnid_rel_met   = $res_muestras['trnid_rel_met'];
                $muestra_met     = $res_muestras['muestra_met'];
                $muestra_control = $res_muestras['control']; 
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                             
                            <td>".$muestra_met."</td>
                            <td> <input type='' value=11.9 id='peso_met_cha".$con."' class='form-control' /></td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 5 pesaje
            
        if ($fase_sel == 20 & $etapa_sel == 5 & $metodo_sel == 5) {
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Muestra</th>
                                <th>".$nombrecampo."</th>
                                <th></th>                    
                        </thead>
                        <tbody>";

            $cont = 0;
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trnid_batch_met'];
                $trnid_rel_met   = $res_muestras['trnid_rel_met'];
                $muestra_met     = $res_muestras['muestra_met'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                             
                            <td>".$muestra_met."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            
        }//Fin de etapa 5 pesaje de cianurado
            
        if ($fase_sel == 7 & $etapa_sel == 5 & $metodo_sel == 33) {
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Folio Interno</th>
                                <th>Muestra</th>
                                <th>Peso g</th>
                                <th></th>                     
                        </thead>
                        <tbody>";

            $cont = 0;              
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                $control         = $res_muestras['control'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>
                            <td>".$muestra_met."</td>
                            <td>".$control."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 5 pesaje
            
        //Pesaje m�todo EF-Grav2 metodo 2
        if ($fase_sel == 11 & $etapa_sel == 5 & $metodo_sel == 2) {
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Folio Interno</th>
                                <th>Muestra</th> 
                                <th>".(isset($nombrecampo) ? $nombrecampo : "")."</th>    
                                <th></th>                   
                        </thead>
                        <tbody>";

            $cont = 0;
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                $control         = $res_muestras['control'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>
                            <td>".$muestra_met."</td>
                            <td>".$control."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 5 pesaje metodo 2 Ef-grav2
            
        //Pesaje m�todo EF-Grav2 metodo 2
        if ($fase_sel == 10 & $etapa_sel == 19 & $metodo_sel == 2){
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Folio Interno</th>
                                <th>Control</th> 
                                <th>Incuarte mg</th>    
                                <th></th>                   
                        </thead>
                        <tbody>";

            $cont = 0;
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                $control         = $res_muestras['control'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>
                            <td>".$muestra_met."</td>
                            <td>".$control."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 19 Incuarte metodo 2 Ef-grav2
            
        //Peso pay�n m�todo EF-Grav2 metodo 2
        if ($fase_sel == 11 & $etapa_sel == 6 & $metodo_sel == 2){
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Folio Interno</th>
                                <th>Control</th> 
                                <th>Peso Pay&oacuten</th>    
                                <th></th>                   
                        </thead>
                        <tbody>";

            $cont = 0;
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                $control         = $res_muestras['control'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>
                            <td>".$muestra_met."</td>
                            <td>".$control."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 6 payones metodo 2 Ef-grav2
            
        //Peso dore metodo EF-Grav2 metodo 2
        if ($fase_sel == 10 & $etapa_sel == 20 & $metodo_sel == 2) {
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Folio Interno</th>
                                <th>Control</th> 
                                <th>Peso Dor&eacute mg</th>    
                                <th></th>                   
                        </thead>
                        <tbody>";

            $cont = 0;
            if ($resultado->num_rows > 0) {
                while ($res_muestras = $resultado->fetch_assoc()) {
                    $cont = $cont+1;
                    $trnid_batch_met = $res_muestras['trn_id_batch'];
                    $trnid_rel_met   = $res_muestras['trn_id_rel'];
                    $muestra_met     = $res_muestras['muestra'];
                    $control         = $res_muestras['control'];
                                                    
                    $html.="<tr>                    
                                <td>".$cont."</td> 
                                <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                                <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>
                                <td>".$muestra_met."</td>
                                <td>".$control."</td>
                                <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                                <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                        <span class='fa fa-cloud fa-1x'></span>
                                    </button>
                                </td>                                   
                            </tr>"; 
                }                
            }
            else {
                if ($reensaye == 0) {
                    $resultado = $mysqli->query("SELECT 
                                                            mq.trn_id_batch
                                                        ,mq.trn_id_rel
                                                        ,mq.metodo_id
                                                        ,mq.folio_interno AS muestra
                                                        ,ROUND(mc.peso_dore, 2) AS peso_dore
                                                        ,(CASE WHEN mq.tipo_id = 0 THEN '' WHEN mq.tipo_id = 1 THEN mq.folio_interno ELSE mq.control END) AS control
                                                    FROM 
                                                        arg_muestras_cianurado mc
                                                        LEFT JOIN ordenes_metalurgia mq
                                                            ON mc.trn_id = mq.trn_id_batch
                                                            AND mc.trn_id_rel = mq.trn_id_rel
                                                            AND mc.metodo_id = mq.metodo_id
                                                    WHERE
                                                        mq.trn_id_batch  = ".$trn_id."  
                                                        AND mq.metodo_id =  ".$metodo_id."
                                                    ORDER BY mq.posicion") 
                                            or die(mysqli_error($mysqli));       
                }
                else {
                    $resultado = $mysqli->query("SELECT
                                                        ot.trn_id_rel AS trn_id_batch,
                                                        ot.trn_id_muestra AS trn_id_rel,
                                                        ROUND(pul.peso_dore, 2) AS peso_dore,
                                                        ot.folio_interno as muestra,                                                              
                                                        met.nombre as metodo_nombre,
                                                        ot.control
                                                    FROM 
                                                    arg_muestras_cianurado pul
                                                    LEFT JOIN ordenes_reensayes_metal ot
                                                        ON  pul.trn_id = ot.trn_id_rel
                                                        AND pul.trn_id_rel = ot.trn_id_muestra
                                                        AND pul.metodo_id = ot.metodo_id
                                                    LEFT JOIN arg_metodos met
                                                            ON met.metodo_id = pul.metodo_id
                                                    WHERE
                                                        pul.trn_id = ".$trn_id." 
                                                        AND pul.metodo_id = ".$metodo_id." 
                                                    ORDER BY ot.posicion") or die(mysqli_error($mysqli));
                }

                $con = 0;
                while ($res_muestras = $resultado->fetch_assoc()) {
                    $con = $con+1;
                    $trnid_batch_met = $res_muestras['trn_id_batch'];
                    $trnid_rel_met   = $res_muestras['trn_id_rel'];
                    $muestra_met     = $res_muestras['muestra'];     
                    $metodo          = $res_muestras['metodo_nombre'];
                    $peso            = $res_muestras['peso_dore'];
                                    
                    $html.="<td>".$con."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_pay".$con."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_pay".$con."' value='".$trnid_rel_met."'/>".$muestra_met."</td>                                        
                            <td style='display:none;'> <input type='input' id='mode_edicion' value='1'/></td>                               
                            <td>".$muestra_met."</td>
                            <td>".$peso."</td>";
                            if ($supervisor == 1){
                                $html .="<td> <input type='number' id='peso_met".$con."' value='".$peso_actual."' class='form-control'/> </td>
                                    <td> <button type='button'class='btn btn-primary' id='boton_save_pay' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_id.",".$fase_id.",".$etapa_id.",".$con.",".$unidad_id.")' >
                                            <span class='fa fa-cloud fa-1x'></span>                
                                        </button>
                                    </td>";
                            }                                                                           
                            $html.="</tr>"; 
                }
                $html.="<div class='container-fluid'><td></td>  <td style='align:center;'> <button type='button'class='btn btn-warning' id='boton_save_payconfirm' onclick='met_payon_finalizarCarb(".$trnid_batch_met.",".$metodo_id.",".$fase_id.",".$etapa_id.")' >
                                            <span class='fa fa-cloud-upload fa-2x'> Finalizar Etapa </span>
                                        </button></td>";
                $html.="</div>";
                $html .= "</tbody></table></div>";

            }

            $html .= "</tbody></table></div>";
        }//Fin de etapa 20 peso dor� metodo 2 EF-grav2
            
        //Peso oro m�todo EF-Grav2 metodo 2
        if ($fase_sel == 10 & $etapa_sel == 21 & $metodo_sel == 2){
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Folio Interno</th>
                                <th>Control</th> 
                                <th>Peso Au mg</th>    
                                <th></th>                   
                        </thead>
                        <tbody>";

            $cont = 0;                     
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                $control         = $res_muestras['control'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>
                            <td>".$muestra_met."</td>
                            <td>".$control."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 20 peso dor� metodo 2 EF-grav2
            
        //Pesaje m�todo Au_LibreAA
        if ($fase_sel == 9 & $etapa_sel == 5 & $metodo_sel == 27){
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Folio Interno</th>
                                <th>Muestra</th> 
                                <th>".$nombrecampo."</th>    
                                <th></th>                   
                        </thead>
                        <tbody>";

            $cont = 0;
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                $control         = $res_muestras['control'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>
                            <td>".$muestra_met."</td>
                            <td>".$control."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 5 pesaje 
            
        //Incuarte m�todo Au_LibreAA
        if ($fase_sel == 9 & $etapa_sel == 19 & $metodo_sel == 27) {
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Folio Interno</th>
                                <th>Muestra</th> 
                                <th>Incuarte mg</th>                      
                        </thead>
                        <tbody>";

            $cont = 0;
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                $control         = $res_muestras['control'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>
                            <td>".$muestra_met."</td>
                            <td>".$control."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 19 Incuarte
            
        //Peso payon m�todo Au_LibreAA 27
        if ($fase_sel == 9 & $etapa_sel == 6 & $metodo_sel == 27) {
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Folio Interno</th>
                                <th>Muestra</th>
                                <th>Peso Pay&oacuten g</th>                      
                        </thead>
                        <tbody>";

            $cont = 0;                  
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                $control         = $res_muestras['control'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>
                            <td>".$muestra_met."</td>
                            <td>".$control."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 6 peso dore
            
        if ($fase_sel == 8 & $etapa_sel == 20 & $metodo_sel == 27){                
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Folio Interno</th>
                                <th>Muestra</th>
                                <th>Peso Dor&eacute mg</th>                      
                        </thead>
                        <tbody>";
            $cont = 0;
                                        
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                $control         = $res_muestras['control'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>
                            <td>".$muestra_met."</td>
                            <td>".$control."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 6 peso pay�n
            
        //Pesaje m�todo Au_LibreGr
        if ($fase_sel == 18 & $etapa_sel == 5 & $metodo_sel == 31){
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Muestra</th>                                        
                                <th>Control</th> 
                                <th>".$nombrecampo."</th>    
                                <th></th>                   
                        </thead>
                        <tbody>";

            $cont = 0;                
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                $control         = $res_muestras['control'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>
                            <td>".$muestra_met."</td>
                            <td>".$control."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 5 pesaje 
            
        //Pesaje incuarte m�todo Au_LibreGr
        if ($fase_sel == 18 & $etapa_sel == 19 & $metodo_sel == 31){
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Muestra</th>                                        
                                <th>Control</th> 
                                <th>Incuarte mg</th>    
                                <th></th>                   
                        </thead>
                        <tbody>";
                        
            $cont = 0;
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                $control         = $res_muestras['control'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>
                            <td>".$muestra_met."</td>
                            <td>".$control."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 19 incuarte 
            
        //Pesaje peso payonn m�todo Au_LibreGr 31
        if ($fase_sel == 18 & $etapa_sel == 6 & $metodo_sel == 31){
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Muestra</th>                                        
                                <th>Control</th> 
                                <th>Peso Pay&oacuten mg</th>    
                                <th></th>                   
                        </thead>
                        <tbody>";
            $cont = 0;
                                        
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                $control         = $res_muestras['control'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>
                            <td>".$muestra_met."</td>
                            <td>".$control."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 6 peso payon metodo 31 Au_LibreGr 
            
            //Pesaje peso dor� m�todo Au_LibreGr 31
        if ($fase_sel == 19 & $etapa_sel == 20 & $metodo_sel == 31){
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Muestra</th>                                        
                                <th>Control</th> 
                                <th>Peso Dor&eacute mg</th>    
                                <th></th>                   
                        </thead>
                        <tbody>";
            $cont = 0;
                                        
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                $control         = $res_muestras['control'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>
                            <td>".$muestra_met."</td>
                            <td>".$control."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 20 peso dor� metodo 31 Au_LibreGr 
            
        //Pesaje peso oro m�todo Au_LibreGr 31
        if ($fase_sel == 19 & $etapa_sel == 21 & $metodo_sel == 31){
            $html.="<tr class='table-info' align='left'>
                                <th>No.</th>
                                <th>Muestra</th>                                        
                                <th>Control</th> 
                                <th>Peso Oro mg</th>    
                                <th></th>                   
                        </thead>
                        <tbody>";

            $cont = 0;           
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                $control         = $res_muestras['control'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>
                            <td>".$muestra_met."</td>
                            <td>".$control."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 22 peso oro metodo 31 Au_LibreGr 

        //Pesaje método ACT_CB 9
        if ($fase_sel == 23 & $etapa_sel == 5 & $metodo_sel == 9) {
            $html.="<tr class='table-info' align='left'>
                                    <th>No.</th>
                                    <th>Muestra</th>  
                                    <th>Control</th> 
                                    <th>Peso g</th>    
                                    <th></th>                   
                            </thead>
                            <tbody>";

            $cont = 0;
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                $control         = $res_muestras['control'];
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>
                            <td>".$muestra_met."</td>
                            <td>".$control."</td>
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 5 pesaje metodo 35 CB_DIGAg

        //Pesaje método CB_DIGAg 35
        if ($fase_sel == 24 & $etapa_sel == 5 & $metodo_sel == 35) {
            $html.="<tr class='table-info' align='left'>
                                    <th>No.</th>
                                    <th>Muestra</th>   
                                    <th>Peso g</th>    
                                    <th></th>                   
                            </thead>
                            <tbody>";

            $cont = 0;
            while ($res_muestras = $resultado->fetch_assoc()) {
                $cont = $cont+1;
                $trnid_batch_met = $res_muestras['trn_id_batch'];
                $trnid_rel_met   = $res_muestras['trn_id_rel'];
                $muestra_met     = $res_muestras['muestra'];
                // $control         = $res_muestras['control'];
                //   <td>".$control."</td>
                                                
                $html.="<tr>                    
                            <td>".$cont."</td> 
                            <td style='display:none;'> <input type='input' id='trnid_batch_met".$cont."' value='".$trnid_batch_met."'/></td>  
                            <td style='display:none;'> <input type='input' id='trnid_rel_met".$cont."' value='".$trnid_rel_met."'/>".$muestra_met."</td>
                            <td>".$muestra_met."</td>
                        
                            <td> <input type='number' id='peso_met".$cont."' class='form-control' /> </td>
                            <td> <button type='button'class='btn btn-primary' id='boton_save' onclick='met_pesoHum_guardar(".$trnid_batch_met.",".$trnid_rel_met.",".$metodo_sel.",".$fase_sel.",".$etapa_sel.",".$cont.", ".$unidad_id.")' >
                                    <span class='fa fa-cloud fa-1x'></span>
                                </button>
                            </td>                                   
                        </tr>"; 
            }
            $html .= "</tbody></table></div>";
        }//Fin de etapa 5 pesaje metodo 35 CB_DIGAg
    }
    else {
        $html = 'Ha finalizado la etapa.';
    }

    $mysqli -> set_charset("utf8");
    echo ($html);
}
?>