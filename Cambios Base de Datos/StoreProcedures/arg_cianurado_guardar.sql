DELIMITER $$
DROP PROCEDURE IF EXISTS arg_cianurado_guardar$$
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_cianurado_guardar`(IN `trn_id_batch` INT, IN `metodo_id_sel` INT, IN `fase_id_sel` INT, IN `etapa_id_sel` INT, IN `fecha_ini` VARCHAR(25), IN `fecha_fin` VARCHAR(25), IN `u_id_sel` INT)
    NO SQL
BEGIN
DECLARE trn_id_sig INT;

SET trn_id_sig = IFnull((SELECT max(trn_id) as trn_id FROM arg_muestras_cianuradoAgitacion), 0);

SET trn_id_sig = trn_id_sig+1;                     

IF (fase_id_sel = 7 AND etapa_id_sel = 17) THEN
BEGIN
	INSERT INTO arg_muestras_cianuradoAgitacion (trn_id, trn_id_rel, metodo_id, fase_id, etapa_id, fecha_inicio, fecha_final, u_id)
    VALUES (trn_id_sig,trn_id_batch, metodo_id_sel, fase_id_sel, etapa_id_sel, fecha_ini, fecha_fin, u_id_sel );
    
INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
    SELECT 
    	od.trn_id_rel, od.metodo_id, od.fase_id, 18 as etapa_id, now(), u_id
    FROM
    	arg_ordenes_bitacora_detalle od
    WHERE
    	od.trn_id_rel = trn_id_batch
        AND metodo_id = metodo_id_sel
        AND fase_id = fase_id_sel
        AND etapa_id = etapa_id_sel;
END;
END IF;

IF (fase_id_sel = 7 AND etapa_id_sel = 18) THEN
BEGIN
	INSERT INTO arg_muestras_cianuradoAgitacion (trn_id, trn_id_rel, metodo_id, fase_id, etapa_id, fecha_inicio, fecha_final, u_id)
    VALUES (trn_id_sig,trn_id_batch, metodo_id_sel, fase_id_sel, etapa_id_sel, '', fecha_fin, u_id_sel );

INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
    SELECT 
    	od.trn_id_rel, od.metodo_id, 3, 7 as etapa_id, now(), u_id
    FROM
    	arg_ordenes_bitacora_detalle od
    WHERE
    	od.trn_id_rel = trn_id_batch
        AND metodo_id = metodo_id_sel
        AND fase_id = fase_id_sel
        AND etapa_id = etapa_id_sel;
END;
END IF;


IF (fase_id_sel = 23 AND etapa_id_sel = 17) THEN
BEGIN
	INSERT INTO arg_muestras_cianuradoAgitacion (trn_id, trn_id_rel, metodo_id, fase_id, etapa_id, fecha_inicio, fecha_final, u_id)
    VALUES (trn_id_sig,trn_id_batch, metodo_id_sel, fase_id_sel, etapa_id_sel, fecha_ini, fecha_fin, u_id_sel );

INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
      VALUES (trn_id_batch, metodo_id_sel,3, 7, now(),  u_id_sel);
END;
END IF;



END$$
DELIMITER ;
