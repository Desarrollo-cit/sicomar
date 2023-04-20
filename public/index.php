<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\OperacionesController;
$router = new Router();
$router->setBaseURL('/sicomar');

$router->get('/', [AppController::class,'index']);

$router->get('/operaciones',[OperacionesController::class,'index']);
$router->get('/API/operaciones/catalogo', [OperacionesController::class , 'catalogoAPI']);
$router->post('/API/operaciones/guardar', [OperacionesController::class, 'guardarApi']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
