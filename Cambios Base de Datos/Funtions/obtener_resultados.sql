DELIMITER $$
DROP FUNCTION IF EXISTS obtener_resultados$$
CREATE DEFINER=`root`@`localhost` FUNCTION `obtener_resultados`(`trn_id_batch` INT, `trn_id_muestra` INT, `metodo_obt` INT) RETURNS decimal(11,3)
    NO SQL
BEGIN
	DECLARE resultado DECIMAL(16,3);
    
    
    SET resultado = (SELECT (CASE WHEN mro.reensaye = 0 
                     			 THEN TRUNCATE(mro.absorcion, 3)
                     	     ELSE segundo_reensaye(mro.trn_id_rel, mro.metodo_id) END) 
                         FROM `arg_muestras_resultados` mro
                     WHERE 
                        mro.trn_id = trn_id_batch
                        AND mro.trn_id_rel = trn_id_muestra
                        AND mro.metodo_id = metodo_obt);
    
    RETURN resultado;
END$$
DELIMITER ;