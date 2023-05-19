
<div class="container-fluid text-center">
    <div class="row justify-content-center mb-3">
        <div class="col-lg-4 border bg-light rounded">
           
                <div class="row justify-content-center">
           
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title">Informacion Derrota</h3>
                </div>

            <div class="row align-items-center mb-1">
                <div class="col-md-6">
                    <form id="formId" enctype="multipart/form-data" autocomplete="off">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="hidden" class="form-control" id="ope_id" name="id_ope" value="<?php echo $decoded_id; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-12">
                                <label for="identificador" class="form-label">Identificador:</label>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="ope_identificador" name="ope_identificador" value="<?php echo $decoded_identificador; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-12">
                                <label for="fecha_zarpe" class="form-label">Fecha de zarpe:</label>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="fecha_zarpe" name="fecha_zarpe" value="<?php echo $decoded_fecha_zarpe; ?>">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6" id="imagen">
                    <img src="<?= asset('./images/mar.png') ?>" alt="Imagen" width="200">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="card">
        <div class="card-header text-white bg-dark">
            <h5 class="card-title">Comunicaciones</h5>
        </div>
        <div class="card-body bg-light rounded">
            <form id="formComunicacion">
                <input type="hidden" name="codigoOperacion3" id="codigoOperacion3">

                <div class="row justify-content-start mb-3">
                    <div class="col-lg-1">
                        <button type="button" id="buttonAgregarComunicacion" class="btn btn-primary mb-2 mb-lg-0 w-100"><i class="bi bi-plus-circle"></i></button>
                    </div>
                    <div class="col-lg-1">
                        <button type="button" id="buttonQuitarComunicacion" class="btn btn-danger w-100"><i class="bi bi-dash-circle"></i></button>
                    </div>
                </div>
                <div id="divComunicacion">
                </div>

                <div class="row justify-content-center mt-3">
                    <div class="col-lg-2">
                        <button type="submit" form="formComunicacion" class="btn btn-success"><i class="bi bi-save me-2"></i>Guardar</button>
                    </div>
                    <div class="col-lg-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>







<script src="<?= asset('/build/js/Reporte/comunicaciones.js') ?>"></script>