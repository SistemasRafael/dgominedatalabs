DELIMITER $$
DROP PROCEDURE IF EXISTS arg_prc_actualizarAbsorcion $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_prc_actualizarAbsorcion`(IN `trn_id_batch` INT, IN `metodo_id_ab` INT, IN `u_id_act` INT)
    NO SQL
BEGIN 
    UPDATE arg_ordenes_csv_detalle det
    LEFT JOIN ordenes_transacciones ot ON det.trn_id_rel = ot.trn_id_batch AND det.folio = ot.folio_interno
    LEFT JOIN arg_muestras_resultados mr ON mr.trn_id = ot.trn_id_batch AND mr.trn_id_rel = ot.trn_id_rel AND mr.metodo_id = det.metodo_id
    SET mr.absorcion = (CASE WHEN det.valor1 <= 0 AND metodo_id_ab = 3 THEN 0.020
                             WHEN det.valor1 <= 0 AND metodo_id_ab = 6 THEN 0.001
                             WHEN det.valor1 <= 0.042 AND metodo_id_ab = 36 THEN 0.042
                             WHEN det.valor1 <= 0.700 AND metodo_id_ab = 37 THEN 0.700
                        ELSE det.valor1 END),
        mr.porcentaje = IFNULL((CASE WHEN ot.trn_id_dup = 0 THEN 0 ELSE duplicado_resultado (trn_id_batch, det.folio, metodo_id_ab) END), 0),
        mr.lectura = det.trn_id
    WHERE ot.trn_id_batch = trn_id_batch AND 
            mr.metodo_id = metodo_id_ab AND 
            mr.reensaye <> 3 AND 
            det.folio NOT LIKE '%PATRON%' AND 
            det.folio NOT LIKE '%BLANCO%';

    UPDATE arg_muestras_resultados mr   
    LEFT JOIN resultados_detalle ot ON ot.trn_id_batch = mr.trn_id AND ot.trn_id_muestra = mr.trn_id_rel AND mr.metodo_id = ot.metodo_id
    SET mr.reensaye = 2
    WHERE mr.trn_id = trn_id_batch 
         AND mr.metodo_id = metodo_id_ab 
         AND mr.reensaye = 0
         AND ot.folio_interno NOT IN (SELECT folio FROM arg_ordenes_csv_detalle WHERE trn_id_rel = trn_id_batch AND metodo_id = metodo_id_ab);
 

    UPDATE arg_muestras_resultados mr
    LEFT JOIN ordenes_transacciones ot ON ot.trn_id_batch = mr.trn_id AND ot.trn_id_rel = mr.trn_id_rel    
    SET mr.validacion_tipo  = validacion_margen(mr.metodo_id, ot.material_id, mr.absorcion)
    WHERE ot.trn_id_batch = trn_id_batch AND 
          mr.metodo_id = metodo_id_ab AND 
          ot.trn_id_dup <> 0;

    IF (metodo_id_ab = 3 or metodo_id_ab = 6 or metodo_id_ab = 7 or metodo_id_ab = 11 or metodo_id_ab = 12 or metodo_id_ab = 13 or metodo_id_ab = 36 or metodo_id_ab = 37) THEN
            INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, fecha, u_id)
            VALUES (trn_id_batch, metodo_id_ab, 4, now(), 0);

            INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
            VALUES (trn_id_batch, metodo_id_ab, 4, 10, now(), 0);
    END IF;

END$$
DELIMITER ;