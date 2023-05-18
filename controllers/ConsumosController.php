<?php

namespace Controllers;

use Model\Derrota;
use Model\Consumos;


use MVC\Router;
use Exception;


class ConsumosController
{

    public static function index(Router $router)
    {
        $router->render('Reporte/consumos', []);
    }


    public static function BuscarInsumos()
    {
        getHeadersApi();
        
     
        $valor = $_GET['id'];
        try {

       

            $datos = Derrota::fetchArray("SELECT * FROM codemar_insumos_operaciones INNER JOIN codemar_unidades_medida on insumo_unidad = uni_id where insumo_situacion = 1 
            ");
        

         

            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public static function BuscarConsumos()
    {
        getHeadersApi();
        $valor = $_GET['id'];
     

        try {

            $datos = Derrota::fetchArray("SELECT con_insumo, sum(con_cantidad) as con_cantidad, insumo_desc as insumo, uni_desc as unidad 
            FROM codemar_consumos inner join codemar_insumos_operaciones on con_insumo = insumo_id 
            inner join codemar_unidades_medida on insumo_unidad = uni_id where con_operacion = $valor and con_situacion = 1 group by con_insumo, insumo, unidad
            ");
        

         

            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }






    public static function GuardarConsumoAPI()
    {

        getHeadersApi();

        // echo json_encode($_POST);
        // exit;

        try {
            $cantidades = $_POST['cantidades'];
            $insumos = $_POST['insumos'];
            $id_ope = $_POST['id_ope'];
           
            // descomponer arrays    
            $cantidades_array = explode(",", $cantidades);
            $insumos_array = explode(",", $insumos);
        
                
            $num_elementos = count($cantidades_array);
       
            $datos = Consumos::fetchArray("SELECT * FROM codemar_consumos where con_operacion = $id_ope and con_situacion = 1");  

            if ($datos) {
               
                foreach ($datos as $key => $value) {
                    $cambio = new Consumos([
                        'con_id' => $value['con_id'],
                        'con_operacion' => $value['con_operacion'],
                        'con_insumo' => $value['con_insumo'],
                        'con_cantidad' => $value['con_cantidad'],
                        'con_situacion' =>  "0"
                    ]);
                    $cambiar = $cambio->guardar();
                }
            }
    

            for ($i = 0; $i < $num_elementos; $i++) {

                $valor_cantidades = $cantidades_array[$i];
                $valor_insumos = $insumos_array[$i];
           
            

                $trabajo_motor = new Consumos([
                    'tra_fallas' => $valor_fallas,
                    'tra_horas' => $valor_horas,
                    'tra_motor' => $valor_ids,
                    'tra_observacion' => $valor_observaciones,
                    'tra_rpm' => $valor_rpm,
                    'tra_operacion' => $id_ope,
                    'tra_situacion' =>  "1"
                ]);
            

                $guardado = $trabajo_motor->guardar();
            
        
              
            }
            if ($guardado) {
                echo json_encode([   
                    "mensaje" => "Trabajo de los motores guardado",
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
