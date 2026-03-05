
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
<?php 
$_SESSION['LoggedIn'] = 1; 

if(($_SESSION['LoggedIn']) <> ''){
    $u_id = $_SESSION['u_id'];  
    $unidad_mina_sel = $_GET['unidad'];
?>
   
 <div class="container">
    <div class="row">
        <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-info o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon big">
                <i class="fa fa-building-o fa-2x"></i>
              </div>
            </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        EMPRESA
                     </button>
                       <ul class="dropdown-menu" role="menu">
                            <li> <a href="empresa.php?tipo=0&unidad=<?echo $unidad_mina_sel;?>"><h5>Datos generales</h5></a> </li>
                            <li class="divider"></li> 
                            <li> <a href="doc_personas.php?u_id=<?echo $u_id;?>&unidad=<?echo $unidad_mina_sel;?>"><h5>Documentación de Personas</h5></a> </li>
                            <li> <a href="doc_vehiculos.php?unidad=<?echo $unidad_mina_sel;?>"><h5>Documentación de Vehículos</h5></a> </li>   
                        </ul>
                   </div>
              </div>
          </div>  
          
        <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-secondary o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon">
                <i class="fa fa-bars fa-2x"></i>
              </div>
            </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        BITÁCORA
                     </button>
                        <ul class="dropdown-menu" role="menu">
                            <li> <a href="app.php"><h5>Crear Visita</h5></a> </li>
                            <li class="divider"></li>                   
                            <li> <a  href="dashboard.php?tipo=1&unidad=<?echo $unidad_mina_sel;?>"><h5>Visitas</h5></a> </li>
                            <li> <a  href="#"><h5>Cerradas</h5></a></li>
                        </ul>
                   </div>
              </div>
          </div>
          
          <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-success o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon big">
                <i class="fa fa-play-circle-o fa-2x"></i>
              </div>
            </div>
                    <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        CURSOS
                     </button>
                        <ul class="dropdown-menu" role="menu">                          
                           <li> <a href="cursos.php?tipo=0&unidad=<?echo $unidad_mina_sel;?>"><h5>Visita General</h5></a> </li>                                              
                            <li> <a  href="cursos.php?tipo=1&unidad=<?echo $unidad_mina_sel;?>"><h5>Trabajos de altos riesgos</h5></a> </li>
                            <li> <a  href="calendario.php?motivo=0&unidad=<?echo $unidad_mina_sel;?>"><h5>Calendarios</h5></a></li>
                            <li> <a  href="calificaciones.php?uid_califica=2&trn_id=1"><h5>Mis cursos</h5></a></li>
                        </ul>
                   </div>
                
              </div>
          </div>
          
          <div class="col-xl-3 col-sm-3 col-md-3 col-ld-3 ">
          <div class="card text-white text-xl-center bg-warning o-hidden h-80">
            <div class="card-body">
              <div class="card-body-icon big">
                <i class="fa fa-medkit fa-2x"></i>
              </div>
            </div>
                    <div class="btn-group">
                    <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                        C O V I D   19
                     </button>
                        <!--<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">-->
                        <ul class="dropdown-menu" role="menu">
                            <li> <a href="#"><h5>Información</h5></a> </li>   
                        </ul>
                   </div>
                
              </div>
          </div>
       
      </div>
    </div> 
    
        
       <?
        
        //echo $org_id;
        //if ($u_id_tipo == 'empleado'){
           $mysqli -> set_charset("utf8");  
           $tipo = $_GET['tipo'];
           if ($tipo == 0 or $tipo == 1){  //Lista de empresas
                mysqli_multi_query ($mysqli, "CALL visor_cursos (".$tipo.")") OR DIE (mysqli_error($mysqli));
                ?>
                    <br /> <br />
                  <div class="container">                                                
                 <? 
                 $html_en = "<table class='table table-bordered' id='encabezado'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Tipo Inducción</th>
                                    <th scope='col'>Descripción</th>
                                    <th scope='col'>Vigencia</th>
                                    ";
                                    if ($tipo == 0){                                                
                                        $html_en .= "<th scope='col'>Curso Online</th>";
                                    }
                                  $html_en .= "</tr>
                              </thead>
                              <tbody>";
                               if ($result = mysqli_store_result($mysqli)) {                
                                      while ($row = mysqli_fetch_assoc($result)) {
                                             $html_en.="<tr>
                                                <td> ".$row["induccion"]."</td>
                                                <td> ".$row["nombre"]."</td>
                                                <td> ".$row["vigencia"]."</td>";
                                                if ($tipo == 0){                                                
                                                $html_en .= "<td> 
                                                    <div class='btn-group'>
                                                    <button type='button' class='btn btn-info'>
                                                         <a href='curso_online.php?curso_id=".$row['curso_id']."'><h5>".'Iniciar'."</h5></a> </li>
                                                    </button>                                                    
                                                    
                                                    </div>";
                                                 }
                                                $html_en .= "</td>
                                             </tr>";
                                      }
                                      mysqli_free_result($result);
                                }
                $html_en.="</tbody></table>";
                echo ("$html_en");      
                ?>
             </div>  
             <?            
           }
           
           // Vehículos
            if ($tipo == 2){
                mysqli_multi_query ($mysqli, "CALL visor_vehiculos (".$org_id.")") OR DIE (mysqli_error($mysqli));
                ?>
                  <br /> <br />
                  <div class="container">                                                
                 <? 
                 $html_per = "<table class='table table-bordered' id='encabezadop'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Empresa</th>
                                    <th scope='col'>Placas</th>
                                    <th scope='col'>Marca</th>
                                    <th scope='col'>Modelo</th>
                                    <th scope='col'>Color</th>
                                    <th scope='col'>Póliza</th>
                                  </tr>
                              </thead>
                              <tbody>";
                               if ($result_per = mysqli_store_result($mysqli)) {                
                                      while ($row = mysqli_fetch_assoc($result_per)) {
                                             $html_per.="<tr>
                                                <td> ".$row["empresa"]."</td>
                                                <td> ".$row["placas"]."</td>
                                                <td> ".$row["marca"]."</td>
                                                <td> ".$row["modelo"]."</td>
                                                <td> ".$row["color"]."</td>
                                                <td> ".$row["poliza"]."</td>
                                             </tr>";
                                      }
                                      mysqli_free_result($result_per);
                                }
                $html_per.="</tbody></table>";
                echo ("$html_per");  
                ?>
             </div>  
             <? 
           }
           
           // Herramientas
            if ($tipo == 3){
                mysqli_multi_query ($mysqli, "CALL visor_herramientas (".$org_id.")") OR DIE (mysqli_error($mysqli));
                ?>
                  <br /> <br />
                  <div class="container">                                                
                 <? 
                 $html_per = "<table class='table table-bordered' id='encabezadop'>
                             <thead>
                                 <tr class='table-info'>   
                                    <th scope='col'>Empresa</th>
                                    <th scope='col'>Nombre</th>
                                    <th scope='col'>Marca</th>
                                    <th scope='col'>Modelo</th>
                                  </tr>
                              </thead>
                              <tbody>";
                               if ($result_per = mysqli_store_result($mysqli)) {                
                                      while ($row = mysqli_fetch_assoc($result_per)) {
                                             $html_per.="<tr>
                                                <td> ".$row["empresa"]."</td>
                                                <td> ".$row["nombre"]."</td>
                                                <td> ".$row["marca"]."</td>
                                                <td> ".$row["modelo"]."</td>
                                             </tr>";
                                      }
                                      mysqli_free_result($result_per);
                                }
                $html_per.="</tbody></table>";
                echo ("$html_per");  
                ?>
             </div>  
             <? 
           }
            
              
         //}
       ?>
       
       <br/>
       <br/>
       <br/>
       <?
     } // Si estás conectado
    ?>
    
       
<!--<script type="text/javascript" src="js/popper/src/popper.js"></script>-->
<!--<script type="text/javascript" src="js/vehiculos.js"></script>-->
         
          

