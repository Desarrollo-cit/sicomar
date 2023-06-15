      <div class="container-fluid pt-5">
         <div class="row justify-content-center mb-3 text-center">
             <div class="col-12">
                 <h1>
                     Zarpes generados                
                 </h1>
             </div>
         </div>
     <div class="row" id="divTabla">
     <div class="col-sm-12 col-lg-12 table-responsive " >
        <table id="zarpesTabla" class="table table-hover table-bordered table-hover w-100">
            <thead class='table-dark'>
            <tr>
                     <th>NO</th>
                     <th>TIPO</th>
                     <th>IDENTIFICADOR</th>
                     <th>EMBARCACION</th>
                     <th>ESTADO</th>
                     <th>PERSONAL</th>
                     <th>IMPRIMIR</th>
                     <th>VER</th>
                     <th>MODIFICAR</th>
                     <th>ELIMINAR</th>
                     </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>




<!----------------------------------------------------------- MODAL PARA VER PERSONAL ASIGNADO ------------------------------------------------------>
<div class="modal" id="myModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Personal asignado</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body container">

                <div class="modal-body container ">
                    <form id="PersonasAsig" class="badge-light p-1 ">
                        <!-- <input type="hidden" name="codigo" id="codigo"> -->
                        <div class="row mb-2 justify-content-center text-center" id="tabla1">
                    <div class="col-lg-12 col-lg-12 table-responsive ">
                        <table id='tabla_resultados' class='table table-hover table-condensed table-bordered w-100'>
                            <thead class='table-dark'>
                                <tr>
                                    <th>NO.</th>
                                    <th>CATALOGO</th>
                                    <th>NOMBRE</th>
                          


                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>


                    </div>
                </div>
                    </form>
                </div>
      
        
            </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

<!----------------------------------------------------------- MODALMODIFICAR ------------------------------------------------------------------------->
<div class="modal" id="modalModifica">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Modificar Datos</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="container-fluid pt-5">
    <div class="row justify-content-center mb-3">

        <form class="col-lg-10 p-5 border bg-light rounded" id="formZarpe">
        <input type="hidden" name="ope_identificador" id="ope_identificador">
        <input type="hidden" name="ope_dependencia" id="ope_dependencia">

            <input type="hidden" name="codigo" id="codigo">
            <div class="row justify-content-between mb-3">
                <div class="col-lg-6">
                    <h1>Nueva hoja de zarpe</h1>
                </div>
                <div class="col-lg-2 d-flex align-items-center ">
                
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" value="1" checked name="ope_reutilizar" id="ope_reutilizar">
                        <label class="form-check-label" for="ope_reutilizar">
                            Reutilizar información
                        </label>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mb-5">
                <div class="col-12 text-center">
                    <label class="h3 " for="ope_situacion">Situación de la operación</label>
                    <textarea name="ope_situacion" id="ope_situacion" class="form-control"></textarea>
                </div>
            </div>
            <div class="row justify-content-center mb-5">
                <div class="col-12 text-center">
                    <label class="h3 " for="mision">Misión de la operación</label>
                    <textarea name="ope_mision" id="ope_mision" class="form-control"></textarea>
                </div>
            </div>
            <div class="row justify-content-center mb-5">
                <div class="col-12 text-center">
                    <label class="h3 " for="ejecucion">Ejecución de la operación</label>
                    <textarea name="ope_ejecucion" id="ope_ejecucion" class="form-control"></textarea>
                </div>
            </div>
            <hr>
            <div class="row justify-content-center mb-3">
                <div class="col-12 text-center">
                    <h3>Detalles de la operación</h3>
                </div>
            </div>
            <div class="row justify-content-center mb-3">
                <div class="col-lg-3">
                    <label for="tipoOpe">Tipo Operacion</label>

                    <select name="ope_tipo" id="ope_tipo" selected class="form-control">
                        <option value="">Seleccione...</option>
                        <?php foreach ($busqueda as $busqueda) { ?>
                            <option value="<?= $busqueda['tipo_id']  ?>"><?= $busqueda['tipo_desc'] ?></option>
                        <?php  }  ?>
                    </select>
                </div>
                <div class="col-lg-3">
                    <label for="fechazarpe">Fecha de zarpe</label>
                    <input type="datetime-local" name="ope_fecha_zarpe" id="ope_fecha_zarpe" class="form-control">
                </div>
                <div class="col-lg-3">
                    <label for="fechaatraque">Fecha de atraque</label>
                    <input type="datetime-local" name="ope_fecha_atraque" id="ope_fecha_atraque" class="form-control">
                </div>
                <div class="col-lg-3">
                    <label for="tipoEmb">Tipo Embarcacion</label>

                    <select name="asi_unidad" id="asi_unidad" selected class="form-control">
                        <option value="">Seleccione...</option>
                        <?php foreach ($embarcacion as $rmbarcacion) { ?>
                            <option value="<?= $rmbarcacion['emb_id']  ?>"><?= $rmbarcacion['emb_nombre'] ?></option>
                        <?php  }  ?>
                    </select>
                </div>
            </div>
            <hr>
            <div class="row justify-content-center">

                <div class="col">

                    <h3 class="text-center">Asignar Personal</h3>
                </div>
                <div class="col-lg-1">
                    <button type="button" id="agregarInputsorden" class="btn btn-primary w-100 "><i class="bi bi-plus-circle "></i></button>
                </div>
                <div class="col-lg-1">
                    <button type="button" id="quitarInputsorden" class="btn btn-danger w-100"><i class="bi bi-dash-circle"></i></button>
                </div>
            </div>

            <div id='divAsignados'>
                    <div class="row justify-content-center mb-3">
                        <div class="col-lg-4 text-center">
                            <input type="tel" name="catalogo0" id="catalogo0" placeholder="Ingrese el catálogo" class="form-control">
                        </div>
                        <div class="col-lg-8 text-center">
                            <input disabled type="text" name="nombre0" id="nombre0" placeholder="El nombre del asignado aparecera automáticamente" class="form-control">
                        </div>
                    </div>
                </div>
           
            <div class="row justify-content-center mb-3">
                <div class="col-lg-6 d-grid mb-lg-0 mb-2">
                   
                        <button type="button" class="btn btn-warning" id="btnModificar"><i class="bi bi-save me-2"></i>Modificar</button>
                 
                        <!-- <button type="button" class="btn btn-warning" id="btnModificar"><i class="bi bi-save me-2"></i>Modificar</button> -->
                
                </div>
            </div>
        </form>
    </div>


</div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

<!----------------------------------------------------------- MODAL VER REGISTRO ------------------------------------------------------------------------->
<div class="modal fade" id="modalVer" tabindex="-1" role="dialog" aria-labelledby="infomodalReporte" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content">
                <div class="modal-header ">
                    <h2 class="modal-title " id="infomodalReporte">Información del reporte de patrulla</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body container-fluid " >
                <div class="row justify-content-around">
                        <div class="col-lg-4">
                            <div class="row mb-3">
                                <div class="col border border-light p-4 rounded"  id="map" style="height: 80vh;min-height:auto">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                <h4 class="text-center">Derrota navegada</h4>
                                    <table class="table table-bordered table-condensed text-center" id="tablaDerrota">
                                        <thead>
                                            <tr>
                                                <th>NO.</th>
                                                <th>LATITUD</th>
                                                <th>LONGITUD</th>
                                                <th>DISTANCIA (MN)</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <h3 class="text-center">Información de la operación</h3>
                            <div class="row">
                                <div class="col table-responsive">
                                    <table class="table table-bordered table-condensed" id="tablaInformacion">
                                        <thead>
                                            <tr>
                                                <th>IDENTIFICADOR</th>
                                                <th>TIPO</th>
                                                <th>ZARPE</th>
                                                <th>ATRAQUE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
        
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                           
                            <div class="row">
                                <div class="col-lg-6">
                                
                                    <h4 class="text-center">Personal asignado</h4>
                                    <table class="table table-bordered table-condensed text-center" id="tablaPersonal">
                                        <thead>
                                            <tr>
                                                <th>NO.</th>
                                                <th>CATÁLOGO</th>
                                                <th>NOMBRE</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    
                                </div>
                                <div class="col-lg-6">
                                    
                                            
                                    <h4 class="text-center">Unidades asignadas</h4>
                                    <table class="table table-bordered table-condensed text-center" id="tablaUnidades">
                                        <thead>
                                            <tr>
                                                <th>NO.</th>
                                                <th>TIPO</th>
                                                <th>NOMBRE</th>
                                            </tr>
                                        </thead>
                                    </table>
                                            
                                        
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-lg-6">
                                    <h4 class="text-center">Motores</h4>
                                    <table class="table table-bordered table-condensed text-center" id="tablaMotores">
                                        <thead>
                                            <tr>
                                                <th>NO.</th>
                                                <th>MOTOR</th>
                                                <th>HORAS</th>
                                                <th>RPM</th>
                                                <th>FALLAS</th>
                                                <th>OBSERVACIONES</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="col-lg-6">
                                    <h4 class="text-center">Comunicaciones</h4>
                                    <table class="table table-bordered table-condensed text-center" id="tablaComunicaciones">
                                        <thead>
                                            <tr>
                                                <th>NO.</th>
                                                <th>MEDIO</th>
                                                <th>RECEPTOR</th>
                                                <th>CALIDAD</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h4 class="text-center">Consumos</h4>
                                    <table class="table table-bordered table-condensed text-center" id="tablaConsumos">
                                        <thead >
                                            <tr>
                                                <th>NO.</th>
                                                <th>INSUMO</th>
                                                <th>CANTIDAD</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h4 class="text-center">Novedades</h4>
                                    <table class="table table-bordered table-condensed text-center" id="tablaNovedades">
                                        <thead>
                                            <tr>
                                                <th>HORA</th>
                                                <th>NOVEDAD</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h4 class="text-center">Recomendaciones</h4>
                                    <table class="table table-bordered table-condensed text-center" id="tablaRecomendaciones">
                                        <thead>
                                            <tr>
                                                <th>NO.</th>
                                                <th>RECOMENDACIÓN</th>
                                        
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

<!----------------------------------------------------------- MODAL IMPRIMIR ------------------------------------------------------------------------->
<div class="modal" id="modalImprimir">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">IMPRIMIR</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body container">

                <div class="modal-body container ">
                    <form id="PersonasAsig" class="badge-light p-1 ">
                        <!-- <input type="hidden" name="codigo" id="codigo"> -->
                        <div class="row mb-2 justify-content-center text-center" id="tabla1">
                    <div class="col-lg-12 col-lg-12 table-responsive ">
                        <table id='tabla_resultados' class='table table-hover table-condensed table-bordered w-100'>
                            <thead class='table-dark'>
                                <tr>
                                    <th>NO.</th>
                                    <th>CATALOGO</th>
                                    <th>NOMBRE</th>
                          


                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>


                    </div>
                </div>
            </form>
        </div>    
    </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

<script src="build/js/zarpes/index.js"></script>