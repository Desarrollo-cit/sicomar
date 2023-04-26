<?php

namespace Controllers;
use Model\Derrota;

use MVC\Router;
use Exception;
use Model\Ingreso;

class DerrotaController{

    public static function index(Router $router){
        $router->render('Reporte/derrota',[]);
    }




public static function GuardarAPI(){

    getHeadersApi();

   


try {

    $der_coodigo = $_POST['id'];
    //$der_coodigos = $_POST['puntos'];
    $der_ope =$_POST['puntos'];

$val_id = 0;
foreach ($der_ope as $val) {
    $datos = explode(',', $val);
    $latitud = $datos[0];
    $longitud = $datos[1];
    $fecha = $datos[2];


    $Ingreso = new Derrota([
        // 'der_id' => $val_id,
        'der_ope' =>        $der_coodigo,
        'der_latitud' =>    $latitud,
        'der_longitud' =>   $longitud,
        'der_fecha' =>      $fecha,
        'der_situacion' =>  "1"

    ]);
    $guardado = $Ingreso->guardar(); 
    

}
if ($guardado) {

    echo json_encode([

        "codigo" => 7,
    ]);

} else {
    echo json_encode([

        "codigo" => 2,
    ]);
}



} catch (Exception $e) {
    echo json_encode([
        "detalle" => $e->getMessage(),
        "mensaje" => "Ocurrió un error en la base de datos.",
        "codigo" => 4,
    ]);
}



}


}

