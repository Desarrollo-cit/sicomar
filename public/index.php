<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\EstadisticasController;
use Controllers\InternacionalesController;
$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);
$router->get('/internacionales', [InternacionalesController::class,'index']);
$router->get('/API/internacionales/catalogo', [InternacionalesController::class,'getCatalogo']);
$router->post('/API/internacionales/guardar', [InternacionalesController::class,'guardar']);
$router->post('/API/internacionales/modificar', [InternacionalesController::class,'modificar']);
$router->post('/API/internacionales/eliminar', [InternacionalesController::class,'eliminar']);
$router->get('/API/internacionales/buscar', [InternacionalesController::class,'buscarAPI']);
$router->get('/API/internacionales/colocarInfo', [InternacionalesController::class,'colocarInfo']);

/**ESTADISTICAS */
$router->get('/estadisticas', [EstadisticasController::class,'index']);
$router->get('/API/estadisticas/mapa', [EstadisticasController::class,'operacionesMapaApi']);
$router->get('/API/estadisticas/consumos', [EstadisticasController::class,'operacionesConsumosApi']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
