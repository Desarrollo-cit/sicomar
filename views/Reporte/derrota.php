

<div class="container-fluid text-center ">

    <div class="row justify-content-center mb-3">
        <form class="col-lg-10  border bg-light rounded" id="formDerrota" enctype="multipart/form-data" autocomplete="off">
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
                            <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $decoded_id; ?>">

                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label for="identificador" class="form-label">Identificador:</label>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="identificador" name="identificador" value="<?php echo $decoded_identificador; ?>" readonly>

                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label for="fecha_zarpe" class="form-label">Fecha de zarpe:</label>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="fecha_zarpe" name="fecha_zarpe" value="<?php echo $decoded_fecha_zarpe; ?> " readonly>

                        </div>
                    </div>
                </div>
       
                </div>
  </div>


  <div class="col-md-6" id="imagen">
    <img src="<?= asset('./images/mar.png') ?>" alt="Imagen" width="200">
  </div>
</div>
    <div class="row mb-3">
        <div class="col-lg-8 border border-light p-4 rounded" id="map" style="height: 80vh;min-height:auto;">

        </div>
        <div class="col-lg-4">
            <table id="tablePuntos" class='table table-hover table-condensed table-bordered w-100'>
                <thead class='table-light'>
                    <tr>
                        <th>LATITUD</th>
                        <th>LONGITUD</th>
                        <th>FECHA</th>
                    </tr>
                </thead>
                <tbody id="tbodyPuntos">
                    <tr>
                        <td colspan="2">Los puntos ingresados se visualizaran acá</td>
                    </tr>
                </tbody>
            </table>
            <span class="fw-bold" id="distancia">0 MN</span>
        </div>
    </div>
    <div class="row justify-content-center mb-3">
        <div class="col-lg-3 mb-lg-0 mb-2">
            <button type="submit" class="btn w-100 btn-success" id="btnGuardar"><i class="bi bi-save me-2"></i>Guardar</button>
        </div>

        <div class="col-lg-3 mb-lg-0 mb-2">
            <button type="button" class="btn w-100 btn-warning" id="btnModificar"><i class="bi bi-pencil-square me-2"></i>Modificar</button>
        </div>
        <div class="col-lg-3 mb-lg-0 mb-2">
            <button type="reset" class="btn w-100 btn-danger" id="btnLimpiar"><i class="bi bi-arrow-clockwise me-2"></i>Limpiar</button>
        </div>
    </div>
    </form>
</div>

</div>
<div class="modal fade" id="modalPuntos" tabindex="-1" role="dialog" aria-labelledby="modalPuntosLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title " id="modalPuntosLabel">Puntos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="modal-body container" id="formPuntos" novalidate>
                <div class="row">
                    <div class="col-lg-12">
                        <label for="latitud">Latitud</label>
                        <input type="number" name="latitud" id="latitud" class="form-control">
                    </div>
                    <div class="col-lg-12">
                        <label for="longitud">Longitud</label>
                        <input type="number" name="longitud" id="longitud" class="form-control">
                    </div>
                    <div class="col-lg-12">
                        <label for="fecha">fecha</label>
                        <input type="datetime-local" name="fecha" id="fecha" class="form-control">
                    </div>
                </div>
                
            </form>
            <div class="modal-footer">
                <button type="submit" form="formPuntos" class="btn btn-success" id="buttonLimpiar"><i class="bi bi-save me-2"></i>Guardar</button>
                <button type="button" class="btn btn-secondary" id="buttonAnterior" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalInternacionales" tabindex="-1" role="dialog" aria-labelledby="modalInternacionalesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title " id="modalInternacionalesLabel">Operaciones ingresadas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body container text-center">
                <table id='tablaOperaciones' class='table table-hover table-condensed table-bordered w-100'>
                    <thead class='table-dark'>
                        <tr>
                            <th>PAÍS</th>
                            <th>ZARPE</th>
                            <th>ATRAQUE</th>
                            <th>ESTADO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="buttonAnterior" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<script src="<?= asset('/build/js/Reporte/derrota.js') ?>"></script>