<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\ReporteController;
use Controllers\DerrotaController;
use Controllers\MotoresController;
use Controllers\ConsumosController;
$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);


//reporte
$router->get('/reporte', [ReporteController::class,'index']);
$router->get('/API/reporte/BusDatos', [ReporteController::class, 'BuscarDatosAPI'] );



$router->get('/reporte/derrota', [DerrotaController::class,'index']);
$router->post('/API/reporte/derrota/GuardarDatos', [DerrotaController::class,'GuardarAPI']);
$router->get('/API/reporte/derrota/BusDerrota', [DerrotaController::class, 'BuscarDerrotas'] );

// motores
$router->get('/reporte/motores', [MotoresController::class,'index']);
$router->get('/API/reporte/motores/BusMotor', [MotoresController::class, 'BuscarMotores'] );
$router->get('/API/reporte/motores/BusTrabajo', [MotoresController::class, 'BuscarTrabajo'] );
$router->post('/API/reporte/motores/GuardarTrabajo', [MotoresController::class, 'GuardarTrabajoAPI'] );

//consumos
$router->get('/reporte/consumos', [ConsumosController::class,'index']);
$router->get('/API/reporte/consumos/BusConsumos', [ConsumosController::class, 'BuscarConsumos'] );
$router->get('/API/reporte/consumos/BusInsumos', [ConsumosController::class, 'BuscarInsumos'] );


$router->comprobarRutas();
