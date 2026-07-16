DELIMITER $$
DROP PROCEDURE IF EXISTS arg_prc_revisionAbsorcion $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_prc_revisionAbsorcion`(IN `trn_id_rel` INT, IN `metodo_id_abso` INT)
    NO SQL
BEGIN
DECLARE det_done BOOLEAN DEFAULT FALSE;
DECLARE posicion_muestra INT;
DECLARE blanco_dupl INT;
DECLARE i INT;
DECLARE j INT;
DECLARE contador INT;
DECLARE es_control INT;
DECLARE total_filas INT;
DECLARE total_registros INT;
DECLARE contador_avance INT;
DECLARE diferencia INT;
DECLARE total_arriba INT;
DECLARE contador_arriba INT;
DECLARE max_sobrelimite SMALLINT;
DECLARE existe_revision SMALLINT;

SET existe_revision = IFNULL((SELECT COUNT(*) 
                              FROM temp_controles 
                              WHERE 
                              trn_id_batch = trn_id_rel 
							  AND metodo_id = metodo_id_abso), 0);
                              
SET max_sobrelimite = (SELECT (CASE WHEN metodo_id_abso = 3 THEN 1 END) AS max_sob);

IF (existe_revision = 0) THEN 
BEGIN

DELETE FROM temp_controles
WHERE trn_id_batch = trn_id_rel 
AND metodo_id = metodo_id_abso;
    
INSERT INTO temp_controles (trn_id_batch, metodo_id, posicion,folio_interno,tipo_id, material_id, control, muestra_geologia, absorcion, porcentaje,  validacion_tipo, nombre, minimo, maximo, inicio_proceso)

SELECT
	trn_id_batch, metodo_id_abso, ot.posicion, ot.folio_interno, ot.tipo_id, ot.material_id, ot.control, ot.muestra_geologia, mr.absorcion, mr.porcentaje, mr.validacion_tipo, mref.nombre, mref.minimo, mref.maximo, 1
FROM
  arg_muestras_resultados mr
  LEFT JOIN ordenes_transacciones ot
     	ON ot.trn_id_batch = mr.trn_id
        AND ot.metodo_id = mr.metodo_id
        AND ot.trn_id_rel = mr.trn_id_rel
  LEFT JOIN arg_controles_materiales mref
  	ON mref.material_id = ot.material_id
    AND ot.tipo_id = 1
    AND mref.metodo_id = ot.metodo_id
WHERE 
	ot.trn_id_batch = trn_id_rel
	AND tipo_id = 1
	AND ot.metodo_id = metodo_id_abso
    AND mr.reensaye = 0
    AND mr.lectura <> 0
    
UNION ALL

SELECT
	trn_id_batch, metodo_id_abso, ot.posicion, ot.folio_interno, ot.tipo_id, ot.material_id, ot.control, ot.muestra_geologia, mr.absorcion, mr.porcentaje, mr.validacion_tipo, mref.nombre, mref.minimo, mref.maximo, 1
FROM
  arg_muestras_resultados mr
  LEFT JOIN ordenes_transacciones ot
     	ON ot.trn_id_batch = mr.trn_id
        AND ot.metodo_id = mr.metodo_id
        AND ot.trn_id_rel = mr.trn_id_rel
  LEFT JOIN arg_controles_blancos mref
  	ON mref.material_id = ot.material_id
    AND mref.metodo_id = ot.metodo_id
WHERE ot.trn_id_batch = trn_id_rel
    AND tipo_id IN (2)
    AND ot.metodo_id = metodo_id_abso
    AND mr.reensaye = 0
    AND mr.lectura <> 0
    
UNION ALL

SELECT
	trn_id_batch, metodo_id_abso, ot.posicion, ot.folio_interno, ot.tipo_id, ot.material_id, ot.control, ot.muestra_geologia, mr.absorcion, mr.porcentaje, mr.validacion_tipo, mref.nombre, mref.minimo, mref.maximo, 0
FROM
  arg_muestras_resultados mr
  LEFT JOIN ordenes_transacciones ot
     	ON ot.trn_id_batch = mr.trn_id
        AND ot.metodo_id = mr.metodo_id
        AND ot.trn_id_rel = mr.trn_id_rel
  LEFT JOIN arg_controles_blancos mref
  	ON mref.material_id = ot.material_id
    AND mref.metodo_id = ot.metodo_id
WHERE 
	ot.trn_id_batch = trn_id_rel
    AND tipo_id IN (3)
    AND ot.metodo_id = metodo_id_abso
	AND mr.reensaye = 0
    AND mr.lectura <> 0
    
UNION ALL

SELECT
	trn_id_batch, metodo_id_abso, ot.posicion, ot.folio_interno, ot.tipo_id, ot.material_id, ot.control, ot.muestra_geologia, mr.absorcion, mr.porcentaje, mr.validacion_tipo, dup.nombre
,(CASE mr.validacion_tipo WHEN 1 THEN dup.porc_ley_baja_min WHEN 2 THEN dup.porc_ley_media_min ELSE dup.porc_ley_alta_min END) AS minimo
,(CASE mr.validacion_tipo WHEN 1 THEN dup.porc_ley_baja_max WHEN 2 THEN dup.porc_ley_media_max ELSE dup.porc_ley_alta_max END) AS maximo, 1
FROM
  arg_muestras_resultados mr
  LEFT JOIN ordenes_transacciones ot
     	ON ot.trn_id_batch = mr.trn_id
        AND ot.metodo_id = mr.metodo_id
        AND ot.trn_id_rel = mr.trn_id_rel
  LEFT JOIN arg_controles_duplicados dup
  	ON dup.material_id = ot.material_id
    AND dup.metodo_id = ot.metodo_id
WHERE
	ot.trn_id_batch = trn_id_rel
    AND tipo_id IN (4)
	AND ot.metodo_id = metodo_id_abso
	AND mr.reensaye = 0
    AND mr.lectura <> 0
    
UNION ALL

SELECT
	trn_id_batch, metodo_id_abso, ot.posicion, ot.folio_interno, ot.tipo_id, ot.material_id, ot.control, ot.muestra_geologia, mr.absorcion, mr.porcentaje, mr.validacion_tipo, dup.nombre
,(CASE mr.validacion_tipo WHEN 1 THEN dup.porc_ley_baja_min WHEN 2 THEN dup.porc_ley_media_min ELSE dup.porc_ley_alta_min END) AS minimo
,(CASE mr.validacion_tipo WHEN 1 THEN dup.porc_ley_baja_max WHEN 2 THEN dup.porc_ley_media_max ELSE dup.porc_ley_alta_max END) AS maximo, 0
FROM
  arg_muestras_resultados mr
  LEFT JOIN ordenes_transacciones ot
     	ON ot.trn_id_batch = mr.trn_id
        AND ot.metodo_id = mr.metodo_id
        AND ot.trn_id_rel = mr.trn_id_rel
  LEFT JOIN arg_controles_duplicados dup
  	ON dup.material_id = ot.material_id
    AND dup.metodo_id = ot.metodo_id
WHERE
	ot.trn_id_batch = trn_id_rel
	AND tipo_id IN (5)
	AND ot.metodo_id = metodo_id_abso
    AND mr.reensaye = 0
    AND mr.lectura <> 0
    
UNION ALL

SELECT
	trn_id_batch, metodo_id_abso, ot.posicion, ot.folio_interno, ot.tipo_id, ot.material_id, ot.control, ot.muestra_geologia, mr.absorcion, mr.porcentaje, mr.validacion_tipo, '' as nombre
,0 AS minimo
,0 AS maximo
,1
FROM
  arg_muestras_resultados mr
  LEFT JOIN ordenes_transacciones ot
     	ON ot.trn_id_batch = mr.trn_id
        AND ot.trn_id_rel = mr.trn_id_rel
WHERE
	ot.trn_id_batch = trn_id_rel
	AND mr.metodo_id = metodo_id_abso
	AND ot.tipo_id = 0
	AND mr.reensaye = 0
    AND mr.lectura <> 0;
    
UPDATE temp_controles  tc1
	SET tc1.reensaye = 2
WHERE
    tc1.trn_id_batch = trn_id_rel
    AND tc1.metodo_id = metodo_id_abso
    AND tc1.tipo_id IN(1,2,3) 
    AND tc1.absorcion NOT BETWEEN tc1.minimo AND tc1.maximo;
    
UPDATE temp_controles tc2
	SET tc2.reensaye = 2
WHERE
    tc2.trn_id_batch = trn_id_rel
    AND tc2.metodo_id = metodo_id_abso
    AND	tc2.tipo_id IN(4,5) 
    AND tc2.porcentaje NOT BETWEEN tc2.minimo AND tc2.maximo;
    
UPDATE temp_controles temc
	SET temc.sobrelimite = 1
WHERE
    temc.absorcion >= max_sobrelimite
    AND temc.trn_id_batch = trn_id_rel 
    AND temc.tipo_id = 0
    AND temc.metodo_id = metodo_id_abso;
    
SET total_registros = (SELECT COUNT(*) FROM temp_controles WHERE trn_id_batch = trn_id_rel 
AND metodo_id = metodo_id_abso);

BLOCK3: BEGIN
DECLARE cursor1 CURSOR FOR  
          SELECT
              posicion, tipo_id
          FROM 
              temp_controles
          WHERE
              trn_id_batch = trn_id_rel
              AND metodo_id = metodo_id_abso
              AND reensaye = 2
          ORDER BY posicion;

     DECLARE CONTINUE HANDLER FOR NOT FOUND 
                SET det_done = TRUE;
            OPEN cursor1;
            bucle1: loop
            FETCH FROM cursor1 INTO posicion_muestra, blanco_dupl;          
            	IF det_done THEN
                	 CLOSE cursor1;
            		 LEAVE bucle1;
             	END IF;
                
                SET i = 1;
                SET contador = 1;
                IF (posicion_muestra < 4) THEN
                    BEGIN
                        SET contador_avance = (posicion_muestra-1);
                        SET total_filas = (posicion_muestra-1);    
                    END;
                       ELSE
                    BEGIN
                        SET contador_avance = 3;
                        SET total_filas = (posicion_muestra-1);             
                    END;
                END IF;
                
                WHILE (contador <= contador_avance AND i <= total_filas) DO
                    SET es_control = (SELECT tipo_id FROM temp_controles WHERE posicion = (posicion_muestra-i) AND trn_id_batch = trn_id_rel AND metodo_id = metodo_id_abso);
                    
                    IF (es_control = 0) THEN 
                    	BEGIN
                            UPDATE temp_controles
                                 SET reensaye = 1
                            WHERE
                            	trn_id_batch = trn_id_rel
                                AND metodo_id = metodo_id_abso
                                AND posicion = (posicion_muestra-i);
                                
                                IF (blanco_dupl = 3 or blanco_dupl = 5) THEN
                                	BEGIN
                                    	UPDATE temp_controles
                                             SET inicio_proceso = 0
                                        WHERE
                                            trn_id_batch = trn_id_rel
                                            AND metodo_id = metodo_id_abso
                                            AND posicion = (posicion_muestra-i);
                                    END;
                                END IF;
                                
                            SET contador = contador+1;
                            SET i = i+1;
                      	END;
                     ELSE
                     	BEGIN
                        	 SET i = i+1;                            
                        END;
                     END IF;
                END WHILE;
                
                SET j = 1;
                SET contador_arriba = 1;
                
                SET diferencia = (total_registros - posicion_muestra);
                IF (diferencia < 3) THEN
                  BEGIN
                	SET total_arriba = diferencia;
                  END;
                ELSE
                	BEGIN                    
                		SET total_arriba = 3;
                    END;
                END IF;
                	
                
                WHILE (contador_arriba <= total_arriba AND j <= total_registros) DO
                	SET es_control = (SELECT tipo_id FROM temp_controles WHERE trn_id_batch = trn_id_rel AND metodo_id = metodo_id_abso AND posicion = (posicion_muestra+j));
                    
                    IF (es_control = 0) THEN
                    BEGIN
                        UPDATE temp_controles
                             SET reensaye = 1
                        WHERE
                            trn_id_batch = trn_id_rel 
                            AND metodo_id = metodo_id_abso
                            AND posicion = (posicion_muestra+j);
                            
                            IF (blanco_dupl = 3 or blanco_dupl = 5) THEN
                               BEGIN
                                    UPDATE temp_controles
                                        SET inicio_proceso = 0
                                    WHERE
                                        trn_id_batch = trn_id_rel
                                        AND metodo_id = metodo_id_abso
                                        AND posicion = (posicion_muestra+j);
                                END;
                              END IF;
                            
                        SET contador_arriba = contador_arriba+1;
                        SET j = j+1;
                    END;
                    ELSE
                    	BEGIN
                            SET j = j+1;
                         END;   
                  END IF;
                      
                END WHILE;
                
   END LOOP bucle1;
  END BLOCK3;

  UPDATE arg_muestras_resultados mr
  LEFT JOIN ordenes_transacciones ot
     	ON ot.trn_id_batch = mr.trn_id
        AND ot.trn_id_rel = mr.trn_id_rel
     
  LEFT JOIN temp_controles tc
        ON tc.trn_id_batch = ot.trn_id_batch
        AND tc.metodo_id = mr.metodo_id
        AND tc.folio_interno = ot.folio_interno      
  SET  mr.reensaye = (CASE WHEN tc.reensaye <> 0 THEN 1 ELSE 0 END)
WHERE 
     ot.trn_id_batch = trn_id_rel 
     AND mr.metodo_id = metodo_id_abso
     AND mr.reensaye = 0;
END;
END IF;

SELECT * FROM temp_controles WHERE trn_id_batch = trn_id_rel AND metodo_id = metodo_id_abso ORDER BY posicion; 

END$$
DELIMITER ;