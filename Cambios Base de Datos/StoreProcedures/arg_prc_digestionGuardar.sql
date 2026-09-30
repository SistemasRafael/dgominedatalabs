DELIMITER $$
DROP PROCEDURE IF EXISTS arg_prc_digestionGuardar $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_prc_digestionGuardar`(IN `trn_id_batch` INT, IN `metodo_id_dig` INT, IN `cantidad_t` INT, IN `u_id` INT)
    NO SQL
BEGIN
DECLARE trn_id_sig INT;
SET trn_id_sig = IFnull((SELECT max(trn_id) as trn_id FROM arg_muestras_digestion), 0);

SET trn_id_sig = trn_id_sig+1;
                     
INSERT INTO arg_muestras_digestion(trn_id, trn_id_rel, metodo_id,  cantidad)
VALUES (trn_id_sig, trn_id_batch, metodo_id_dig, cantidad_t);

IF (metodo_id_dig = 3) THEN
INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, fecha, u_id)
    SELECT 
    	od.trn_id_rel, od.metodo_id, 3, now(), u_id
    FROM
    	arg_ordenes_bitacora_detalle od
    WHERE
    	od.trn_id_rel = trn_id_batch
        AND metodo_id = metodo_id_dig
        AND fase_id = 2 AND etapa_id = 4;
        
INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
    SELECT 
    	od.trn_id_rel, od.metodo_id, 3, 7 as etapa_id, now(), u_id
    FROM
    	arg_ordenes_bitacora_detalle od
    WHERE
    	od.trn_id_rel = trn_id_batch
        AND metodo_id = metodo_id_dig
        AND fase_id = 2 AND etapa_id = 4;
END IF;

IF (metodo_id_dig = 6 or 
    metodo_id_dig = 7 or 
    metodo_id_dig = 38 or 
    metodo_id_dig = 39) THEN
INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, fecha, u_id)
    SELECT 
    	od.trn_id_rel, od.metodo_id, 3, now(), u_id
    FROM
    	arg_ordenes_bitacora_detalle od
    WHERE
    	od.trn_id_rel = trn_id_batch
        AND metodo_id = metodo_id_dig
        AND fase_id = 6 AND etapa_id = 4;
        
INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
    SELECT 
    	od.trn_id_rel, od.metodo_id, 3, 7 as etapa_id, now(), u_id
    FROM
    	arg_ordenes_bitacora_detalle od
    WHERE
    	od.trn_id_rel = trn_id_batch
        AND metodo_id = metodo_id_dig
        AND fase_id = 6 AND etapa_id = 4;
END IF;

 
END$$
DELIMITER ;