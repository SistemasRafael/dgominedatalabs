<?$curso_id = $_GET['curso_id'];?>

      
       <div class="modal-header">
                <h5 class="modal-title" id="evaluaModal">Evaluación (1) </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="col-lg-12">
                    <label for="<?echo $curso_id;?>" class="col-form-label">Esta es la pregunta  número 1: </label>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="exampleRadios1" id="exampleRadios1" value="optio45" >
                          <label class="form-check-label" for="exampleRadios1">
                                o       Respuesta correcta
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="exampleRadios1" id="exampleRadios2" value="option2" >
                          <label class="form-check-label" for="exampleRadios2">
                                o       Respuesta incorrecta
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="exampleRadios1" id="exampleRadios3" value="option3" >
                          <label class="form-check-label" for="exampleRadios3">
                                o       Respuesta incorrecta
                          </label>
                        </div>
                      <br />                    
                      
                      <!--Pregunta II -->
                        <label for="<?echo $curso_id;?>" class="col-form-label">Esta es la pregunta  número 2: </label>
                        <br />
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="exampleRadios2" id="exampleRadios21" value="option21" >
                          <label class="form-check-label" for="exampleRadios4">
                                Respuesta Incorrecta
                          </label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="exampleRadios2" id="exampleRadios22" value="option22" >
                          <label class="form-check-label" for="exampleRadios5">
                                Respuesta Correcta
                          </label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="exampleRadios2" id="exampleRadios23" value="option23" >
                          <label class="form-check-label" for="exampleRadios3">
                                Respuesta incorrecta
                          </label>
                        </div>
                        <br />
                        <br />
                        <br />
                        
                         <!--Pregunta III -->
                        <label for="<?echo $curso_id;?>" class="col-form-label">Selecciona la imagen de Rosita: </label>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="customCheck31">
                          <label class="custom-control-label" for="customCheck31">
                                <a class="thumbnail" href="images/logo.gif" id="imagen1" data-image-id="1" data-toggle="modal" data-title="Imagen1" data-image="imagen1" data-target="#image-gallery">
        							<img class="img-responsive" src="images/camion.jpg" alt="Another alt text">
        						  </a>
                            
                          </label>
                        </div>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="customCheck32">
                          <label class="custom-control-label" for="customCheck32">
                                  <a class="thumbnail" href="images/logo.gif" id="imagen1" data-image-id="1" data-toggle="modal" data-title="Imagen1" data-image="imagen1" data-target="#image-gallery">
        							<img class="img-responsive" src="images/logo.gif" alt="Another alt text">
        						  </a>
                          
                          </label>
                        </div>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="customCheck33">
                          <label class="custom-control-label" for="customCheck33">
                                <a class="thumbnail" href="images/logo.gif" id="imagen1" data-image-id="1" data-toggle="modal" data-title="Imagen1" data-image="imagen1" data-target="#image-gallery">
        							<img class="img-responsive" src="images/logo.gif" alt="Another alt text">
        						  </a>
                            
                          </label>
                        </div>                        
                        <br />
                        <br />
                        
                         <!--Pregunta IV -->
                        <label for="<?echo $curso_id;?>" class="col-form-label">Esta es la pregunta  número 4 Selección Múltiple: </label>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="customCheck41">
                          <label class="custom-control-label" for="customCheck41">Respuesta Incorrecta</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="customCheck42">
                          <label class="custom-control-label" for="customCheck42">Respuesta Correcta 1</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="customCheck43">
                          <label class="custom-control-label" for="customCheck43">Respuesta Correcta 2</label>
                        </div>
                        
                        <br />
                        <br />
                        
                        <!--Pregunta IV 
                        <label for="<?echo $curso_id;?>" class="col-form-label">Elige la imagen correcta (pregunta 4): </label>
                        <br />
                        <div class="col-lg-3">
                          <a class="thumbnail" href="images/logo.gif" id="imagen1" data-image-id="1" data-toggle="modal" data-title="Imagen1" data-image="imagen1" data-target="#image-gallery">
							<img class="img-responsive" src="images/logo.gif" alt="Another alt text">
						</a>
                        </div>
                        <div class="col-lg-3">
                          <a class="thumbnail" href="images/logo.gif" id="imagen2" data-image-id="1" data-toggle="modal" data-title="Imagen2" data-image="imagen2" data-target="#image-gallery">
							<img class="img-responsive" src="images/logo.gif" alt="Another alt text">
						</a>
                        </div>
                        <div class="col-lg-3">
                          <a class="thumbnail" href="images/camion.jpg" id="imagen3" data-image-id="1" data-toggle="modal" data-title="Imagen3" data-image="imagen3" data-target="#image-gallery">
							<img class="img-responsive" src="images/camion.jpg" alt="Another alt text">
						</a>
                        </div>-->
                        
                        
                </div>
              </div>
              
               <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="GuardarEvaluacion()">Guardar</button>
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              </div>
              

<!--
</body>
</html>   --!>
          

