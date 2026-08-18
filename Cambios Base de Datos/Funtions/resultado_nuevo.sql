DELIMITER $$
DROP FUNCTION IF EXISTS resultado_nuevo$$
CREATE DEFINER=`root`@`localhost` FUNCTION `resultado_nuevo`(`trn_id_muestra` INT, `metodo_id_muestra` INT) RETURNS decimal(11,6)
    DETERMINISTIC
BEGIN
	DECLARE absorcion_nuevo DECIMAL(11,6);
    
    SET absorcion_nuevo = (SELECT 
                                mr.absorcion
                            FROM arg_muestras_resultados mr
                                LEFT JOIN arg_ordenes_bitacora_detalle bd ON mr.trn_id = bd.trn_id_rel AND mr.metodo_id = bd.metodo_id
                            WHERE
                                mr.trn_id_rel = trn_id_muestra
                                AND mr.metodo_id = metodo_id_muestra
                                AND bd.etapa_id = 12
                            ORDER BY bd.fecha desc
                                LIMIT 1);

RETURN(absorcion_nuevo);
END$$
DELIMITER ;