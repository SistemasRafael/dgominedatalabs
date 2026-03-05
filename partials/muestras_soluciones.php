<!-- jQuery 
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>-->
<!-- BS JavaScript 
<script type="text/javascript" src="js/bootstrap.js"></script>-->
<!-- Have fun using Bootstrap JS -->

<?php
//include "../connections/config.php";
$tipo = $_GET['tipo'];
$unidad_id = $_GET['unidad_id'];
$_SESSION['unidad_id'] = $unidad_id;

$unidad = $mysqli->query(
  "SELECT
                            nombre
                          FROM
                            `arg_empr_unidades`
                          WHERE
                            unidad_id = " . $unidad_id
) or die(mysqli_error($mysqli));
$unidad_sele = $unidad->fetch_assoc();
$unidad_mina = $unidad_sele['nombre'];

?>
<script>
  $(document).ready(function() {
    $("#folio").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#motivos tbody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });

    $("#unidad_id_ex").on("change", function() {
      var value = $("#unidad_id_ex option:selected").val();
      var area = $("#area_sel option:selected").val();
      $.ajax({
        url: 'datos_muestras_soluciones.php',
        type: 'POST',
        dataType: 'html',
        data: {
          value: value,
          area: area
        },
        success: function(html) {
          $("#motivos tbody").html(html);
        }
      })
    });

    $("#area_sel").on("change", function() {
      var value = $("#unidad_id_ex option:selected").val();
      var area = $("#area_sel option:selected").val();
      $.ajax({
        url: 'datos_muestras_soluciones.php',
        type: 'POST',
        dataType: 'html',
        data: {
          value: value,
          area: area
        },
        success: function(html) {
          $("#motivos tbody").html(html);
        }
      })
    });

    $("#area").on("change", function() {
      var area = $("#area option:selected").val();
      const orden = document.getElementById("orden_ex");
      const orden_lbl = document.getElementById("orden_ex_lbl");
      if (area == 1){
        orden.disabled = false;
        orden.value = "";
        orden.style.opacity = 1;
        orden_lbl.style.opacity = 1;
      }else{
        orden.disabled = true;
        orden.value = "0";
        orden.style.opacity = 0;
        orden_lbl.style.opacity = 0;
      }
      $.ajax({
        url: 'datos_muestras_soluciones.php',
        type: 'POST',
        dataType: 'html',
        data: {
          value: value,
          area: area
        },
        success: function(html) {
          $("#motivos tbody").html(html);
        }
      })
    });

    $("#orden_sel").on("change", function() {
      var value = $("#orden_sel option:selected").text().toLowerCase();

      if (value == "todos") {
        value = "";
      }
      $("#motivos tbody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });

  function exportar(unidad_id) {
    var unidad_id = unidad_id;
    var area = document.getElementById("area_sel").value;
    var exportar = '<?php echo "\ export_muestras_soluciones.php?unidad_id=" ?>' + unidad_id + "&area=" + area;
    window.location.href = exportar;
  }

  function setActivo(id, estado) {
    var estado = estado;
    var id = id;
    var unidad_id = <?php echo $unidad_id ?>;
    $.ajax({
        url: 'muestras_soluciones_activo.php',
        type: 'POST',
        dataType: 'html',
        data: {
          estado: estado,
          id: id
        },
      })
      .done(function(respuesta) {
        if (respuesta == 'Se cambió exitosamente.') {
          alert('Se realizó el cambio con éxito');
          var direccionar = '<?php echo "\ muestras_soluciones.php?tipo=2&unidad_id=" ?>' + unidad_id;
          window.location.href = direccionar;
        }
      })
  }

  function cambiarOrden(id) {
    var box = 'orden_box'+id;
    var orden = document.getElementById(box).value;
    var id = id;    
    console.log(orden);
    var unidad_id = <?php echo $unidad_id ?>;
    $.ajax({
        url: 'muestras_soluciones_orden.php',
        type: 'POST',
        dataType: 'html',
        data: {
          orden: orden,
          id: id,
          unidad_id: unidad_id
        },
      })
      .done(function(respuesta) {
        if (respuesta == 'Se cambió exitosamente.') {
          alert('Se realizó el cambio con éxito');
          var direccionar = '<?php echo "\ muestras_soluciones.php?tipo=2&unidad_id=" ?>' + unidad_id;
          window.location.href = direccionar;
        } else {
          alert(respuesta);
        }
      })
  }
  
  function cambiarCantCiclos(id) {
    var box_ciclos = 'ciclos_box'+id;
    var ciclos = document.getElementById(box_ciclos).value;
    var id = id;    
    //alert(ciclos);
    console.log(ciclos);
    var unidad_id = <?php echo $unidad_id ?>;
    $.ajax({
        url: 'muestras_soluciones_ciclos.php',
        type: 'POST',
        dataType: 'html',
        data: {
          ciclos: ciclos,
          id: id
        },
      })
      .done(function(respuesta) {
        if (respuesta == 'Se cambió exitosamente.') {
          alert('Se realizó el cambio con éxito');
          var direccionar = '<?php echo "\ muestras_soluciones.php?tipo=2&unidad_id=" ?>' + unidad_id;
          window.location.href = direccionar;
        } else {
          alert(respuesta);
        }
      })
  }

  //Crear Bancos
  function GuardarMuestra(unidad_id) {
    var unidad_id = unidad_id
    var folio     = document.getElementById("folio_ex").value;
    var area      = document.getElementById("area").value;
    var ciclica   = document.getElementById("can_lecturas").value;
    var orden     = document.getElementById("orden_ex").value;
    $.ajax({
        url: 'agregar_muestra.php',
        type: 'POST',
        dataType: 'html',
        data: {
          unidad_id: unidad_id,
          folio: folio,
          area: area,
          ciclica: ciclica,
          orden: orden
        },
      })
      .done(function(respuesta) {
        ///$("#placas_dat").html(respuesta);
        if (respuesta == 'Se registro exitosamente.') {
          alert('Se guardó con éxito');
          var direccionar = '<?php echo "\ muestras_soluciones.php?tipo=2&unidad_id=" ?>' + unidad_id;
          window.location.href = direccionar;
        } else {
          alert(respuesta);
        }
      })
  }

  //Crear Voladuras
  function GuardarVoladuras(unidad_id) {
    var unidad_id = unidad_id
    var banco_id = document.getElementById("banco_ori").value;
    //var banco = document.getElementById("banco_num").value;
    var voladura_id = document.getElementById("voladura_id").value;
    var folio_ini = document.getElementById("folio_inicial").value;
    var folio_act = document.getElementById("folio_actual").value;
    //alert(banco_id);
    //alert(folio_ini);
    /*alert(nombre);**/
    $.ajax({
        url: 'datos_voladuras.php',
        type: 'POST',
        dataType: 'html',
        data: {
          unidad_id: unidad_id,
          banco_id: banco_id,
          voladura_id: voladura_id,
          folio_ini: folio_ini,
          folio_act: folio_act
        },
      })
      .done(function(respuesta) {
        ///$("#placas_dat").html(respuesta);
        alert(respuesta);
        //console.log(respuesta);
        if (respuesta == 'Se registró exitosamente.') {
          //alert('Se guardó con éxito');
          var direccionar = '<?php echo "\ catalogos.php?tipo=2&unidad_id=" ?>' + unidad_id;
          window.location.href = direccionar;
        }
      })
  }

  function actualizar_met() {
    var unidad_id = document.getElementById('unidad_mina').value;
    var direccionar = '<? echo "\catalogos.php?tipo=2&unidad_id=" ?>' + unidad_id;
    window.location.href = direccionar;
  }
</script>

<!-- Modal bancos  -->
<div class="modal fade" id="ModalBan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Muestra de Soluciones</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label for="unidad_mina" class="col-form-label">Unidad de Mina:</label>
        <input name="unidad_mina" id="unidad_mina" class="form-control" value="<? echo $unidad_mina; ?>" disabled />
        <label for="folio_ex" class="col-form-label">Muestra:</label>
        <input name="folio_ex" id="folio_ex" class="form-control" value="" enabled />
        <label for="area" class="col-form-label">Area:</label>
        <select name="area" id="area" class="form-control">
          <option value="1">Planta</option>
          <option value="2">Metalurgia</option>
        </select>
        
        <label for="orden_ex" class="col-form-label">No. de Lecturas:</label>
        <input name="can_lecturas" id="can_lecturas" class="form-control" value="" enabled />
        <label id="orden_ex_lbl" for="orden_ex" class="col-form-label">Posición en reporte:</label>
        <input name="orden_ex" id="orden_ex" class="form-control" value="" enabled />
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="GuardarMuestra(<? echo $unidad_id; ?>)">Guardar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

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

  .circulos {
    padding-top: 5em;
  }

  img {
    max-width: 100%;
  }
</style>

<?php
$area = [
  1 => "Planta",
  2 => "Metalurgia",
];

if (isset($unidad_id)) {
  if ($tipo == 2) {
    $datos_bancos_detalle = $mysqli->query(
      "SELECT ba.*
                FROM `arg_ordenes_muestrasSoluciones` ba 
                LEFT JOIN arg_empr_unidades un 
                ON un.unidad_id = ba.unidad_id WHERE ba.unidad_id = " . $unidad_id
    ) or die(mysqli_error($mysqli));

    $datos_unidades = $mysqli->query(
      "SELECT nombre FROM arg_empr_unidades WHERE unidad_id = " . $unidad_id
    ) or die(mysqli_error($mysqli));

    $nombre_uni = $datos_unidades->fetch_assoc();
    $nombre = $nombre_uni['nombre'];

?>

    <br><br><br>
    <div class="container">
      <div class="col-md-2 col-lg-2">
        <button type="button" class="btn btn-primary" name="agregar_banco" id="agregar_banco" data-toggle="modal" data-target="#ModalBan">+ AGREGAR</button>
      </div>
    </div>
    <br><br><br>
    <div class="container">

      <div class="col-md-2 col-lg-2">
        <label for="unidad_id_ex" class="col-form-label"><b>UNIDAD DE MINA</b></label>

        <select name="unidad_id_ex" id="unidad_id_ex" class="form-control">
          <? if ($_SESSION['unidad_acc'] == '0') {
            $datos_minas = $mysqli->query("SELECT unidad_id, nombre
                                                      FROM arg_empr_unidades") or die(mysqli_error($mysqli));
            //while ($row2 = $datos_minas->fetch_assoc()){
            while ($row2 = $datos_minas->fetch_array(MYSQLI_ASSOC)) {
              $met_sele = $row2['nombre'];
              $string = "";
              if ($row2['unidad_id'] == $unidad_id) {
                $string = "selected";
              }
          ?>
              <option value="<? echo $row2['unidad_id'] ?>" <? echo $string ?>><? echo $met_sele ?></option>
          <? }
          } ?>

          <? if ($_SESSION['unidad_acc'] <> '0' and $_SESSION['unidad_acc'] <> '999') {
            $datos_minas = $mysqli->query("SELECT unidad_id, nombre
                                                                    FROM arg_empr_unidades 
                                                                    WHERE unidad_id = " . $_SESSION['unidad_acc']) or die(mysqli_error($mysqli));
            //while ($row2 = $datos_minas->fetch_assoc()){
            while ($row2 = $datos_minas->fetch_array(MYSQLI_ASSOC)) {
              $met_sele = $row2['nombre'];
              $string = "";
              if ($row2['unidad_id'] == $unidad_id) {
                $string = "selected";
              }
          ?>
              <option value="<? echo $row2['unidad_id'] ?>" <? echo $string ?>><? echo $met_sele ?></option>
          <?
            }
          } ?>

          <?  //999=Varias unidades de mina (No Todas) 
          $cadena = $_SESSION['unidades'];
          $i = 0;
          if ($_SESSION['unidad_acc'] == '999') {
            while ($i <= strlen($cadena)) {
              $valor = substr($cadena, $i, 1);
              $i = $i + 1;
              if (is_numeric($valor)) {
                $datos_mina = $mysqli->query("SELECT unidad_id, nombre FROM arg_empr_unidades WHERE unidad_id = " . $valor) or die(mysqli_error($mysqli));
                while ($row2 = $datos_mina->fetch_array(MYSQLI_ASSOC)) {
                  $met_sele = $row2['nombre'];
                  $string = "";
                  if ($row2['unidad_id'] == $unidad_id) {
                    $string = "selected";
                  }
          ?>
                  <option value="<? echo $row2['unidad_id'] ?>" <? echo $string ?>><? echo $met_sele ?></option>
          <? }
              }
            }
          } ?>
        </select>
      </div>
      <div class="col-md-2 col-lg-2">
        <label for="folio" class="col-form-label"><b>BUSQUEDA</b></label>
        <input class="form-control" type="text" name="folio" id="folio" autocomplete="off" placeholder="Buscar folio..."></input>
      </div>

      <div class="col-md-2 col-lg-2">
        <label for="folio" class="col-form-label"><b>AREA</b></label>
        <select name="area_sel" id="area_sel" class="form-control">
          <option value="0">Todos</option>
          <option value="1">Planta</option>
          <option value="2">Metalurgia</option>
        </select>
      </div>

      <?/*<div class="col-md-2 col-lg-2">
        <label for="orden_sel" class="col-form-label"><b>ORDEN</b></label>
        <input list="list_orden_sel" id="orden_sel" name="orden_sel" class="form-control">
        <datalist id="list_orden_sel">
          <option value="Todos"></option>
          <?
          $datos_ordenes = $mysqli->query("SELECT DISTINCT orden as orden FROM arg_ordenes_muestrasSoluciones") or die(mysqli_error($mysqli));
          while ($row2 = $datos_ordenes->fetch_array(MYSQLI_ASSOC)) {
            $orden = $row2['orden'];

          ?>
            <option value="<? echo $orden ?>">
            <?
          }
            ?>
        </datalist>
      </div>*/
      ?>


      <div class="col-md-2 col-lg-2" style="margin-top:24;">
        <button type='button' class='btn btn-success' name='export' id='export' onclick="exportar(1, <? echo $unidad_id ?>)"> EXPORTAR
          <span class='fa fa-file-excel-o fa-1x'></span>
        </button>
      </div>



      <br>
      <br>
      <br>
      <br>
  <?
    $html_det = "<div class='container'>
                        <table class='table table-striped' id='motivos'>
                                <thead>
                                    <tr class='table-info' justify-content: center;>            
                                        <th scope='col1'>Unidad de Mina</th>
                                        <th scope='col1'>Muestra</th>
                                        <th scope='col1'>Area</th>
                                        <th scope='col1'>Cant. Lecturas</th>
                                        <th scope='col1'>Cambiar Cant.</th>
                                        <th scope='col1'>Estado</th>
                                        <th colspan=2 scope='col1'>Orden</th>";
    $html_det .= "</tr>
                               </thead>
                               <tbody>";

    while ($fila = $datos_bancos_detalle->fetch_assoc()) {
      $texto = "";
      $css = "";
      if ($fila['activo'] == '1') {
        $texto = "Desactivar";
        $css = "btn-secondary";
        $estado = 0;
      } else {
        $texto = "Activar";
        $css = "btn-primary";
        $estado = 1;
      }
      $num = 1;
      $html_det .= "<tr>";
      $html_det .= "<td>" . $nombre . "</td>";
      $html_det .= "<td>" . $fila['folio'] . "</td>";
      $html_det .= "<td>" . $area[$fila['area_id']] . "</td>";      
     /// $html_det .= "<td>" . $fila['ciclica'] . "</td>";
      $html_det .= "<td> <input id='ciclos_box".$fila['id']."' class='form-control' type='text' value='" . $fila['ciclica'] . "'></input></td>";
        $html_det .= "<td> <button type='button' class='btn btn-info'";
        $html_det .= "onclick = cambiarCantCiclos(" . $fila['id'] . ")";
        $html_det .= "><span> Cambiar Cant. </span>
                                                  </button>
                                            </td>";
      $html_det .= "<td> <button type='button' class='btn $css'";
      $html_det .= "onclick = setActivo(" . $fila['id'] . "," . $estado . ")";
      $html_det .= "><span> $texto </span>
                                                  </button>
                                            </td>";
      if ($fila['area_id'] == 1) {
        $html_det .= "<td> <input id='orden_box".$fila['id']."' class='form-control' type='text' value='" . $fila['orden'] . "'></input></td>";
        $html_det .= "<td> <button type='button' class='btn btn-info'";
        $html_det .= "onclick = cambiarOrden(" . $fila['id'] . ")";
        $html_det .= "><span> Cambiar </span>
                                                  </button>
                                            </td>";
        $html_det .= "</tr>";
      } else {
        $html_det .= "<td> " . $fila['orden'] . "</td>";
        $html_det .= "<td> <button type='button' class='btn btn-secondary' disabled style='pointer-eventes:none'";
        $html_det .= "onclick = cambiarOrden(" . $fila['id'] . ")";
        $html_det .= "><span> Cambiar </span>
                                                  </button>
                                            </td>";
        $html_det .= "</tr>";
      }
    }

    $html_det .= "</tbody></table></div></div>";

    echo ("$html_det");
  }
}

  ?>
  <br /><br /><br /><br /><br /><br /><br /><br />
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <!--<script type="text/javascript" src="js/vehiculos.js"></script>-->