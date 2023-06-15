<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\OperacionesController;
use Controllers\ZarpesController;
$router = new Router();
$router->setBaseURL('/sicomar');

$router->get('/', [AppController::class,'index']);

$router->get('/operaciones',[OperacionesController::class,'index']);
$router->get('/API/operaciones/catalogo', [OperacionesController::class , 'catalogoAPI']);
$router->post('/API/operaciones/guardar', [OperacionesController::class, 'guardarApi']);

$router->get('/zarpes',[ZarpesController::class,'index']);
$router->get('/API/zarpes/buscar', [ZarpesController::class, 'buscarApi']);
$router->post('/API/zarpes/eliminarRegistro', [ZarpesController::class, 'eliminar']);
$router->post('/API/zarpes/modificar', [ZarpesController::class, 'modificar']);
$router->get('/API/zarpes/buscarPer', [ZarpesController::class, 'buscarPersonasApi']);
$router->get('/API/zarpes/verRegistro', [ZarpesController::class, 'verRegistroApi']);
$router->get('/API/zarpes/imprimirRegistro', [ZarpesController::class, 'imprimirRegistroApi']);
$router->get('/API/zarpes/colocarInformacion', [ZarpesController::class, 'colocar']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();

