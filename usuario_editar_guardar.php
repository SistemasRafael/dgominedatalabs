<? include "connections/config.php"; ?>
<?php
//$html = '';
$u_id      = $_SESSION['u_id'];
$perfileli = $_POST['perfileli'];
$user_id_eli = $_POST['usereli'];
$accion      = $_POST['accion']; //0: Eliminas;1:Agregas
$hoy = date('d-m-Y h:i:s');

//$fecha = date_format($hoy, 'd/m/yyyy h:i:s');
//echo $fecha;
//echo $accion;
/*echo $user_id_eli;
echo $u_id;
echo $perfileli;*/
if (isset($perfileli)) {

  if ($accion == 1) {

    $check = $mysqli->query("SELECT activo FROM arg_usuarios_perfiles WHERE u_id = " . $user_id_eli . " AND perfil_id = " . $perfileli . "");
    $query = "";
    if (mysqli_num_rows($check) == 0) {
      $query .= "INSERT INTO arg_usuarios_perfiles (u_id, perfil_id, fecha_inicial, fecha_final, activo, u_id_resp) " .
        "VALUES(" . $user_id_eli . "," . $perfileli . ",'" . $hoy . "','12/12/2999',1," . $u_id . ")";
    } else {
      $query .= "UPDATE arg_usuarios_perfiles SET activo = 1 WHERE u_id = " . $user_id_eli . " AND perfil_id = " . $perfileli . "";
    }
    $mysqli->query($query);

    $datos_user = $mysqli->query("SELECT
                                       	 up.perfil_id, up.u_id
                                        ,per.perfil_id, per.descripcion AS perfil
                                        ,up.fecha_inicial, up.fecha_final
                                        ,(CASE WHEN up.activo = 1 THEN 'SI' ELSE 'NO' END) AS activo
                                    FROM
                                    	arg_usuarios_perfiles AS up 
                                    LEFT JOIN arg_perfiles AS per
                                    	ON up.perfil_id = per.perfil_id
                                    WHERE u_id =" . $user_id_eli) or die(mysqli_error());

    $html .=  "<table class='table text-black' id='datos_perfiles_us'>
                                <thead class='thead-warning' align='center'>
                                <tr class='table-info' align='left'>
                                <th>Perfil</th>
                                <th>Fecha Inicial</th>
                                <th>Fecha Final</th>
                                <th>Activo</th>";

    $html .=  "</tr></thead><tbody>";

    $cont = 0;
    while ($res = $datos_user->fetch_assoc()) {
      $cont = $cont + 1;
      $perfil_id = $res['perfil_id'];
      $perfil    = $res['perfil'];
      $fecha_ini = $res['fecha_inicial'];
      $fecha_fin = $res['fecha_final'];
      $activo    = $res['activo'];

      $html .= "<tr>
                                    <td>" . $perfil . "</td>
                                    <td>" . $fecha_ini . "</td>
                                    <td>" . $fecha_fin . "</td>
                                    <td>" . $activo . "</td>";
    }

    $html .= " </tr></tbody></table></div>";
  }
  if ($accion == 0) {
    //echo 'acc';
    $query = mysqli_multi_query($mysqli, "CALL arg_desactivar_perfil(" . $perfileli . ", " . $user_id_eli . ", '" . $hoy . "', " . $u_id . ")") or die(mysqli_error($mysqli));

    $datos_user = $mysqli->query("SELECT
                                       	 up.perfil_id, up.u_id
                                        ,per.perfil_id, per.descripcion AS perfil
                                        ,up.fecha_inicial, up.fecha_final
                                        ,(CASE WHEN up.activo = 1 THEN 'SI' ELSE 'NO' END) AS activo
                                    FROM
                                    	arg_usuarios_perfiles AS up 
                                    LEFT JOIN arg_perfiles AS per
                                    	ON up.perfil_id = per.perfil_id
                                    WHERE u_id =" . $user_id_eli) or die(mysqli_error());

    $html .=  "<table class='table text-black' id='datos_perfiles_us'>
                                <thead class='thead-warning' align='center'>
                                <tr class='table-info' align='left'>
                                <th>Perfil</th>
                                <th>Fecha Inicial</th>
                                <th>Fecha Final</th>
                                <th>Activo</th>";

    $html .=  "</tr></thead><tbody>";

    $cont = 0;
    while ($res = $datos_user->fetch_assoc()) {
      $cont = $cont + 1;
      $perfil_id = $res['perfil_id'];
      $perfil    = $res['perfil'];
      $fecha_ini = $res['fecha_inicial'];
      $fecha_fin = $res['fecha_final'];
      $activo    = $res['activo'];

      $html .= "<tr>
                                    <td>" . $perfil . "</td>
                                    <td>" . $fecha_ini . "</td>
                                    <td>" . $fecha_fin . "</td>
                                    <td>" . $activo . "</td>";
    }

    $html .= " </tr></tbody></table></div>";
  }
}

$mysqli->set_charset("utf8");
echo utf8_encode($html);

?>