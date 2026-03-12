<?php
    include "connections/config.php";
    $u_id        = $_SESSION['u_id'];
    $unidad_id   = $_POST['unidad_id'];
    $banco_id    = $_POST['banco_id'];
    $voladura_id = $_POST['voladura_id'];
    $folio_ini = $_POST['folio_ini'];
    $folio_act = $_POST['folio_act'];

    $max_banco_id = $mysqli->query("SELECT banco FROM arg_bancos WHERE banco_id = ".$banco_id) or die(mysqli_error($mysqli));
    $max_ban = $max_banco_id ->fetch_array(MYSQLI_ASSOC);
    $banco = $max_ban['banco'];
    
    if (isset($banco_id)){
        //Validar duplicados
        $validar_dupl = $mysqli->query("SELECT COUNT(voladura_id) AS voladura 
                                        FROM 
                                            arg_bancos_voladuras bvo
                                            LEFT JOIN arg_bancos ban
                                                ON bvo.banco_id = ban.banco_id
                                        WHERE 
                                            bvo.banco_id = ".$banco_id." AND ban.unidad_id = ".$unidad_id." AND bvo.voladura_id = ".$voladura_id) or die(mysqli_error($mysqli));
        $vol_dup = $validar_dupl ->fetch_array(MYSQLI_ASSOC);   
        $vol_duplicado = $vol_dup['voladura'];
        
        // echo $validar_dupl;                                                
        if ($vol_duplicado == 0){
                            $query = "INSERT INTO arg_bancos_voladuras (banco_id, banco, voladura_id, folio_inicial, folio_actual, u_id) ".
                                    "VALUES ($banco_id, '$banco', $voladura_id, $folio_ini, $folio_act , $u_id)";
                            $mysqli->query($query) ;
                            
                            $resultado = $mysqli->query("SELECT
                                                            banco
                                                        FROM 
                                                            arg_bancos_voladuras
                                                        WHERE banco_id = ".$banco_id." AND voladura_id = ".$voladura_id) or die(mysqli_error($mysqli));
                                                        
                                                        //echo $query;
            if ($resultado->num_rows > 0) {
                $html = "Se registro exitosamente.";
            }
            else{
                $html = 'Hubo un error, reintente por favor.';
            }
        }
        else{
            $html = 'La voladura '.$voladura_id.' para el banco '.$banco.' ya existe, favor de validar.';
        } 
    }
  
    $mysqli -> set_charset("utf8");
    echo $html;
?>