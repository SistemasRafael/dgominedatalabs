<?
/**
 * facturas_visor_envio.php v0.1
 * ----------------------------------------
 * Envío de facturas por correo
 **/

//Configuración central de sistema.

//Conectarse al servicio de datos.
include "..connections/config.php";


    ?>
    <!DOCTYPE html> 
     <html lang="en"> 
     <head> 
     	<meta charset="UTF-8"> 
   
     <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
      <link href="http://192.168.20.3:81/__pro/argonaut/boostrapp/css/check.css" rel="stylesheet">
      <!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script></strong> --> 
      <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> 
       <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script> 
      <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>    
      
    <script type="text/javascript"> 
     
        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })

    </script>
             
    </head> 
      <!-- Navigation-->  
 
  <body> 
    <div class="row">
           
                    <div class="col-sm-3 col-md-4 col-lg-1 col-xg-1">
                    </div> <!-- Fin de botones-->
                     
                    <!-- Areas de botones generales-->
                  
                              <div class="col-xs-12 col-sm-8 col-md-4 col-lg-8">                             
                                <h3>  CALENDARIOS DE CAPACITACIÓN </h3><hr />   
                                <br />    
                                 <?          
                                            echo ("<div class=\"col-sm-4 col-md-4 col-lg-2 col-xl-2\">"); 
                                            $year =  date("Y");
                                             
                                            echo ("<select name=\"year\"  id=\"year\" placeholder=\"Nombre\" class=\"form-control\" onchange=\"ValidaCursos(".$u_id.");\" > ");        
                                             echo ("<option value=$year>$year</option>");
                                                    
                                            $result = $mysqli->query("SELECT (YEAR(CURDATE())+1) AS year") or die(mysqli_error());
                                            while( $row = $result ->fetch_array(MYSQLI_ASSOC)) 
                                            
                                            {
                                                $year =($row["year"]);
                                                echo ("<option value=$year>$year</option>");
                                            }          
                                            echo ("</select>");
                                            echo ("</div>");

                                       ?>  
                                    
                                     <br /> 
                                     <br /> 
                                    
                                <div class="col-xl-2 col-md-2 col-lg-2">          
                                       <?    
                                            echo ("<select name=\"mes\" id=\"mes\" class=\"form-control\" onchange=\"ValidaCursos(".$u_id.");\" > ");        
                                                   
                                            $result = $mysqli->query("SELECT num_mes, mes FROM meses") or die(mysqli_error());
                                            while( $row = $result ->fetch_array(MYSQLI_ASSOC)) 
                                            
                                            {
                                                $num_mes =($row["num_mes"]);
                                                $mes =($row["mes"]);
                                                echo ("<option value=$num_mes>$mes</option>");
                                            }          
                                            echo ("</select><br />");
                                       ?>  
                                    </div>
                            </div>
           </div>
           
           <div class="row">
        
                            <div class="container-fluid">
           
                            <div class="col-xl-1 col-md-1 col-lg-1">
                            </div>
                                   
                                    <div class="[ form-group ]">
                                                <input type="checkbox" name="Lunes" id="Lunes" autocomplete="off" />
                                                <div class="[ btn-group col-xs-12 col-sm-8 col-md-8 col-lg-5 col-xg-8]">
                                                
                                                    <label for="Lunes" class="[ btn btn-primary  align-left  col-xs-2 col-sm-2 col-md-1 col-lg-1]">
                                                    <span class="[ glyphicon glyphicon-ok ]"></span>                            
                                                    <span></span>
                                                    </label>
                                                    <label for="Lunes" class="[ btn btn-default active text-left col-xs-10 col-sm-8 col-md-2 col-lg-1]">
                                                        LUNES 
                                                    </label>                              
                                                </div>
                                    </div> 
                                                
                            
                                    <br />
                                    <br />  
                                    <div class="col-xl-1 col-md-1 col-lg-1">
                                    </div>
                                    
                                        <div class="[ form-group ]">
                                         <input type="checkbox" name="Martes" id="Martes" autocomplete="off" />
                                         <div class="[ btn-group col-xs-12 col-sm-8 col-md-8 col-lg-5 col-xg-10]">
                                            
                                                <label for="Martes" class="[ btn btn-success  col-xs-2 col-sm-2 col-md-1 col-lg-1]">
                                                    <span class="[ glyphicon glyphicon-ok ]"></span>
                                                    <span></span>
                                                </label>
                                                
                                                <label for="Martes" class="[ btn btn-default active text-left col-xs-10 col-sm-8 col-md-2 col-lg-1]">
                                                     MARTES 
                                                </label>
                                        </div>
                                       </div>                       
                                    <br />
                                    <br />
                                    
                                    <div class="col-xl-1 col-md-1 col-lg-1">
                                    </div>
                                    
                                        <div class="[ form-group ]">
                                         <input type="checkbox" name="Miercoles" id="Miercoles" autocomplete="off" />
                                         <div class="[ btn-group  col-xs-12 col-sm-8 col-md-12 col-lg-5 col-xg-10]">
                                         
                                                 <label for="Miercoles" class="[ btn btn-info  col-xs-2 col-sm-2 col-md-1 col-lg-1]">
                                                    <span class="[ glyphicon glyphicon-ok ]"></span>
                                                    <span></span>
                                                </label>
                                                <label for="Miercoles" class="[ btn btn-default active text-left col-xs-10 col-sm-9 col-md-2 col-lg-1]">
                                                      MIERCOLES
                                                </label>
                                           
                                         </div>                              
                                         </div>
                                    
                                     
                                     <br />
                                     <br />  
                                    
                                    <div class="col-xl-1 col-md-1 col-lg-1">
                                    </div>
                                     
                                         <div class="[ form-group ]">
                                         <input type="checkbox" name="Jueves" id="Jueves" autocomplete="off" />
                                         <div class="[ btn-group col-xs-12 col-sm-8 col-md-12 col-lg-5 col-xg-10]">
                                         
                                                 <label for="Jueves" class="[ btn btn-primary  col-xs-2 col-sm-2 col-md-1 col-lg-1]">
                                                    <span class="[ glyphicon glyphicon-ok ]"></span>
                                                    <span></span>
                                                </label>
                                          
                                                <label for="Jueves" class="[ btn btn-default active text-left col-xs-10 col-sm-9 col-md-2 col-lg-2 col-xg-10]">                                             
                                                     JUEVES 
                                                </label>
                                          
                                         </div>                              
                                         </div>
                                         
                                         
                                     <br />
                                     <br />  
                                    
                                         <div class="col-xl-1 col-md-1 col-lg-1">
                                         </div>
                                         <div class="[ form-group ]">
                                         <input type="checkbox" name="Viernes" id="Viernes" autocomplete="off" />
                                         <div class="[ btn-group col-sm-8 col-md-12 col-lg-5 col-xg-10]">
                                         
                                                 <label for="Viernes" class="[ btn btn-success col-xs-2 col-sm-2 col-md-1 col-lg-1]">
                                                    <span class="[ glyphicon glyphicon-ok ]"></span>
                                                    <span></span>
                                                </label>
                                                
                                                <label for="Viernes" class="[ btn btn-default active text-left col-xs-10 col-sm-6 col-md-4 col-lg-2 col-xg-10]">
                                                    VIERNES 
                                                </label>
                                        </div>                              
                                        </div>     
                                     
                  </div>
           </div>
                  <br />
                 <br />
              
                     <br />
                   <div class="container-fluid">  
                   <div class="col-xl-1 col-md-1 col-lg-1">
                                         </div>
                    <div class="col-xl-1 col-md-1 col-lg-1">
                     <input type="button" class="btn btn-warning" name="agregar" id="agregar" onclick="agregarFila(<?echo $cont_click=$cont_click+1?>);" value="GENERAR" /> 
                    </div>
                   </div>              
                              
                              
    </body>  
    </html>
    
          
     <script type="text/javascript"> 
     
    //var $unidad_id = '2';
    var $unidad_id = <?echo $unidad_mina;?> 
    var hoy = new Date();       
    var $year_c = hoy.getFullYear();
     
    $(document).ready(function() {        
        
        $('#enviar').click(function(){
            var selected = ''; 
            var $production = '';
            
            //alert('Has seleccionado: '+$unidad_id); 
            
            $('.form-group input[type=checkbox]').each(function(){
                if (this.checked) {                
                    //selected += $(this).val()+', ';
                    selected += $(this).attr('id');
                   // $production = $(this).attr('id:produccion');
                }
            });
               
            if (selected == "Catalogo")
                window.open("lc_descargas_exec_t.php?unidad_id="+$unidad_id+"&cadena='"+selected+"'","_self");
            else
                {
                    if (selected.match("Produccion") || selected.match("Contractor") || selected.match("Costs_usd") || selected.match("Costs_mxn") || selected.match("Capital"))
                        {
                            if (selected.match("Actual") || selected.match("Budget") || selected.match("Forecast") )
                                {
                                    if (selected.match("2016") || selected.match("2017") || selected.match("2018") || selected.match("2019") || selected.match("2020"))
                                        {
                                            window.open("lc_descargas_exec.php?unidad_id="+$unidad_id+"&cadena='"+selected+"'","_self");                        
                                            //alert('Has seleccionado: '+selected);
                                            //alert('Has seleccionado: '+$unidad_id);
                                        }
                                    else
                                        alert('You must select at least one year.');
                                }
                            else                    
                                alert('You must select at least one type.');
                        }
                         
                    else            
                         alert('You must select at least one area.');
                
            return false;
            }
        });         
    });    //total ore pads
    
</script>
     
