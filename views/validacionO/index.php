
<div class="container-fluid pt-5">

<div class="row justify-content-center mb-3 text-center">
    <div class="col-12">
        <h1>
            VALIDAR OPERACION
        </h1>
    </div>
</div>
<div class="row mb-2 justify-content-center text-center" id="tabla">
    <div class="col-sm-12 col-lg-12 table-responsive ">
        <table id='dataTable' class='table table-hover table-condensed table-bordered w-100'>
            <thead class='table-dark'>
                <tr>
                    <th>NO</th>
                    <th>IDENTIFICADOR</th>
                    <th>VER INFORMACIÓN</th>
                    <th>RECHAZAR</th>
                    <th>VALIDAR</th>
                </tr>
            </thead>
            <tbody id="tabla_body">

            </tbody>
        </table>


    </div>
</div>

<!-- modal validacion -->

<div class="modal fade" id="modalReporte" tabindex="-1" role="dialog" aria-labelledby="infomodalReporte" aria-hidden="true">
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
                                        <thead class="table-dark" >
                                            <tr>
                                                <th>NO.</th>
                                                <th>LATITUD</th>
                                                <th>LONGITUD</th>
                                                <th>FECHA/HORA</th>
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
                                        <thead class="table-dark" >
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
                                        <thead class="table-dark" >
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
                                        <thead class="table-dark" >
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
                                        <thead class="table-dark" >
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
                                        <thead class="table-dark" >
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
                                        <thead class="table-dark" >
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
                                        <thead class="table-dark" >
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
                                        <thead class="table-dark" >
                                            <tr>
                                                <th>NO.</th>
                                                <th>RECOMENDACIÓN</th>
                                        
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h4 class="text-center">Inteligencia</h4>
                                    <table class="table table-bordered table-condensed text-center" id="tablaInteligencia">
                                        <thead class="table-dark" >
                                            <tr>
                                                <th>NO.</th>
                                                <th>INFORMACIÓN</th>
                                        
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

<script src="<?= asset('/build/js/validacionO/index.js') ?>"></script>