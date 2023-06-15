<?php

namespace Controllers;

use Model\Derrota;
use Model\Lecciones;
use MVC\Router;
use Exception;


class LeccionesController
{

    public static function index(Router $router)
    {
        $ope_id = $_GET['id'];
        $ope_identificador = $_GET['identificador'];
        $ope_fecha_zarpe = $_GET['fecha_zarpe'];

        $decoded_id = base64_decode($ope_id);
        $decoded_identificador = base64_decode($ope_identificador);
        $decoded_fecha_zarpe = base64_decode($ope_fecha_zarpe);

        $router->render('Reporte/lecciones', [
            'decoded_id'            =>   $decoded_id,
            'decoded_identificador' =>   $decoded_identificador,
            'decoded_fecha_zarpe'   =>   $decoded_fecha_zarpe
        ]);
    }


    public static function BuscarLecciones()
    {
        getHeadersApi();
        $valor = $_GET['id'];
        // echo json_encode($valor);
        // exit;
        try {

            $datos = Derrota::fetchArray("SELECT * FROM  codemar_recomendaciones where rec_operacion =  $valor and rec_situacion = 1 ");

            if ($datos) {

                echo json_encode($datos);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }


    public static function GuardarLeccionesAPI()
    {

        getHeadersApi();

        // echo json_encode($_POST);
        // exit;

        try {

            $recomendaciones = $_POST['recomendaciones'];
            $id_ope = $_POST['id_ope'];

            // descomponer arrays
            $recomendaciones_array = explode(",", $recomendaciones);



            $num_elementos = count($recomendaciones_array);

            $datos = Lecciones::fetchArray("SELECT * FROM codemar_recomendaciones where rec_operacion = $id_ope and rec_situacion = 1  
           ");
            if ($datos) {
                foreach ($datos as $key => $value) {
                    $cambio = new Lecciones([
                        'rec_id' => $value['rec_id'],
                        'rec_operacion' => $value['rec_operacion'],
                        'rec_recomendacion' => $value['rec_recomendacion'],
                        'rec_situacion'  => "0"
                    ]);
                    $cambiar = $cambio->guardar();
                }
            }

            for ($i = 0; $i < $num_elementos; $i++) {
                $valor_recomendaciones = $recomendaciones_array[$i];



                $recomendaciones = new Lecciones([
                    'rec_operacion' => $id_ope,
                    'rec_recomendacion' => $valor_recomendaciones,
                    'rec_situacion' => "1"
                ]);

                $guardado = $recomendaciones->guardar();
            }

            if ($guardado) {
                echo json_encode([
                    "mensaje" => "Lecciones guardadadas",
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
