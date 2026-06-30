DELIMITER $$
DROP PROCEDURE IF EXISTS arg_consultar_resultados$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_consultar_resultados`(IN `trn_id_batch` INT, IN `metodo_id` INT, IN `ver_reensayes` INT)
    NO SQL
IF (ver_reensayes = 0) THEN
BEGIN
    SELECT
         ot.tipo_id
        ,(ot.muestra_geologia) AS muestra
        ,CONCAT(ban.banco, vol.voladura_id) AS banvol
        ,ot.folio_interno, det.folio_interno
        ,(CASE WHEN mr.absorcion >= 1 THEN '>1' ELSE (ROUND(mr.absorcion, 3)) END) AS absorcion
        ,o.folio
        ,date_format(o.fecha, "%d-%m-%Y") AS fecha
        ,o.hora
        ,date_format(bde.fecha, "%d-%m-%Y") AS fecha_fin
        ,met.nombre AS metodo
    FROM 
        `arg_muestras_resultados` mr
        LEFT JOIN ordenes_transacciones ot
            ON mr.trn_id = ot.trn_id_batch
            AND mr.trn_id_rel = ot.trn_id_rel
        LEFT JOIN arg_ordenes_detalle det
            ON det.trn_id = mr.trn_id
        LEFT JOIN arg_bancos ban
            ON ban.banco_id = det.banco_id
        LEFT JOIN arg_bancos_voladuras vol
            ON vol.banco_id = ban.banco_id
            AND vol.voladura_id = det.voladura_id
        LEFT JOIN arg_ordenes o
            ON o.trn_id = det.trn_id_rel
        LEFT JOIN arg_ordenes_bitacora_detalle bde
            ON bde.trn_id_rel = mr.trn_id
            AND bde.metodo_id = mr.metodo_id
            AND bde.etapa_id = 11
        LEFT JOIN arg_metodos met
            ON met.metodo_id = mr.metodo_id
    WHERE 
        mr.trn_id = trn_id_batch
        AND mr.metodo_id = metodo_id
        AND mr.reensaye = 0;
    END;
    
ELSEIF (ver_reensayes = 2) THEN
BEGIN
    SELECT
        ot.tipo_id
        , (ot.muestra_geologia) AS muestra
        , CONCAT(ban.banco
        , vol.voladura_id) AS banvol
        , ot.folio_interno
        , det.folio_interno
        , (CASE WHEN mr.absorcion >= 1 THEN '>1' ELSE (ROUND(mr.absorcion, 3)) END) AS absorcion
        , o.folio
        , date_format(o.fecha, "%d-%m-%Y") AS fecha
        , date_format(bde.fecha, "%H:%i:%S" ) as hora
        ,date_format(bde.fecha, "%d-%m-%Y") AS fecha_fin
        ,met.nombre AS metodo
    FROM 
        `arg_muestras_resultados` mr
        LEFT JOIN ordenes_transacciones ot
            ON mr.trn_id = ot.trn_id_batch
            AND mr.trn_id_rel = ot.trn_id_rel
        LEFT JOIN arg_ordenes_detalle det
            ON det.trn_id = mr.trn_id
        LEFT JOIN arg_bancos ban
            ON ban.banco_id = det.banco_id
        LEFT JOIN arg_bancos_voladuras vol
            ON vol.banco_id = ban.banco_id
            AND vol.voladura_id = det.voladura_id
        LEFT JOIN arg_ordenes o
            ON o.trn_id = det.trn_id_rel
        LEFT JOIN arg_ordenes_bitacora_detalle bde
            ON bde.trn_id_rel = mr.trn_id
            AND bde.metodo_id = mr.metodo_id
            AND bde.etapa_id = 11
        LEFT JOIN arg_metodos met
            ON met.metodo_id = mr.metodo_id
    WHERE 
        mr.trn_id = trn_id_batch
        AND mr.metodo_id = metodo_id
        AND ot.tipo_id = 0
        AND mr.reensaye = 0;
  END;
    ELSEIF (ver_reensayes = 3) THEN
		BEGIN
        SELECT
            ot.tipo_id
            , (ot.muestra_geologia) AS muestra
            , CONCAT(ban.banco, vol.voladura_id) AS banvol
            , ot.folio_interno
            , det.folio_interno
            , (CASE WHEN mr.absorcion >= 1 THEN '>1' ELSE (ROUND(mr.absorcion, 3)) END) AS absorcion
            , o.folio
            , date_format(o.fecha, "%d-%m-%Y") AS fecha
            , date_format(bde.fecha, "%H:%i:%S" ) as hora
            ,date_format(bde.fecha, "%d-%m-%Y") AS fecha_fin
            ,met.nombre AS metodo
        FROM 
            `arg_muestras_resultados` mr
            LEFT JOIN ordenes_transacciones ot
                ON mr.trn_id = ot.trn_id_batch
                AND mr.trn_id_rel = ot.trn_id_rel
            LEFT JOIN arg_ordenes_detalle det
                ON det.trn_id = mr.trn_id
            LEFT JOIN arg_bancos ban
                ON ban.banco_id = det.banco_id
            LEFT JOIN arg_bancos_voladuras vol
                ON vol.banco_id = ban.banco_id
                AND vol.voladura_id = det.voladura_id
            LEFT JOIN arg_ordenes o
                ON o.trn_id = det.trn_id_rel
            LEFT JOIN arg_ordenes_bitacora_detalle bde
                ON bde.trn_id_rel = mr.trn_id
                AND bde.metodo_id = mr.metodo_id
                AND bde.etapa_id = 12
            LEFT JOIN arg_metodos met
                ON met.metodo_id = mr.metodo_id
        WHERE 
            mr.trn_id = trn_id_batch
            AND mr.metodo_id = metodo_id
            AND ot.tipo_id = 0
            AND mr.reensaye = 0;
      END;
      
    ELSE
	BEGIN
    	 SELECT
        	ot.tipo_id
            , (ot.muestra_geologia) AS muestra
            , CONCAT(ban.banco, vol.voladura_id) AS banvol
            , ot.folio_interno
            , det.folio_interno
            , (CASE WHEN mr.absorcion >= 1 THEN '>1' ELSE (ROUND(mr.absorcion, 3)) END) AS absorcion
            , o.folio
            , date_format(o.fecha, "%d-%m-%Y") AS fecha, o.hora
        	,date_format(bde.fecha, "%d-%m-%Y") AS fecha_fin
        	,met.nombre AS metodo
    FROM 
        `arg_muestras_resultados` mr
        LEFT JOIN ordenes_transacciones ot
            ON mr.trn_id = ot.trn_id_batch
            AND mr.trn_id_rel = ot.trn_id_rel
        LEFT JOIN arg_ordenes_detalle det
            ON det.trn_id = mr.trn_id
        LEFT JOIN arg_bancos ban
            ON ban.banco_id = det.banco_id
        LEFT JOIN arg_bancos_voladuras vol
            ON vol.banco_id = ban.banco_id
            AND vol.voladura_id = det.voladura_id
        LEFT JOIN arg_ordenes o
            ON o.trn_id = det.trn_id_rel
        LEFT JOIN arg_ordenes_bitacora_detalle bde
            ON bde.trn_id_rel = mr.trn_id
            AND bde.metodo_id = mr.metodo_id
            AND bde.etapa_id = 11
        LEFT JOIN arg_metodos met
            ON met.metodo_id = mr.metodo_id
    WHERE 
        mr.trn_id = trn_id_batch
        AND mr.metodo_id = metodo_id
        AND mr.reensaye = 1;
    
    END;
END IF$$
DELIMITER ;