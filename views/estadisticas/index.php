<div class="row justify-content-center mb-3">
    <h1>Estadísticas Operativas</h1>
</div>
<div class="row justify-content-around mb-3">
    <div class="col-lg-2 border bg-light p-3 sticky-top" style="max-height: 50vh;">
        <h2>Filtrar información</h2>
        <div class="row">
            <form class="col" id="formEstadisticas">
                <div class="row mb-3">
                    <div class="col">
                        <label for="inicio">Fecha de Inicio</label>
                        <input type="datetime-local" class="form-control" name="inicio" id="inicio">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="fin">Fecha de Fin</label>
                        <input type="datetime-local" class="form-control" name="fin" id="fin">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <button class="btn btn-info w-100" type="submit"><i class="bi bi-filter me-2"></i>Filtrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-10">
        <div class="row justify-content-center mb-3">
            <div class="col-lg-11 p-3 border  p-4 rounded" id="map" style="height: 60vh;min-height:auto;">

            </div>
        </div>
        <div class="row justify-content-around mb-3">
            <div class="col-lg-4 border p-3">
                <canvas id="chartConsumos"></canvas>

            </div>
            <div class="col-lg-7 border p-3">
                <canvas id="chartOperaciones"></canvas>

            </div>
        </div>
        <div class="row justify-content-around">
            <div class="col-lg-4 border p-3">
                <canvas id="chartTop"></canvas>
            </div>
            <div class="col-lg-7 border p-3">
                <canvas id="chartMeses"></canvas>
            </div>
        </div>


    </div>
</div>
<script src="<?= asset('./build/js/estadisticas/index.js') ?>"></script>