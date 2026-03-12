<?php
    $unidad_id = $_GET['unidad_id'];
    $area_muestras = $_GET['area_id'] ?? "";
    $ricos = $_GET['r_id'] ?? null;
    $fecha = $_GET['fecha'] ?? '';
    $turno = $_GET['turno'] ?? null;
    $_SESSION['unidad_id'] = $unidad_id;

if ($fecha == ''){    
    $fecha = date('Y-m-d');
    $fecha_eti = str_replace('-', '', $fecha);
}
else{
    $fecha_eti = str_replace('-', '', $fecha);
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
                        
     ?>                             
         <form method="post" action="app_barr_dgo.php?unidad_id=<?php echo $unidad_id."&area_id=".$area_muestras;?>" name="Visitaform" id="Visitaform">  
         <fieldset>                       
            <div class="col-md-12 col-lg-12 bg-info text-black text-center">
                <br />
                <h4>ORDEN DE TRABAJO</h4>
            </div>
            <br/><br/><br/> <br/>
            <div class="container">                                                                                                                                                 
                <div class="col-md-11 col-lg-11">
                    <div class="col-md-3 col-lg-3">  
                        <?php     
                        $area_muestras = $_GET['area_id'] ?? "";  
                        if ($area_muestras == ""){
                            $nombretop = "Seleccione Tipo de Orden";
                            $area_id = 0;
                        }
                        else{
                            switch ($area_muestras){
                                case 1: $nombretop = 'Quebradora';
                                break;
                                case 2: $nombretop = 'Metaluriga Mineral';
                                break;
                                case 3: $nombretop = 'Carbones';
                                break;                                
                                case 4: $nombretop = 'Precipitados';
                                break;                                
                                case 5: $nombretop = 'Barras';
                                break;
                                case 6: $nombretop = 'Escorias';
                                break;
                            } 
                            switch ($area_muestras){
                                case 1: $area_id = 1;
                                break;
                                case 2: $area_id = 2;
                                break;
                                case 3: $area_id = 3;
                                break;                                
                                case 4: $area_id = 4;
                                break;                                
                                case 5: $area_id = 5;
                                break;
                                case 6: $area_id = 6;
                                break;
                            } 
                            switch ($turno){
                                case '07:00': $turnotop = '07:00';
                                break;
                                case '19:00': $turnotop = '19:00';
                                break;
                            } 
                            switch ($turno){
                                case '07:00': $turnonombre = '1T';
                                break;
                                case '19:00': $turnonombre = '2T';
                                break;
                            }    
                                                 
                        } 
                                                         
                        echo ("<form name=\"area\" id=\"area\">");                                   
                        echo ("<select name=\"area_id\" id=\"area_id\" onchange=\"cargar_muestras($unidad_id)\" class=\"form-control\" > ");        
                        echo ("<option value=$area_id>$nombretop</option>");
                        $result = $mysqli->query("SELECT 1 as tipo_orden, 'Quebradora' as nombre_orden
                                                                    UNION ALL
                                                                  SELECT 2 as tipo_orden, 'Metalurgia Mineral' as nombre_orden
                                                                    UNION ALL
                                                                  SELECT 3 as tipo_orden, 'Carbones' as nombre_orden
                                                                    UNION ALL
                                                                  SELECT 4 as tipo_orden, 'Precipitados' as nombre_orden
                                                                    UNION ALL
                                                                  SELECT 5 as tipo_orden, 'Barras' as nombre_orden
                                                                    UNION ALL
                                                                  SELECT 6 as tipo_orden, 'Escorias' as nombre_orden                                                                  
                                                                  ") or die(mysqli_error($mysqli));
                                    while( $row = $result ->fetch_array(MYSQLI_ASSOC))                                      
                                        {
                                            $nombre =($row["nombre_orden"]);
                                            $nomenclatura = $row["tipo_orden"];                                          
                                            echo ("<option value=$nomenclatura>$nombre</option>");
                                        }          
                                    echo ("</select>");                           
                    ?> 
                    </div>
                                                    
                    <div class="col-md-1 col-lg-1">               
                        <h5><?php echo 'Fecha:'?></h5>
                    </div>
                    
                    <div class="col-md-2 col-lg-2">
                        <input type="date" name="fecha" class="form-control" id="fecha" onchange="cargar_muestras(<?php echo $unidad_id;?>)" value="<?php echo $fecha;?>"/>
                    </div>                                
                    
                    <div class="col-md-2 col-lg-2">                                                                                                         
                    <?php    
                        if ($turno == ''){
                            $turnotop = '07:00';
                            $turnonombre = '1T';
                        }                       
                        echo ("<form name=\"turno\" id=\"turno\">");                                   
                        echo ("<select name=\"turno\" id=\"turno\" onchange=\"cargar_muestras($unidad_id)\"  class=\"form-control\" > ");        
                        echo ("<option value=$turnotop>$turnonombre</option>");
                        
                        $result = $mysqli->query("SELECT '07:00' AS turno, '1T' AS etiqueta 
                                                  UNION ALL 
                                                  SELECT '19:00' AS turno, '2T' AS etiqueta"
                                                  ) or die(mysqli_error($mysqli));
                            while( $row = $result ->fetch_array(MYSQLI_ASSOC))                                      
                            {
                                $nombre =($row["etiqueta"]);
                                $nomenclatura = $row["turno"];                                          
                                echo ("<option value=$nomenclatura>$nombre</option>");
                            }          
                        echo ("</select>");                          
                    ?>                                     
                    </div> 
                     
                    <div class="col-md-2 col-lg-2">                                
                    <?php                            
                        $unidad_id = $_GET['unidad_id'];
                        if ($unidad_id == ""){
                            $nombretop = "Seleccione Mina";
                        }
                        else{
                            $nomtop = $unidad_id;
                            $result = $mysqli->query("SELECT unidad_id, Nombre FROM arg_empr_unidades WHERE unidad_id = ".$unidad_id) or die(mysqli_error($mysqli));
                                while( $row = $result ->fetch_array(MYSQLI_ASSOC)){
                                    $nombretop = $row['Nombre']; 
                                }
                            }                                  
                            echo ("<form name=\"Busqueda\" id=\"Busqueda\">");                                   
                            echo ("<select name=\"mina_seleccionada\" id=\"mina_seleccionada\" disabled class=\"form-control\" > ");        
                            echo ("<option value=$nomtop>$nombretop</option>");
                            $result = $mysqli->query("SELECT unidad_id, Nombre FROM arg_empr_unidades") or die(mysqli_error($mysqli));
                            while( $row = $result ->fetch_array(MYSQLI_ASSOC))                                      
                                {
                                    $nombre =($row["Nombre"]);
                                    $nomenclatura = $row["unidad_id"];                                          
                                    echo ("<option value=$nomenclatura>$nombre</option>");
                                }          
                            echo ("</select>");                           
                        ?>
                        </div>
                    </div>
                            
                    <br /><br /><br />
                    <div class="col-md-10 col-lg-10">
                        <table class="table table-hover text-black" id="tablaprueba">
                                  <thead class="thead-light" align='center'>
                                    <tr>
                                      <th colspan='4'>METODOS</th>
                                      <div class="row">  
                                           <div class="col-md-10 col-lg-10">                      
                                      </div>
                                      <div class="col-md-1 col-lg-1"> 
                                            <input type="submit" class="btn btn-success" name="generar_ordenBarra" id="generar_ordenBarra" data-toggle="modal" data-target="#exampleModal" value="GUARDAR ORDEN" />                      
                                      </div>    
                                    </tr>
                                    
                                    <tr>
                                       <th colspan='8'>
                                              <?php if ($area_id == 1) {$datos_res = $mysqli->query("SELECT 
                                                                                    metodo_id, nombre 
                                                                              FROM 
                                                                                    arg_metodos 
                                                                              WHERE 
                                                                                    metodo_id IN(29, 30, 5, 27, 33)
                                                                              ORDER BY 
                                                                                    nombre") or die(mysqli_error($mysqli));
                                                 }
                                                 elseif ($area_id == 2) {$datos_res = $mysqli->query("SELECT 
                                                                                    metodo_id, nombre 
                                                                              FROM 
                                                                                    arg_metodos 
                                                                              WHERE 
                                                                                    metodo_id IN(27, 33)
                                                                              ORDER BY 
                                                                                    nombre") or die(mysqli_error($mysqli));
                                                 }
                                                 elseif ($area_id == 3) {$datos_res = $mysqli->query("SELECT 
                                                                                    metodo_id, nombre 
                                                                              FROM 
                                                                                    arg_metodos 
                                                                              WHERE 
                                                                                    metodo_id IN(2, 9, 30, 35)
                                                                              ORDER BY 
                                                                                    nombre") or die(mysqli_error($mysqli));
                                                 }
                                                 elseif ($area_id == 4) {$datos_res = $mysqli->query("SELECT 
                                                                                    metodo_id, nombre 
                                                                              FROM 
                                                                                    arg_metodos 
                                                                              WHERE 
                                                                                    metodo_id IN(2, 28, 30)
                                                                              ORDER BY 
                                                                                    nombre") or die(mysqli_error($mysqli));
                                                 }
                                                 elseif ($area_id == 5) {$datos_res = $mysqli->query("SELECT 
                                                                                    metodo_id, nombre 
                                                                              FROM 
                                                                                    arg_metodos 
                                                                              WHERE 
                                                                                    metodo_id IN(2, 28)
                                                                              ORDER BY 
                                                                                    nombre") or die(mysqli_error($mysqli));
                                                 }
                                                 elseif ($area_id == 6) {$datos_res = $mysqli->query("SELECT 
                                                                                    metodo_id, nombre 
                                                                              FROM 
                                                                                    arg_metodos 
                                                                              WHERE 
                                                                                    metodo_id IN(28, 30, 31)
                                                                              ORDER BY 
                                                                                    nombre") or die(mysqli_error($mysqli));
                                                 }
                                                
                                                 ?>
                                                 <div class="[ form-group ] ">   
                                                    <?php  
                                                        if(isset($datos_res)){
                                                            while ($fila = $datos_res->fetch_assoc()) {?>
                                                                    <input type="checkbox" name="<?php echo 'fila2_'.$fila['metodo_id']?>" id="<?php echo 'fila2_'.$fila['metodo_id']?>" autocomplete="off" />
                                                                    <div class="[ btn-group ]">                                                                
                                                                        <label for="<?php echo 'fila2_'.$fila['metodo_id']?>" class="[ btn btn-info ]">
                                                                            <span class="[ glyphicon glyphicon-ok ]"></span>                            
                                                                            <span></span>
                                                                        </label>                                                    
                                                                        <label for="<?php echo 'fila2_'.$fila['metodo_id']?>" class="[ btn btn-info active ]">
                                                                            <?php echo $fila['nombre']?>
                                                                        </label>                              
                                                                    </div>                                            
                                                        <?php }
                                                        }?>                                        
                                                 </div>   
                                         </th>
                                    </tr>
                                    <?php if ($area_muestras == 1){ ?> 
                                            <th colspan='1'>No.</th>
                                            <th colspan='1'>MUESTRA
                                            <?php                                      
                                             $result = $mysqli->query("SELECT 
                                                                            CONCAT(serie, 'QB-') AS folio 
                                                                       FROM
                                                                            `arg_empr_unidades` 
                                                                       WHERE unidad_id = ".$unidad_id
                                                                ) or die(mysqli_error($mysqli));
                                             $i = 3;                                       
                                             $muestra = 'muestra'.$i;
                                             $posicion_nombre = 'p'.$muestra;
                                             $muestra_sol = $row['folio'];
                                             $muestra_trn = $row['trn_id']; 
                                             echo ("<td>"); 
                                                echo ("<form name=\"muestra\" id=\"muestra\">");                               
                                                echo ("<select name=\"sig_muestra1\" id=\"sig_muestra1\" onchange=\"calculatotal()\" class=\"form-control\" > ");        
                                                     echo ("<option value=0>Seleccione Muestra</option>");
                                                     while( $row = $result ->fetch_array(MYSQLI_ASSOC))                                       
                                                     {  
                                                            $muestra_trn = $row['trn_id'];
                                                            $muestra_folio_e = $row['folio_envia'];                                                        
                                                            $muestra_folio = $row['folio'].$fecha_eti.'-'.$turnonombre;                                                                                                        
                                                            echo ("<option value=$muestra_folio>$muestra_folio</option>");                                                        
                                                     }
                                                    echo ("</select>"); 
                                                    echo ("</td>");
                                                   
                                            ?> 
                                             <table>
                                  </table>
                                <?php  
                                }
                                ?> 
                                <?php if ($area_muestras == 2){ ?> 
                                            <th colspan='1'>No.</th>
                                            <th colspan='1'>MUESTRA
                                            
                                            <?php                                       
                                             $i = 3;
                                             $fr = 1;
                                             $reng = 1;
                                             $max_renglones_c = 8;
                                            
                                             ?> 
                                                <div class="col-md-1 col-lg-1">
                                                
                                      <?php while ($fr <= $max_renglones_c){?>
                                         <tr>
                                            <td> <input type="text" name="id" id="sig1" disabled="" class="form-control" value="<?php echo $fr;?>" />  </td>  
                                                                                    
                                            <td> <input type="text" name="<?php echo 'sig_muestra'.$fr;?>" id="<?php echo 'sig_muestra'.$fr;?>" class="form-control" onchange="calculatotal(<?php echo $fr;?>);" /> </td>
                                        </tr>
                                     <?php $fr++;
                                     }?>
                                     </div>
                                             <table>
                                             </table>
                                <?php  
                                }
                                ?>
                                 <?php if ($area_muestras == 3){
                                 ?> 
                                            <th colspan='1'>No.</th>
                                            <th colspan='1'>MUESTRA</th>
                                                                            
                                           <div class="col-md-6 col-lg-6">                                                                                                         
                                           <?php  $max_renglones = 8;
                                                $fr = 1;
                                                
                                            if ($ricos == null){
                                                $ricos = 0;
                                                $rictop = 'Ricos';
                                            }     
                                            else{
                                                switch ($ricos){
                                                    case 0: $rictop = 'Ricos';
                                                    break;
                                                    case 1: $rictop = 'Pobres';
                                                    break;
                                                }   
                                            }
                                           // echo 'qlijo'.$ricos;    
                                           ?>               
                                                <label for="ricospobres"><b>CARBONES RICOS/POBRES:</b></label>
                                                <?php                                                                  
                                                echo ("<form name=\"ricospobres\" id=\"ricospobres\">");                                    
                                                echo ("<select name=\"ricospobres\" id=\"ricospobres\"  onchange=\"cargar_rp_muestras($unidad_id)\"   class=\"form-control\" > ");    
                                                echo ("<option value=$ricos>$rictop</option>");
                                                $result = $mysqli->query("SELECT 0 AS ric_id, 'Ricos' AS etiqueta
                                                                          UNION ALL 
                                                                          SELECT 1 AS ric_id, 'Pobres' AS etiqueta"
                                                                          ) or die(mysqli_error($mysqli));
                                                while( $row = $result ->fetch_array(MYSQLI_ASSOC))                                      
                                                    {
                                                        $nom_id =($row["ric_id"]);
                                                        $nomenclatura = $row["etiqueta"];                                          
                                                        echo ("<option value=$nom_id>$nomenclatura</option>");
                                                    }          
                                                echo ("</select>");                          
                                           ?>       
                                           </div> 
                                           
                                     <div class="col-md-1 col-lg-1">
                                    
                                      <?php 
                                       $i = 1;  
                                       while ($fr <= $max_renglones){ ?>
                                         <tr>
                                            <td> <input type="text" name="id" id="sig1" disabled="" class="form-control" value="<?php echo $fr;?>" />  </td>  
                                            
                                            <?php           
                                             /*$result = $mysqli->query("SELECT trnid_muestra, folio_interno
                                                                        FROM `temp_muestrasInv` 
                                                                        WHERE ricos = ".$ricos."
                                                                        ORDER BY folio_interno
                                                                        AND trnid_muestra NOT IN (SELECT DISTINCT trnid_rel_mdi 
                                                                                                  FROM arg_ordenes_muestrasMetalurgia)"
                                                                ) or die(mysqli_error());*/
                                                                              
                                             $muestra = 'muestra'.$i;
                                             $posicion_nombre = 'p'.$muestra;
                                             ?>
                                                <td> <input type="text" name="<?php echo 'muestra'.$i;?>" id="<?php echo 'muestra'.$i;?>" class="form-control" onchange="calculatotal(<?php echo $i;?>);" /> </td>
                                             <?php
                                             /*echo ("<td>"); 
                                                echo ("<form name=\"muestra\" id=\"muestra\">");                               
                                                echo ("<select name=\"$muestra\" id=\"$muestra\" onchange=\"calculatotal($i)\" class=\"form-control\" > ");        
                                                     echo ("<option value=0>Seleccione Muestra</option>");
                                                     while( $row = $result ->fetch_array(MYSQLI_ASSOC))                                       
                                                     {  
                                                            $muestra_trn = $row['trnid_muestra'];                                                       
                                                            $muestra_folio = $row['folio_interno'];                                                                                                        
                                                            echo ("<option value=$muestra_trn>$muestra_folio</option>"); 
                                                                                                                  
                                                     }
                                                    echo ("</select>"); 
                                                    echo ("</td>");*/

                                                $i++;                                                   
                                            ?> 
                                        </tr>
                                     <?php $fr++;
                                     }?>
                                     </div>
                                     <table>
                                     </table>
                                <?php  
                                    }
                                ?>
                                <?php if ($area_muestras == 4){
                                     $max_renglones = 5;
                                     $fr = 1;
                                ?> 
                                     <th colspan='1'>No.</th>
                                     <th colspan='1'>MUESTRA</th>      
                                     <div class="col-md-1 col-lg-1">
                                      <?php while ($fr <= $max_renglones){?>
                                         <tr>
                                            <td> <input type="text" name="id" id="sig1" disabled="" class="form-control" value="<?php echo $fr;?>" />  </td>  
                                                                                    
                                            <td> <input type="text" name="<?php echo 'sig_muestra'.$fr;?>" id="<?php echo 'sig_muestra'.$fr;?>" class="form-control" onchange="calculatotal(<?php echo $fr;?>);" /> </td>
                                        </tr>
                                     <?php $fr++;
                                     }?>
                                     </div>
                                     <table>
                                     </table>
                                <?php  
                                }
                                if ($area_muestras == 5){
                                     $max_renglones = 6;
                                     $fr = 1;
                                ?> 
                                     <th colspan='1'>No.</th>
                                     <th colspan='1'>MUESTRA</th>      
                                     <div class="col-md-1 col-lg-1">
                                      <?php while ($fr <= $max_renglones){?>
                                         <tr>
                                            <td> <input type="text" name="id" id="sig1" disabled="" class="form-control" value="<?php echo $fr;?>" />  </td>  
                                                                                    
                                            <td> <input type="text" name="<?php echo 'sig_muestra'.$fr;?>" id="<?php echo 'sig_muestra'.$fr;?>" class="form-control" onchange="calculatotal(<?php echo $fr;?>);" /> </td>
                                        </tr>
                                     <?php $fr++;
                                     }?>
                                     </div>
                                     <table>
                                     </table>
                                <?php  
                                }
                                ?>
                                <?php if ($area_muestras == 6){
                                     $max_renglones = 2;
                                     $fr = 1;
                                ?> 
                                   
                                            <th colspan='1'>No.</th>
                                            <th colspan='1'>MUESTRA</th>
                                                                            
                                           <div class="col-md-6 col-lg-6">                                                                                                         
                                           <?php  
                                                                        
                                           ?>       
                                           
                                           </div> 
                                           
                                     <div class="col-md-1 col-lg-1">
                                      <?php while ($fr <= $max_renglones){?>
                                         <tr>
                                            <td> <input type="text" name="id" id="sig1" disabled="" class="form-control" value="<?php echo $fr;?>" />  </td>  
                                                                                    
                                            <td> <input type="text" name="<?php echo 'sig_muestra'.$fr;?>" id="<?php echo 'sig_muestra'.$fr;?>" class="form-control" onchange="calculatotal(<?php echo $fr;?>);" /> </td>
                                        </tr>   
                                    
                                     <?php $fr++;
                                     }?>
                                     </div>
                                             <table>
                                            </table>
                                <?php   
                                }
                                ?>
                </tbody>                                  
                </table>
                                
                <table>
                <tbody>
                    <tr> 
                        <td style="width:10%"><strong>Total Muestras: </strong></td>    
                        <td style="width:1%"></td>                               
                        <td style="width:5%"><input type="input" name="total_muestras_1" id="total_muestras_1" disabled="1" value="" class="form-control" /></td> 
                        <input type="hidden" name="total_muestras1" id="total_muestras1" value="" class="form-control" />
                        <input type="hidden" name="total_muestras_lista" id="total_muestras_lista" value="<?php echo  $total_muestras;?>" class="form-control" />
                        <td style="width:20%"></td>     
                    </tr>                                    
                </tbody>                                      
                </table>
                              
            </div>
            </div>
            </div>
            <br/>
         </fieldset>  
         </form>
                
                <!-- Modal -->
         <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">GENERANDO ORDEN</h5> 
                      </div>
                      <div class="modal-body">
                        <div class="text-center">
                            <h4>Please wait...</h4>        
                            <img  src="images\upload.gif">          
                        </div>
                      </div>
                     
                    </div>
         </div>
         </div> 
       <?php 
       //Click en Generar Orden
       if (isset($_POST['generar_ordenBarra'])){
                     $caracter_mina  = $mysqli->query("SELECT caracter_folio, nombre, serie
                                                      FROM `arg_empr_unidades`                                        
                                                        WHERE unidad_id = ".$unidad_id) or die(mysqli_error($mysqli));
                     $caracter_fol   = $caracter_mina ->fetch_array(MYSQLI_ASSOC);
                     $caracter_folio = $caracter_fol['caracter_folio'];
                     $serie_mina     = $caracter_fol['serie'];
                     
                     $fecha             = $_POST['fecha'];
                     $hora              = $_POST['turno'];
                     $mina_seleccionada = $_POST['mina_seleccionada'];
                     $u_id              = $_SESSION['u_id'];
                     $total_muest       = $_POST['total_muestras1']; 
                     $total_muest_list  = $_POST['total_muestras_lista'];  
                     
                     echo $total_muest_list.'Total listado'.$total_muest;
                     if ($area_id == 1){
                        $tipo = 3;
                        $eti = '-QB';
                        $fin = 1;
                        echo 'entro a area quebrador';
                        $ricos_pobres = 'NULL';
                     }
                     if ($area_id == 2){
                        $tipo = 4;
                        $eti = '-MM';
                        $fin = $total_muest;
                        $ricos_pobres = 'NULL';
                     }
                     if ($area_id == 3){
                        $tipo = 7;
                        $eti = '-CB';
                        $fin = $total_muest;                        
                        $ricos_pobres = $_POST['ricospobres']; 
                     }
                     if ($area_id == 4){//Precipitados
                        $tipo = 8;
                        $eti = '-PR';
                        $fin = $total_muest;
                        $ricos_pobres = 'NULL';
                     }
                     if ($area_id == 5){//Barras
                        $tipo = 9;
                        $eti = '-BR';
                        $fin = $total_muest;
                        $ricos_pobres = 'NULL';
                     }
                     if ($area_id == 6){//Escorias
                        $tipo = 10;
                        $eti = '-ES';
                        $fin = $total_muest;
                        $ricos_pobres = 'NULL';
                     }
                     
                     $j = 1;
                     $pos = 1;
                     $cons_det = 1;                     
                     $crear = 1;          
                     //die();           
                                
                     //Métodos
                     $val_met = 0;
                     $metodos_validar = $mysqli->query("SELECT metodo_id FROM arg_metodos WHERE activo = 1 AND metodo_id IN(2, 5, 9, 27, 28, 29, 30, 31, 33,35) ") or die(mysqli_error($mysqli));
                     while ($metodos = $metodos_validar->fetch_assoc()) {
                        $metodo_id = $metodos['metodo_id'];
                        $fila1 = 'fila2_'.$metodo_id;
                        $metodo_sel = $_POST[$fila1];
                        if ($metodo_sel == 'on'){
                            $val_met = 1;
                        }
                     }
                                
                     if ($val_met == 0){                           
                        echo "<script>";
                        echo "verificar_seleccion(4)";
                        echo "</script>";
                        $i = 0;      
                     }     
                     else{
                        if ($crear == 1 & $val_met > 0 & $area_id <> 2){
                            $max_trn_id = $mysqli->query("SELECT ifnull(MAX(trn_id), 0) AS trn_id FROM arg_ordenes") or die(mysqli_error($mysqli));
                            $ma_trn_id = $max_trn_id ->fetch_array(MYSQLI_ASSOC);
                            $trn_id = $ma_trn_id['trn_id'];
                            $trn_id = $trn_id + 1;
                        
                            $max_fol = $mysqli->query("SELECT IFNULL(MAX(folio), 0) AS folio FROM arg_ordenes WHERE unidad_id = ".$unidad_id) or die(mysqli_error($mysqli));
                            $max_foli = $max_fol ->fetch_array(MYSQLI_ASSOC);
                            $max_folio = $max_foli['folio'];
                            $folio_orden = $max_folio + 1;
                                         //echo 'fechaayer:'.$fecha;
                                         //Ordenes
                            $query = "INSERT INTO arg_ordenes (trn_id, trn_id_rel, folio, hora, fecha, fecha_inicio, fecha_final, unidad_id, usuario_id, tipo, activo, comentario ) ".
                                    "VALUES ($trn_id, 0, $folio_orden, '$hora', '$fecha', '', '', $unidad_id, $u_id, $tipo, 1, '')";
                            $mysqli->query($query) ;
                           // echo $query;
                            //Ordenes_detalle
                            $max_trn_det = $mysqli->query("SELECT MAX(trn_id) AS trn_id FROM arg_ordenes_detalle") or die(mysqli_error($mysqli));
                            $max_trn = $max_trn_det ->fetch_array(MYSQLI_ASSOC);
                            $tr_id_det = $max_trn['trn_id'];
                            $tr_id_det = $tr_id_det + 1;
                                                     
                            $max_folio_det = $mysqli->query("SELECT  IFNULL(MAX(od.folio), 0) AS folio_ord 
                                                                            FROM arg_ordenes_detalle od
                                                                            LEFT JOIN arg_ordenes AS o
                                                                                    ON od.trn_id_rel = o.trn_id
                                                                            WHERE
                                                                                    o.unidad_id = ".$unidad_id) or die(mysqli_error($mysqli));
                            $max_fol = $max_folio_det ->fetch_array(MYSQLI_ASSOC);
                            $folio_det = $max_fol['folio_ord'];
                            $folio_det = $folio_det + 1;
                                                     
                            $length = 6;                            
                            $string_c = (string)$folio_det;
                            $cons_c = str_pad($string_c,$length,"0", STR_PAD_LEFT);
                                                        
                            $length_fs = 3;
                            $folio_interno = $serie_mina.$cons_c.$eti;
                                                     
                            $query = "INSERT INTO arg_ordenes_detalle (trn_id, trn_id_rel, banco_id, voladura_id, cantidad, folio_inicial, folio_final, folio, folio_interno, estado, usuario_id) ".
                                    "VALUES ($tr_id_det, $trn_id, 0, 0, $total_muest, '','',$folio_det , '$folio_interno', 1, $u_id)";
                            $mysqli->query($query) ;
                            //echo $query;
                            $crear = $crear++;
                    
                                     
                            //MUESTRAS METODOS   
                            $max_trn_id_met = $mysqli->query("SELECT IFNULL(MAX(trn_id), 0) AS trn_id FROM arg_ordenes_metodos ") or die(mysqli_error($mysqli));
                            $ma_trn_id_m = $max_trn_id_met ->fetch_array(MYSQLI_ASSOC);
                            $trn_id_met = $ma_trn_id_m['trn_id'];
                            $trn_id_met = $trn_id_met+1;   
                            $unic = 1;
                            
                            $max_trn_id_mu = $mysqli->query("SELECT IFNULL(MAX(trn_id), 0) AS trn_id FROM arg_ordenes_muestrasMetalurgia ") or die(mysqli_error($mysqli));
                                        $max_trn_id_mue = $max_trn_id_mu ->fetch_array(MYSQLI_ASSOC);
                                        $max_trn_id_mues = $max_trn_id_mue['trn_id'];
                                        $max_trn_id_mues = $max_trn_id_mues+1; 
                            
                        //Insertando en MuestrasMetalurgia
                        $muestras_cantidad = 1;
                        $i = 1;
                        
                        while ($muestras_cantidad <= $fin){ 
                            $muestra_sel = "muestra".$i;                                                                    
                           // $muestra_trnmdi = $_POST[$muestra_sel]; pARA LEER MUESTRAS DESDE MINEDATA-INVS
                           $muestra_sln = $_POST[$muestra_sel];
                        
                            //echo 'muestrasel: '.$muestra_sln;

                            /*$trnid_mdi = $mysqli->query("SELECT folio_interno FROM temp_muestrasInv WHERE trnid_muestra = $muestra_trnmdi") or die(mysqli_error());
                            $trnid_mdinvs = $trnid_mdi ->fetch_array(MYSQLI_ASSOC);
                            $muestra_sln = $trnid_mdinvs['folio_interno'];*/

                            $query = "INSERT INTO arg_ordenes_muestrasMetalurgia (trn_id, trn_id_rel, folio, tipo_id, trnid_rel_mdi) ".
                                 //   "VALUES ($max_trn_id_mues, $tr_id_det, '$muestra_sln', 0, $muestra_trnmdi)"; 
                                      "VALUES ($max_trn_id_mues, $tr_id_det, '$muestra_sln', 0, 0)";                                                    
                            $mysqli->query($query);  
                            //echo $query.'</br>'; 
                                                    
                            $metodos_validar = $mysqli->query("SELECT metodo_id FROM arg_metodos WHERE metodo_id IN(2, 5, 9, 27, 28, 29, 30, 31, 33, 35)") or die(mysqli_error($mysqli));
                            while ($metodos = $metodos_validar->fetch_assoc()) {
                                
                                $metodo_id = $metodos['metodo_id'];
                                $fila1 = 'fila2_'.$metodo_id;
                                $metodo_sel = $_POST[$fila1];
                               // echo $metodo_sel;
                               // echo 'fila: '.$fila1;
                                
                                if ($metodo_sel == 'on'){
                                  //  echo 'entro metodOOO'.$metodo_id;
                                    if ($i == 1){
                                        $posicion = 1;
                                        $query = "INSERT INTO arg_ordenes_metodos (trn_id, trn_id_rel, metodo_id ) ".
                                                 "VALUES ($trn_id_met, $tr_id_det, $metodo_id)";
                                        $mysqli->query($query) ;
                                        echo $query.'</br>';
                                                
                                         if($metodo_id == 33 && $area_id <> 2){//CNAu, Ag, Cu
                                            $query = "INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id)
                                                      VALUES ($tr_id_det, $metodo_id, 7, $u_id )";
                                                      $mysqli->query($query) ;
                                                      $query = "INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin)
                                                                VALUES ($tr_id_det, $metodo_id, 7, 5, $u_id, '', $u_id )";                                                    
                                                      $mysqli->query($query);
                                        }
                                        elseif ($metodo_id == 27 && $area_id <> 2){//AU_LibreAA
                                            $query = "INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id)
                                                      VALUES ($tr_id_det, $metodo_id, 9, $u_id )";
                                            $mysqli->query($query);
                                            
                                            $query = "INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin)
                                                      VALUES ($tr_id_det, $metodo_id, 9, 5, $u_id, '', $u_id )";                                                    
                                            $mysqli->query($query);
                                        }
                                        elseif ($metodo_id == 30){//%Hum
                                            $query = "INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id)
                                                      VALUES ($tr_id_det, $metodo_id, 21, $u_id )";
                                            $mysqli->query($query);
                                            
                                            $query = "INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin)
                                                      VALUES ($tr_id_det, $metodo_id, 21, 28, $u_id, '', $u_id )";                                                    
                                            $mysqli->query($query);
                                        }
                                        elseif($metodo_id == 29){//Densidad
                                            $query = "INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id)
                                                      VALUES ($tr_id_det, $metodo_id, 20, $u_id )";
                                            $mysqli->query($query);
                                                                        
                                            $query = "INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin)
                                                      VALUES ($tr_id_det, $metodo_id, 20, 5, $u_id, '', $u_id )";                                                    
                                            $mysqli->query($query);
                                        }
                                        elseif($metodo_id == 5){//Gravimetria 
                                            $query = "INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id)
                                                      VALUES ($tr_id_det, $metodo_id, 20, $u_id )";
                                            $mysqli->query($query);
                                            
                                            $query = "INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin)
                                                      VALUES ($tr_id_det, $metodo_id, 20, 5, $u_id, '', $u_id )";                                                    
                                            $mysqli->query($query);                                                                
                                        }
                                        elseif($metodo_id == 9){//%ACT
                                            $query = "INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id)
                                                      VALUES ($tr_id_det, $metodo_id, 23, $u_id )";
                                            $mysqli->query($query);
                                            
                                            $query = "INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin)
                                                      VALUES ($tr_id_det, $metodo_id, 23, 5, $u_id, '', $u_id )";                                                    
                                            $mysqli->query($query);                                                                
                                        }
                                        elseif($metodo_id == 2 && $area_id <> 2){//EF-Grav2
                                            $query = "INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id)
                                                      VALUES ($tr_id_det, $metodo_id, 11, $u_id )";
                                            $mysqli->query($query);
                                            
                                            $query = "INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin)
                                                      VALUES ($tr_id_det, $metodo_id, 11, 5, $u_id, '', $u_id )";                                                    
                                            $mysqli->query($query);   
                                            // Para que inicie en posicion 3 la orden de carbones para el metodo 2
                                            $car_i = $i+2;
                                        }
                                        elseif($metodo_id == 28){ //Impureas
                                            $query = "INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id)
                                                      VALUES ($tr_id_det, $metodo_id, 6, $u_id )";
                                            $mysqli->query($query);
                                            
                                            $query = "INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin)
                                                      VALUES ($tr_id_det, $metodo_id, 6, 5, $u_id, '', $u_id )";                                                    
                                            $mysqli->query($query);                                                                
                                        }
                                        elseif($metodo_id == 31){ //Impureas
                                            $query = "INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id)
                                                      VALUES ($tr_id_det, $metodo_id, 18, $u_id )";
                                            $mysqli->query($query);
                                            
                                            $query = "INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin)
                                                      VALUES ($tr_id_det, $metodo_id, 18, 5, $u_id, '', $u_id )";                                                    
                                            $mysqli->query($query);                                                                
                                        }
                                        elseif($metodo_id == 35){//CB_DIGAg
                                            $query = "INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id)
                                                      VALUES ($tr_id_det, $metodo_id, 24, $u_id )";
                                            $mysqli->query($query);
                                            
                                            $query = "INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin)
                                                      VALUES ($tr_id_det, $metodo_id, 24, 5, $u_id, '', $u_id )";                                                    
                                            $mysqli->query($query);                                                                
                                        }
                                       // echo $query;
                                        $trn_id_met++;
                                    }

                                    if($metodo_id == 2 && $area_id <> 2){//EF-Grav2     
                                        $car_i = $i+2;                                   
                                        $query = "INSERT INTO arg_ordenes_transquebradora (trn_id_batch, bloque, pos_geo, trn_id_rel, trn_id_dup, metodo_id, tipo_id, material_id, posicion, u_id, folio_interno, ricos) ".
                                                "VALUES ($tr_id_det, $car_i, $car_i, $max_trn_id_mues, 0, $metodo_id, 0, 0, $car_i, $u_id, '$muestra_sln', $ricos_pobres)";                                                    
                                        $mysqli->query($query);
                                        //echo $query.'</br>';
                                        
                                        $posicion++;
                                    }
                                    else{
                                                
                                        $query = "INSERT INTO arg_ordenes_transquebradora (trn_id_batch, bloque, pos_geo, trn_id_rel, trn_id_dup, metodo_id, tipo_id, material_id, posicion, u_id, folio_interno, ricos) ".
                                        "VALUES ($tr_id_det, $i, $i, $max_trn_id_mues, 0, $metodo_id, 0, 0, $i, $u_id, '$muestra_sln', $ricos_pobres)";                                                    
                                        $mysqli->query($query);
                                      //  echo $query.'</br>';
                                
                                        $posicion++;
                                    }

                                }        
                         } 
                         $muestras_cantidad = $muestras_cantidad+1;
                         $max_trn_id_mues = $max_trn_id_mues+1;
                         $i++;                                                            
                              
                            }  
                        }
                     //}
                     
                     elseif ($area_id == 2){
                        $metodos_validar = $mysqli->query("SELECT metodo_id FROM arg_metodos WHERE metodo_id IN( 27, 33)") or die(mysqli_error( $mysqli));
                        while ($metodos = $metodos_validar->fetch_assoc()) {
                            
                            $metodo_id = $metodos['metodo_id'];
                            $fila1 = 'fila2_'.$metodo_id;
                            $metodo_sel = $_POST[$fila1];
                            echo $metodo_sel;
                            echo 'fila: '.$fila1;
                            
                            if ($metodo_sel == 'on'){
                                echo 'entro metodaparteE'.$metodo_id;
                                
                                $max_trn_id = $mysqli->query("SELECT ifnull(MAX(trn_id), 0) AS trn_id FROM arg_ordenes") or die(mysqli_error($mysqli));
                                $ma_trn_id = $max_trn_id ->fetch_array(MYSQLI_ASSOC);
                                $trn_id = $ma_trn_id['trn_id'];
                                $trn_id = $trn_id + 1;
                        
                                $max_fol = $mysqli->query("SELECT IFNULL(MAX(folio), 0) AS folio FROM arg_ordenes WHERE unidad_id = ".$unidad_id) or die(mysqli_error($mysqli));
                                $max_foli = $max_fol ->fetch_array(MYSQLI_ASSOC);
                                $max_folio = $max_foli['folio'];
                                $folio_orden = $max_folio + 1;
                                         //echo 'fechaayer:'.$fecha;
                                         //Ordenes
                                $query = "INSERT INTO arg_ordenes (trn_id, trn_id_rel, folio, hora, fecha, fecha_inicio, fecha_final, unidad_id, usuario_id, tipo, activo, comentario ) ".
                                          "VALUES ($trn_id, 0, $folio_orden, '$hora', '$fecha', '', '', $unidad_id, $u_id, $tipo, 1, '')";
                                $mysqli->query($query) ;
                                //echo $query;
                                //Ordenes_detalle
                                $max_trn_det = $mysqli->query("SELECT MAX(trn_id) AS trn_id FROM arg_ordenes_detalle") or die(mysqli_error($mysqli));
                                $max_trn = $max_trn_det ->fetch_array(MYSQLI_ASSOC);
                                $tr_id_det = $max_trn['trn_id'];
                                $tr_id_det = $tr_id_det + 1;
                                                             
                                $max_folio_det = $mysqli->query("SELECT  IFNULL(MAX(od.folio), 0) AS folio_ord 
                                                                                  FROM arg_ordenes_detalle od
                                                                                   LEFT JOIN arg_ordenes AS o
                                                                                        ON od.trn_id_rel = o.trn_id
                                                                                   WHERE
                                                                                        o.unidad_id = ".$unidad_id) or die(mysqli_error($mysqli));
                                $max_fol = $max_folio_det ->fetch_array(MYSQLI_ASSOC);
                                $folio_det = $max_fol['folio_ord'];
                                $folio_det = $folio_det + 1;
                                
                                              //MUESTRAS METODOS   
                                 $max_trn_id_met = $mysqli->query("SELECT IFNULL(MAX(trn_id), 0) AS trn_id FROM arg_ordenes_metodos ") or die(mysqli_error($mysqli));
                                 $ma_trn_id_m = $max_trn_id_met ->fetch_array(MYSQLI_ASSOC);
                                 $trn_id_met = $ma_trn_id_m['trn_id'];
                                 $trn_id_met = $trn_id_met+1;   
                                 $unic = 1;
                                                             
                              
                                if($metodo_id == 27){
                                  echo 'aqui voy'.$fin;
                                   // $i = 1;  
                                    $fin_div = ($fin/2);
                                    $b = 1;
                                    $rec = 2;
                                    $a = 1;
                                    $total_muest = 2;                                    
                                      
                                    while ($b <= $fin_div){                                        
                                                 echo  'entroa la divion'; 
                                    
                                     
                                                           
                                         $length = 6;                            
                                         $string_c = (string)$folio_det;
                                         $cons_c = str_pad($string_c,$length,"0", STR_PAD_LEFT);
                                                                     
                                         $length_fs = 3;
                                         $folio_interno = $serie_mina.$cons_c.$eti;
                                                                     
                                         $query = "INSERT INTO arg_ordenes_detalle (trn_id, trn_id_rel, banco_id, voladura_id, cantidad, folio_inicial, folio_final, folio, folio_interno, estado, usuario_id) ".
                                                  "VALUES ($tr_id_det, $trn_id, 0, 0, $total_muest, '','',$folio_det , '$folio_interno', 1, $u_id)";
                                         $mysqli->query($query);
                                         
                                         $query = "INSERT INTO arg_ordenes_metodos (trn_id, trn_id_rel, metodo_id ) ".
                                                  "VALUES ($trn_id_met, $tr_id_det, $metodo_id)";
                                         $mysqli->query($query) ;
                                         
                                      //EF-Grav2
                                    $query = "INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id)
                                              VALUES ($tr_id_det, $metodo_id, 9, $u_id )";
                                    $mysqli->query($query);
                                    
                                    $query = "INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin)
                                              VALUES ($tr_id_det, $metodo_id, 9, 5, $u_id, '', $u_id )";                                                    
                                    $mysqli->query($query);   
                                    echo     $query;  
                                    
                                    
                                    $max_trn_id_mu = $mysqli->query("SELECT IFNULL(MAX(trn_id), 0) AS trn_id FROM arg_ordenes_muestrasMetalurgia ") or die(mysqli_error($mysqli));
                                    $max_trn_id_mue = $max_trn_id_mu ->fetch_array(MYSQLI_ASSOC);
                                    $max_trn_id_mues = $max_trn_id_mue['trn_id'];
                                    $max_trn_id_mues = $max_trn_id_mues+1;  
                                   
                                         $posn = 1;
                                         while($a <= $rec){ 
                                            echo 'entro al while transquebr';
                                            $muestra_sel = 'sig_muestra'.$a;                                                                    
                                            $muestra_sln = $_POST[$muestra_sel];
                                            echo 'muestrsel: '.$muestra_sln;                                            
                                            if ($muestra_sln <> 0 AND $muestra_sln <> ''){
                                                echo 'ENTROarea2'.$a.'</br>';
                                                $length = 6;                                                     
                                                $string = (string)$muestra_sln;
                                                $folio_orden_int = str_pad($string,$length,"0", STR_PAD_LEFT);
                                                $length_c = 3;
                                                $string_c = (string)$cons_det;
                                                $cons_deta = str_pad($string_c,$length_c,"0", STR_PAD_LEFT);                                                       
                                                $folio_interno_det = $folio_orden_int; 
                                                
                                                $query = "INSERT INTO arg_ordenes_muestrasMetalurgia (trn_id, trn_id_rel, folio, tipo_id) ".
                                                "VALUES ($max_trn_id_mues, $tr_id_det, '$muestra_sln', 0)";                                             
                                                $mysqli->query($query);
                                               
                                                $query = "INSERT INTO arg_ordenes_transquebradora (trn_id_batch, bloque, pos_geo, trn_id_rel, trn_id_dup, metodo_id, tipo_id, material_id, posicion, u_id, folio_interno) ".
                                                                                             "VALUES ($tr_id_det, $posn, $a, $max_trn_id_mues, 0, $metodo_id, 0, 0, $posn, $u_id, '$muestra_sln')";                                                    
                                                $mysqli->query($query);
                                                echo $query.'</br>';
                                                $unic = $unic+1;
                                              //  $i++;  
                                                $a++;
                                                $pos=$pos+1;
                                                $cons_det++;
                                                $posn = $posn+1;
                                                $max_trn_id_mues = $max_trn_id_mues+1;
                                           }
                                           
                                            
                                       }
                                        
                                        $tr_id_det = $tr_id_det+1;
                                        $trn_id_met = $trn_id_met+1;
                                        $folio_det = $folio_det+1;
                                    /*   $query = "INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id)
                                                  VALUES ($tr_id_det, $metodo_id, 9, $u_id )";
                                        $mysqli->query($query);
                                        
                                        $query = "INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin)
                                                  VALUES ($tr_id_det, $metodo_id, 9, 5, $u_id, '', $u_id )";                                                    
                                        $mysqli->query($query);*/
                                       $b = $b+1;
                                       $rec = $rec+2;
                                       
                                    }   
                             }//FIN DEL WHILE QUE RECORRE TODAS LAS MUESTRAS DEL AREA 2 MET MINERAL
                                
                            
                             elseif($area_id == 2 AND $metodo_id == 33){
                                    $i = 1;  
                                    $length = 6;                            
                                         $string_c = (string)$folio_det;
                                         $cons_c = str_pad($string_c,$length,"0", STR_PAD_LEFT);
                                                                     
                                         $length_fs = 3;
                                         $folio_interno = $serie_mina.$cons_c.$eti;
                                                                     
                                         $query = "INSERT INTO arg_ordenes_detalle (trn_id, trn_id_rel, banco_id, voladura_id, cantidad, folio_inicial, folio_final, folio, folio_interno, estado, usuario_id) ".
                                                  "VALUES ($tr_id_det, $trn_id, 0, 0, $fin, '','',$folio_det , '$folio_interno', 1, $u_id)";
                                         $mysqli->query($query);
                                         
                                         $query = "INSERT INTO arg_ordenes_metodos (trn_id, trn_id_rel, metodo_id ) ".
                                                  "VALUES ($trn_id_met, $tr_id_det, $metodo_id)";
                                         $mysqli->query($query) ;                                         
                                         
                                         $max_trn_id_mu = $mysqli->query("SELECT IFNULL(MAX(trn_id), 0) AS trn_id FROM arg_ordenes_muestrasMetalurgia ") or die(mysqli_error($mysqli));
                                         $max_trn_id_mue = $max_trn_id_mu ->fetch_array(MYSQLI_ASSOC);
                                         $max_trn_id_mues = $max_trn_id_mue['trn_id'];
                                         $max_trn_id_mues = $max_trn_id_mues+1;
                                         
                                      
                                    while($j <= $fin){ 
                                        echo 'entro PRIMER  while';
                                        $muestra_sel = 'sig_muestra'.$i;                                                                    
                                        $muestra_sln = $_POST[$muestra_sel];
                                        echo 'muestrsel: '.$muestra_sln;
                                        
                                        
                                    
                                        if ($muestra_sln <> 0 AND $muestra_sln <> ''){
                                            echo 'ENTROarea2'.$i.'</br>';
                                          
                                            $query = "INSERT INTO arg_ordenes_muestrasMetalurgia (trn_id, trn_id_rel, folio, tipo_id) ".
                                                     "VALUES ($max_trn_id_mues, $tr_id_det, '$muestra_sln', 0)";   
                                            $mysqli->query($query);
                                            echo $query.'</br>';                                       
                                
                                            $query = "INSERT INTO arg_ordenes_transquebradora (trn_id_batch, bloque, pos_geo, trn_id_rel, trn_id_dup, metodo_id, tipo_id, material_id, posicion, u_id, folio_interno) ".
                                                                                         "VALUES ($tr_id_det, $i, $i, $max_trn_id_mues, 0, $metodo_id, 0, 0, $i, $u_id, '$muestra_sln')";                                                    
                                            $mysqli->query($query);
                                            echo $query.'</br>';
                                            $unic = $unic+1;
                                            $max_trn_id_mues = $max_trn_id_mues + 1;
                                            $i++;  
                                            $j++;
                                            $pos=$pos+1;
                                            $cons_det++;                                           
                                              
                                       } 
                                    }//FIN DEL WHILE QUE RECORRE TODAS LAS MUESTRAS DEL AREA 2 MET MINERAL
                                       $query = "INSERT INTO arg_ordenes_bitacora (trn_id_rel, metodo_id, fase_id, u_id)
                                              VALUES ($tr_id_det, $metodo_id, 7, $u_id )";
                                              $mysqli->query($query) ;
                                              $query = "INSERT INTO arg_ordenes_bitacora_detalle (trn_id_rel, metodo_id, fase_id, etapa_id, u_id, fecha_fin, u_id_fin)
                                                        VALUES ($tr_id_det, $metodo_id, 7, 5, $u_id, '', $u_id )";                                                    
                                              $mysqli->query($query);
                                }//FIN DEL MET 33
                        }
                        }
                        }
                   }// Fin de creación de orden
                }//Fin del post
                   if (isset($trn_id) && $trn_id <> 0){
                        echo "<script>";
                        echo "imprimir(".$unidad_id.", ".$trn_id.")";
                        echo "</script>"; 
                    }
        }   
                      
    
?>           
<br /> <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<scrip type="text/javascript" src="js/jquery.min.js"></script>
 