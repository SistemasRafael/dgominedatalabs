<? include "../connections/config.php";
$trn_id = $_GET['trn_id'];
$unidad_id = $_GET['unidad_id'];
//echo $trn_id;
?>

<script>


</script>

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



if (isset($_GET['trn_id'])) {

    $datos_orden = $mysqli->query(
        "SELECT
                                                un.nombre AS unidad, ord.folio, ord.fecha, ba.banco, ba.voladura_id, us.nombre AS usuario
                                           FROM `arg_preordenes` ord
                                           LEFT JOIN arg_bancos_voladuras AS ba
                                           		ON ba.banco_id = ord.banco_id
                                                AND ba.voladura_id = ord.voladura_id                                           
                                           LEFT JOIN arg_empr_unidades AS un
                                            	ON un.unidad_id = ord.unidad_id
                                           LEFT JOIN arg_usuarios us
                                            	ON us.u_id = ord.usuario_id
                                           WHERE ord.trn_id = " . $trn_id
    ) or die(mysqli_error($mysqli));
    $preorden = $datos_orden->fetch_assoc();

?>
    <div class="container">
        <br /> <br /><br /> <br /><br /> <br />
        <?
        $html_en = "<table class='table table-bordered' id='encabezado'>
                             <thead>
                                 <tr class='table-info'>
                                    <th scope='col'>PRE-ORDEN No.: " . $preorden['folio'] . "</th>
                                    <th scope='col'>Elaboró: " . $preorden['usuario'] . "</th>
                                  </tr>";
        $html_en .= "</thead></table>";

        $html_det = "<table class='table table-bordered'>
                                <thead>                                
                                     <tr class='table-info'>      
                                        <th colspan='1'>Mina</th>
                                        <th colspan='1'>Fecha/Hora</th>                                        
                                        <th colspan='1'>Banco+Voladura</th>
                                     </tr>
                                    <tr class='table-secondary' justify-content: center;>";
        $html_det .= "<th align='center'>" . $preorden['unidad'] . "</th>";
        $html_det .= "<th align='center'>" . $preorden['fecha'] . "</th>";
        $html_det .= "<th align='center'>" . $preorden['banco'] . $preorden['voladura_id'] . "</th>";

        $html_det .= "</tr>
                               </thead>
                               <tbody>";


        $html_det .= "</tbody></table>";

        echo ("$html_en");
        echo ("$html_det");
        ?>

        <div class="container">
            <div class="col-6 col-md-12 col-lg-12">

                <div class="col-2 col-md-2 col-lg-2">
                    <form method="post" action="preorden_trabajo.php?unidad_id=<? echo $unidad_id; ?>" name="newpre" id="newpre">
                        <fieldset>
                            <input type="submit" class="btn btn-success" name="nueva_preorden" id="nueva_preorden" value="NUEVA PRE-ORDEN" />
                        </fieldset>
                    </form>
                </div>

                <div class="col-2 col-md-2 col-lg-2">
                    <form method="post" action="app.php?unidad_id=<? echo $unidad_id; ?>" name="newform" id="newform">
                        <fieldset>
                            <input type="submit" class="btn btn-primary" name="nueva_orden" id="nueva_orden" value="NUEVA ORDEN" />
                        </fieldset>
                    </form>
                </div>

                <div class="col-2 col-md-2 col-lg-2">
                    <form method="post" action="preorden_trabajo_pdf.php?trn_id=<? echo $trn_id; ?>&unidad_id=<? echo $unidad_id; ?>" name="imprim" id="imprim">
                        <fieldset>
                            <input type="submit" class="btn btn-secondary" name="imprimir" id="imprimir" value="IMPRIMIR" />
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>

    </div>
<?
}
?>