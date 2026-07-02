DELIMITER $$
DROP PROCEDURE IF EXISTS arg_prc_temperaturaGuardarCia $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_prc_temperaturaGuardarCia`(IN `trn_id_batch` INT, IN `metodo_id_cia` INT, IN `cant_temp` DECIMAL(10,6), IN `u_id_cia` INT)
    NO SQL
BEGIN

DECLARE trn_id_sig INT;

SET trn_id_sig = IFnull((SELECT max(trn_id) as trn_id FROM arg_muestras_temperaturas), 0);
SET trn_id_sig = trn_id_sig+1;

INSERT INTO arg_muestras_temperaturas(trn_id, trn_id_rel, metodo_id, instrumento_id, cantidad)
VALUES (trn_id_sig, trn_id_batch, metodo_id_cia, 0, cant_temp);

IF (metodo_id_cia = 11) THEN
BEGIN

	INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
    SELECT 
    	od.trn_id_rel, od.metodo_id, od.fase_id, 17 as etapa_id, now(), u_id
    FROM
    	arg_ordenes_bitacora_detalle od
    WHERE
    	od.trn_id_rel = trn_id_batch
        AND metodo_id = metodo_id_cia
        AND fase_id = 7 AND etapa_id = 16;
END;
	ELSEIF(metodo_id_cia = 12) THEN
BEGIN

	INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
    SELECT 
    	od.trn_id_rel, od.metodo_id, od.fase_id, 17 as etapa_id, now(), u_id
    FROM
    	arg_ordenes_bitacora_detalle od
    WHERE
    	od.trn_id_rel = trn_id_batch
        AND metodo_id = metodo_id_cia
        AND fase_id = 7 AND etapa_id = 16;
END;
	ELSEIF (metodo_id_cia = 13) THEN
BEGIN

	INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
    SELECT 
    	od.trn_id_rel, od.metodo_id, od.fase_id, 17 as etapa_id, now(), u_id
    FROM
    	arg_ordenes_bitacora_detalle od
    WHERE
    	od.trn_id_rel = trn_id_batch
        AND metodo_id = metodo_id_cia
        AND fase_id = 7 AND etapa_id = 16;
END;
END IF;
        
        
        
IF (metodo_id_cia = 27) THEN
BEGIN

    INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
        SELECT 
            od.trn_id_rel, od.metodo_id, 9, 6 as etapa_id, now(), u_id
        FROM
            arg_ordenes_bitacora_detalle od
        WHERE
            od.trn_id_rel = trn_id_batch
            AND metodo_id = metodo_id_cia
            AND fase_id = 9 AND etapa_id = 8;
END;
	ELSEIF(metodo_id_cia = 2) THEN
	BEGIN
        INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
            SELECT 
                od.trn_id_rel, od.metodo_id, 11, 6 as etapa_id, now(), u_id
            FROM
                arg_ordenes_bitacora_detalle od
            WHERE
                od.trn_id_rel = trn_id_batch
                AND metodo_id = metodo_id_cia
                AND fase_id = 11 AND etapa_id = 8;
    END;
    ELSEIF(metodo_id_cia = 9) THEN
	BEGIN
        INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
         VALUES (trn_id_batch, metodo_id_cia, 23, 17, now(),  u_id_cia);
    END;
	ELSE
BEGIN


    INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
        SELECT 
            od.trn_id_rel, od.metodo_id, od.fase_id, 17 as etapa_id, now(), u_id
        FROM
            arg_ordenes_bitacora_detalle od
        WHERE
            od.trn_id_rel = trn_id_batch
            AND metodo_id = metodo_id_cia
            AND fase_id = 7 AND etapa_id = 16;
END;
END IF;

END$$
DELIMITER ;
