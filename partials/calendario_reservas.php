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
    function inscribir(u_id, ubi_id, fecha)
            {
                alert('Usted ha quedado inscrito');
                $.ajax({
            		url: 'datos_reservar.php' ,
            		type: 'POST' ,
            		dataType: 'html',
            		data: {u_id: u_id, ubi_id:ubi_id, fecha: fecha},
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
    $hoy = date("m/d/y");   
    echo $hoy; 
    
    mysqli_multi_query ($mysqli, "CALL visor_reservas (".$hoy.")") OR DIE (mysqli_error($mysqli));
?>

<div class="container">
<div class="col-md-12 col-lg-12">
    <div class="col-md-4 col-lg-4">               
        <h4><b><?echo 'Seleccione su fecha de reserva:'?></b></h4>
    </div>
    <div class="col-md-2 col-lg-2">
        <input type="date" name="fecha" class="form-control" id="fecha" value="<?php echo date("Y-m-d");?>"/>
    </div>
     <div class="col-md-3 col-lg-3">
        <input type="submit" class="btn btn-primary" name="visita" id="visita" value="Ver Disponibilidad" />
    </div>
</div>
  
                            
    <div class="row">
    <div class="col-xl-12 col-sm-12 col-md-12 col-ld-12">
    
        <div class="col-xl-1 col-sm-1 col-md-1 col-ld-1">
              <div class="card text-white text-xs-center bg-secondary o-hidden h-80">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fa fa-archive fa-3x"></i>                    
                  </div>
                </div>
          </div>
        </div>
     
          <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2">
              <div class="card text-white text-xl-center bg-warning o-hidden h-80">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fa fa-coffee fa-3x"></i>
                    <a>Cocina</a>
                  </div>
                </div>
          </div>
        </div>
        
          <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2">
              <div class="card text-white text-xl-center bg-primary o-hidden h-80">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fa  fa-users fa-4x"></i>
                    <a>Sala de Juntas</a>
                  </div>
                </div>
          </div>
        </div>
        <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2">
              <div class="card text-white text-xl-center bg-secondary o-hidden h-80">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fa fa-shield fa-3x"></i>
                    <a>Guardia</a>
                  </div>
                </div>
          </div>
        </div>
    </div>
    </div>
     
    <!-- Segundo Row--!>    
    <div class="row">
        <div class="col-xl-12 col-sm-12 col-md-12 col-ld-12">
        
            <div class="col-xl-1 col-sm-1 col-md-1 col-ld-1">
                  <div class="card text-white text-xl-center bg-secondary o-hidden h-80">
                    <div class="card-body">
                      <div class="card-body-icon">
                        <i class="fa fa-archive fa-3x"></i>     
                      </div>
                    </div>
              </div>
            </div>
            
            <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2 ">
              <div class="card text-white text-center bg-info o-hidden h-100">
                        <div class="card-body">
                          <div class="card-body-icon big">
                            <i class="fa fa-user-circle-o fa-3x"></i>
                            <br />
                            <?echo 'IT OFICINA';?> 
                          </div>
                        </div>                                          
                  </div>
            </div>
              
            <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2">
                  <div class="card text-white text-xl-left bg-info o-hidden h-80">
                    <div class="card-body">
                      <div class="card-body-icon">
                        <i class="fa fa-server fa-3x"></i>
                        <a>SITE</a>
                      </div>
                    </div>
              </div>
            </div>
            
            <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2 ">
              <div class="card text-white text-center bg-success o-hidden h-100">
                        <div class="card-body">
                          <div class="card-body-icon big">
                            <i class="fa fa-building fa-2x"></i>
                            <br />
                            <?echo 'OFICINA 1: '.$total;?> 
                          </div>
                        </div>
                         <div class="btn-group">
                        <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                           <? echo $row['nombre']." ".$row['fecha'];?>
                         </button>
                            <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                            <ul class="dropdown-menu" role="menu">
                                <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                            </ul>
                       </div>                   
                  </div>
            </div>
    </div>
    </div>
    
    <!--Tercer row--!>    
    <div class="row">    
    <div class="col-xl-12 col-sm-12 col-md-12 col-ld-12">
    
          <div class="col-xl-1 col-sm-1 col-md-1 col-ld-1">
              <div class="card text-white text-xl-center bg-secondary o-hidden h-80">
                <div class="card-body">
                  <div class="card-body-icon">
                   <i class="fa fa-archive fa-3x"></i>     
                  </div>
                </div>
              </div>
          </div>
          
          <div class="col-xl-8 col-sm-6 col-md-6 col-ld-6">
              <div class="card text-blue text-xl-left o-hidden h-80">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fa fa-bars fa-3x"></i>
                    <a>Limpieza</a>
                  </div>
                </div>
                </div>
          </div>
    </div>
    </div>
    
    <!--Cuarto row--!>    
    <div class="row">    
    <div class="col-xl-12 col-sm-12 col-md-12 col-ld-12">
    
          <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2">
              <div class="card text-white text-xl-center bg-primary o-hidden h-80">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fa fa-phone fa-3x"></i>
                    <a>RECEPCION</a>
                  </div>
                </div>
              </div>
          </div>
          
           <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2 ">
              <div class="card text-white text-center bg-success o-hidden h-100">
                        <div class="card-body">
                          <div class="card-body-icon big">
                            <i class="fa fa-building fa-2x"></i>
                            <br />
                            <?echo 'OFICINA 3 '.$total;?> 
                          </div>
                        </div>
                         <div class="btn-group">
                        <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                           <? echo $row['nombre']." ".$row['fecha'];?>
                         </button>
                            <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                            <ul class="dropdown-menu" role="menu">
                                <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                            </ul>
                       </div>                   
                  </div>
            </div>
          
           <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2 ">
              <div class="card text-white text-center bg-success o-hidden h-100">
                        <div class="card-body">
                          <div class="card-body-icon big">
                            <i class="fa fa-building fa-2x"></i>
                            <br />
                            <?echo 'OFICINA 2 '.$total;?> 
                          </div>
                        </div>
                         <div class="btn-group">
                        <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                           <? echo $row['nombre']." ".$row['fecha'];?>
                         </button>
                            <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                            <ul class="dropdown-menu" role="menu">
                                <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                            </ul>
                       </div>                   
                  </div>
            </div>
            
            <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2">
              <div class="card text-white text-xl-center bg-info o-hidden h-80">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fa fa-user-circle-o fa-3x"></i>
                    <a>TESORERIA</a>
                  </div>
                </div>
              </div>
          </div>
    </div>
    </div>
    
    <!--Pasillo tesoreria--!>
    <div class="row">
    <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2">
    </div>
     <div class="col-xl-8 col-sm-8 col-md-8 col-ld-8">
              <div class="card text-white text-xl-center  o-hidden h-80">
                <div class="card-body">
                  <div class="card-body-icon">
                  
                  </div>
                </div>
              </div>
     </div>
     </div>
    
     <!--Quinto row--!>    
    <div class="row">       
    <div class="col-xl-12 col-sm-12 col-md-12 col-lg-12">
   
          <div class="col-xl-1 col-sm-1 col-md-1 col-lg-1">          
              <div class="card text-white text-xl-center bg-secondary o-hidden h-80">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fa fa-bars fa-3x"></i>
                    <a>AIRE</a>
                  </div>
                </div>
              </div>
          </div>
          
          <div class="col-xl-1 col-sm-1 col-md-1 col-lg-1">
          </div>
          
        <div class="container">   
           <div class="col-xl-7 col-sm-7 col-md-7 col-lg-7">
           
                <div class="col-xl-2 col-sm-2 col-md-2 col-lg-2 ">                          
                      <div class="card text-white text-center bg-success o-hidden h-40">
                                <div class="card-body">
                                  <div class="card-body-icon">
                                    <i class="fa fa-user-o fa-2x"></i>
                                    <br />
                                    <?echo 'CUB 1 '.$total;?> 
                                  </div>
                                </div>
                                 <div class="btn-group">
                                <button type="button" class="btn btn-success  btn-block dropdown-toggle" data-toggle="dropdown">
                                   <? echo $row['nombre']." ".$row['fecha'];?>
                                 </button>
                                    <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                                    <ul class="dropdown-menu" role="menu">
                                        <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h6>Reservar día</h6></a> </li>  
                                        <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h6>Reservar horas</h6></a> </li>                          
                                    </ul>
                               </div>                   
                          </div>
                         
                      <div class="card text-white text-center bg-success o-hidden h-40">
                                <div class="card-body">
                                  <div class="card-body-icon big">
                                    <i class="fa fa-user-o fa-2x"></i>
                                    <br />
                                    <?echo 'CUB 3'.$total;?> 
                                  </div>
                                </div>
                                 <div class="btn-group">
                                <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                   <? echo $row['nombre']." ".$row['fecha'];?>
                                 </button>
                                    <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                                    <ul class="dropdown-menu" role="menu">
                                        <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                        <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                    </ul>
                               </div>                   
                          </div>
                </div>
                
                <div class="col-xl-2 col-sm-2 col-md-2 col-lg-2"> 
                      <div class="card text-white text-center bg-success o-hidden h-40">
                                <div class="card-body">
                                  <div class="card-body-icon big">
                                    <i class="fa fa-user-o fa-2x"></i>
                                    <br />
                                    <?echo 'CUB 2 '.$total;?> 
                                  </div>
                                </div>
                                 <div class="btn-group">
                                <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                   <? echo $row['nombre']." ".$row['fecha'];?>
                                 </button>
                                    <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                                    <ul class="dropdown-menu" role="menu">
                                        <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                        <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                    </ul>
                               </div>                   
                          </div>
                       
                      <div class="card text-white text-center bg-success o-hidden h-40">
                                <div class="card-body">
                                  <div class="card-body-icon big">
                                    <i class="fa fa-user-o fa-2x"></i>
                                    <br />
                                    <?echo 'CUB 4 '.$total;?> 
                                  </div>
                                </div>
                                 <div class="btn-group">
                                <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                   <? echo $row['nombre']." ".$row['fecha'];?>
                                 </button>
                                    <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                                    <ul class="dropdown-menu" role="menu">
                                        <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                        <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                    </ul>
                               </div>                   
                          </div>
                 </div>
                 
                 <div class="col-xl-1 col-sm-1 col-md-1 col-ld-1">    
                 </div>
            
                 <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3">            
                      <div class="card text-white text-center bg-success o-hidden h-40">
                                <div class="card-body">
                                  <div class="card-body-icon big">
                                    <i class="fa fa-user-o fa-2x"></i>
                                    <br />
                                    <?echo 'CUB 5'.$total;?> 
                                  </div>
                                </div>
                                 <div class="btn-group">
                                <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                   <? echo $row['nombre']." ".$row['fecha'];?>
                                 </button>
                                    <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                                    <ul class="dropdown-menu" role="menu">
                                        <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                        <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                    </ul>
                               </div>                   
                          </div>
                       
                      <div class="card text-white text-center bg-success o-hidden h-40">
                                <div class="card-body">
                                  <div class="card-body-icon big">
                                    <i class="fa fa-user-o fa-2x"></i>
                                    <br />
                                    <?echo 'CUB 7'.$total;?> 
                                  </div>
                                </div>
                                 <div class="btn-group">
                                <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                   <? echo $row['nombre']." ".$row['fecha'];?>
                                 </button>
                                    <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                                    <ul class="dropdown-menu" role="menu">
                                        <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                        <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                    </ul>
                               </div>                   
                          </div>
                 </div>
                 
                 
                 <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">            
                      <div class="card text-white text-center bg-success o-hidden h-40">
                                <div class="card-body">
                                  <div class="card-body-icon big">
                                    <i class="fa fa-user-o fa-2x"></i>
                                    <br />
                                    <?echo 'CUB 7 '.$total;?> 
                                  </div>
                                </div>
                                 <div class="btn-group">
                                <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                   <? echo $row['nombre']." ".$row['fecha'];?>
                                 </button>
                                    <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                                    <ul class="dropdown-menu" role="menu">
                                        <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                        <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                    </ul>
                               </div>                   
                          </div>
                       
                      <div class="card text-white text-center bg-success o-hidden h-40">
                                <div class="card-body">
                                  <div class="card-body-icon big">
                                    <i class="fa fa-user-o fa-2x"></i>
                                    <br />
                                    <?echo 'CUB 8 '.$total;?> 
                                  </div>
                                </div>
                                 <div class="btn-group">
                                <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                   <? echo $row['nombre']." ".$row['fecha'];?>
                                 </button>
                                    <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                                    <ul class="dropdown-menu" role="menu">
                                        <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                        <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                    </ul>
                               </div>                   
                          </div>
                 </div>
          </div>
        </div>      <!--Fin quinto row--!> 
           
   <!--Pasillo recepcion--!>
    <div class="row">
    <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2">
    </div>
     <div class="col-xl-8 col-sm-8 col-md-8 col-ld-8">
              <div class="card text-white text-xl-center  o-hidden h-80">
                <div class="card-body">
                  <div class="card-body-icon">
                  
                  </div>
                </div>
              </div>
     </div>
     </div>
           
           <!--Inicio sexto row--!>
            <!--Cuarto row--!>    
    <div class="row">    
    <div class="col-xl-12 col-sm-12 col-md-12 col-ld-12">
    
          <div class="col-xl-1 col-sm-1 col-md-1 col-ld-1">
              <div class="card text-white text-xl-center bg-secondary o-hidden h-80">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fa fa-male fa-3x"></i>
                    <i class="fa fa-female fa-3x"></i>
                  </div>
                </div>
              </div>
          </div>
          
          <div class="col-xl-1 col-sm-1 col-md-1 col-ld-1">
          </div>
          
          
          <div class="container">          
          
                       <div class="col-xl-11 col-sm-11 col-md-11 col-ld-11">
                            <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">  
                                <div class="card text-white text-center bg-success o-hidden h-80">
                                        <div class="card-body">
                                          <div class="card-body-icon big">
                                            <i class="fa fa-building fa-2x"></i>
                                            <br />
                                            <?echo 'OFICINA 4 '.$total;?> 
                                          </div>
                                        </div>
                                         <div class="btn-group">
                                        <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                           <? echo $row['nombre']." ".$row['fecha'];?>
                                         </button>
                                            <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                                            <ul class="dropdown-menu" role="menu">
                                                <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                                <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                            </ul>
                                       </div>                   
                                  </div>
                                </div>
                           
                            <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
                              <div class="card text-white text-center bg-success o-hidden h-80">
                                    <div class="card-body">
                                      <div class="card-body-icon big">
                                        <i class="fa fa-building fa-2x"></i>
                                        <br />
                                        <?echo 'OFICINA 5 '.$total;?> 
                                      </div>
                                    </div>
                                     <div class="btn-group">
                                    <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                       <? echo $row['nombre']." ".$row['fecha'];?>
                                     </button>
                                        <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                                        <ul class="dropdown-menu" role="menu">
                                            <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                            <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                        </ul>
                                   </div>                   
                              </div>
                            </div>
                        
                        <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
                          <div class="card text-white text-center bg-success o-hidden h-30">
                                    <div class="card-body">
                                      <div class="card-body-icon big">
                                        <i class="fa fa-building fa-2x"></i>
                                        <br />
                                        <?echo 'OFICINA 6 '.$total;?> 
                                      </div>
                                    </div>
                                     <div class="btn-group">
                                    <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                       <? echo $row['nombre']." ".$row['fecha'];?>
                                     </button>
                                        <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                                        <ul class="dropdown-menu" role="menu">
                                            <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                            <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                        </ul>
                                   </div>                   
                              </div>
                        </div>
                                
                    <div class="container">  <!--Grupo de cubos del 12 al 13--!>
                     <div class="col-xl-1 col-sm-1 col-md-1 col-ld-1">   
                          <div class="card text-white text-center bg-success o-hidden h-30">
                                    <div class="card-body">
                                      <div class="card-body-icon big">
                                        <i class="fa fa-user-o fa-2x"></i>
                                        <br />
                                        <?echo 'CUB 12 '?> 
                                      </div>
                                    </div>
                                     <div class="btn-group">
                                    <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                       <? echo $row['nombre']." ".$row['fecha'];?>
                                     </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                            <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                        </ul>
                                   </div>                   
                              </div>
                       
                          <div class="card text-white text-center bg-success o-hidden h-30">
                                <div class="card-body">
                                  <div class="card-body-icon big">
                                    <i class="fa fa-user-o fa-2x"></i>
                                    <br />
                                    <?echo 'CUB 13'.$total;?> 
                                  </div>
                                </div>
                                 <div class="btn-group">
                                <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                   <? echo $row['nombre']." ".$row['fecha'];?>
                                 </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                        <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                    </ul>
                               </div>                   
                          </div>
                    
                          
                 </div> 
                 </div> <!--Fin de container grupo de cubos 12 Y 13--!>
                        
          </div>  
                 
          </div>
          </div>      
                 
             
        </div>
        </div>
            
           
    </div>
         <span class="clearfix"></span>
         
    
    
    <!--Septimo row Rec Humanos--!>    
    <div class="row">    
    <div class="col-xl-12 col-sm-12 col-md-12 col-ld-12">
    
          <div class="col-xl-1 col-sm-1 col-md-1 col-ld-1">             
          </div>
          
          <div class="container">         
          
                       <div class="col-xl-11 col-sm-11 col-md-11 col-ld-11">
                            <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">  
                                <div class="card text-white text-center bg-info o-hidden h-80">
                                        <div class="card-body">
                                          <div class="card-body-icon big">
                                            <i class="fa fa-user-circle-o fa-2x"></i>
                                            <br />
                                            <?echo 'RECURSOS HUMANOS'.$total;?> 
                                          </div>
                                        </div>                                                         
                                  </div>
                                </div>
                           
                            <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3">
                            <div class="container">
                            
                            <div class="col-xl-5 col-sm-5 col-md-5 col-ld-5">
                               <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3">
                               <div class="card text-white text-center bg-success o-hidden h-80">
                              
                                    <div class="card-body">
                                      <div class="card-body-icon big">
                                        <i class="fa fa-user-o fa-2x"></i>
                                        <br />
                                        <?echo 'CUB 9'.$total;?> 
                                      </div>
                                    </div>
                                     <div class="btn-group">
                                    <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                       <? echo $row['nombre']." ".$row['fecha'];?>
                                     </button>
                                        <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                                        <ul class="dropdown-menu" role="menu">
                                            <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                            <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                        </ul>
                                   </div>                   
                              </div>
                              </div>
                              
                              
                              <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3">
                              <div class="card text-white text-center bg-success o-hidden h-30">
                                        <div class="card-body">
                                          <div class="card-body-icon big">
                                            <i class="fa fa-user-o fa-2x"></i>
                                            <br />
                                            <?echo 'CUB 10'.$total;?> 
                                          </div>
                                        </div>
                                         <div class="btn-group">
                                        <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                           <? echo $row['nombre']." ".$row['fecha'];?>
                                         </button>
                                            <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                                            <ul class="dropdown-menu" role="menu">
                                                <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                                <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                            </ul>
                                       </div>                   
                                  </div>
                            </div>
                            
                            <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3">
                              <div class="card text-white text-center bg-success o-hidden h-30">
                                        <div class="card-body">
                                          <div class="card-body-icon big">
                                            <i class="fa fa-play-circle-o fa-2x"></i>
                                            <br />
                                            <?echo 'CUB 11'.$total;?> 
                                          </div>
                                        </div>
                                         <div class="btn-group">
                                        <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                           <? echo $row['nombre']." ".$row['fecha'];?>
                                         </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                                <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                            </ul>
                                       </div>                   
                                  </div>
                            </div>
                            </div>
                       
                                
                   <!--Grupo de cubos del 12 al 13--!>
                     <div class="col-xl-1 col-sm-1 col-md-1 col-ld-1">   
                          <div class="card text-white text-center bg-success o-hidden h-30">
                                    <div class="card-body">
                                      <div class="card-body-icon big">
                                        <i class="fa fa-user-o fa-2x"></i>
                                        <br />
                                        <?echo 'CUB 14 '?> 
                                      </div>
                                    </div>
                                     <div class="btn-group">
                                    <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                       <? echo $row['nombre']." ".$row['fecha'];?>
                                     </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                            <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                        </ul>
                                   </div>                   
                              </div>
                       
                          <div class="card text-white text-center bg-success o-hidden h-30">
                                <div class="card-body">
                                  <div class="card-body-icon big">
                                    <i class="fa fa-user-o fa-2x"></i>
                                    <br />
                                    <?echo 'CUB 15'.$total;?> 
                                  </div>
                                </div>
                                 <div class="btn-group">
                                <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                   <? echo $row['nombre']." ".$row['fecha'];?>
                                 </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                        <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                    </ul>
                               </div>                   
                          </div>
                    
                    </div> <!--Fin de container grupo de cubos 14 Y 15--!>
                    
                    
                    </div>
                    </div>
                     
                        
          </div>  
                 
          </div>
          </div>     
        </div> <!--Fin Row--!>
        
    <!--Octavo Row Nóminas-sala juntas--!>    
    <div class="row">    
    <div class="col-xl-12 col-sm-12 col-md-12 col-ld-12">
    
          <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2">             
          </div>
          
          <div class="container">         
          
                       <div class="col-xl-10 col-sm-10 col-md-10 col-ld-10">
                            <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">  
                                <div class="card text-white text-center bg-success o-hidden h-80">
                                        <div class="card-body">
                                          <div class="card-body-icon big">
                                            <i class="fa fa-building fa-2x"></i>
                                            <br />
                                            <?echo 'OFICINA 7'.$total;?> 
                                          </div>
                                        </div>
                                         <div class="btn-group">
                                        <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown">
                                           <? echo $row['nombre']." ".$row['fecha'];?>
                                         </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li> <a href='' id='aLink' onclick="inscribir(<?echo $u_id.",".$cal_id_in ;?>);"><h5>Reservar día</h5></a> </li>  
                                                <li> <a href="inscritos.php?cal_id=<?echo $cal_id_in;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Reservar horas</h5></a> </li>                          
                                            </ul>
                                       </div>                   
                                  </div>
                                </div>
                                
                                <div class="col-xl-2 col-sm-2 col-md-2 col-ld-2 ">  
                                <div class="card text-white text-center bg-info o-hidden h-80">
                                        <div class="card-body">
                                          <div class="card-body-icon big">
                                            <i class="fa fa-user-circle-o fa-2x"></i>
                                            <br />
                                            <?echo 'NOMINAS'.$total;?> 
                                          </div>
                                        </div>                                                         
                                  </div>
                                </div>
                                
                                <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">  
                                <div class="card text-white text-center bg-primary o-hidden h-80">
                                        <div class="card-body">
                                          <div class="card-body-icon big">
                                            <i class="fa fa-users fa-2x"></i>
                                            <br />
                                            <?echo 'SALA DE JUNTAS'.$total;?> 
                                          </div>
                                        </div>                                                       
                                  </div>
                                </div>
                            
                    
                    </div>
                     </div>
                     
                     
              
              
              
                     
                     
                        
          </div>  
                 
          </div>
          </div>      
                 
             
        </div> <!--Fin Row--!>
        
        
        
        
        </div>
            
           
    </div>
      
    


  </div> 
    <?
 }
 
    ?> 
<!--<script type="text/javascript" src="js/popper/src/popper.js"></script>-->
<!--<script type="text/javascript" src="js/vehiculos.js"></script>-->
         
          

