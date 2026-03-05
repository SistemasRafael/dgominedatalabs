<?php
include "connections/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre  = $mysqli->real_escape_string($_POST['nombre']);
    $codigo  = $mysqli->real_escape_string($_POST['codigo']);
    $division = $mysqli->real_escape_string($_POST['division']);
    $email  = $mysqli->real_escape_string($_POST['email']);
    $clave = md5($mysqli->real_escape_string($_POST['clave']));
    $comclave = md5($mysqli->real_escape_string($_POST['comclave']));
    $u_id_creado = $mysqli->real_escape_string($_POST['u_id_creado']);
    $mina_seleccionada = $mysqli->real_escape_string($_POST['mina_seleccionada']);
    $fecha_creacion = date("Y-m-d");
    $id_max_ve = $mysqli->query("SELECT max(u_id) as maximo FROM arg_usuarios");
    $id_maximo = $id_max_ve->fetch_array(MYSQLI_ASSOC);
    $id_max_u = $id_maximo['maximo'];
    $id_max_u = $id_max_u + 1;
    $cod_dup = 0;
    $email_dup = 0;

    $codi = $mysqli->query("SELECT codigo as codigo FROM arg_usuarios WHERE codigo = '" . $codigo . "'") or die(mysqli_error($mysqli));
    if (mysqli_num_rows($codi) > 0) {
        $cod_dup = 1;
    }

    $ema = $mysqli->query("SELECT email as email FROM arg_usuarios WHERE email = '" . $email . "'") or die(mysqli_error($mysqli));
    if (mysqli_num_rows($ema) > 0) {
        $email_dup = 1;
    }

    if ($division == "local"){
        $email_dup = 0;
        $email = "";
    }

    if ($clave != $comclave) {
        echo 'Error: Las contraseñas no coinciden.';
    } else {
        if ($cod_dup == 1) {
            echo 'Error: Este usuario ya se encuentra registrado.';
        } else {
            if ($email_dup == 1) {
                echo 'Error: Este usuario ya se encuentra registrado.';
            } else {
                $query = "INSERT INTO arg_usuarios (u_id, codigo, nombre, clave, email, activo, division, codigo_reset, u_id_created, fecha_creacion, fecha_fin, fecha_edicion) " .
                    "VALUES ($id_max_u, '$codigo', '$nombre', '$clave', '$email', '1', '$division', '', $u_id_creado, '$fecha_creacion', '', '')";

                $mysqli->query($query) or die('Error, query failed : ' . mysqli_error($mysqli));
                $resultado = $mysqli->query("SELECT u_id, nombre FROM arg_usuarios WHERE u_id = " . $id_max_u . "") or die(mysqli_error($mysqli));
                $inmina = "INSERT INTO arg_usuarios_directivas (u_id, directiva_id, valor, firma) VALUES ($id_max_u,1,$mina_seleccionada,'')";
                $mysqli->query($inmina) or die('Error, query failed : ' . mysqli_error($mysqli));
                if (mysqli_num_rows($resultado) > 0) {
                    echo 'Se agregó correctamente el usuario';
                }
            }
        }
    }
}
