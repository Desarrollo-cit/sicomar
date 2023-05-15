<?php
$ope_id = $_GET['id'];
$ope_identificador = $_GET['identificador'];
$ope_fecha_zarpe = $_GET['fecha_zarpe'];

$decoded_id = base64_decode($ope_id);
$decoded_identificador = base64_decode($ope_identificador);
$ope_fecha_zarpe = base64_decode($ope_fecha_zarpe);

?>




<div class="container-fluid text-center ">

    <div class="row justify-content-center mb-3">
        <form class="col-lg-10  border bg-light rounded" id="formId" enctype="multipart/form-data" autocomplete="off">
            <div class="row justify-content-center">
                <div class="col-12 mb-3">
                    <h1>Informacion Derrota<i class="mdi mdi-distribute-horizontal-left:"></i></h1>
                </div>
            </div>
            <div class="row align-items-center mb-5">
                <div class="col-md-6">
                    <div id="trae">
                        <div class="row ">

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="ope_id" name="id_ope" value="<?php echo $decoded_id; ?>">

                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label for="identificador" class="form-label">Identificador:</label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="ope_identificador" name="ope_identificador" value="<?php echo $decoded_identificador; ?>">

                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label for="fecha_zarpe" class="form-label">Fecha de zarpe:</label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="fecha_zarpe" name="fecha_zarpe" value="<?php echo $ope_fecha_zarpe; ?>">

                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="col-md-6" id="imagen">
                    <img src="<?= asset('./images/mar.png') ?>" alt="Imagen" width="200">
                </div>
            </div>
        </form>
        <div class="container">

            <h5>Trabajo de los Motores</h5>

            <div class="container ">
                <form id="formMotores">

                    <div id="divMotores">
                    </div>
                    <div class="row justify-content-end mb-3">
                        <div class="col-lg-4 d-grid mb-lg-0 mb-2">
                            <button type="submit" class="btn btn-success"><i class="bi bi-save me-2"></i>Guardar</button>
                        </div>
                    </div>
                </form>
            </div>


        </div>
        <script src="<?= asset('/build/js/Reporte/motores.js') ?>"></script>