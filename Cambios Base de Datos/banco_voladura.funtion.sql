DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `banco_voladura`(`trn_id_muestra` INT) RETURNS varchar(150) CHARSET utf8mb4
    DETERMINISTIC
BEGIN 
	DECLARE banvol VARCHAR(150);
    DECLARE banco VARCHAR(50);    
    DECLARE voladura VARCHAR(50);
 
	SET banco = (SELECT vol.banco AS banco
                       FROM
                            arg_ordenes_muestras orm
                            LEFT JOIN arg_ordenes_detalle od
                                ON orm.trn_id_rel = od.trn_id
                            LEFT JOIN arg_bancos ba
                                ON ba.banco_id = od.banco_id
                            LEFT JOIN arg_bancos_voladuras vol
                               ON vol.banco_id = od.banco_id  
                               AND vol.voladura_id = od.voladura_id
                        WHERE
                            orm.trn_id = trn_id_muestra
                     );
                     
      SET voladura = (SELECT LPAD(vol.voladura_id, 3, '0') AS vol

                       FROM
                            arg_ordenes_muestras orm
                            LEFT JOIN arg_ordenes_detalle od
                                ON orm.trn_id_rel = od.trn_id
                            LEFT JOIN arg_bancos ba
                                ON ba.banco_id = od.banco_id
                            LEFT JOIN arg_bancos_voladuras vol
                               ON vol.banco_id = od.banco_id  
                               AND vol.voladura_id = od.voladura_id
                        WHERE
                            orm.trn_id = trn_id_muestra
                     );
                     
    SET  banvol = CONCAT(banco,voladura); 
    
    RETURN (banvol);
END$$
DELIMITER ;