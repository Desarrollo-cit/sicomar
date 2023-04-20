<div class="container-fluid pt-5">
    <div class="row justify-content-center mb-3">
        <form class="col-lg-10 p-5 border bg-light rounded" id="formZarpe">
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
                    <label class="h3 " for="situacion">Situación de la operación</label>
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

                    <select name="tipoEmb" id="tipoEmb" selected class="form-control">
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

            <div id='divpuntosdeorden' class="row ms-2">

            </div>
           
            <div class="row justify-content-center mb-3">
                <div class="col-lg-6 d-grid mb-lg-0 mb-2">
                   
                        <button type="submit" class="btn btn-success" id="btnGuardar"><i class="bi bi-save me-2"></i>Guardar</button>
                 
                        <button type="button" class="btn btn-warning" id="btnModificar"><i class="bi bi-save me-2"></i>Modificar</button>
                
                </div>
            </div>
        </form>
    </div>

    <script src="<?= asset('./build/js/operaciones/index.js') ?>"></script>
</div>

</body>

</html>