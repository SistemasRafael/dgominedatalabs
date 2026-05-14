<?php
    $unidad_id = $_GET['unidad_id'];
    $_SESSION['unidad_id'] = $unidad_id;

    $fecha = $_GET['fecha'] ?? date('Y-m-d');
    $fecha_eti = str_replace('-', '', $fecha);

    
enum Metodos: int
{
    case EFAA30 = 3;
    case HUM = 30;
    case VHAAAg = 6;
}

?> 

<script>
    var contador=1;
</script>

<script>
    function buscar_orden($unidad_id)
    {
         var trn_id = $trn_id;
         var unidad_id = $unidad_id;                
         var print_d = '<?php echo "\orden_trabajo_print.php?unidad_id="?>'+unidad_id;                
         window.location.href = print_d;
    }
    
     function cargar_muestras($unidad_id)
    {
         var area_id   = document.getElementById('area_id').value;
         var turno     = document.getElementById('turno').value;
         var fecha     = document.getElementById('fecha').value;
         
         //var ricosp   = document.getElementById('ricospobres').value;
      ///   alert(ricosp);
         var unidad_id = $unidad_id;                
         
         //alert(turno);
         var print_d = '<?php echo "\app_barr_dgo.php?unidad_id="?>'+unidad_id+'&area_id='+area_id+'&r_id=0'+'&turno='+turno+'&fecha='+fecha;                
         window.location.href = print_d;
         calculatotal();
    }

    function cargar_rp_muestras($unidad_id)
    {
         var area_id   = document.getElementById('area_id').value;
         var turno     = document.getElementById('turno').value;
         var fecha     = document.getElementById('fecha').value;
         
         var ricosp   = document.getElementById('ricospobres').value;
         alert(ricosp);
         var unidad_id = $unidad_id;                
         
         //alert(turno);
         var print_d = '<?php echo "\app_barr_dgo.php?unidad_id="?>'+unidad_id+'&area_id='+area_id+'&r_id='+ricosp+'&turno='+turno+'&fecha='+fecha;                
         window.location.href = print_d;
         calculatotal();
    }
  
    function calculatotal(l)
    {
         var j = 3;     
         var table = document.getElementById("tablaprueba");
         var total_rows = parseInt(table.rows.length);
         var total_mues = parseInt(0);
         var fila_validar = '';
         var act = '';
         var cl = l;
         var cln = 0;
         //alert(total_rows);
         var area_id = document.getElementById('area_id').value;
         var unidad_id = document.getElementById('mina_seleccionada').value;
         
         var activos = 0;
         if (area_id == 2)// || area_id ==3)
         {
            document.getElementById('total_muestras_1').value = cl;         
            document.getElementById('total_muestras1').value  = cl; 
         }

         if (area_id == 1 || area_id == 6)
         {
            document.getElementById('total_muestras_1').value = 1;         
            document.getElementById('total_muestras1').value  = 1; 

         }
         if (area_id == 3)
         {
            var muestr = 'muestra'+cl;
            var existe_tex  = document.getElementById(muestr).value
            var ij = 1;
           // alert(existe_tex);

           if (existe_tex == ''){
                while(ij <= 8){
                    var muestra_sele = 'muestra'+ij;
                    var existe_texto  = document.getElementById(muestra_sele).value;
                   
                            if (existe_texto == ''){
                                ij = ij+1;
                            }
                            else{
                                cln = cln+1;
                                ij = ij+1;
                            }
                        }       
                            document.getElementById('total_muestras_1').value = cln;         
                            document.getElementById('total_muestras1').value  = cln; 
           }
           else{
          
                $.ajax({
                        url: 'valida_muestra.php' ,
                        type: 'POST' ,
                        dataType: 'html',
                        data: {unidad_id: unidad_id, existe_tex: existe_tex},
                    })
                    .done(function(respuesta){
                    if (respuesta == 'existe'){
                            alert('La muestra ya existe. Reintente por favor');
                            document.getElementById(muestr).value = '';
                    }else{
                        while(ij <= 8){
                            var muestra_sele = 'muestra'+ij;
                            var existe_texto  = document.getElementById(muestra_sele).value;
                       //     alert(existe_texto);
                            if (existe_texto == ''){
                                ij = ij+1;
                            }
                            else{
                                cln = cln+1;
                                ij = ij+1;
                            }
                        }
                            document.getElementById('total_muestras_1').value = cln;         
                            document.getElementById('total_muestras1').value  = cln; 
                    }
                                    
                })  
            }
         }
         
         if (area_id == 4 || area_id == 5 || area_id == 6)
         {
            var muestr = 'sig_muestra'+cl;
            var existe_tex  = document.getElementById(muestr).value
                  
            $.ajax({
            		url: 'valida_muestra.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {unidad_id: unidad_id, existe_tex: existe_tex},
            	})
            	.done(function(respuesta){
            	   if (respuesta == 'existe'){
            	           alert('La muestra ya existe. Reintente por favor');
                           document.getElementById(muestr).value = '';
            	   }else{
                        document.getElementById('total_muestras_1').value = cl;         
                        document.getElementById('total_muestras1').value  = cl; 
            	   }
            	                
              })  
         }         
    }
    
    function calculatotal_met(contador)
    {
         
         var table = document.getElementById("tablaprueba");
         var activos = parseInt(table.rows.length-3);        
         
         document.getElementById('total_muestras_1').value = activos;         
         document.getElementById('total_muestras1').value  = activos; 
    }
        
    function verificar_seleccion(numb){          
          var validar = numb;
          
         var unidadimina = document.getElementById('mina_seleccionada').value;
          //alert(unidadimina);
          if(validar == 4){
            alert('Se debe capturar al menos un método');
            cargar_muestras(unidadimina);
          } 
          if(validar == 5){
            alert('Se debe seleccionar una muestra. Reintente por favor');
            cargar_muestras(unidadimina);
          }         
           if(validar == 6){
            alert('La muestra ingresada ya existe, favor de validar. Reintente por favor');
            cargar_muestras(unidadimina);
          }                  
     }
    
     function imprimir($unidad_id,$trn_id)
            {
                 alert('Se generó la orden de trabajo satisfactoriamente');
                 var trn_id = $trn_id;
                 var unidad_id = $unidad_id 
                 var print_d = '<?php echo "\orden_trabajo_rep.php?trn_id="?>' + trn_id + '&unidad_id=' + unidad_id;
                 window.location.href = print_d;
            }
            
      function agregar_muestra(){
          var table = document.getElementById("tablaprueba");
          var contador = (table.rows.length-2);
          //alert(contador);
          var sig_mues = "muestra"+contador;
          var muestra_sele = document.getElementById("muestra_sel").value;
          //alert(muestra_sele);
          $.ajax({
            		url: 'obtener_muestra_folio.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {muestra_sele, muestra_sele},
            	})
            	.done(function(respuesta){
            	  document.getElementById("tablaprueba").insertRow(-1).innerHTML = '<td><input type="hidden" name="fila" id="fila" value = "'+contador+'" class="form-control" />'+contador+'</td>'
        +'<td><input type="hidden" name="'+sig_mues+'" id="'+sig_mues+'" value = "'+muestra_sele+'" class="form-control" />'+respuesta+'</td>'; 
          	  calculatotal_met();                
              })   
    } 
    
</script>
<br/><br/>
<?php  
    if(($_SESSION['LoggedIn']) <> '')
    {
        $user_fir       = $mysqli->query("SELECT nombre
                                            FROM `arg_usuarios`                                        
                                            WHERE u_id = ".$_SESSION['u_id']) or die(mysqli_error($mysqli));
        $user_firmado   = $user_fir ->fetch_array(MYSQLI_ASSOC);
        $nombre_usuario = $user_firmado['nombre'];
        
        if ($unidad_id == "") {
            $nombretop = "Seleccione Mina";
        }
        else {
            $nomtop = $unidad_id;
            $result = $mysqli->query("SELECT unidad_id, Nombre FROM arg_empr_unidades WHERE unidad_id = ".$unidad_id) or die(mysqli_error($mysqli));
            while( $row = $result ->fetch_array(MYSQLI_ASSOC)) {
                $nombretop = $row['Nombre']; 
            }
        }  

        $metodosfetch = $mysqli->query("SELECT metodo_id, nombre FROM arg_metodos WHERE metodo_id IN(3, 6, 30) ORDER BY  nombre") or die(mysqli_error($mysqli));
        $metodos = [];
        while ($row = $metodosfetch->fetch_assoc()) {
            $metodos[] = $row;
        }

        $minas = $mysqli->query("SELECT unidad_id, Nombre FROM arg_empr_unidades") or die(mysqli_error($mysqli));

        $dropDownMinas = "<select name=\"mina_seleccionada\" id=\"mina_seleccionada\" disabled class=\"form-control\" >
                            <option value=$nomtop>$nombretop</option>";
        while( $row = $minas ->fetch_array(MYSQLI_ASSOC)) {
            $nombre =($row["Nombre"]);
            $nomenclatura = $row["unidad_id"];                                          
            $dropDownMinas .= "<option value=$nomenclatura>$nombre</option>";
        }
        $dropDownMinas .= "</select>";
?>

        <form method="post" action="preorden_trabajo_staker.php?unidad_id=<?php echo $unidad_id; ?>" name="Visitaform" id="Visitaform">
            <fieldset>                       
                <div class="col-md-12 col-lg-12 bg-info text-black text-center">
                    <br />
                    <h4>ORDEN DE TRABAJO STAKER</h4>
                </div>
                <br/><br/><br/> <br/>
                <div class="container">                                                                                                                                                 
                    <div class="col-md-11 col-lg-11">
                        <div class="col-md-1 col-lg-1"><h5>Fecha:</h5></div>
                        <div class="col-md-2 col-lg-2">
                            <input type="date" name="fecha" class="form-control" id="fecha" onchange="cargar_muestras(<?php echo $unidad_id; ?>)" value="<?php echo $fecha;?>"/>
                        </div>  
                        <div class="col-md-2 col-lg-2">                                
                            <?php echo $dropDownMinas; ?>
                        </div>
                    </div>
                    <br /><br /><br />
                    <div class="col-md-10 col-lg-10">
                        <div class="row">  
                            <div class="col-md-10 col-lg-10"></div>
                            <div class="col-md-1 col-lg-1"> 
                                <input type="submit" class="btn btn-success" name="generar_ordenBarra" id="generar_ordenBarra" data-toggle="modal" data-target="#exampleModal" value="GUARDAR ORDEN" />                      
                            </div>
                        </div>
                        <table class="table table-hover text-black" id="tablaprueba">
                            <thead class="thead-light" align='center'>
                                <tr>
                                    <th colspan='4'>METODOS</th>
                                </tr>
                                <tr>
                                    <th colspan='8'>
                                        <div class="[ form-group ] ">   
                                            <?php
                                                if(isset($metodos)){
                                                    foreach ($metodos as $fila) {?>
                                                        <input type="checkbox" name="<?php echo 'metodo_'.$fila['metodo_id']; ?>" id="<?php echo 'metodo_'.$fila['metodo_id']; ?>" autocomplete="off" />
                                                        <div class="[ btn-group ]">                                                                
                                                            <label for="<?php echo 'metodo_'.$fila['metodo_id']; ?>" class="[ btn btn-info ]">
                                                                <span class="[ glyphicon glyphicon-ok ]"></span>                            
                                                                <span></span>
                                                            </label>                                                    
                                                            <label for="<?php echo 'metodo_'.$fila['metodo_id']; ?>" class="[ btn btn-info active ]">
                                                                <?php echo $fila['nombre']?>
                                                            </label>                              
                                                        </div>                                            
                                            <?php } }?>                                        
                                        </div>   
                                    </th>
                                </tr>
                                <th colspan='1'>No.</th>
                                <th colspan='1'>MUESTRA</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input 
                                            type="text" 
                                            name="No_1" 
                                            id="No_1" 
                                            disabled="true"
                                            class="form-control" 
                                            value="1"/>
                                    </td>                   
                                    <td>
                                        <input 
                                            type="text" 
                                            name="muestra_1" 
                                            id="muestra_1"
                                            class="form-control"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input 
                                            type="text" 
                                            name="No_2" 
                                            id="No_2" 
                                            disabled="true"
                                            class="form-control" 
                                            value="2"/>
                                    </td>                   
                                    <td>
                                        <input 
                                            type="text"
                                            name="muestra_2"
                                            id="muestra_2"
                                            class="form-control"/> 
                                    </td>
                                </tr>
                            </tbody>                                  
                        </table>        
                    </div>
                </div>
            </fieldset>  
        </form>
<?php
        if (isset($_POST['generar_ordenBarra'])) {
            $fecha = $_POST['fecha'];
            $u_id = $_SESSION['u_id'];
            $muestra_1 = $_POST['muestra_1']; 
            $muestra_2 = $_POST['muestra_2'];
            $listaMuestras = array($muestra_1, $muestra_2);
            
            $metodosSeleccionados = obtenerMetodosSeleccionados($metodos);

            if (!empty($metodosSeleccionados) && 
                (!empty($listaMuestras[0]) || !empty($listaMuestras[1]))) {
            
                $trn_id = obtenerMaxTrnIdDeArgOrdenes($mysqli) + 1;
                $folio_orden = obtenerMaxFolioDeArgOrdenes($mysqli, $unidad_id) + 1;
                
                $mysqli->query("INSERT INTO arg_ordenes (trn_id, trn_id_rel, folio, hora, fecha_inicio, fecha_final, unidad_id, usuario_id, tipo, activo, comentario)
                                VALUES ($trn_id, 0, $folio_orden, '07:00', '$fecha', '', $unidad_id, $u_id, 3, 1, '')")or die(mysqli_error($mysqli));

                $tr_id_det = obtenerMaxTrnIdDeArgOrdenesDetalle($mysqli) + 1;
                $folio_det = obtenerMaxFolioDeArgOrdenesDetalle($mysqli, $unidad_id) + 1;

                $length = 6;                            
                $string_c = (string)$folio_det;
                $cons_c = str_pad($string_c,$length,"0", STR_PAD_LEFT);
                
                $datos_mina = ObtenerArgEmprUnidadesPor($mysqli, $unidad_id);
                $folio_interno = $datos_mina['serie'].$cons_c."-STK";

                $total_muest = count(array_filter($listaMuestras, function($valor) {
                    return !empty($valor);
                }));

                $mysqli->query("INSERT INTO arg_ordenes_detalle (trn_id, trn_id_rel, banco_id, voladura_id, cantidad, folio_inicial, folio_final, folio, folio_interno, estado, usuario_id)
                                VALUES ($tr_id_det, $trn_id, 0, 0, $total_muest, '','',$folio_det , '$folio_interno', 0, $u_id)") or die(mysqli_error($mysqli));

                $trn_id_met = obtenerMaxTrnIdDeArgOrdenesMetodos($mysqli) + 1;
                $max_trn_id_mues = obtenerMaxTrnIdDeArgOrdenesMuestrasMetalurgia($mysqli) + 1; 
                
                $i = 1;
                foreach ($listaMuestras as $muestra) {
                    if(!empty($muestra)) {
                        $mysqli->query("INSERT INTO arg_ordenes_muestrasMetalurgia (trn_id, trn_id_rel, folio, tipo_id, trnid_rel_mdi)
                                    VALUES ($max_trn_id_mues, $tr_id_det, '$muestra', 0, 0)");  
                                                    
                        foreach ($metodosSeleccionados as $metodo) {
                            $metodo_id = $metodo['metodo_id'];

                            if ($i == 1) {
                                $mysqli->query("INSERT INTO arg_ordenes_metodos (trn_id, trn_id_rel, metodo_id )
                                                VALUES ($trn_id_met, $tr_id_det, $metodo_id)") or die(mysqli_error($mysqli));
                                        
                                if ($metodo_id == "30") {
                                    $mysqli->query("INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id) 
                                                    VALUES ($tr_id_det, $metodo_id, 21, $u_id )") or die(mysqli_error($mysqli));
                                                                                                    
                                    $mysqli->query("INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin) 
                                                    VALUES ($tr_id_det, $metodo_id, 21, 28, $u_id, '', $u_id )") or die(mysqli_error($mysqli));
                                    
                                }

                                if ($metodo_id == "3") {
                                    $mysqli->query("INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id) 
                                                    VALUES ($tr_id_det, $metodo_id, 1, $u_id )") or die(mysqli_error($mysqli));
                                }

                                if ($metodo_id == "6") {
                                    $mysqli->query("INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id) 
                                                    VALUES ($tr_id_det, $metodo_id, 1, $u_id )") or die(mysqli_error($mysqli));
                                }

                                $trn_id_met++;
                            }

                            $mysqli->query("INSERT INTO arg_ordenes_transquebradora (trn_id_batch, bloque, pos_geo, trn_id_rel, trn_id_dup, metodo_id, tipo_id, material_id, posicion, u_id, folio_interno, ricos)
                                            VALUES ($tr_id_det, $i, $i, $max_trn_id_mues, 0, $metodo_id, 0, 0, $i, $u_id, '$muestra', NULL)") or die(mysqli_error($mysqli));
                        }

                        $max_trn_id_mues++;
                        $i++;
                    }
                }
            }

            if (isset($trn_id) && $trn_id <> 0) {
                echo "<script> imprimir(".$unidad_id.", ".$trn_id.")</script>"; 
            }
            else {
                echo "<script> alert('No se seleccionó ningún método o no se ingresó ninguna muestra. Reintente por favor'); </script>";
            }
        }
    }

    function obtenerMetodosSeleccionados(array $metodos): array {
        $metodosSeleccionados = [];
        // var_dump("Metodos recibidos:", $metodos);
        foreach ($metodos as $metodo) {
            $metodo_id = $metodo['metodo_id'];
            if (isset($_POST['metodo_'.$metodo_id]) && $_POST['metodo_'.$metodo_id] === 'on') {
                    $metodosSeleccionados[] = $metodo;
            }
        }
        return $metodosSeleccionados;
    }

    function ObtenerArgEmprUnidadesPor(mysqli $mysqli, int $unidad_id): array {
        $result = $mysqli->query(
            "SELECT caracter_folio, nombre, serie
            FROM arg_empr_unidades
            WHERE unidad_id = $unidad_id"
        );

        if ($result === false) {
            throw new RuntimeException($mysqli->error);
        }

        $row = $result->fetch_assoc();
        $result->free();

        return $row ?? [];
    }

    function obtenerMaxTrnIdDeArgOrdenes(mysqli $mysqli): int {
        $result = $mysqli->query("SELECT IFNULL(MAX(trn_id), 0) AS trn_id FROM arg_ordenes");

        if ($result === false) {
            throw new RuntimeException('Error al obtener el máximo trn_id: ' . $mysqli->error);
        }

        $row = $result->fetch_assoc();
        $result->free();

        return (int) $row['trn_id'];
    }

    function obtenerMaxTrnIdDeArgOrdenesMetodos(mysqli $mysqli): int {
        $result = $mysqli->query("SELECT IFNULL(MAX(trn_id), 0) AS trn_id FROM arg_ordenes_metodos");

        if ($result === false) {
            throw new RuntimeException('Error al obtener el máximo trn_id: ' . $mysqli->error);
        }

        $row = $result->fetch_assoc();
        $result->free();

        return (int) $row['trn_id'];
    }

    function obtenerMaxTrnIdDeArgOrdenesMuestrasMetalurgia(mysqli $mysqli): int {
        $result = $mysqli->query("SELECT IFNULL(MAX(trn_id), 0) AS trn_id FROM arg_ordenes_muestrasMetalurgia");

        if ($result === false) {
            throw new RuntimeException('Error al obtener el máximo trn_id: ' . $mysqli->error);
        }

        $row = $result->fetch_assoc();
        $result->free();

        return (int) $row['trn_id'];
    }

    function obtenerMaxTrnIdDeArgOrdenesDetalle(mysqli $mysqli): int {
        $result = $mysqli->query("SELECT MAX(trn_id) AS trn_id FROM arg_ordenes_detalle");

        if ($result === false) {
            throw new RuntimeException('Error al obtener el máximo trn_id: ' . $mysqli->error);
        }

        $row = $result->fetch_assoc();
        $result->free();

        return (int) $row['trn_id'];
    }

    function obtenerMaxFolioDeArgOrdenes(mysqli $mysqli, int $unidad_id): int {
        $stmt = $mysqli->prepare("SELECT IFNULL(MAX(folio), 0) AS folio FROM arg_ordenes WHERE unidad_id = ?");

        if ($stmt === false) {
            throw new RuntimeException('Error al preparar la consulta: ' . $mysqli->error);
        }

        $stmt->bind_param('i', $unidad_id);

        if (!$stmt->execute()) {
            throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();

        return (int) $row['folio'];
    }

    function obtenerMaxFolioDeArgOrdenesDetalle(mysqli $mysqli, int $unidad_id): int {
        $stmt = $mysqli->prepare("SELECT  IFNULL(MAX(od.folio), 0) AS folio_ord
                                                FROM arg_ordenes_detalle od
                                                LEFT JOIN arg_ordenes AS o ON od.trn_id_rel = o.trn_id
                                                WHERE o.unidad_id = ?");

        if ($stmt === false) {
            throw new RuntimeException('Error al preparar la consulta: ' . $mysqli->error);
        }

        $stmt->bind_param('i', $unidad_id);

        if (!$stmt->execute()) {
            throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();

        return (int) $row['folio_ord'];
    }
?>
<script type="text/javascript" src="js/jquery.min.js"></script>