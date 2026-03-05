<style type="text/css">
	.izq{
		background-color:;
	}
	.derecha{
		background-color:;
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
      max-width: 100%;
    }
</style>

<script>
    function inscribir(u_id, cal_id)
            {
                alert('Usted ha quedado inscrito');
                $.ajax({
            		url: 'datos_inscr.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {u_id: u_id, cal_id: cal_id},
            	})
            	.done(function(respuesta){
            		resp = html(respuesta);                    
                    console.log(respuesta);
                    alert(resp)
              })
            }  

</script>
<?php 
//$_SESSION['LoggedIn'] = 1; 

if(($_SESSION['LoggedIn']) <> ''){
   
    $u_id = $_SESSION['u_id'];
    $tipo_motivo = $_GET['motivo'];
    $unidad_mina_sel = $_GET['unidad'];
    $mes = 11;
    
    $motivo_vis = $mysqli->query("SELECT nombre FROM arg_actividad WHERE act_id = ".$tipo_motivo) or die(mysqli_error());
    $mot_visita = $motivo_vis->fetch_assoc();
    $motivo_visita = $mot_visita['nombre'];
    
    mysqli_multi_query ($mysqli, "CALL visor_calendario (".$mes.")") OR DIE (mysqli_error($mysqli));
?>

<div class="container">
    
    <br />
    <br />
    <h4><?if ($tipo_motivo > 0) echo 'Su motivo de visita: '.$motivo_visita.' requiere Inducción de Seguridad Completa.'?></h4>
  
    <h4><b><?echo 'Por favor seleccione su fecha de registro para la inducción:'?></b></h4>
    <br />
    <div class="row">
    <?
    $i = 0;
  ///  while ($i < 9){  
    if ($result = mysqli_store_result($mysqli)) {         
         while ($row = mysqli_fetch_assoc($result)) {
            $total = (10-$row['total']);
                $cal_id_in = $row['cal_id'];
           if ($i == 4){
            ?>  
                <br />
                <br />
                <br />
                <div class="row">                
             <?
           }
           
           
           
           //mysqli_multi_query ($mysqli, "CALL arg_inscritos (".$cal_id_in.")") OR DIE (mysqli_error($mysqli));
?>
            
          <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2 ">
          <div class="card text-white text-center bg-info o-hidden h-100">
                    <div class="card-body">
                      <div class="card-body-icon big">
                        <i class="fa fa-play-circle-o fa-2x"></i>
                        <br />
                        <?echo 'Lugares: '.$total;?> 
                      </div>
                    </div>
                     <div class="btn-group">
                    <button type="button" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">
                       <? echo $row['nombre']." ".$row['fecha'];?>
                     </button>
                        <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                        <ul class="dropdown-menu" role="menu">
                            <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Solicitar Inducción</h5></a> </li>  
                            <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Ver inscritos</h5></a> </li>                          
                        </ul>
                   </div>                   
              </div>
          </div>
       
     
    <? 
         $i = $i+1;
        if ($i == 4)
            {
                ?>   </div> <?
            } 
        }         
    }//Fin if result
    ?>
    </div>
    </div> 
    <?
 }
 
    ?> 
<!--<script type="text/javascript" src="js/popper/src/popper.js"></script>-->
<!--<script type="text/javascript" src="js/vehiculos.js"></script>-->
         
          

