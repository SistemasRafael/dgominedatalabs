<?//include "connections/config.php";

$unidad_id = $_GET['unidad_id'];
$_SESSION['unidad_id'] = $unidad_id;

        $datos_preparacion = $mysqli->query("SELECT 
                                                   tm.fase_id, etapa_id, fa.nombre AS fase,  etapa, SUM(cantidad) AS total
                                                FROM
                                                   total_muestras_preparacion tm
                                                   LEFT JOIN arg_fases fa
                                                   		ON tm.fase_id = fa.fase_id 
                                                WHERE
                                                    tm.fase_id = 1
                                                GROUP BY
                                                	etapa
                                                ORDER BY
                                                    etapa_id"
                                            ) or die(mysqli_error());
                                                
      $datos_preparacion_tot = $mysqli->query("SELECT 
                                                  SUM(cantidad) AS total_fase
                                                FROM
                                                   preparacion_fase tm                                                   
                                                WHERE
                                                	tm.fase_id = 1 AND estado_id = 1"
                                            ) or die(mysqli_error());
      $total_prep = $datos_preparacion_tot->fetch_assoc();
      $total_prepa  = $total_prep['total_fase']; 
      
      $datos_preparacion_normal = $mysqli->query("SELECT 
                                                  SUM(cantidad) AS total_fase
                                                FROM
                                                   total_muestras_preparacion tm
                                                   LEFT JOIN arg_fases fa
                                                   		ON tm.fase_id = fa.fase_id 
                                                WHERE
                                                	tm.fase_id = 1"
                                            ) or die(mysqli_error());
      $total_prepa_no      = $datos_preparacion_normal->fetch_assoc();
      $total_prepa_normal  = $total_prepa_no['total_fase']; 
      
      $datos_preparacion_ree = $mysqli->query("SELECT 
                                                  SUM(cantidad) AS total_fase
                                                FROM
                                                   total_muestras_preparacionree tm
                                                   LEFT JOIN arg_fases fa
                                                   		ON tm.fase_id = fa.fase_id 
                                                WHERE
                                                	tm.fase_id = 1"
                                            ) or die(mysqli_error());
      $total_prepa_re   = $datos_preparacion_ree->fetch_assoc();
      $total_prepa_ree  = $total_prepa_re['total_fase']; 
      
      $datos_preparacion_ree = $mysqli->query("SELECT 
                                                   etapa_id, etapa, SUM(cantidad) AS total
                                                FROM
                                                   total_muestras_preparacionree tmr
                                                WHERE
                                                    tmr.fase_id = 1
                                                GROUP BY
                                                	etapa
                                                ORDER BY
                                                    etapa_id"
                                            ) or die(mysqli_error());
                                            
     $muestras_unidad1  = $mysqli->query("SELECT nombre, cantidad, tipo_nombre FROM `dash_unidades_muestras` WHERE tipo = 0 AND unidad_id = 1" ) or die(mysqli_error());
     $total_mues_lc     = $muestras_unidad1->fetch_assoc();
     $total_muestras_lc = $total_mues_lc['cantidad'];
     
     $muestras_unidad2  = $mysqli->query("SELECT nombre, cantidad, tipo_nombre FROM `dash_unidades_muestras` WHERE tipo = 0 AND unidad_id = 2" ) or die(mysqli_error());
     $total_mues_sa     = $muestras_unidad2->fetch_assoc();
     $total_muestras_sa = $total_mues_sa['cantidad'];
     
     $muestras_unidad3  = $mysqli->query("SELECT nombre, cantidad, tipo_nombre FROM `dash_unidades_muestras` WHERE tipo = 0 AND unidad_id = 3" ) or die(mysqli_error());
     $total_mues_ec     = $muestras_unidad3->fetch_assoc();
     $total_muestras_ec = $total_mues_ec['cantidad'];
     
     $muestras_unidad0   = $mysqli->query("SELECT nombre, cantidad, tipo_nombre FROM `dash_unidades_muestras` WHERE tipo = 0 AND unidad_id = 0" ) or die(mysqli_error());
     $total_mues_all     = $muestras_unidad0->fetch_assoc();
     $total_muestras_all = $total_mues_all['cantidad'];
    
?>

<body>

<!-- BS JavaScript -->
<!-- Have fun using Bootstrap JS -->

    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">
    
    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">

    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">

  <link rel="stylesheet" href="assets/css/style.css">
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

 <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">--!>
<!-- 2. GOOGLE JQUERY JS v3.2.1  JS --!>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- 3. BOOTSTRAP v4.0.0         JS --!>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js">


<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">  -->


<style  type="text/css">
    .izq{
		background-color:#455A64;
	}
	.derecha{
		background-color:#455A64;
	}
	.btnSubmit
    {
        width: 50%;
        border-radius: 1rem;
        padding: 1.5%;
        border: none;
        cursor: pointer;
    }
    .circulos{
    	padding-top: 5em;
    }
    img{
      max-width: 20%;
    }
    body {
    height: 100%;
     overflow-x: hidden;
}

.card {
    z-index: 0;
    padding-bottom: 20px;
    margin-top: 10px;
    margin-bottom: 30px;
    border-radius: 10px;
}

.top {
    padding-top: 10px;
    padding-left: 1% !important;
    padding-right: 1% !important;
}

/*Icon progressbar*/
#progressbar {
    margin-bottom: 60px;
    overflow: hidden;
    color: #455A64;
    padding-left: 0px;
    margin-top: 30px;
} 

#progressbar li {
    list-style-type: none;
    font-size: 13px;
    width: 25%;
    float: left;
    position: relative;
    font-weight: 400;
}

#progressbar .step0:before {
    font-family: FontAwesome;
    content: "\f10c";
    color: #fff;
}

#progressbar li:before {
    width: 40px;
    height: 40px;
    line-height: 45px;
    display: block;
    font-size: 20px;
    background: #C5CAE9;
    border-radius: 50%;
    margin: auto;
    padding: 0px;
}

/*ProgressBar connectors*/
#progressbar li:after {
    content: '';
    width: 100%;
    height: 12px;
    background: #C5CAE9;
    position: absolute;
    left: 0;
    top: 16px;
    z-index: -1;
}
/*Color number of the step and the connector before it*/


.icon {
    width: 60px;
    height: 60px;
    margin-right: 45px;
}

.icon-content { 
    padding-bottom: 20px;
}

@media screen and (max-width: 992px) {
    .icon-content {
        width: 50%;
    }
}
</style>

<script>

    function llama_datos(metodo_id, trn_id_batch, fase){
        var metodo_id = metodo_id;
        var trn_batch = trn_id_batch;
        var fase_id = fase;
        $.ajax({
            		url: 'datos_fases_muestras.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {metodo_id:metodo_id, trn_batch:trn_batch, fase_id:fase_id },
            	})
            	.done(function(respuesta){
            	  // alert(respuesta);
                   jQuery.noConflict();
            	   $('#fases_modal').modal('show');
                   $("#datos_fases").html(respuesta);
                       
              })   
    }
    
    function grafico_widgetlc (){
    $.ajax({
        url:'grafico_mensual.php',
        type:'POST'
    }).done(function(resp){
        //alert(resp);
        var titulo = [];
        var cantidad = [];
       // var items = json_decode(resp, true);
        //lista = items['mes']['cantidad_muestras'];
     //   alert(titulo);
      var mydata = JSON.parse(resp);
//alert(mydata[0].mes);
//alert(mydata[0].cantidad_muestras);
      
      for(var l = 0;  l<mydata.length; l++){
            titulo.push(mydata[l]['mes']);
            cantidad.push(mydata[l]['cantidad_muestras']);
        }
   
        var ctxwid = document.getElementById( "widgetChart1" );
        ctxwid.height = 150;
        var myChartWid = new Chart( ctxwid, {
            type: 'line',
            data: {
                labels: titulo,
                type: 'line',
                datasets: [ {
                    data: cantidad,
                    label: 'Muestras',
                    backgroundColor: 'transparent',
                    borderColor: 'rgba(255,255,255,.55)',
                } ]
            },
            options: {

            maintainAspectRatio: false,
            legend: {
                display: false
            },
            responsive: true,
            scales: {
                xAxes: [ {
                    gridLines: {
                        color: 'transparent',
                        zeroLineColor: 'transparent'
                    },
                    ticks: {
                        fontSize: 2,
                        fontColor: 'transparent'
                    }
                } ],
                yAxes: [ {
                    display:false,
                    ticks: {
                        display: false,
                    }
                } ]
            },
            title: {
                display: false,
            },
            elements: {
                line: {
                    borderWidth: 1
                },
                point: {
                    radius: 12,
                    hitRadius: 5,
                    hoverRadius: 12
                }
            }
        }
    })
})
};
    
    </script>
    
    <!--Modal humedad--!>
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="fases_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-scrollable" style="max-width:850px!important;" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="humedad">DETALLE DE FASE Y ETAPAS</h5>                   
              </div>
            
               <div class="modal-body" style="font-size:5px;" id="datos_fases">
               </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" id="quebrado_btn" onclick="quebrado_guardar()">Guardar</button>--!>
              </div>
            </div>
    </div>              
</div>
    <!-- <li class="active"><a data-toggle="tab" href="#home">Buscar</a></li>
    <li><a data-toggle="tab" href="#menu1">Busqueda Avanzada</a></li>--!>
    
   <?
     $muestras_unidad1  = $mysqli->query("SELECT nombre, cantidad, tipo_nombre FROM `dash_unidades_muestras` WHERE tipo = 0 AND unidad_id = 1" ) or die(mysqli_error());
     $total_mues_lc     = $muestras_unidad1->fetch_assoc();
     $total_muestras_lc = $total_mues_lc['cantidad'];
     
     $muestras_unidad2  = $mysqli->query("SELECT nombre, cantidad, tipo_nombre FROM `dash_unidades_muestras` WHERE tipo = 0 AND unidad_id = 2" ) or die(mysqli_error());
     $total_mues_sa     = $muestras_unidad2->fetch_assoc();
     $total_muestras_sa = $total_mues_sa['cantidad'];
     
     $muestras_unidad3  = $mysqli->query("SELECT nombre, cantidad, tipo_nombre FROM `dash_unidades_muestras` WHERE tipo = 0 AND unidad_id = 3" ) or die(mysqli_error());
     $total_mues_ec     = $muestras_unidad3->fetch_assoc();
     $total_muestras_ec = $total_mues_ec['cantidad'];
     
     $muestras_unidad0   = $mysqli->query("SELECT nombre, cantidad, tipo_nombre FROM `dash_unidades_muestras` WHERE tipo = 0 AND unidad_id = 0" ) or die(mysqli_error());
     $total_mues_all     = $muestras_unidad0->fetch_assoc();
     $total_muestras_all = $total_mues_all['cantidad'];
     
     //Diario
     $dia    = $mysqli->query("SELECT SUM(cantidad) total_diario FROM `dash_recibidas_dia` WHERE  fecha = (date_format(curdate(), '%d-%m-%Y'))" ) or die(mysqli_error());
     $dia_to = $dia->fetch_assoc();
     $dia_total_rec = $dia_to['total_diario'];
     
     $dia_l    = $mysqli->query("SELECT COUNT(*) as total_dia_liberadas
                                              FROM
                                                	`arg_ordenes_bitacora_detalle` bd
                                                	LEFT JOIN arg_muestras_resultados mr
                                                    	ON bd.trn_id_rel = mr.trn_id
                                                  		AND bd.metodo_id = mr.metodo_id
                                              WHERE 
                                                    etapa_id = 11
                                                    AND date_format(bd.fecha, '%d-%m-%Y') = (date_format(curdate(), '%d-%m-%Y'))" ) or die(mysqli_error());
     $dia_li = $dia_l->fetch_assoc();
     $dia_liberadas = $dia_li['total_dia_liberadas'];
     
     $dia_reen    = $mysqli->query("SELECT COUNT(*) as total_dia_reensayes
                                              FROM
                                                	`arg_ordenes_bitacora_detalle` bd
                                                	LEFT JOIN arg_muestras_resultados mr
                                                    	ON bd.trn_id_rel = mr.trn_id
                                                  		AND bd.metodo_id = mr.metodo_id
                                              WHERE 
                                                    mr.reensaye = 1 
                                                    AND date_format(bd.fecha, '%d-%m-%Y') = date_format(curdate(), '%d-%m-%Y')" ) or die(mysqli_error());
     $dia_reens = $dia_reen->fetch_assoc();
     $dia_reens_hoy = $dia_reens['total_dia_reensayes'];
     
     $dia_pro    = $mysqli->query("SELECT COUNT(*) as total_dia_proceso
                                              FROM
                                                	`arg_ordenes_bitacora_detalle` bd
                                                	LEFT JOIN arg_muestras_resultados mr
                                                    	ON bd.trn_id_rel = mr.trn_id
                                                  		AND bd.metodo_id = mr.metodo_id
                                              WHERE 
                                                    etapa_id <> 11
                                                    AND date_format(bd.fecha, '%d-%m-%Y') = (date_format(curdate(), '%d-%m-%Y'))" ) or die(mysqli_error());
     $dia_proc = $dia_pro->fetch_assoc();
     $dia_proceso = $dia_proc['total_dia_proceso'];

    $hoy = date('d/m/Y');
    $mes = (date('F'));
    ?>
    
    <nav class="nav nav-tabs">
            <a class="nav-item nav-link active" data-toggle="tab" href="#home">Dashboard</a>
            <a class="nav-item nav-link" data-toggle="tab" href="#menu1">Batchs / Ordenes de Trabajo</a>
            <a class="nav-item nav-link"  data-toggle="tab" href="#menu2">Muestras</a>
            <a class="nav-item nav-link" data-toggle="tab" href="#menu3">Fases y Etapas</a>
    </nav>

    <div class="tab-content">
   
	<div id="home" class="tab-pane fade show active">
    <br/>
    
        <div class="breadcrumbs">
        
                <div class="page-header float-left">
                    <div class="page-title">
                        <h3>Muestras de Hoy: <?echo $hoy;?></h3>
                    </div>
                </div>
                
                 <div class="col-sm-1">
                 </div>
                  
                <div class="page-header float-center">
                    <div class="page-title">
                        <h3>Muestras del Mes: <?echo $mes;?></h3>
                    </div>
                  </div>  
                 
              
             
        </div>
                 
         </div>  

               
      <div class="col-sm-12">
            <br/>
            
            <div class="row">
                 <br />  <br />  <br />  <br /> 
             
                 <div class="col-xl-3 col-lg-3">
                       <div class="card bg-info">
                            <div class="card-body" class="text-secondary success-black">
                                <div class="stat-widget-one" >
                                    <div class="stat-icon dib"><i class="fa fa-flask text-warning border-warning"></i></div>
                                    <div class="stat-content dib">
                                        <div class="stat-text text-warning stat-digit text-bg">MUESTRAS RECIBIDAS</div>
                                        <div class="count stat-digit text-warning text-bg"><?echo $dia_total_rec;?></div>
                                    </div>
                                </div>
                            </div>
                       </div>
                                                    
                        <div class="card bg-success">
                            <div class="card-body">
                            <div class="stat-widget-one">
                                <div class="stat-icon dib"><i class="fa fa-check text-white border-white"></i></div>
                                    <div class="stat-content dib">
                                        <div class="stat-text stat-digit text-white text-bg">MUESTRAS LIBERADAS</div>
                                        <div class="count stat-digit text-white text-bg"><?echo $dia_liberadas?></div>
                                    </div>
                                </div>
                            </div>                                
                        </div>
                                                    
                        <div class="card bg-light">
                            <div class="card-body">
                            <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="fa fa-repeat text-danger border-danger"></i></div>
                                        <div class="stat-content dib">
                                            <div class="stat-text stat-digit text-danger text-bg"> REENSAYES HOY</div>
                                        <div class="count stat-digit text-danger text-bg"><?echo $dia_reens_hoy?></div>
                                    </div>
                            </div>
                            </div>                                
                        </div>
                                                    
                        <div class="card bg-warning">
                            <div class="card-body">
                            <div class="stat-widget-one">
                                    <div class="stat-icon dib"><i class="fa fa-refresh text-secondary border-secondary"></i></div>
                                    <div class="stat-content dib">
                                        <div class="stat-text stat-digit text-secondary"> Muestras en Proceso Hoy</div>
                                        <div class="count stat-digit text-secondary text-bg"><?echo $dia_proceso?></div>
                                    </div>
                            </div>
                            </div>                                
                        </div>
            
                    </div>       <!--Fin Tarjetas Dia--!>
                    
               
                 
                 
       <?
           $total_reensayes_glo = $mysqli->query("SELECT cantidad AS total_reensayes FROM `dash_unidades_muestras` WHERE unidad_id = 0 AND tipo = 1") or die(mysqli_error());
           $total_reensayes_mes = $total_reensayes_glo ->fetch_array(MYSQLI_ASSOC);
           $total_reensayes = $total_reensayes_mes['total_reensayes'];
           
           $total_liberadas_mes = $mysqli->query("SELECT COUNT(*) as total_liberadas
                                                  FROM
                                                    	`arg_ordenes_bitacora_detalle` bd
                                                    	LEFT JOIN arg_muestras_resultados mr
                                                        	ON bd.trn_id_rel = mr.trn_id
                                                      		AND bd.metodo_id = mr.metodo_id
                                                  WHERE
                                                        etapa_id = 11
                                                        AND MONTH(bd.fecha) = MONTH(CURDATE()) ") or die(mysqli_error());
           $total_liber_mes = $total_liberadas_mes ->fetch_array(MYSQLI_ASSOC);
           $total_lib_mes = $total_liber_mes['total_liberadas'];
           
           $total_proceso_mes = $mysqli->query("SELECT COUNT(*) as total_proceso
                                                  FROM
                                                    	`arg_ordenes_bitacora_detalle` bd
                                                    	LEFT JOIN arg_muestras_resultados mr
                                                        	ON bd.trn_id_rel = mr.trn_id
                                                      		AND bd.metodo_id = mr.metodo_id
                                                  WHERE
                                                        etapa_id <> 11
                                                        AND etapa_id > 3
                                                        AND MONTH(bd.fecha) = MONTH(CURDATE()) ") or die(mysqli_error());
           $total_proc_mes = $total_proceso_mes ->fetch_array(MYSQLI_ASSOC);
           $total_pro_mes = $total_proc_mes['total_proceso']-46;
       
       
       ?>    
       <div class="col-xl-3 col-lg-3">
            <div class="card bg-info">
            <div class="card-body">
                <div class="stat-widget-one">
                    <div class="stat-icon dib"><i class="fa fa-flask text-warning border-warning"></i></div>
                    <div class="stat-content dib">
                    <div class="stat-text stat-digit text-warning text-bg">MUESTRAS RECIBIDAS </div>
                        <div class="count stat-digit text-warning text-bg"><?echo $total_muestras_all;?></div>
                    </div>
                </div>
            </div>
            </div>
                                        
            <div class="card bg-success">
            <div class="card-body">
                <div class="stat-widget-one">
                    <div class="stat-icon dib"><i class="fa fa-check text-white border-white"></i></div>
                    <div class="stat-content dib">
                        <div class="stat-text stat-digit text-white text-bg">MUESTRAS LIBERADAS</div>
                      <h1>  <div class="count stat-digit text-white text-bg"><?echo $total_lib_mes;?></div></h1>
                        </div>
                    </div>
                </div>                                
            </div>
                                        
            <div class="card bg-light">
            <div class="card-body">
                <div class="stat-widget-one">
                <div class="stat-icon dib"><i class="fa fa-repeat text-danger border-danger"></i></div>
                    <div class="stat-content dib">
                        <div class="stat-text stat-digit text-danger text-bg"> REENSAYES </div>
                        <div class="count stat-digit text-danger text-bg"><?echo $total_reensayes;?></div>
                    </div>
                </div>
                </div>                                
            </div>
                                        
            <div class="card bg-warning">
            <div class="card-body ">
                <div class="stat-widget-one">
                    <div class="stat-icon dib"><i class="fa fa-refresh text-secondary border-secondary"></i></div>
                        <div class="stat-content dib">
                            <div class="stat-text stat-digit text-secondary text-bg"> MUESTRAS EN PROCESO</div>
                            <div class="count stat-digit text-secondary text-bg"><?echo $total_pro_mes;?></div>
                        </div>
                    </div>
                </div>                                
            </div>
            </div>
       <!--Fin de mes tarjetas del mes --!>
                 
                 
            <!-- Grafico --!>    
                <div class="col-sm-5 col-lg-5">   
                    <div class="chart-wrapper px-1">  
                        <canvas id="myChartDia" width="400" height="400"></canvas>
                    </div>
                 </div>      
                    
          
       <div class="col-sm-9">
            
                <div class="col-sm-12">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h3>Muestras Procesadas en el Mes por Unidad de Mina</h3>
                    </div>
                </div>
                </div>  
                <br/>
                <br/>
                <br/>  
                
                <?
                    $t_ens_lc = $mysqli->query("SELECT cantidad AS total_ensayes_lc FROM `dash_unidades_muestras` WHERE unidad_id = 1 AnD tipo = 0") or die(mysqli_error());
                    $to_ens_lc = $t_ens_lc ->fetch_array(MYSQLI_ASSOC);
                    $tot_ens_lc = $to_ens_lc['total_ensayes_lc'];
                    
                    $t_rens_lc = $mysqli->query("SELECT cantidad AS total_reensayes_lc FROM `dash_unidades_muestras` WHERE unidad_id = 1 AnD tipo = 1") or die(mysqli_error());
                    $to_reens_lc = $t_rens_lc ->fetch_array(MYSQLI_ASSOC);
                    $to_reensaye_lc = $to_reens_lc['total_reensayes_lc'];
                    
                    $t_lc = $mysqli->query("SELECT cantidad AS total_lc FROM `dash_unidades_muestras` WHERE unidad_id = 1 AnD tipo = -1") or die(mysqli_error());
                    $to_lc = $t_lc ->fetch_array(MYSQLI_ASSOC);
                    $total_lc = $to_lc['total_lc'];
                   
                    $t_ens_sa = $mysqli->query("SELECT cantidad AS total_ensayes_sa FROM `dash_unidades_muestras` WHERE unidad_id = 2 AnD tipo = 0") or die(mysqli_error());
                    $t_ens_san = $t_ens_sa ->fetch_array(MYSQLI_ASSOC);
                    $tot_ens_sa = $t_ens_san['total_ensayes_sa'];
                    
                    $t_rens_sa = $mysqli->query("SELECT cantidad AS total_reensayes_sa FROM `dash_unidades_muestras` WHERE unidad_id = 2 AnD tipo = 1") or die(mysqli_error());
                    $to_reens_sa = $t_rens_sa ->fetch_array(MYSQLI_ASSOC);
                    $to_reensaye_sa = $to_reens_sa['total_reensayes_sa'];
                    
                    $t_sa = $mysqli->query("SELECT cantidad AS total_sa FROM `dash_unidades_muestras` WHERE unidad_id = 2 AnD tipo = -1") or die(mysqli_error());
                    $to_sa = $t_sa ->fetch_array(MYSQLI_ASSOC);
                    $total_sa = $to_sa['total_sa'];
                    
                    $t_ens_ec = $mysqli->query("SELECT cantidad AS total_ensayes_ec FROM `dash_unidades_muestras` WHERE unidad_id = 3 AnD tipo = 0") or die(mysqli_error());
                    $to_ens_ec = $t_ens_ec ->fetch_array(MYSQLI_ASSOC);
                    $tot_ens_ec = $to_ens_ec['total_ensayes_ec'];
                    
                    $t_rens_ec = $mysqli->query("SELECT cantidad AS total_reensayes_ec FROM `dash_unidades_muestras` WHERE unidad_id = 3 AnD tipo = 1") or die(mysqli_error());
                    $to_reens_ec = $t_rens_ec ->fetch_array(MYSQLI_ASSOC);
                    $to_reensaye_ec = $to_reens_ec['total_reensayes_ec'];
                    
                    $t_ec = $mysqli->query("SELECT cantidad AS total_ec FROM `dash_unidades_muestras` WHERE unidad_id = 3 AnD tipo = -1") or die(mysqli_error());
                    $to_ec = $t_ec ->fetch_array(MYSQLI_ASSOC);
                    $total_ec = $to_ec['total_ec'];
                    
                    $t_ens_gl = $mysqli->query("SELECT cantidad AS total_ensayes FROM `dash_unidades_muestras` WHERE tipo = 0 and unidad_id = 0") or die(mysqli_error());
                    $t_ens_glo = $t_ens_gl ->fetch_array(MYSQLI_ASSOC);
                    $t_ens_glob = $t_ens_glo['total_ensayes'];
                    
                    $t_rens_gl = $mysqli->query("SELECT cantidad AS total_reensayes FROM `dash_unidades_muestras` WHERE tipo = 1 and unidad_id = 0") or die(mysqli_error());
                    $to_reens_global = $t_rens_gl ->fetch_array(MYSQLI_ASSOC);
                    $total_reensaye_gl = $to_reens_global['total_reensayes'];
                    
                    $t_glo = $mysqli->query("SELECT cantidad AS total FROM `dash_unidades_muestras` WHERE tipo = -1 and unidad_id = 0") or die(mysqli_error());
                    $t_globa = $t_glo ->fetch_array(MYSQLI_ASSOC);
                    $t_global = $t_globa['total'];
                
                ?>
                
                          
              <div class="col-lg-12 col-md-12">
                    <div class="col-lg-3 col-md-3">
                        <div class="social-box twitter">
                            <i> <?echo 'La Colorada';?></i>
                             <div class="weather-category twt-category">
                                    <ul>
                                            <li>
                                                <span class="count stat-digit text-secondary"> <?echo $tot_ens_lc; ?> </span>  <br/>  <? echo 'ENSAYES'; ?>
                                            </li>                                        
                                             <li>
                                                <span class="count stat-digit text-secondary"> <?echo $to_reensaye_lc; ?> </span>  <br/>  <? echo 'REENSAYES'; ?>
                                            </li>
                                            <li>
                                                <span class="count stat-digit text-secondary"> <?echo $total_lc; ?> </span>  <br/>  <? echo 'TOTAL'; ?>
                                            </li> 
                                   </ul>
                              </div>
                    <!--/Total de muestras LC box-->
                        </div>
                    </div>  
                    
                    <div class="col-lg-3 col-md-4">
                        <div class="social-box facebook">
                            <i> <?echo 'San Agustin';?></i>
                             <div class="weather-category twt-category">
                                    <ul>
                                            <li>
                                                <span class="count"> <?echo $tot_ens_sa; ?> </span>  <br/>  <? echo 'ENSAYES'; ?>
                                            </li>                                        
                                             <li>
                                                <span class="count"> <?echo $to_reensaye_sa; ?> </span>  <br/>  <? echo 'REENSAYES'; ?>
                                            </li>
                                            <li>
                                                <span class="count"> <?echo $total_sa; ?> </span>  <br/>  <? echo 'TOTAL'; ?>
                                            </li> 
                                   </ul>
                              </div>
                    <!--/Total de muestras LC box-->
                        </div>
                    </div>  
                    
                    <div class="col-lg-3 col-md-4">
                        <div class="social-box twitter">
                            <i> <?echo 'El Castilo ';?></i>
                              <div class="weather-category twt-category">
                     
                            <ul>
                                        <li>
                                            <span class="count"> <?echo $tot_ens_ec; ?> </span>  <br/>  <? echo 'ENSAYES'; ?>
                                        </li>
                                        
                                         <li>
                                            <span class="count"> <?echo $to_reensaye_ec; ?> </span>  <br/>  <? echo 'REENSAYES'; ?>
                                        </li>
                                        <li>
                                            <span class="count"> <?echo $total_ec; ?> </span>  <br/>  <? echo 'TOTAL'; ?>
                                        </li> 
                               </ul>
                    </div>
                    <!--/Total de muestras LC box-->
                    </div>
                    </div>  
                    
                    
                    
                    <div class="col-sm-1 col-lg-2 col-md-4">
                                  
                        <div class="social-box linkedin">
                                <i> <?echo 'Total';?></i>
                                <div class="weather-category twt-category">
                                    <ul>
                                        <li>
                                            <span class="count"> <?echo $t_ens_glob; ?> </span>  <br/>  <? echo 'ENSAYES'; ?>
                                        </li>
                                        <li>
                                            <span class="count"> <?echo $total_reensaye_gl; ?> </span>  <br/>  <? echo 'REENSAYES'; ?>
                                        </li>
                                        <li>
                                            <span class="count"> <?echo $t_ens_glob+$total_reensaye_gl; ?> </span>  <br/>  <? echo 'TOTAL'; ?>
                                        </li> 
                                    </ul>
                                </div>
                        </div>
                        </div> <!--/Total de todas las unidades box-->
                                
                    
                    
                    
                     
                     <div class="col-sm-4 col-lg-4">   
                            <div class="chart-wrapper px-1">    
                                <canvas id="myChart" width="400" height="400"></canvas>
                            </div>
                     </div>
                     
                     <div class="col-sm-4 col-lg-4">   
                            <div class="chart-wrapper px-1">    
                                <canvas id="myChartSA" width="400" height="400"></canvas>
                            </div>
                     </div>
                     
                     <div class="col-sm-4 col-lg-4">   
                            <div class="chart-wrapper px-1">    
                                <canvas id="myChartEC" width="400" height="400"></canvas>
                            </div>
                     </div>                 
                     <br />
                     <br />
               
             </div>
             
               <br/>
               <br/>
               <br/>
               <br/>
               <br/>
               
       </div>
       
      
      
      
      
      <!--Acumuladas al dia de hoy - anual--!>
      <div class="content mt-8">
            <br/><br/><br/>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-flat-color-2">
                    <div class="card-body pb-0">
                        <div class="dropdown float-right">
                            <button class="btn bg-transparent  text-light" type="button" id="dropdownMenuButton1" >
                                <i class="fa fa-flask"></i>
                            </button>
                           
                        </div>
                        <h4 class="mb-0">
                            <span class="count"><?echo $total_muestras_lc; ?></span>
                        </h4>
                        <p class="text-light">Unidad La Colorada</p>

                        <div class="chart-wrapper px-0" style="height:70px;" height="70">
                            <canvas id="widgetChart1"></canvas>
                        </div>

                    </div>

                </div>
            </div>
            <!--/.col-->

            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-flat-color-3">
                    <div class="card-body pb-0">
                        <div class="dropdown float-right">
                            <button class="btn bg-transparent text-light" type="button" id="dropdownMenuButton2" data-toggle="dropdown">
                                <i class="fa fa-flask"></i>
                            </button>
                            
                        </div>
                        <h4 class="mb-0">
                            <span class="count"><?echo $total_muestras_sa; ?></span>
                        </h4>
                        <p class="text-light">Undad San Agustin</p>

                        <div class="chart-wrapper px-0" style="height:70px;" height="70">
                            <canvas id="widgetChart2"></canvas>
                        </div>

                    </div>
                </div>
            </div>
            <!--/.col-->

            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-flat-color-4">
                    <div class="card-body pb-0">
                        <div class="dropdown float-right">
                            <button class="btn bg-transparent text-light" type="button" id="dropdownMenuButton3" data-toggle="dropdown">
                                <i class="fa fa-flask"></i>
                            </button>
                          
                        </div>
                        <h4 class="mb-0">
                            <span class="count"><?echo $total_muestras_ec; ?></span>
                        </h4>
                        <p class="text-light">Unidad El Castillo</p>

                    </div>

                    <div class="chart-wrapper px-0" style="height:70px;" height="70">
                        <canvas id="widgetChart3"></canvas>
                    </div>
                </div>
            </div>
            <!--/.col-->

            <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-flat-color-1">
                    <div class="card-body pb-0">
                        <div class="dropdown float-right">
                            <button class="btn bg-transparent text-light" type="button" id="dropdownMenuButton4" data-toggle="dropdown">
                                <i class="fa fa-flask"></i>
                            </button>
                            
                        </div>
                        <h4 class="mb-0">
                            <span class="count"><?echo $total_muestras_all; ?></span>
                        </h4>
                        <p class="text-light">Todas Las Unidades</p>

                        <div class="chart-wrapper px-3" style="height:70px;" height="70">
                            <canvas id="widgetChart4"></canvas>
                        </div>

                    </div>
                </div>
            </div>
            <!--/.col-->
       </div>
      
      
      
      
           
     <div class="col-sm-11">
      <br/>
      <br/>
      <br/>         
            <div class="row">        
                
                
                <div class="col-sm-6 col-lg-10">   
                    <div class="chart-wrapper px-1">    
                        <canvas id="myChartMultip" width="600" height="150"></canvas>
                    </div>
                </div>
                
            </div>
      </div>
                 
              </div>      <!--Fin row--!>  
        </div>        
                      
      </div>  <!--Fin primer tab = Home --!>   
                   
                   
      <div id="menu1" class="tab-pane fade">
            <br />  
         <div class="row">    
            <div class="container col-md-4 col-lg-4">
                 <h4> <span class="text-primary text-center font-weight-bold">TRAZABILIDAD NIVEL FASE</span></h4>            
            </div>
            <div class="formulario" >
                        <div id="content" class="col-md-12 col-lg-12">                     
                       
                    		<label for="caja_busqueda"></label>
                    		<input class="search_query form-control" type="text" name="caja_busqueda" id="caja_busqueda"  placeholder="Buscar BATCH..."></input>
                             
                    	</div>	
            </div>                       
           
           </div>           
            	<div class="col-md-12 col-lg-12" id="datos_batchs"></div>
       
            <!--    <div class="container col-md-11 col-lg-11">
                    
                    <div class="card" >
                        <div class="row d-flex justify-content-between">
                           <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <h6>BATCH <span class="text-primary font-weight-bold">EC000001</span></h6>                                
                                <h6>METODO <span class="text-primary font-weight-bold">EFAA30</span></h6>
                                <h6>Fecha de Inicio <span class="text-primary font-weight-bold">15/05/2022</span></h6>
                            </div>
                          </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-12">
                            <ul id="progressbar" class="text-center">
                                <li class="active step0"></li>
                                <li class="active step0"></li>  
                                 <li class="active step0"></li> 
                                <li class="step2"></li>
                            </ul>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="col-1">
                            </div>
                         
                            <div class="row justify-content-between top">
                                <div class="row d-flex icon-content">
                                   
                                    <div class="d-flex flex-column">
                                        <p class="font-weight-bold"><i class="fa fa-plus-circle" aria-hidden="true"> PREPARACION</i></p>                                       
                                    </div>
                                </div>
                                <div class="row d-flex icon-content">                                     
                                    <div class="d-flex flex-column">
                                        <a type="button" onclick="llama_datos();"><i class="fa fa-fire" aria-hidden="true"> ENSAYE A FUEGO</i></a>
                                    </div>
                                </div>
                                
                                <div class="row d-flex icon-content">                                   
                                    <div class="d-flex flex-column">
                                        <p class="font-weight-bold"><i class="fa fa-spinner" aria-hidden="true"> ABSORCION ATOMICA</i></p>
                                    </div>
                                </div>--!>
                                
                               <!-- <div class="row d-flex icon-content">-->
                                      <!--  <i class="fa fa-thermometer-quarter" aria-hidden="true"></i>-->
                                  <!--  <img class="icon" src="https://i.imgur.com/HdsziHP.png">  --!>
                                  <!--  <div class="d-flex flex-column">
                                        <p class="font-weight"><i class="fa fa-check-square" aria-hidden="true"> CONTROL CALIDAD</i></p>                                        
                                    </div>
                                 </div>
                                
                            </div>
                        </div>
                    </div>
                </div>-->
        </div>     <!--Fin segundo tab = Batchs --!> 
                    
      <div id="menu2" class="tab-pane fade">
              
                	<div class="formulario" >
                    <div id="content" class="col-md-6 col-lg-6"> 
                     
                    <h5>Buscar muestra:</h5>
                   
                		<label for="caja_busqueda"></label>
                		<input class="search_query form-control" type="text" name="caja_busqueda" id="caja_busqueda"  placeholder="Buscar..."></input>
                           <br />
                           <br />
                	</div>	
                    </div>                       
                	<div class="col-md-12 col-lg-12" id="datos2"></div>
      </div>    <!--Fin tercer tab = Muestras --!> 
         
      <div id="menu3" class="tab-pane fade">
                               
                    <div class="col-sm-9">
                        <div class="page-header float-left">
                            <div class="page-title">
                                <h3>TRAZABILIDAD DE BATCHS EN PROCESO: Fases y Etapas</h3>
                            </div>
                        </div>
                    </div>  
            
                    <div class="col-xl-5 col-lg-5">
                        <section class="card">
                            <div class="twt-feed blue-bg">
                                <div class="corner-ribon black-ribon">
                                    <i class="fa fa-plus-circle"></i>
                                </div>
                                <div class="fa fa-flask wtt-mark"></div>
        
                                <div class="media">
                                    
                                    <div class="media-body">
                                        <h2 class="text-white display-6">PREPARACION</h2>
                                        <p class="text-light">Total de muestras: <?echo $total_prepa; ?> </p>
                                    </div>
                                </div>
                            </div>
                            <div class="weather-category twt-category">
                               <ul>
                                            <li>
                                                <span class="count"> <?echo $total_prepa_normal; ?> </span>  <br/>  <? echo 'ENSAYES'; ?>
                                            </li>                                        
                                             <li>
                                                <span class="count"> <?echo $total_prepa_ree; ?> </span>  <br/>  <? echo 'REENSAYES'; ?>
                                            </li>
                                            <li>
                                                <span class="count"> <?echo $total_prepa; ?> </span>  <br/>  <? echo 'TOTAL'; ?>
                                            </li> 
                                   </ul>
                            </div>                   
                        <footer class="twt-footer">
                            <div class="col-12">                     
                                 <div class="col-6">  
                                       <h5>Ensayes</h5>                                                                            
                                       <div class="weather-category twt-category">                        
                                           <?
                                             while ($fila = $datos_preparacion->fetch_assoc()) {
                                                    $etapa    = $fila['etapa'];
                                                    $total_et =  $fila['total'];
                                            ?>
                                            <ul>
                                                <span class="count"> <?echo $total_et; ?> </span> <? echo $etapa; ?>                                       
                                            </ul>
                                            <?}?>    
                                      </div>
                                  </div>                          
                                  <div class="col-6">  
                                       <h5>Reensayes</h5>                                                                            
                                       <div class="weather-category twt-category">
                                           <?  while ($fila = $datos_preparacion_ree->fetch_assoc()) {
                                                    $etapa    = $fila['etapa'];
                                                    $total_et =  $fila['total'];
                                            ?>
                                                <span class="count"> <?echo $total_et; ?> </span> <? echo $etapa; ?>
                                            <?}?>
                                       </div>
                                   </div>  
                            </div>                      
                        </footer>
                    </section>
                </div>  
            </div>   <!--Fin Tab 4 Fases y etapas-->
       
    </div><!-- Fin tab - content -->
  
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/widget.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    
    <!--Graficos--!>
    <script>
    
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['EFAA30', 'HUM', 'VHAAAg', 'VHAACu', 'CNAAAu', 'CNAACu'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
   
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

const ctxe = document.getElementById('myChartEC').getContext('2d');
    const myChartEC = new Chart(ctxe, {
    type: 'doughnut',
    data: {
        labels: ['EFAA30', 'HUM', 'VHAAAg', 'VHAACu', 'CNAAAu', 'CNAACu'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
   
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

const ctxs = document.getElementById('myChartSA').getContext('2d');
    const myChartSA = new Chart(ctxs, {
    type: 'doughnut',
    data: {
        labels: ['EFAA30', 'HUM', 'VHAAAg', 'VHAACu', 'CNAAAu', 'CNAACu'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
   
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});


const ctxd = document.getElementById('myChartDia').getContext('2d');
    const myChartDia = new Chart(ctxd, {
    type: 'pie',
    data: {
        labels: ['EFAA30', 'HUM', 'VHAAAg', 'VHAACu', 'CNAAAu', 'CNAACu'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
   
    options: {
        legend: {
            position: 'right',
      },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});





const ctxmult = document.getElementById('myChartMultip').getContext('2d');
    const myChartMultip = new Chart(ctxmult, {
    type: 'bar',
    data: {
        labels: ['EFAA30', 'VHAAg', 'VHAAu', 'HUM', 'CIANAg', 'CIANAu'],
         datasets: [
    {
      label: ' La Colorada',
      data: [12, 19, 3, 5, 2, 3],
      backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
     
      borderWidth: 2,
      borderRadius: Number.MAX_VALUE,
      borderSkipped: false,
    },
    {
      label: 'El Castillo',
       data: [12, 19, 3, 5, 2, 3],
      backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
     
      borderWidth: 2,
      borderRadius: Number.MAX_VALUE,
      borderSkipped: false,
    }
  ]
    },
   
    options: {
            legend: {
            position: 'right',
      },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});



//Fin windget grafico 1 lc

var ctxwid4 = document.getElementById( "widgetChart2" );
    ctxwid4.height = 150;
    var myChartWid4 = new Chart( ctxwid4, {
        type: 'line',
        data: {
            labels: ['este', 'Februaryyyyy', 'March', 'April', 'May', 'June', 'July'],
            type: 'line',
            datasets: [ {
                data: [65, 59, 84, 84, 51, 55, 40],
                label: 'Dataset',
                backgroundColor: 'transparent',
                borderColor: 'rgba(255,255,255,.55)',
            }, ]
        },
        options: {

            maintainAspectRatio: false,
            legend: {
                display: false
            },
            responsive: true,
            scales: {
                xAxes: [ {
                    gridLines: {
                        color: 'transparent',
                        zeroLineColor: 'transparent'
                    },
                    ticks: {
                        fontSize: 2,
                        fontColor: 'transparent'
                    }
                } ],
                yAxes: [ {
                    display:false,
                    ticks: {
                        display: false,
                    }
                } ]
            },
            title: {
                display: false,
            },
            elements: {
                line: {
                    borderWidth: 1
                },
                point: {
                    radius: 4,
                    hitRadius: 10,
                    hoverRadius: 4
                }
            }
        }
    } );
    
    var ctxwid3 = document.getElementById( "widgetChart3" );
    ctxwid3.height = 150;
    var myChartWid3 = new Chart( ctxwid3, {
        type: 'line',
        data: {
            labels: ['este', 'Februaryyyyy', 'March', 'April', 'May', 'June', 'July'],
            type: 'line',
            datasets: [ {
                data: [65, 59, 84, 84, 51, 55, 40],
                label: 'Dataset',
                backgroundColor: 'transparent',
                borderColor: 'rgba(255,255,255,.55)',
            }, ]
        },
        options: {

            maintainAspectRatio: false,
            legend: {
                display: false
            },
            responsive: true,
            scales: {
                xAxes: [ {
                    gridLines: {
                        color: 'transparent',
                        zeroLineColor: 'transparent'
                    },
                    ticks: {
                        fontSize: 2,
                        fontColor: 'transparent'
                    }
                } ],
                yAxes: [ {
                    display:false,
                    ticks: {
                        display: false,
                    }
                } ]
            },
            title: {
                display: false,
            },
            elements: {
                line: {
                    borderWidth: 1
                },
                point: {
                    radius: 4,
                    hitRadius: 10,
                    hoverRadius: 4
                }
            }
        }
    } );    
        
    //WidgetChart 4
    var ctxch4 = document.getElementById( "widgetChart4" );
    ctxch4.height = 70;
    var myChartCh4 = new Chart( ctxch4, {
        type: 'bar',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            datasets: [
                {
                    label: "Ensayes",
                    data: [78, 81, 80, 45, 34, 12, 40, 75, 34, 89, 32, 68],
                    borderColor: "rgba(0, 123, 255, 0.9)",
                    //borderWidth: "0",
                    backgroundColor: "rgba(153,101,21)"
                },
                 {
                    label: "Reensayes",
                    data: [18, 22, 20, 26, 22, 14, 40, 25, 22, 20, 12, 18],
                    borderColor: "rgba(0, 145, 255, 0.9)",
                    //borderWidth: "0",
                    backgroundColor: "rgba(255,255,255,.3)"
                }
            ]
        },
        options: {
              maintainAspectRatio: false,
              legend: {
                display: false
            },
            scales: {
                xAxes: [{
                  display: false,
                  categoryPercentage: 1,
                  barPercentage: 0.5
                }],
                yAxes: [ {
                    display: false
                } ]
            }
        }
    } );

  <?echo ("grafico_widgetlc();");?>
 
</script>


</body>

</html>

       
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/buscar_orden.js"></script> 