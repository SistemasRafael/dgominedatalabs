DELIMITER $$
DROP PROCEDURE IF EXISTS arg_prc_OrdenInicioRee$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_prc_OrdenInicioRee`(IN `trn_id_reensaye` INT, IN `u_id_ree` INT)
    DETERMINISTIC
BEGIN 
	DECLARE inicio_proceso SMALLINT;
    DECLARE metodo_id_row INTEGER;
 	DECLARE met_done BOOLEAN DEFAULT FALSE;
    DECLARE i INTEGER;
    DECLARE total_met SMALLINT;
    DECLARE primero_met SMALLINT;
    DECLARE inicia_prep SMALLINT;
  
    SET primero_met = (SELECT 
         od.metodo_id
    FROM
        arg_ordenes_metodos od
    WHERE
        od.trn_id_rel = trn_id_reensaye
    LIMIT 1);
    
    SET inicia_prep = (SELECT COUNT(*) AS prep 
                       FROM arg_muestras_reensaye mr 
                       WHERE 
                       mr.trn_id_rel = trn_id_reensaye 
                       AND mr.inicio_proceso = 0);
    
    IF (inicia_prep <> 0) THEN
    	BEGIN
          INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, fecha, u_id)
                      VALUES (trn_id_reensaye, primero_met, 1, curdate(), u_id_ree);

                      INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
                      VALUES (trn_id_reensaye, primero_met, 1,2, now(), u_id_ree);    
    
    	END;
    ELSE
    BEGIN
    
        DECLARE cursor3 CURSOR FOR

             SELECT 
                DISTINCT metodo_id
             FROM 
                arg_ordenes_metodos
             WHERE
                trn_id_rel = trn_id_reensaye
                AND metodo_id <> 4;

                DECLARE CONTINUE HANDLER FOR NOT FOUND 
                    SET met_done = TRUE;
                    OPEN cursor3;
                    bucle3: loop

                    FETCH FROM cursor3 INTO metodo_id_row;
                    IF met_done THEN
                        CLOSE cursor3;
                        LEAVE bucle3;
                    END IF;
                    	-- Carbones
                    		IF (metodo_id_row = 2) THEN
                           	 BEGIN
                                INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, fecha, u_id)
                                VALUES (trn_id_reensaye, metodo_id_row, 2, curdate(), u_id_ree);

                                INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
                                VALUES (trn_id_reensaye, metodo_id_row, 11, 5, now(), u_id_ree);
                           		END;
                           END IF;	

                            IF (metodo_id_row = 3) THEN
                            BEGIN
                                INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, fecha, u_id)
                                VALUES (trn_id_reensaye, metodo_id_row, 2, curdate(), u_id_ree);

                                INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
                                VALUES (trn_id_reensaye, metodo_id_row, 2,5, now(), u_id_ree);
                           END;
                           END IF;

                            IF (metodo_id_row = 6) THEN
                            BEGIN
                                INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, fecha, u_id)
                                VALUES (trn_id_reensaye, metodo_id_row, 6, curdate(), u_id_ree);

                                INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
                                VALUES (trn_id_reensaye, metodo_id_row, 6,5, now(), u_id_ree);
                            END;
                            END IF;
                            
                            IF (metodo_id_row = 36) THEN
                            BEGIN
                                INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, fecha, u_id)
                                VALUES (trn_id_reensaye, metodo_id_row, 7, curdate(), u_id_ree);

                                INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
                                VALUES (trn_id_reensaye, metodo_id_row, 7,5, now(), u_id_ree);
                           END;
                           END IF;

                            IF (metodo_id_row = 37) THEN
                            BEGIN
                                INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, fecha, u_id)
                                VALUES (trn_id_reensaye, metodo_id_row, 7, curdate(), u_id_ree);

                                INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
                                VALUES (trn_id_reensaye, metodo_id_row, 7,5, now(), u_id_ree);
                            END;
                            END IF;
                            
                             IF (metodo_id_row = 1) THEN
                            BEGIN
                                INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, fecha, u_id)
                                VALUES (trn_id_reensaye, metodo_id_row, 6, curdate(), u_id_ree);

                                INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
                                VALUES (trn_id_reensaye, metodo_id_row, 11,5, now(), u_id_ree);
                            
                            	INSERT INTO arg_muestras_sobrelimites (trn_id, trn_id_rel, trn_id_batch, trn_id_muestra, metodo_id, tipo_id, material_id, fecha, inicio_proceso, folio_interno, prom)
                                SELECT
                                	generar_trn_muestrasSobr(), ore.trn_id_rel, ore.trn_id_batch, ore.trn_id_muestra, ore.metodo_id, ore.tipo_id, ore.material_id, now(), 1, ms.folio_interno, ms.prom
                                    
                                FROM
                                     ordenes_reensayes ore
                                     LEFT JOIN arg_muestras_sobrelimites AS ms
                                     	ON ore.trn_id_batch = ms.trn_id_rel
                                        AND ore.trn_id_muestra = ms.trn_id_muestra
                                        AND ore.metodo_id = ms.metodo_id 
                                WHERE 
                                	ore.trn_id_rel = trn_id_reensaye 
                                    AND ore.metodo_id = 1;  
                                    
                                    INSERT INTO arg_muestras_resultados (trn_id, trn_id_rel, metodo_id, validacion_tipo, porcentaje)
 									SELECT
                                		reo.trn_id_rel, reo.trn_id_muestra, 1, 0, 0
                               		FROM
                                    	ordenes_reensayes reo
                                	WHERE 
                                		reo.trn_id_rel = trn_id_reensaye 
                                    	AND reo.metodo_id = 1; 
                            END;
                            END IF;

             END LOOP bucle3;
END;

END IF;
                  

UPDATE arg_ordenes_detalle ad
	SET ad.estado = 1
WHERE
    ad.trn_id = trn_id_reensaye;
END$$
DELIMITER ;