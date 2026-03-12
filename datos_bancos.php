<?php
    include "connections/config.php";
    $u_id = $_SESSION['u_id'];
    $unidad_id = $_POST['unidad_id'];
    $banco = $_POST['banco'];
    $nombre = $_POST['nombre'];

    $max_banco_id = $mysqli->query("SELECT MAX(banco_id) AS banco_id FROM arg_bancos") or die(mysqli_error($mysqli));
    $max_ban = $max_banco_id ->fetch_array(MYSQLI_ASSOC);
    $banco_id = $max_ban['banco_id'];
    $banco_id = $banco_id + 1;
        
    if (isset($u_id)){
        //Validar duplicados
        $validar_dupl = $mysqli->query("SELECT COUNT(banco) AS ban 
                                        FROM 
                                            arg_bancos 
                                        WHERE 
                                            banco = '".$banco."' AND unidad_id = ".$unidad_id) or die(mysqli_error($mysqli));
        $banco_dup = $validar_dupl ->fetch_array(MYSQLI_ASSOC);   
        $banco_duplicado = $banco_dup['ban'];
            
        // echo $validar_dupl;                                                
        if ($banco_duplicado == 0){
            $mysqli->query("INSERT INTO arg_bancos (unidad_id, banco_id, banco, nombre, u_id) 
                            VALUES ($unidad_id, $banco_id, '$banco', '$nombre', $u_id)");
            $resultado = $mysqli->query("SELECT banco FROM  arg_bancos WHERE banco = '".$banco."'") or die(mysqli_error($mysqli));

            if ($resultado->num_rows > 0) 
            {
                $html = "Se registro exitosamente.";
            }
            else
            {
                $html = 'Hubo un error, reintente por favor.';
            }
        }
        else
        {
            $html = 'El banco ya existe, favor de validar.';
        } 
    }

    $mysqli -> set_charset("utf8");
 
    echo $html;
?>