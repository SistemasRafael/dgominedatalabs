<? include "connections/config.php"; ?>
<!--<!DOCTYPE html>-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>PRUEBAS MineData-Labs Argonaut Gold</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="PRUEBAS MineData-Labs">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Custom fonts for this template  -->

  <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>-->

  <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<!--  <link href="http://localhost/dgominedatalabs/css/check.css" rel="stylesheet">--!>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


  <style type="text/css">
    body {
      padding-bottom: 20px;
    }

    .navbar {
      margin-bottom: 10px;
    }

    .bg-blue {
      background-color: #152c52;
    }

    .navbar-dark .navbar-nav .nav-link {
      color: white;
    }

    .navbar-brand {

      height: 80px;
    }

    img {
      max-width: 80%;
    }

    .barra {
      width: 100%;
      padding: 0px;
      height: 50px;
      background-color: #cecece;
      text-align: left;
      color: blue;
    }

    .barra a {
      color: #152c52;
    }

    .nav-item a {
      font-size: 14px;
      font-weight: lighter;
      font-family: tahoma;
    }
    .form-group input[type="checkbox"] {
    display: none;
}

    .form-group input[type="checkbox"] + .btn-group > label span {
        width: 40px;
    }
    
    .form-group input[type="checkbox"] + .btn-group > label span:first-child {
        display: none;
    }
    .form-group input[type="checkbox"] + .btn-group > label span:last-child {
        display: inline-block;   
    }
    
    .form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
        display: inline-block;
    }
    .form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
        display: none;   
}
  </style>

</head>

<body>
  <div class="barra">
    <div class="container" class="col-xl-10 col-sm-7 col-md-5 col-ld-5">
      <ul class="nav nav-pills">

        <li class="nav-item">
          <a class="text-muted" href="#" target="_blank">PRUEBAS MinaData Labs</a>
        </li>

        <? if ($_SESSION['LoggedIn'] == 1) { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Hola <?echo $_SESSION['nombre']?></a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="logout.php">Cerra sesión</a>
          </li>
          
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Cambiar de Mina</a>
            <div class="dropdown-menu">
              <?
              //0 = Todos
              if ($_SESSION['unidad_acc'] == '0') {
                $datos_at = $mysqli->query("SELECT serie, unidad_id, nombre
                                                      FROM arg_empr_unidades") or die(mysqli_error());
                while ($row = $datos_at->fetch_assoc()) {
              ?> <a class="dropdown-item" href="app.php?unidad_id=<? echo $row['unidad_id']; ?>"><? echo $row['nombre']; ?></a><?
                                                                                                                              } //Fin while
                                                                                                                            } //Fin if para todas las unidades
                                                                                                                                ?>
              <?
              //1|2|3=Solo una unidad de mina
              if ($_SESSION['unidad_acc'] <> '0' and $_SESSION['unidad_acc'] <> '999') {
                $datos_umina = $mysqli->query("SELECT serie, unidad_id, nombre
                                 FROM arg_empr_unidades WHERE unidad_id = " . $_SESSION['unidad_acc']) or die(mysqli_error());
                while ($row = $datos_umina->fetch_assoc()) {
              ?><a class="dropdown-item" id="unidad" href="app.php?&unidad_id=<? echo $row['unidad_id']; ?>"><? echo $row['nombre']; ?></a>
              <? }
              } ?>
              <?
              //999=Varias unidades de mina (No Todas)
              $cadena = strlen($_SESSION['unidades']);
              $i = 0;
              if ($_SESSION['unidad_acc'] == '999') {
                while ($i <= $cadena) {
                  $valor = substr($_SESSION['unidades'], $i, 1);
                  if (is_numeric($valor)) {
                    // echo $valor;
                    $datos_umina = $mysqli->query("SELECT serie, unidad_id, nombre FROM arg_empr_unidades WHERE unidad_id = " . $valor) or die(mysqli_error());
                    $mina_acce = $datos_umina->fetch_array(MYSQLI_ASSOC);
                    $mina_acc = $mina_acce['unidad_id'];
                    $mina_acc_nombre = $mina_acce['nombre'];

              ?> <a class="dropdown-item" href="app.php?unidad_id=<? echo $mina_acc; ?>"><? echo $mina_acc_nombre; ?></a><?
                                                                                                                        }
                                                                                                                        $i = $i + 1;
                                                                                                                      }
                                                                                                                    }
                                                                                                                          ?>
          </li>
        <? } ?>
      </ul>
    </div>
  </div>
  </div>

  <? $unidad_mina_sel = $_GET['unidad_id'];
  if ($unidad_mina_sel == '') {
    $unidad_mina_sel = $_SESSION['unidad_def'];
  }
  ?>

  <br />
  <div class="col-xl-2 col-sm-4 col-md-5 col-ld-5 ">
    <a class="navbar-brand logos" href="seguimiento_ordenes.php?unidad_id=<? echo $unidad_mina_sel; ?>">
      <img src="images/minedata_lab.jpg" alt="ArgonautGold Logo">
    </a>
  </div>

  <div class="container" class="col-xl-3 col-sm-3 col-md-5 col-ld-5 ">
    <div class="row">
      <? $mysqli->set_charset("utf8");
      $datos_menu = $mysqli->query("SELECT DISTINCT
                                    menu_id, `nombre_directiva`, clase 
                                FROM 
                                    `perfiles_privilegios` 
                                WHERE 
                                    directiva_id = 1 AND activo = 1 AND u_id = " .$_SESSION['u_id']."
                                ORDER BY menu_id")
        or die(mysqli_error());
      //utf8_encode($mysqli);
      while ($row_menu = $datos_menu->fetch_assoc()) { ?>
        <div class="col-xl-3 col-sm-5 col-md-4 col-ld-5 ">
          <div class="card text-white text-xl-center bg-info o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon big">
                <i class="<? echo $row_menu['clase'] ?>"></i>
              </div>
            </div>
            <div class="btn-group">
              <button type="button" id="dropdownMenuButton" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                <? echo $row_menu['nombre_directiva'] ?>
              </button>
              <ul class="dropdown-menu">
                <? $datos_transacciones = $mysqli->query("SELECT 
                                                                DISTINCT nombre_directiva, valor_directiva
                                                                menu_id, accion, orden 
                                                         FROM 
                                                                `perfiles_privilegios` 
                                                         WHERE 
                                                                directiva_id = 2
                                                                AND activo = 1
                                                                AND menu_id = " . $row_menu['menu_id'] . " 
                                                                AND u_id = " . $_SESSION['u_id'] . 
                                                         " ORDER BY  orden")
                  or die(mysqli_error());
                $total_rows =  mysqli_num_rows($datos_transacciones);
                $i = 1;
                while ($row_transaccion = $datos_transacciones->fetch_assoc()) { ?>
                  <li> <a href="<? echo $row_transaccion['accion'] . $unidad_mina_sel; ?>">
                      <h5><? echo $row_transaccion['nombre_directiva'] ?></h5>
                    </a> </li>
                  <? if ($i < $total_rows) { ?>
                    <li class="divider"></li>
                  <? } ?>
                  <? $i++; ?>
                <? } ?>
              </ul>
            </div>
          </div>
        </div>
      <? } ?>
    </div>
  </div>



  </div>
  <!--   <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-info o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-th-list fa-2x"></i>
              </div>
            </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        ORDENES DE TRABAJO
                     </button>
                        <ul class="dropdown-menu" role="menu">
                            <li> <a href="app.php?unidad_id=<? echo $unidad_mina_sel; ?>"><h5>Crear Orden de Trabajo Sólidos</h5></a> </li>
                            <li> <a href="app_sln.php?unidad_id=<? echo $unidad_mina_sel; ?>"><h5>Crear Orden de Trabajo Solución</h5></a> </li>
                            <li> <a href="#"><h5>Crear Orden de Trabajo Stackers</h5></a> </li>
                            <li class="divider"></li>
                          
                            <li> <a href="seguimiento_ordenes.php?unidad_id=<? echo $unidad_mina_sel; ?>"><h5>Seguimiento a Ordenes</h5></a></li>
                        </ul>
                   </div>
              </div>
          </div>
          
          <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-info o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-flask fa-2x"></i>
              </div>
            </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        MUESTRAS
                     </button>
                   
                        <ul class="dropdown-menu" role="menu3">
                            <li> <a href="app.php"><h5>Ver Muestras</h5></a> </li>
                            <li class="divider"></li>                   
                            <li> <a  href="app.php?tipo=1&unidad=<? echo $unidad_mina_sel; ?>"><h5>Seguimiento a Muestras</h5></a> </li>
                        </ul>
                   </div>
              </div>
          </div>
          
          <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-info o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon big">
                <i class="fa fa-line-chart fa-2x"></i>
              </div>
            </div>
                    <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                        INFORMES
                     </button>
                        
                        <ul class="dropdown-menu" role="menu">
                            <li> <a href="#"<? echo $unidad_mina_sel; ?>"><h5>Rastreador de muestras</h5></a> </li>                                              
                            <li> <a  href="visor_ordenes.php?unidad_id=<? echo $unidad_mina_sel; ?>""<? echo $unidad_mina_sel; ?>"><h5>Ordenes de Trabajo</h5></a> </li>
                            <li> <a  href="calendario.php?motivo=0&unidad=<? echo $unidad_mina_sel; ?>"><h5>Acumulados de Muestras</h5></a></li>
                        </ul>
                   </div>
                
              </div>
          </div>  --!>
          
     
    
    <!--
<a class="navbar-brand logos" href="app.php?unidad_id=<? echo $unidad_mina_sel; ?>">
      <img src="images/argonaut-logo2.jpg" alt="ArgonautGold Logo">
</a>
  
    <div class="container">
    <div class="row">
        <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
           <div class="card text-white text-xl-center bg-info o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon big">
                <i class="fa fa-list-alt fa-2x"></i>
              </div>
            </div>
                <div class="btn-group">
                    <button type="button" id="dropdownMenuButton" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        CATALOGOS
                     </button>
                        <ul class="dropdown-menu">
                           <li> <a href="catalogos.php?tipo=1&unidad_id=<? echo $unidad_mina_sel; ?>"><h5>Bancos</h5></a> </li>
                           <li> <a href="catalogos.php?tipo=2&unidad_id=<? echo $unidad_mina_sel; ?>"><h5>Voladuras</h5></a> </li>
                           <li class="divider"></li>
                           <li> <a href="metodos.php?unidad_id=<? echo $unidad_mina_sel; ?>"><h5>Método de Análisis</h5></a> </li>
                           <li> <a href="controles.php?unidad_id=<? echo $unidad_mina_sel; ?>"><h5>Controles de Calidad</h5></a>  </li>
                           <li class="divider"></li>                    
                           <li> <a href="fases.php?unidad_id=<? echo $unidad_mina_sel; ?>"><h5>Fases de los Métodos</h5></a> </li>  --!>
                          <!-- <li> <a href="doc_vehiculos.php?unidad=<? echo $unidad_mina_sel; ?>"><h5>Etapas de fases</h5></a></li>--!>
                       <!-- </ul>
                   </div>
              </div>
          </div>
          
        <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-info o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-th-list fa-2x"></i>
              </div>
            </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        ORDENES DE TRABAJO
                     </button>
                        <ul class="dropdown-menu" role="menu">
                            <li> <a href="app.php?unidad_id=<? echo $unidad_mina_sel; ?>"><h5>Crear Orden de Trabajo Sólidos</h5></a> </li>
                            <li> <a href="app_sln.php?unidad_id=<? echo $unidad_mina_sel; ?>"><h5>Crear Orden de Trabajo Solución</h5></a> </li>
                            <li> <a href="#"><h5>Crear Orden de Trabajo Stackers</h5></a> </li>
                            <li class="divider"></li>   --!>
                            <!--<li> <a href="mpr.php?unidad_id=<? echo $unidad_mina_sel; ?>"><h5>Pesaje Recepción de Muestras</h5></a></li>--!>
                        <!--    <li> <a href="seguimiento_ordenes.php?unidad_id=<? echo $unidad_mina_sel; ?>"><h5>Seguimiento a Ordenes</h5></a></li>
                        </ul>
                   </div>
              </div>
          </div>
          
          <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-info o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-flask fa-2x"></i>
              </div>
            </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        MUESTRAS
                     </button>--!>
                        <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
  <!--  <ul class="dropdown-menu" role="menu3">
                            <li> <a href="app.php"><h5>Ver Muestras</h5></a> </li>
                            <li class="divider"></li>                   
                            <li> <a  href="app.php?tipo=1&unidad=<? echo $unidad_mina_sel; ?>"><h5>Seguimiento a Muestras</h5></a> </li>
                        </ul>
                   </div>
              </div>
          </div>
          
          <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-info o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon big">
                <i class="fa fa-line-chart fa-2x"></i>
              </div>
            </div>
                    <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                        INFORMES
                     </button>-!>
                        <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
  <!--    <ul class="dropdown-menu" role="menu">
                            <li> <a href="#"<? echo $unidad_mina_sel; ?>"><h5>Rastreador de muestras</h5></a> </li>                                              
                            <li> <a  href="visor_ordenes.php?unidad_id=<? echo $unidad_mina_sel; ?>""<? echo $unidad_mina_sel; ?>"><h5>Ordenes de Trabajo</h5></a> </li>
                            <li> <a  href="calendario.php?motivo=0&unidad=<? echo $unidad_mina_sel; ?>"><h5>Acumulados de Muestras</h5></a></li>
                        </ul>
                   </div>
                
              </div>
          </div> 
          
                
      </div>
    </div> --!>
 
   

   