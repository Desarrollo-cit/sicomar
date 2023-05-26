<?php

namespace Controllers;

use Model\Derrota;
use Model\Novedades;
use Model\Inteligencia;

use Model\Lecciones;
use MVC\Router;
use Exception;
use Model\Comunicaciones;

class InteligenciaController
{

    public static function index(Router $router)
    {
        $ope_id = $_GET['id'];
        $ope_identificador = $_GET['identificador'];
        $ope_fecha_zarpe = $_GET['fecha_zarpe'];

        $decoded_id = base64_decode($ope_id);
        $decoded_identificador = base64_decode($ope_identificador);
        $decoded_fecha_zarpe = base64_decode($ope_fecha_zarpe);

        $router->render('Reporte/inteligencia', [
            'decoded_id'            =>   $decoded_id,
            'decoded_identificador' =>   $decoded_identificador,
            'decoded_fecha_zarpe'   =>   $decoded_fecha_zarpe
        ]);
    }


    public static function Buscarinteligencia()
    {
        getHeadersApi();
            $valor = $_GET['id'];
            // echo json_encode($valor);
            // exit;
        try {

        $datos = Derrota::fetchArray("SELECT * FROM  codemar_informacion where info_operacion =  $valor and info_situacion = 1 ");

            if($datos ){

                echo json_encode($datos);

            }

        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }


    


    




    public static function GuardarInteligenciaAPI()
    {

        getHeadersApi();

        // echo json_encode($_POST);

        // exit;

        try {

            $informacion = $_POST['informacion'];
            $id_ope = $_POST['id_ope'];
    
            // descomponer arrays
            $informacion_array = explode(",", $informacion);


       
            $num_elementos = count($informacion_array);

            $datos = Lecciones::fetchArray("SELECT * FROM codemar_informacion where info_operacion = $id_ope and info_situacion = 1  
           ");
            if ($datos) {
                foreach ($datos as $key => $value) {
                    $cambio = new Inteligencia([
                        'info_id' => $value['info_id'],
                        'info_operacion' => $value['info_operacion'],
                        'info_descripcion' => $value['info_descripcion'],
                        'info_situacion' => "0"
                    ]);
                    
                    $cambiar = $cambio->guardar();
                }
            }

for ($i = 0; $i < $num_elementos; $i++) {
            $valor_info = $informacion_array[$i];
      
      

            $recomendaciones = new Inteligencia([
                'info_operacion' => $id_ope,
                'info_descripcion' => $valor_info,
                'info_situacion' => "1"
            ]);

            $guardado = $recomendaciones->guardar();
        }

        if ($guardado) {
            echo json_encode([
                "mensaje" => "Informacion guardadada correctamente",
                "codigo" => 1,
            ]);
        } else {
            echo json_encode([
                "mensaje" => "Error, Verifique sus datos",
                "codigo" => 0,
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
