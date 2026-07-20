DELIMITER $$
DROP PROCEDURE IF EXISTS arg_prc_actualizarAbsorcionSolucion$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_prc_actualizarAbsorcionSolucion`(IN `trnid_batch` INT, IN `metodo_id_ab` INT, IN `u_id_a` INT)
BEGIN 

    DECLARE promedio DECIMAL (11,6);
    DECLARE metodo_sel INT;
    DECLARE det_done BOOLEAN DEFAULT FALSE;
    DECLARE trnid_muestra INT;
    DECLARE repeticion INT;
    DECLARE pos INT;
    DECLARE folio VARCHAR(150);
    DECLARE trn_lectura INT;
    SET pos = 1;

    SET trn_lectura = (SELECT csv.trn_id 
                    FROM arg_ordenes_csv_detalle csv 
                    WHERE 
                            csv.trn_id_rel = trnid_batch 
                            AND csv.metodo_id = metodo_id_ab
                            LIMIT 1);

    IF (metodo_id_ab = 14 or metodo_id_ab = 15 or metodo_id_ab = 16) THEN BEGIN
        DECLARE cursor2 CURSOR FOR
                
            SELECT DISTINCT ms.trn_id_rel, os.ciclica, os.folio
            FROM `arg_ordenes_soluciones` ms
            LEFT JOIN arg_ordenes_muestrasSoluciones os ON os.trn_id = ms.trn_id_rel
            WHERE ms.trn_id_batch = trnid_batch 
                AND ms.metodo_id = metodo_id_ab
                AND os.folio IN (SELECT cs.folio FROM arg_ordenes_csv_detalle cs WHERE cs.trn_id_rel = trnid_batch AND cs.metodo_id = metodo_id_ab);

            DECLARE CONTINUE HANDLER FOR NOT FOUND SET det_done = TRUE;

            OPEN cursor2;
            bucle2: loop
            FETCH FROM cursor2 INTO trnid_muestra, repeticion, folio;

                IF det_done THEN
                        CLOSE cursor2;                         
                        LEAVE bucle2;
                END IF;    
            
            IF (repeticion > 1) THEN BEGIN 
                            
                    SET promedio = ((SELECT SUM(sol.valor1) AS total 
                                    FROM arg_ordenes_csv_detalle sol                    
                                    WHERE
                                        sol.trn_id_rel = trnid_batch
                                        AND sol.metodo_id = metodo_id_ab
                                        AND sol.folio = folio) / repeticion);

                END;
                ELSE BEGIN

                    SET promedio = (SELECT sol.valor1 
                                        FROM arg_ordenes_csv_detalle sol                    
                                        WHERE
                                            sol.trn_id_rel = trnid_batch
                                            AND sol.metodo_id = metodo_id_ab
                                            AND sol.folio = folio);
            
                END;
                END IF;
                                        
                INSERT INTO temp_controles(trn_id_batch, metodo_id, posicion, folio_interno, tipo_id, material_id, absorcion, inicio_proceso)
                VALUES (trnid_batch, metodo_id_ab, pos, folio, 2, 0, promedio,1);                         
            
        END loop bucle2;
        
        UPDATE temp_controles  tc
        LEFT JOIN arg_ordenes_muestrasSoluciones ms ON ms.folio = tc.folio_interno
        LEFT JOIN arg_ordenes_soluciones ot ON ot.trn_id_batch = tc.trn_id_batch AND ot.metodo_id = tc.metodo_id AND ot.trn_id_rel = ms.trn_id
        SET ot.resultado = IFNULL(tc.absorcion, 0), 
            ot.lectura = trn_lectura
        WHERE tc.trn_id_batch = trnid_batch AND 
            tc.metodo_id = metodo_id_ab;
        
    END;
    ELSE BEGIN
            
        UPDATE arg_ordenes_csv_detalle  tc
        LEFT JOIN arg_ordenes_muestrasSoluciones ms ON ms.folio = tc.folio
        LEFT JOIN arg_ordenes_soluciones ot ON ot.trn_id_batch = tc.trn_id_rel AND ot.metodo_id = tc.metodo_id AND ot.trn_id_rel = ms.trn_id
        SET ot.resultado = tc.valor1,
            ot.lectura = trn_lectura
        WHERE tc.trn_id_rel = trnid_batch AND 
            tc.metodo_id = metodo_id_ab;
        
    END;
    END IF;

    INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, fecha, u_id)
    VALUES (trnid_batch, metodo_id_ab, 12, now(), 0);

    INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, fecha, u_id)
    VALUES (trnid_batch, metodo_id_ab, 12, 10, now(), 0);

END$$
DELIMITER ;