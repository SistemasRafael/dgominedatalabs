DELIMITER $$
DROP PROCEDURE IF EXISTS arg_consultar_resultadosesp$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_consultar_resultadosesp`(IN `trn_id_batch` INT, IN `metodo_id` INT, IN `pree` INT) BEGIN
    IF (pree = 1) THEN
        IF (metodo_id = 3) THEN
            SELECT
                (ot.muestra_geologia) AS folio_interno,
                banco_voladura(ot.trn_id_muestra) AS banvol,
                date_format(`fecha_ensaye`(ot.trn_id_muestra),"%d-%m-%Y" ) AS fecha,
                date_format(bde.fecha, "%H:%i:%S" ) AS hora,
                date_format(bde.fecha, "%d-%m-%Y") AS fecha_fin,
                met.nombre AS metodo,
                resultado_original(ot.trn_id_muestra, ot.metodo_id) AS resultado_ori,
                resultado_nuevo(ot.trn_id_muestra, ot.metodo_id) AS resultado_nue
            FROM temp_controles tc            
            INNER JOIN ordenes_reensayes ot ON tc.folio_interno = ot.folio_interno AND tc.metodo_id = ot.metodo_id
                LEFT JOIN arg_metodos met ON met.metodo_id = ot.metodo_id
                LEFT JOIN arg_ordenes_bitacora_detalle AS bde ON bde.trn_id_rel = tc.trn_id_batch AND bde.metodo_id = tc.metodo_id
                LEFT JOIN arg_ordenes_detalle AS od ON od.trn_id = tc.trn_id_batch
            WHERE 
                tc.trn_id_batch = trn_id_batch
                AND tc.metodo_id = metodo_id
                AND bde.etapa_id = 12;
        ELSE
            SELECT
                (ot.muestra_geologia) AS folio_interno,
                banco_voladura(ot.trn_id_muestra) AS banvol,
                date_format(`fecha_ensaye`(ot.trn_id_muestra),"%d-%m-%Y" ) AS fecha,
                date_format(bde.fecha, "%H:%i:%S" ) AS hora,
                date_format(bde.fecha, "%d-%m-%Y") AS fecha_fin,
                met.nombre AS metodo,
                resultado_original(ot.trn_id_muestra, ot.metodo_id) AS resultado_ori,
                primer_reensaye(ot.trn_id_muestra, ot.metodo_id) AS resultado1,
                segundo_reensaye(ot.trn_id_muestra, ot.metodo_id) AS resultado2
            FROM temp_controles tc            
            LEFT JOIN ordenes_reensayes ot ON tc.folio_interno = ot.folio_interno AND tc.metodo_id = ot.metodo_id
            LEFT JOIN arg_metodos met ON met.metodo_id = ot.metodo_id
            LEFT JOIN arg_ordenes_bitacora_detalle AS bde ON bde.trn_id_rel = tc.trn_id_batch AND bde.metodo_id = tc.metodo_id AND bde.etapa_id = 11
            LEFT JOIN arg_ordenes_detalle AS od ON od.trn_id = tc.trn_id_batch
            WHERE tc.trn_id_batch = trn_id_batch
                AND ot.trn_id_rel = trn_id_batch
                AND tc.metodo_id = metodo_id
                AND tc.tope_reensayes >= 2
                AND bde.etapa_id = 11
                AND tc.reensaye <> 0
                AND tc.tipo_id = 0;
        END IF;
    ELSE
        SELECT
            ot.tipo_id, (ot.muestra_geologia) AS muestra,
            banco_voladura(ot.trn_id_muestra) AS banvol, 
            ot.folio_interno, det.folio_interno,  
            mr.absorcion, 
            o.folio, 
            date_format(o.fecha, "%d-%m-%Y") AS fecha, 
            date_format(bde.fecha, "%H:%i:%S" ) as hora,
            date_format(bde.fecha, "%d-%m-%Y") AS fecha_fin,
            met.nombre AS metodo
        FROM arg_muestras_resultados mr
        LEFT JOIN ordenes_reensayes ot ON mr.trn_id = ot.trn_id_rel AND mr.trn_id_rel = ot.trn_id_muestra AND mr.metodo_id= ot.metodo_id
        LEFT JOIN arg_ordenes_detalle det ON det.trn_id = mr.trn_id
        LEFT JOIN arg_ordenes o ON o.trn_id = det.trn_id_rel
        LEFT JOIN arg_ordenes_bitacora_detalle bde ON bde.trn_id_rel = mr.trn_id AND bde.metodo_id = mr.metodo_id AND bde.etapa_id = 12
        LEFT JOIN arg_metodos met ON met.metodo_id = mr.metodo_id
        WHERE mr.trn_id = trn_id_batch
            AND mr.metodo_id = metodo_id
            AND mr.reensaye = 0;
    END IF;
END$$
DELIMITER ;