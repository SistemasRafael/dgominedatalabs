DELIMITER $$
DROP FUNCTION IF EXISTS validacion_margen$$
CREATE DEFINER=`root`@`localhost` FUNCTION `validacion_margen`(`metodo_id_sel` INT, `material_id_sel` INT, `absorcion` DECIMAL(16,6)) RETURNS int
    NO SQL
BEGIN
	DECLARE validacion_tipo INT;

    SET validacion_tipo = (SELECT 
                                (CASE WHEN absorcion BETWEEN ley_baja_min AND ley_baja_max THEN 1 
                                    WHEN absorcion BETWEEN ley_media_min AND ley_media_max THEN 2 
                                    WHEN absorcion >= ley_alta_min THEN 3 
                                    ELSE 0 END)
                            FROM `arg_controles_duplicados` 
                            WHERE metodo_id = metodo_id_sel and material_id = material_id_sel);
                            
    RETURN validacion_tipo;
END$$
DELIMITER ;