<div class="row justify-content-center mb-3">

    <div class="card col-lg-4 border  rounded">

        <div class="row justify-content-center">

            <div class="card-header bg-dark text-white">
                <h3 class="card-title">Informacion Derrota</h3>
            </div>

            <div class="card-body row align-items-center mb-1 bg-light">
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
                                    <input type="text" class="form-control" id="ope_identificador" name="ope_identificador" value="<?php echo $decoded_identificador; ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-12">
                                <label for="fecha_zarpe" class="form-label">Fecha de zarpe:</label>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="fecha_zarpe" name="fecha_zarpe" value="<?php echo $decoded_fecha_zarpe; ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-6" id="imagen">
                    <img src="<?= asset('./images/mar.png') ?>" alt="Imagen" width="200">
                </div>
            </div>
            <div class="card-footer ">
                <div class="row justify-content-center w-100">
                    <div class="col-lg-12">

                        <button type="button" name="back" id="back" class="btn btn-primary w-100"><i class="bi bi-skip-backward-fill"></i> Regresar</button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<div class="container">
    <div class="card">
        <div class="card-header text-white bg-dark">
            <h5 class="card-title">Trabajo de los Motores</h5>
        </div>
        <div class="card-body bg-light ">
            <form id="formMotores">

                <div id="divMotores"></div>

            
            </form>

        </div>
        <div class="card-footer bg-light">
            <div class="row justify-content-center mt-1">
                <div class="col-lg-2">
                    <button type="submit" form="formMotores" class="btn btn-success w-100"><i class="bi bi-save me-2"></i>Guardar</button>
                </div>

            </div>
        </div>

    </div>
</div>

</div>


<script src="<?= asset('/build/js/Reporte/motores.js') ?>"></script>