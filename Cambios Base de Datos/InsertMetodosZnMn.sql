INSERT INTO `arg_metodos` (`metodo_id`, `tipo_id`, `nombre`, `nombre_largo`, `activo`, `u_id`, `color`, `volumen`, `elemento`) VALUES 
(38, 1, 'VHAAZn', 'Metodo de Zinc', 1, 82, 'btn btn-secondary', 0, 'Zn_ppm'),
(39, 1, 'VHAAMn', 'Metodo de manganesio', 1, 82, 'btn btn-secondary', 0, 'Mn_ppm');

INSERT INTO `arg_controles_duplicados` (`unidad_id`, `material_id`, `nombre`, `control_id`, `ley_baja_min`, `ley_baja_max`, `ley_media_min`, `ley_media_max`, `ley_alta_min`, `ley_alta_max`, `porc_ley_baja_min`, `porc_ley_baja_max`, `porc_ley_media_min`, `porc_ley_media_max`, `porc_ley_alta_min`, `porc_ley_alta_max`, `metodo_id`, `activo`, `u_id`) VALUES
(2, 1, 'Duplicado de Ensaye', 3, 0.000000, 0.100000, 0.100000, 0.299999, 0.300000, 100.000000, 0.000000, 32.000000, 0.000000, 20.000000, 0.000000, 20.000000, 38, 1, 5),
(2, 2, 'Duplicado de Cuarteo', 4, 0.000000, 0.099999, 0.100000, 0.299999, 0.300000, 100.000000, 0.000000, 42.000000, 0.000000, 30.000000, 0.000000, 30.000000, 38, 1, 5);

INSERT INTO `arg_controles_duplicados` (`unidad_id`, `material_id`, `nombre`, `control_id`, `ley_baja_min`, `ley_baja_max`, `ley_media_min`, `ley_media_max`, `ley_alta_min`, `ley_alta_max`, `porc_ley_baja_min`, `porc_ley_baja_max`, `porc_ley_media_min`, `porc_ley_media_max`, `porc_ley_alta_min`, `porc_ley_alta_max`, `metodo_id`, `activo`, `u_id`) VALUES
(2, 1, 'Duplicado de Ensaye', 3, 0.000000, 0.100000, 0.100000, 0.299999, 0.300000, 100.000000, 0.000000, 32.000000, 0.000000, 20.000000, 0.000000, 20.000000, 39, 1, 5),
(2, 2, 'Duplicado de Cuarteo', 4, 0.000000, 0.099999, 0.100000, 0.299999, 0.300000, 100.000000, 0.000000, 42.000000, 0.000000, 30.000000, 0.000000, 30.000000, 39, 1, 5);

INSERT INTO `arg_controles_blancos` (`unidad_id`, `material_id`, `nombre`, `control_id`, `valor_ley`, `maximo`, `minimo`, `metodo_id`, `u_id`, `activo`) VALUES
(2, 28, 'Blanco Reactivo', 1, 0.000000, 0.042000, -0.042000, 38, 5, 1),
(2, 29, 'Blanco Preparado', 5, 0.000000, 0.0420000, -0.042000, 38, 5, 1);

INSERT INTO `arg_controles_blancos` (`unidad_id`, `material_id`, `nombre`, `control_id`, `valor_ley`, `maximo`, `minimo`, `metodo_id`, `u_id`, `activo`) VALUES
(2, 30, 'Blanco Reactivo', 1, 0.000000, 0.700000, -0.700000, 39, 5, 1),
(2, 31, 'Blanco Preparado', 5, 0.000000, 0.700000, -0.700000, 39, 5, 1);

INSERT INTO `arg_controles_materiales` (`id`, `unidad_id`, `material_id`, `nombre`, `control_id`, `valor_ley`, `desv_esta`, `cantidad_desviacion`, `maximo`, `minimo`, `metodo_id`, `valor_ley_ag`, `desv_est_ag`, `cant_desv_ag`, `maximo_ag`, `minimo_ag`, `u_id`, `activo`, `ricos_pobres`, `file_path`) VALUES
(150, 2, 108, 'VHAAZn_CDN', 2, 0.121000, 0.003000, 2, 0.127000, 0.115000, 38, NULL, NULL, NULL, NULL, NULL, 82, 1, NULL, 'upload/SA/VHAAZn_Certificate.pdf'),
(151, 2, 109, 'VHAAMn_CDN', 2, 0.121000, 0.003000, 2, 0.127000, 0.115000, 39, NULL, NULL, NULL, NULL, NULL, 82, 1, NULL, 'upload/SA/VHAAMn_Certificate.pdf');