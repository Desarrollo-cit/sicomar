<?php

namespace Controllers;

use Model\Derrota;

use MVC\Router;
use Exception;


class MotoresController
{

    public static function index(Router $router)
    {
        $router->render('Reporte/motores', []);
    }


    public static function BuscarMotores()
    {
        getHeadersApi();
        $valor = $_GET['id'];

        try {

            $datos = Derrota::fetchFirst(" SELECT asi_unidad as unidad from codemar_asig_unidad where asi_operacion = $valor and asi_sit = 1");
            $embarcacion = $datos['unidad'];

            $motores = Derrota::fetchArray(" SELECT * FROM codemar_motores inner join codemar_embarcaciones on mot_embarcacion = emb_id where emb_situacion = 1 and mot_situacion = 1 and mot_embarcacion = $embarcacion");

            echo json_encode($motores);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }




    public static function Buscartrabajo()
    {
        getHeadersApi();

        try {
            $operacion = $_GET['id'];
            $motor = $_GET['motor'];

            $datos = Derrota::fetchArray(" SELECT * FROM codemar_trabajo_motores where tra_operacion = $operacion and tra_motor = $motor and tra_situacion = 1");
           
            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public static function GuardarTrabajoAPI()
    {

        getHeadersApi();

        echo json_encode($_POST);
        exit;


        try {


            $der_coodigo = $_POST['id'];
            //$der_coodigos = $_POST['puntos'];
            $der_ope = $_POST['puntos'];


            $datos = Derrota::fetchArray("SELECT * FROM codemar_derrota  WHERE der_ope = $der_coodigo AND der_situacion = 1");
            if ($datos) {
                foreach ($datos as $key => $value) {

                    $cambio = new Derrota([

                        'der_id' => $value['der_id'],
                        'der_ope' => $value['der_ope'],
                        'der_latitud' => $value['der_latitud'],
                        'der_longitud' => $value['der_longitud'],
                        'der_fecha' => $value['der_fecha'],
                        'der_situacion' =>  "0"

                    ]);
                    $cambiar = $cambio->guardar();
                }
            }


            foreach ($der_ope as $val) {
                $datos = explode(',', $val);
                $latitud = $datos[0];
                $longitud = $datos[1];
                $fecha = $datos[2];

                $Ingreso = new Derrota([

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

            //     $der_coodigo = $_POST['id'];
            //     //$der_coodigos = $_POST['puntos'];
            //     $der_ope =$_POST['puntos'];
            //     $der_id =$_POST['der_id'];
            // $cuantosId = count($der_id);

            // $val_id = 0;
            // for ($i=0; $i < $cuantosId; $i++) { 
            //     # code...
            // foreach ($der_ope as $val) {
            //     $datos = explode(',', $val);
            //     $latitud = $datos[0];
            //     $longitud = $datos[1];
            //     $fecha = $datos[2];
            // // echo json_encode($der_id[$i]);
            // // exit;

            //     $Ingreso = new Derrota([
            //         'der_id' => $der_id[$i],
            //         'der_ope' =>        $der_coodigo,
            //         'der_latitud' =>    $latitud,
            //         'der_longitud' =>   $longitud,
            //         'der_fecha' =>      $fecha,
            //         'der_situacion' =>  "1"

            //     ]);
            //     $guardado = $Ingreso->guardar(); 


            // }
            // }
            // if ($guardado) {

            //     echo json_encode([

            //         "codigo" => 7,
            //     ]);

            // } else {
            //     echo json_encode([

            //         "codigo" => 2,
            //     ]);
            // }



        } catch (Exception $e) {
            echo json_encode([
                "detalle" => $e->getMessage(),
                "mensaje" => "Ocurrió un error en la base de datos.",
                "codigo" => 4,
            ]);
        }
    }
}
