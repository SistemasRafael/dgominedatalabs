DELIMITER $$
DROP PROCEDURE IF EXISTS arg_rpt_generarPDF$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_rpt_generarPDF`(IN `trn_id_batch` INT)
    NO SQL
BEGIN
	
  DECLARE det_done BOOLEAN DEFAULT FALSE;
  DECLARE max INTEGER; 
  DECLARE i INTEGER;
  DECLARE metodo_sel INTEGER;
  DECLARE metodo_nombre VARCHAR(85);
  DECLARE metodo_nombre_largo VARCHAR(85);
  DECLARE tipo_orden INT;  
  
  SET tipo_orden = (SELECT oa.tipo 
                    FROM 
                        arg_ordenes_detalle deo
                        LEFT JOIN arg_ordenes AS oa
                        	ON deo.trn_id_rel = oa.trn_id
                    WHERE 
                    	deo.trn_id = trn_id_batch);
  
  CREATE TEMPORARY TABLE resultados_tempo(
       orden 	    SMALLINT DEFAULT 4
      ,trn_idbatch  INTEGER DEFAULT 0
      ,folio_orden  VARCHAR (255) DEFAULT NULL
      ,folio_batch  VARCHAR (255) DEFAULT NULL
      ,fecha        VARCHAR (255) DEFAULT NULL
      ,trn_idmuestra INTEGER DEFAULT 0
      ,muestra 		VARCHAR (255) DEFAULT NULL
      ,resultado1  	VARCHAR (255) DEFAULT NULL
      ,resultado2  	VARCHAR (255) DEFAULT NULL
      ,resultado3  	VARCHAR (255) DEFAULT NULL
      ,resultado4  	VARCHAR (255) DEFAULT NULL
      ,resultado5  	VARCHAR (255) DEFAULT NULL
      ,resultado6  	VARCHAR (255) DEFAULT NULL
  );
  
  INSERT INTO resultados_tempo(orden, muestra, resultado1, resultado2, resultado3, resultado4, resultado5, resultado6)
  SELECT 
        0 AS orden
       ,'ELEMENTO' AS muestra
       ,'Au' AS resultado1
       ,'Ag' AS resultado2
       ,'Cu' AS resultado3       
       ,'Au' AS resultado4
       ,'Au' AS resultado5
       ,'Ag' AS resultado6
  UNION ALL
  SELECT        
        1 AS orden
       ,'METODO' AS muestra
       ,'EFAA30' AS resultado1
       ,'VHAAAg' AS resultado2
       ,'VHAACu' AS resultado3       
       ,'EFGRA30' AS resultado4
       ,'CNH_Au' AS resultado5
       ,'CNH_Ag' AS resultado6
  UNION ALL
  SELECT
        2 AS orden
       ,'LIM. DET.' AS muestra
       ,'0.0200' AS resultado1
       ,'0.5000' AS resultado2
       ,'0.0200' AS resultado3       
       ,'1.0000' AS resultado4
       ,'0.0420' AS resultado5
       ,'0.7000' AS resultado6
  UNION ALL
  SELECT 
        3 AS orden
       ,'UNIDAD' AS muestra
       ,'PPM' AS resultado1
       ,'PPM' AS resultado2
       ,'PPM' AS resultado3       
       ,'PPM' AS resultado4
       ,'PPM' AS resultado5
       ,'PPM' AS resultado6;
  
  IF (tipo_orden = 1) THEN
  BEGIN
  	  INSERT INTO resultados_tempo (
      trn_idbatch, folio_orden, folio_batch, fecha, trn_idmuestra, muestra)
        	
        SELECT
             od.trn_id
            ,od.folio_interno
            ,o.folio
            ,o.fecha   
            ,mu.trn_id
            ,mu.folio            
         FROM
            arg_ordenes_detalle od
            LEFT JOIN arg_ordenes AS o
                ON o.trn_id = od.trn_id_rel
            LEFT JOIN arg_ordenes_muestras mu
                ON  mu.trn_id_rel = od.trn_id
                AND mu.tipo_id = 0             
        WHERE
            od.trn_id = trn_id_batch;
  END;
     ELSE
  BEGIN
  		INSERT INTO resultados_tempo (
      trn_idbatch, folio_orden, folio_batch, fecha, trn_idmuestra, muestra)
        	
        SELECT
             od.trn_id
            ,od.folio_interno
            ,o.folio
            ,o.fecha   
            ,mu.trn_id
            ,mu.folio            
         FROM
            arg_ordenes_detalle od
            LEFT JOIN arg_ordenes o
                ON o.trn_id = od.trn_id_rel
            LEFT JOIN arg_muestras_sobrelimites sl
            	ON od.trn_id = sl.trn_id_rel
                AND sl.tipo_id = 0
                AND sl.prom = 1
            INNER JOIN arg_ordenes_muestras mu
                ON  mu.trn_id = sl.trn_id_muestra
                AND mu.trn_id_rel = sl.trn_id_batch                
                AND mu.tipo_id = 0             
        WHERE
            od.trn_id = trn_id_batch;
  END;
  END IF;

 BLOCK2: BEGIN
             	
  DECLARE cursor2 CURSOR FOR    
  
        SELECT
        	DISTINCT met.metodo_id         
        FROM
             arg_metodos met
        LEFT JOIN arg_ordenes_metodos om
        	ON met.metodo_id = om.metodo_id
        WHERE
        	om.trn_id_rel =  trn_id_batch;
       
       DECLARE CONTINUE HANDLER FOR NOT FOUND SET det_done = TRUE;       
        
          OPEN cursor2;
          bucle2: loop
          FETCH FROM cursor2 INTO metodo_sel;

           IF det_done THEN
                 CLOSE cursor2;                         
                 LEAVE bucle2;
           END IF;
  
  IF (metodo_sel = 3)
       THEN
  	  		UPDATE resultados_tempo
            	SET resultado1 = obtener_resultados(trn_id_batch, trn_idmuestra, metodo_sel)
            WHERE
            	trn_idbatch = trn_id_batch;      
    END IF;
    
    IF (metodo_sel = 1)
       THEN
  	  		UPDATE resultados_tempo
            	SET resultado4 = obtener_resultados(trn_id_batch, trn_idmuestra, metodo_sel)
            WHERE
            	trn_idbatch = trn_id_batch;      
    END IF;
    
    IF (metodo_sel=6)
       THEN
  	  		UPDATE resultados_tempo
            	SET resultado2 = obtener_resultados(trn_id_batch, trn_idmuestra, metodo_sel)
            WHERE
            	trn_idbatch = trn_id_batch;
      
    END IF;
    
    IF (metodo_sel=11)
       THEN
  	  		UPDATE resultados_tempo
            	SET resultado3 = obtener_resultados(trn_id_batch, trn_idmuestra, metodo_sel)
            WHERE
            	trn_idbatch = trn_id_batch;
      
    END IF;

    IF (metodo_sel=36)
       THEN
  	  		UPDATE resultados_tempo
            	SET resultado5 = obtener_resultados(trn_id_batch, trn_idmuestra, metodo_sel)
            WHERE
            	trn_idbatch = trn_id_batch;
      
    END IF;

    IF (metodo_sel=37)
       THEN
  	  		UPDATE resultados_tempo
            	SET resultado6 = obtener_resultados(trn_id_batch, trn_idmuestra, metodo_sel)
            WHERE
            	trn_idbatch = trn_id_batch;
      
    END IF;
    
    END loop bucle2;
    
  END BLOCK2;
  
      SELECT * FROM resultados_tempo;
      DROP TABLE resultados_tempo;
 
 
 END$$
DELIMITER ;