<?php
 include "../connections/config.php";
$unidad_id = $_GET['unidad_id'];
$_SESSION['unidad_id'] = $unidad_id;

?>
<style type="text/css">
  .izq {
    background-color: ;
  }

  .derecha {
    background-color: ;
  }

  .btnSubmit {
    width: 50%;
    border-radius: 1rem;
    padding: 1.5%;
    border: none;
    cursor: pointer;
  }

  .nav-tabs>li {
    /* width = (100/number of tabs). This example assumes 3 tabs. */
    width: 15%;
  }

  .circulos {
    padding-top: 5em;
  }

  img {
    max-width: 100%;
  }
</style>

<script>
  function exportar_materiales(tipo, metodo_filtro_fun) {
    var tipo = tipo;
    var metodo_id = metodo_filtro_fun //document.getElementById("metodo_id_sel").value;
    //if (tipo == 1){
    var exportar = '<?php echo "\ exportar_controles.php?tipo=" ?>' + tipo + '&metodo_id=' + metodo_id;
    window.location.href = exportar;
    // }
  }
  
  function error_importar(unidadid) {
    var unidad = unidadid;
    alert('Error: Se debe importar un certificado. Reintente por favor.')
    var exportar = '<?php echo "\ controles.php?unidad_id=" ?>' + unidad;
    window.location.href = exportar;
    // }
  }

  function redireccion(unidad_id) {
    var unidad_id = unidad_id
    var metodo_id_sel = document.getElementById("metodo_id_sel").value;
    //alert(metodo_id_sel);
    //window.history.back();
    var direccionar = '<?php echo "\ controles.php?unidad_id=" ?>' + unidad_id + '&metodo_id_sel=' + metodo_id_sel;
    window.location.href = direccionar;
  }

  function GuardarMateriales(unidad_id) {
    var metodo_id = document.getElementById("metodo_id").value;
    var material_id_selecc = document.getElementById("material_id_selecc").value;
    var cantidad_des = document.getElementById("cantidad_desviacion").value;
    var unidad_id = unidad_id
    //alert(metodo_id);
    $.ajax({
        url: 'datos_materiales.php',
        type: 'POST',
        dataType: 'html',
        data: {
          metodo_id: metodo_id,
          material_id_selecc: material_id_selecc,
          cantidad_des: cantidad_des
        },
      })
      .done(function(respuesta) {
        ///$("#placas_dat").html(respuesta);                                       
        console.log(respuesta);
        if (respuesta == 'Se registro exitosamente.') {
          alert('Se guardó con éxito');
          var direccionar = '<?php echo "\ controles.php?unidad_id=" ?>' + unidad_id;
          window.location.href = direccionar;
        }
      })
  }

  function GuardarMaterialesRef(unidad_id) {
    var nombre = document.getElementById("nombre_material").value;
    var ley = document.getElementById("ley_material").value;
    var desviacion_est = document.getElementById("desv_esta_material").value;
    var cantidad_desvi_est = document.getElementById("cant_desv_sta").value;
    
    var mref_max = ley+(desviacion_est*cantidad_desvi_est)//document.getElementById("mref_maximo").value;
    var mref_min = ley-(desviacion_est*cantidad_desvi_est)//document.getElementById("mref_minimo").value;
    
    alert(mref_max);
    alert(mref_min);
    var met_asign = document.getElementById("metodo_id_asig").value;

    var unidad_id = unidad_id
    alert(met_asign);
    /*$.ajax({
        url: 'datos_materialesRef.php',
        type: 'POST',
        dataType: 'html',
        data: {
          nombre: nombre,
          ley: ley,
          desviacion_est: desviacion_est,
          cantidad_desvi_est: cantidad_desvi_est,
          mref_max: mref_max,
          mref_min: mref_min,
          met_asign: met_asign
        },
      })
      .done(function(respuesta) {
        ///$("#placas_dat").html(respuesta);                                       
        console.log(respuesta);
        if (respuesta == 'Se registro exitosamente.') {
          alert('Se guardó con éxito');
          var direccionar = '<?php echo "\ controles.php?unidad_id=" ?>' + unidad_id;
          window.location.href = direccionar;
        }
      })*/
  }

  function setActivo(id, estado) {
    var estado = estado;
    var id = id;
    var unidad_id = <?php echo $unidad_id ?>;
    $.ajax({
        url: 'datos_activo.php',
        type: 'POST',
        dataType: 'html',
        data: {
          estado: estado,
          id: id
        },
      })
      .done(function(respuesta) {
        if (respuesta == 'Se cambió exitosamente.') {
          alert('Se guardó con éxito');
          var direccionar = '<?php echo "\ controles.php?unidad_id=" ?>' + unidad_id;
          window.location.href = direccionar;
        }
      })
  }

  function GuardarBlancos(unidad_id) {
    var metodo_id = document.getElementById("metodo_id_bl").value;
    var control_id = document.getElementById("control_id").value;
    var ley = document.getElementById("valor_ley").value;
    var maximo = document.getElementById("maximo").value;
    var minimo = document.getElementById("minimo").value;
    var unidad_id = unidad_id
    //alert(metodo_id);
    $.ajax({
        url: 'datos_blancos.php',
        type: 'POST',
        dataType: 'html',
        data: {
          metodo_id: metodo_id,
          control_id: control_id,
          ley: ley,
          maximo: maximo,
          minimo: minimo
        },
      })
      .done(function(respuesta) {
        ///$("#placas_dat").html(respuesta);                                       
        console.log(respuesta);
        if (respuesta == 'Se registro exitosamente.') {
          alert('Se guardó con éxito');
          var direccionar = '<?php echo "\ controles.php?unidad_id=" ?>' + unidad_id;
          window.location.href = direccionar;
        }
      })
  }
</script>
<!-- Modal ACTIVAR/DESACTIVAR materiales a métodos -->
<div class="modal fade" id="ModalMaterial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalHerr">Agregar Material de Referencia</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <label for="metodo_id" class="col-form-label">Método a aplicar:</label>
        <select name="metodo_id" id="metodo_id" class="form-control">
          <? $result_h = $mysqli->query("SELECT metodo_id, nombre FROM `arg_metodos`") or die(mysqli_error());
          while ($row2 = $result_h->fetch_array(MYSQLI_ASSOC)) {
            $banco_sele = $row2['nombre'];
          ?>
            <option value="<? echo $row2['metodo_id'] ?>"><? echo $banco_sele ?></option>
          <? } ?>
        </select>

        <label for="material_id_selecc" class="col-form-label">Seleccione el material de referencia:</label>
        <select name="material_id_selecc" id="material_id_selecc" class="form-control">
          <? $result_h = $mysqli->query("SELECT material_id, nombre FROM `arg_materiales_referencia`") or die(mysqli_error());
          while ($row2 = $result_h->fetch_array(MYSQLI_ASSOC)) {
            $banco_sele = $row2['nombre'];
          ?>
            <option value="<? echo $row2['material_id'] ?>"><? echo $banco_sele ?></option>
          <? } ?>
        </select>

        <label for="cantidad_desviacion" class="col-form-label">Cantidad desviaciones:</label>
        <input name="cantidad_desviacion" id="cantidad_desviacion" size=40 style="width:470px; color:#996633" value="" enabled />

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="GuardarMateriales(<? echo $unidad_id; ?>)">Guardar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal asignar materiales a métodos -->
<div class="modal fade" id="ModalMaterialRef" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalHerr">Crear Material de ReferenciaA</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">   
    
    <form name='importar_veh' method='post' action='controles.php?unidad_id=<?echo $unidad_id."'";?> enctype='multipart/form-data' >
        <label for="nombre_material" class="col-form-label">Nombre:</label>
        <input name="nombre_material" id="nombre_material" size=40 style="width:470px; color:#996633" value="" enabled />
        <label for="ley_material" class="col-form-label">Ley:</label>
        <input name="ley_material" id="ley_material" size=40 style="width:470px; color:#996633" value="" enabled />
        <label for="desv_esta_material" class="col-form-label">Desviación Estándar:</label>
        <input name="desv_esta_material" id="desv_esta_material" size=40 style="width:470px; color:#996633" value="" enabled />

        <label for="cant_desv_sta" class="col-form-label">Cantidad Desviación:</label>
        <input name="cant_desv_sta" id="cant_desv_sta" size=40 style="width:470px; color:#996633" value="" enabled />
       
        <label for="metodo_id_asig" class="col-form-label">MÉTODO A APLICAR:</label>
        <select name="metodo_id_asig" id="metodo_id_asig" class="form-control">
          <? $result_h = $mysqli->query("SELECT metodo_id, nombre FROM `arg_metodos`") or die(mysqli_error());
          while ($row2 = $result_h->fetch_array(MYSQLI_ASSOC)) {
            $banco_sele = $row2['nombre'];
          ?>
            <option value="<? echo $row2['metodo_id'] ?>"><? echo $banco_sele ?></option>
          <? } ?>
        </select>
        <table width="470" border="0" cellpadding="1" cellspacing="1" class="box">
          <!--DWLayoutTable-->
          <tr>

            <th>IMPORTAR CERTIFICADO DE MRC</th>
            <br />
            <th> </th>
          <tr class='table-primary' align='left'>
            <th colspan='4'>

              <input type='file' name='pol_vehi' id='pol_vehi' />
              </br>
            <td width="85" height="18"></td>
          </tr>

        </table>

        <button type='input' class='btn btn-success' name='subir_poliza' id='subir_poliza'>
          <i class='fa-upload fa bootstrap'> Guardar </i>
        </button>

        </form>
        </th>
        </table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Blancos  -->
<div class="modal fade" id="ModalBlancos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalHerr">Agregar Blancos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <label for="control_id" class="col-form-label">Tipo de Control:</label>
        <select name="control_id" id="control_id" class="form-control">
          <? $result_h = $mysqli->query("SELECT control_id, nombre FROM `arg_controles_calidad` WHERE control_id IN (1,5)") or die(mysqli_error());
          while ($row2 = $result_h->fetch_array(MYSQLI_ASSOC)) {
            $banco_sele = $row2['nombre'];
          ?>
            <option value="<? echo $row2['control_id'] ?>"><? echo $banco_sele ?></option>
          <? } ?>
        </select>
        <label for="metodo_id_bl" class="col-form-label">Método a aplicar:</label>
        <select name="metodo_id_bl" id="metodo_id_bl" class="form-control">
          <? $result_h = $mysqli->query("SELECT metodo_id, nombre FROM `arg_metodos`") or die(mysqli_error());
          while ($row2 = $result_h->fetch_array(MYSQLI_ASSOC)) {
            $banco_sele = $row2['nombre'];
          ?>
            <option value="<? echo $row2['metodo_id'] ?>"><? echo $banco_sele ?></option>
          <? } ?>
        </select>
        <label for="valor_ley" class="col-form-label">Ley:</label>
        <input name="valor_ley" id="valor_ley" size=40 style="width:470px; color:#996633" value="" enabled />
        <label for="maximo" class="col-form-label">Máximo:</label>
        <input name="maximo" id="maximo" size=40 style="width:470px; color:#996633" value="" enabled />
        <label for="minimo" class="col-form-label">Mínimo:</label>
        <input name="minimo" id="minimo" size=40 style="width:470px; color:#996633" value="" enabled />

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="GuardarBlancos(<? echo $unidad_id; ?>)">Guardar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<?php

//if (isset($unidad_id)){
$metodo_filtro = $_GET['metodo_id_sel'];
$metodo_filtro_fun = $metodo_filtro;
//echo $metodo_filtro;
if ($metodo_filtro == '' or $metodo_filtro == 0) {
  $metodo_filtro = 'met.metodo_id';
  $metodo_filtro_fun = 0;
}
?>

<div class="container">

  <ul class="nav nav-tabs" id="myTab">
    <li class="active"><a data-toggle="tab" href="#home">ASIGNACION DE MATERIALES</a></li>
    <li><a data-toggle="tab" href="#menu1">MATERIALES DE REFERENCIA</a></li>
    <li><a data-toggle="tab" href="#menu2">BLANCOS</a></li>
    <li><a data-toggle="tab" href="#menu3">DUPLICADOS</a></li>
  </ul>

  <div class="tab-content">

    <div id="home" class="tab-pane fade show active">
      <div class="container" class="col-md-12 col-lg-12">
        <br />
        <br />
        <? $datos_materiales = $mysqli->query(
          "SELECT 
                          mat.id as id, met.nombre AS metodo, cal.control_id, cal.nombre as control_calidad
                        ,mat.nombre, cantidad_desviacion, valor_ley, desv_esta, cantidad_desviacion, maximo, minimo
                        ,(CASE WHEN mat.activo = 1 THEN 'SI' ELSE 'NO' END) AS activo
                    FROM `arg_controles_materiales` mat
                    LEFT JOIN arg_controles_calidad cal
                      ON cal.control_id = mat.control_id
                    LEFT JOIN arg_metodos met
                        ON met.metodo_id = mat.metodo_id
                    WHERE met.metodo_id = " . $metodo_filtro
        ) or die(mysqli_error());
        ?>
        <br />
        <div class="container" class="col-md-2 col-lg-12">
          <div class="col-md-2 col-lg-4">
            <!--  <button type='button' class='btn btn-primary' name='agregar_material' id='agregar_material' data-toggle="modal" data-target="#ModalMaterial" >+ ASIGNAR MATERIAL</button>
                                    --!><button type='button' class='btn btn-success' name='exportar_material' id='exportar_material' onclick="exportar_materiales(1, <? echo $metodo_filtro_fun; ?>)" >EXPORTAR
                                        <span class='fa fa-file-excel-o fa-1x'></span>
                                    </button>
                            </div>
                            
                            <div class="col-md-2 col-lg-5">
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <select name="metodo_id_sel" id="metodo_id_sel" value="<? echo $metodo_filtro; ?>" class="form-control"> 
                                <? $result_h = $mysqli->query("SELECT metodo_id, nombre 
                                                              FROM `arg_metodos` 
                                                              UNION ALL SELECT 0 AS metodo_id, 'Todos los métodos' as nombre 
                                                              ORDER BY metodo_id ") or die(mysqli_error());
                                while ($row2 = $result_h->fetch_array(MYSQLI_ASSOC)) {
                                  $banco_sele = $row2['nombre']; ?>       
                                                    <option value="<? echo $row2['metodo_id'] ?>"><? echo $banco_sele ?></option>
                                <? } ?>
                                </select>            
                            </div>
                             <div class="col-md-2 col-lg-1">
                                <button type='button' class='btn btn-warning' name='filtro' id='filtro' onclick="redireccion(1, <? echo $unidad_id ?>)" >FILTRAR
                                        <span class='fa fa-filter fa-1x'></span>
                                </button>
                             </div>                                
                     </div>
                        <br/><br/>
                            <?
                            $html_det = "<div class='container'>
                            <table class='table table-striped' id='materiales'>
                               <thead>
                                   <tr class='table-info' justify-content: center;>                                        
                                       <th scope='col1'>Metodo de Análisis</th>          
                                       <th scope='col1'>Control de Calidad</th>
                                       <th scope='col1'>Nombre</th>
                                       <th scope='col1'>Cantidad Desviación</th>
                                       <th scope='col1'>Desviación Estandar</th>
                                       <th scope='col1'>Ley</th>                                           
                                       <th scope='col1'>Máximo</th>
                                       <th scope='col1'>Mínimo</th>
                                       <th scope='col1'>Activo</th>";
                            $html_det .= "</tr>
                              </thead>
                              <tbody>";
                            while ($fila = $datos_materiales->fetch_assoc()) {
                              $num = 1;
                              $id = $fila["id"];
                              if ($fila['activo'] == "SI") {
                                $texto = "Desactivar";
                                $estado = 0;
                                $btn = "secondary";
                              } else {
                                $texto = "Activar";
                                $estado = 1;
                                $btn = "primary";
                              }
                              $html_det .= "<tr>";
                              $html_det .= "<td>" . $fila['metodo'] . "</td>";
                              $html_det .= "<td>" . $fila['control_calidad'] . "</td>";
                              $html_det .= "<td>" . $fila['nombre'] . "</td>";
                              $html_det .= "<td>" . $fila['cantidad_desviacion'] . "</td>";
                              $html_det .= "<td>" . $fila['desv_esta'] . "</td>";
                              $html_det .= "<td>" . $fila['valor_ley'] . "</td>";
                              $html_det .= "<td>" . $fila['maximo'] . "</td>";
                              $html_det .= "<td>" . $fila['minimo'] . "</td>";
                              $html_det .= "<td><button type='button' class='btn btn-$btn' style='width:10vmin;' name='enUso' id='enUso' onclick='setActivo($id,$estado)'>$texto</button></td>";
                              $html_det .= "</tr>";
                            }
                            $html_det .= "</tbody></table></div>";
                            echo ("$html_det"); ?>
           	 </div>
        </div>
        
        
        <div id="menu1" class="tab-pane fade">
        <div class="container" class="col-md-12 col-lg-12"> 
            <br />
            <br />
                    <? $datos_materiales = $mysqli->query(
                                                            "SELECT 
                                                                 met.`nombre`, `valor_ley`, `desv_esta`
                                                                ,`cantidad_desviacion`, `maximo`, `minimo`
                                                                ,(CASE WHEN met.`activo` = 1 THEN 'Si' ELSE 'No' END) AS activo
                                                                ,met.u_id, us.nombre AS usuario
                                                                ,met.file_path
                                                            FROM `arg_controles_materiales`  met
                                                            LEFT JOIN arg_usuarios us
                                                            	ON met.u_id = us.u_id
                                                            WHERE
                                                                met.metodo_id = " . $metodo_filtro
                    ) or die(mysqli_error());
                    ?>
                     <br />
                     <div class="container" class="col-md-2 col-lg-12">
                            <div class="col-md-2 col-lg-4">
                                    <button type='button' class='btn btn-primary' name='crear_material' id='crear_material' data-toggle="modal" data-target="#ModalMaterialRef" >+ AGREGAR MATERIAL</button>
                                    <button type='button' class='btn btn-success' name='exportar_material' id='exportar_material' onclick="exportar_materiales(1, <? echo $metodo_filtro_fun; ?>)" >EXPORTAR
                                        <span class='fa fa-file-excel-o fa-1x'></span>
                                    </button>
                            </div>
                            
                            <div class="col-md-2 col-lg-5">
                            </div>
                            <div class="col-md-2 col-lg-2">
                                <select name="metodo_id_sel" id="metodo_id_sel" value="<? echo $metodo_filtro; ?>" class="form-control"> 
                                <? $result_h = $mysqli->query("SELECT metodo_id, nombre FROM `arg_metodos` 
                                                              UNION ALL 
                                                              SELECT 0 AS metodo_id, 'Todos los métodos' AS nombre 
                                                              ORDER BY metodo_id ") or die(mysqli_error());
                                while ($row2 = $result_h->fetch_array(MYSQLI_ASSOC)) {
                                  $banco_sele = $row2['nombre']; ?>       
                                                    <option value="<? echo $row2['metodo_id'] ?>"><? echo $banco_sele ?></option>
                                <? } ?>
                                </select>            
                            </div>
                             <div class="col-md-2 col-lg-1">
                                <button type='button' class='btn btn-warning' name='filtro' id='filtro' onclick="redireccion(1, <? echo $unidad_id ?>)" >FILTRAR
                                        <span class='fa fa-filter fa-1x'></span>
                                </button>
                             </div>                                
                     </div>
                        <br/><br/>
                            <?
                            $html_det = "<div class='container'>
                                    <table class='table table-striped' id='materiales'>
                                    <thead>
                                        <tr class='table-info' justify-content: center;>                                        
                                            <th scope='col1'>Material de Referencia</th>          
                                            <th scope='col1'>Ley</th>
                                            <th scope='col1'>Desviación Estándar</th>                                        
                                            <th scope='col1'>Cantidad Desviación</th>
                                            <th scope='col1'>Máximo</th>
                                            <th scope='col1'>Mínimo</th>                                            
                                            <th scope='col1'>Activo</th>                                            
                                            <th scope='col1'>Usuario</th>
                                            <th scope='col1'>Certificado</th>";
                            $html_det .= "</tr>
                                   </thead>
                                   <tbody>";
                            while ($fila = $datos_materiales->fetch_assoc()) {
                              $num = 1;
                              $html_det .= "<tr>";
                              $html_det .= "<td>" . $fila['nombre'] . "</td>";
                              $html_det .= "<td>" . $fila['valor_ley'] . "</td>";
                              $html_det .= "<td>" . $fila['desv_esta'] . "</td>";
                              $html_det .= "<td>" . $fila['cantidad_desviacion'] . "</td>";
                              $html_det .= "<td>" . $fila['maximo'] . "</td>";
                              $html_det .= "<td>" . $fila['minimo'] . "</td>";
                              $html_det .= "<td>" . $fila['activo'] . "</td>";
                              $html_det .= "<td>" . $fila['usuario'] . "</td>";
                              if ($fila['file_path'] <> ''){
                                    $html_det .= "<td> <a button type='button'class='btn btn-success' href='".$fila['file_path']."' target='_blank'>
                                                                    <span class='fa fa-file-pdf-o fa-2x'></span>
                                                                 </button></td>";   
                              }        
                              else{
                                $html_det .= "<td></td>";  
                              }                   
                              $html_det .= "</tr>";
                            }
                            $html_det .= "</tbody></table></div>";
                            echo ("$html_det"); ?>
           	 </div>
        </div>
        
        
        <!--Segundo Tab blancos--!>
        <div id="menu2" class="tab-pane fade">              
                <div id="content" class="col-md-12 col-lg-12">            
                    <br />
                    <br />                  
                    <? $datos_blancos = $mysqli->query(
                      "SELECT
            	                                          bl.nombre, bl.valor_ley, maximo, minimo, met.nombre as metodo
                                                         ,(CASE WHEN bl.activo = 1 THEN 'Si' ELSE 'No' END) AS activo
                                                         ,us.nombre AS usuario
                                                       FROM
                                                	       `arg_controles_blancos` bl
                                                           LEFT JOIN arg_metodos met  
                                                                ON met.metodo_id = bl.metodo_id
                                                           LEFT JOIN arg_usuarios us
                                                                ON us.u_id = bl.u_id 
                                                        WHERE bl.metodo_id = " . $metodo_filtro
                    ) or die(mysqli_error());
                    ?>
                    <br />
                    <div class="container" class="col-md-2 col-lg-12">
                            <div class="col-md-2 col-lg-4">
                                <button type='button' class='btn btn-primary' name='agregar_blanco' id='agregar_blanco' data-toggle="modal" data-target="#ModalBlancos" >+ AGREGAR BLANCO</button>
                                <button type='button' class='btn btn-success' name='exportar_blancos' id='exportar_blancos' onclick="exportar_materiales(2, <? echo $metodo_filtro_fun ?>)">EXPORTAR
                                    <span class='fa fa-file-excel-o fa-1x'></span>
                                </button>
                             </div>   
                             <div class="col-md-2 col-lg-5">
                             </div>
                             
                    </div>
                    <br/><br/>
                    <?
                    $html_det = "<div class='container'>
                            <table class='table table-striped' id='motivos'>
                                    <thead>
                                        <tr class='table-info' justify-content: center;>            
                                            <th scope='col1'>Metodo de Análisis</th>
                                            <th scope='col1'>Blancos</th>
                                            <th scope='col1'>Ley</th>
                                            <th scope='col1'>Maximo</th>
                                            <th scope='col1'>Minimo</th>
                                            <th scope='col1'>Activo</th>
                                            <th scope='col1'>Usuario</th>";

                    $html_det .= "</tr>
                                   </thead>
                                   <tbody>";
                    while ($fila = $datos_blancos->fetch_assoc()) {
                      $num = 1;
                      $html_det .= "<tr>";
                      $html_det .= "<td>" . $fila['metodo'] . "</td>";
                      $html_det .= "<td>" . $fila['nombre'] . "</td>";
                      $html_det .= "<td>" . $fila['valor_ley'] . "</td>";
                      $html_det .= "<td>" . $fila['maximo'] . "</td>";
                      $html_det .= "<td>" . $fila['minimo'] . "</td>";
                      $html_det .= "<td>" . $fila['activo'] . "</td>";
                      $html_det .= "<td>" . $fila['usuario'] . "</td>";
                      $html_det .= "</tr>";
                    }
                    $html_det .= "</tbody></table></div>";
                    echo ("$html_det"); ?>                            
                </div>                                    
          </div>  <!--Fin segundo tab--!>
          
          <!--Tercer Tab--!>
        <div id="menu3" class="tab-pane fade">
              
                <div id="content" class="col-md-12 col-lg-12">            
                    <br />
                    <br />    
                            <? $datos_blancos = $mysqli->query(
                                                                "SELECT
                                                                    dup.`nombre`,
                                                                    met.nombre AS metodo,
                                                                    `ley_baja_min`,
                                                                    `ley_baja_max`,
                                                                    `porc_ley_baja_min`,
                                                                    `porc_ley_baja_max`,
                                                                    `ley_media_min`,
                                                                    `ley_media_max`,
                                                                    `porc_ley_media_min`,
                                                                    `porc_ley_media_max`,
                                                                    `ley_alta_min`,
                                                                    `ley_alta_max`,
                                                                    `porc_ley_alta_min`,
                                                                    `porc_ley_alta_max`,
                                                                    (CASE WHEN dup.activo = 1 THEN 'Si' ELSE 'No' END) AS activo,
                                                                     us.nombre AS usuario
                                                                FROM
                                                                    `arg_controles_duplicados` dup
                                                                LEFT JOIN arg_metodos met ON
                                                                    met.metodo_id = dup.metodo_id
                                                                LEFT JOIN arg_usuarios AS us
                                                                ON us.u_id = dup.u_id 
                                                                WHERE
                                                                    met.metodo_id = " . $metodo_filtro
                            ) or die(mysqli_error());
                            ?>
                    <br />
                    <div class="container" class="col-md-2 col-lg-4">
                            <button type='button' class='btn btn-primary' name='agregar_banco' id='agregar_banco' data-toggle="modal" data-target="#ModalBan" >+ AGREGAR DUPLICADO</button>
                            <button type='button' class='btn btn-success' name='export' id='export' onclick="exportar(1, <? echo $unidad_id ?>)">EXPORTAR
                                <span class='fa fa-file-excel-o fa-1x'></span>
                            </button>           
                    </div>
                    <br/><br/>
                    <?
                    $html_det = "<div class='container'>
                            <table class='table table-striped' id='motivos'>
                                    <thead>
                                        <tr class='table-info' align='center';>            
                                            <th scope colspan='2'></th>
                                            <th scope colspan='2'>Ley Baja</th>
                                            <th scope colspan='2'>% Ley Baja</th>
                                            <th scope colspan='2'>Ley Media</th>
                                            <th scope colspan='2'>% Ley Media</th>
                                            <th scope colspan='2'>Ley Alta</th>
                                            <th scope colspan='2'>% Ley Alta</th>
                                        </tr>
                                        <tr class='table-info' justify-content: center;>            
                                            <th scope='col1'>Método</th>
                                            <th scope='col1'>Duplicados</th>
                                            <th scope='col1'>Desde</th>
                                            <th scope='col1'>Hasta</th>
                                            <th scope='col1'>Desde</th>
                                            <th scope='col1'>Hasta</th>
                                            <th scope='col1'>Desde</th>
                                            <th scope='col1'>Hasta</th>
                                            <th scope='col1'>Desde</th>
                                            <th scope='col1'>Hasta</th>
                                            <th scope='col1'>Desde</th>
                                            <th scope='col1'>Hasta</th>
                                            <th scope='col1'>Desde</th>
                                            <th scope='col1'>Hasta</th>";
                    $html_det .= "</tr>
                                   </thead>
                                   <tbody>";

                    while ($fila = $datos_blancos->fetch_assoc()) {
                      $num = 1;
                      $html_det .= "<tr>";
                      $html_det .= "<td>" . $fila['metodo'] . "</td>";
                      $html_det .= "<td>" . $fila['nombre'] . "</td>";
                      $html_det .= "<td>" . $fila['ley_baja_min'] . "</td>";
                      $html_det .= "<td>" . $fila['ley_baja_max'] . "</td>";
                      $html_det .= "<td>" . $fila['porc_ley_baja_min'] . "</td>";
                      $html_det .= "<td>" . $fila['porc_ley_baja_max'] . "</td>";
                      $html_det .= "<td>" . $fila['ley_media_min'] . "</td>";
                      $html_det .= "<td>" . $fila['ley_media_max'] . "</td>";
                      $html_det .= "<td>" . $fila['porc_ley_media_min'] . "</td>";
                      $html_det .= "<td>" . $fila['porc_ley_media_max'] . "</td>";
                      $html_det .= "<td>" . $fila['ley_alta_min'] . "</td>";
                      $html_det .= "<td>" . $fila['ley_alta_max'] . "</td>";
                      $html_det .= "<td>" . $fila['porc_ley_alta_min'] . "</td>";
                      $html_det .= "<td>" . $fila['porc_ley_alta_max'] . "</td>";
                      $html_det .= "</tr>";
                      $html_det .= "</tr>";
                    }
                    $html_det .= "</tbody></table></div>";
                    echo ("$html_det"); ?>                            
                </div>    
          </div>  <!--Fin tercer tab--!>

        
        </div>
        
        
        
        </div>
        <?
        // }
        
if (isset($_POST['subir_poliza'])) {

  $u_id = $_SESSION['u_id'];
  $unidad_id = $_SESSION['unidad_id'];
  $nombre  = $_POST['nombre_material'];
  $ley  = $_POST['ley_material'];
  $desv_esta = $_POST['desv_esta_material'];
  $cant_desv_esta  = $_POST['cant_desv_sta'];
  $can_maximo = $ley+($desv_esta*$cant_desv_esta);//$_POST['mref_maximo'];
  $can_minimo = $ley-($desv_esta*$cant_desv_esta); //$_POST['mref_minimo'];
  $met_asign = $_POST['metodo_id_asig'];
  $archivo = $_FILES['pol_vehi']['name'];
  if($archivo == ''){
     echo ('<script>error_importar('.$unidad_id.')</script>');
  }
  else{
      $desti = 'upload/LC/'.$archivo;//$_POST['pol_vehi'];
      copy($_FILES['pol_vehi']['tmp_name'],$desti);
      
     // echo 'entro';
      $max_metodo_id = $mysqli->query("SELECT MAX(material_id) AS material_id FROM arg_controles_materiales") or die(mysqli_error());
      $max_meto = $max_metodo_id->fetch_array(MYSQLI_ASSOC);
      $material_id = $max_meto['material_id'];
      $material_id = $material_id + 1;
    
      if (isset($u_id)) {
        //$maximo = ($ley+($desv_esta*$cantidad));
        //$minimo = ($ley-($desv_esta*$cantidad));
        $query = "INSERT INTO arg_controles_materiales (unidad_id, material_id, nombre, control_id, valor_ley, desv_esta, cantidad_desviacion, maximo, minimo, metodo_id, u_id, file_path) " .
                 "VALUES ($unidad_id, $material_id, '$nombre', 2, $ley, $desv_esta, $cant_desv_esta, $can_maximo, $can_minimo, $met_asign, $u_id, '$desti')";
        $mysqli->query($query);
        //echo $query;
        //die();
        
        $resultado = $mysqli->query("SELECT
                                        material_id
                                     FROM 
                                        arg_controles_materiales
                                     WHERE material_id = " . $material_id) or die(mysqli_error());
        //echo $query;
        if ($resultado->num_rows > 0) {
          $html = 'Se registro exitosamente.';
        } else {
          $html = 'Hubo un error, reintente por favor.';
        }
    }
  }

  $mysqli->set_charset("utf8");
  echo utf8_encode($html);
}

        ?>                    
<br /><br /><br /><br /><br /><br /><br /><br />    
<!--<script type="text/javascript" src="js/jquery.min.js"></script>--!>
<!--<script type="text/javascript" src="js/vehiculos.js"></script>--!>  
   