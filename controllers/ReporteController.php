<?php

namespace Controllers;

use Model\Reporte;
use MVC\Router;
use Exception;
use Model\Ingreso;

class ReporteController{

    public static function index(Router $router){
        $router->render('Reporte/index',[]);
    }


    
    public static function BuscarDatosAPI(){


        try {
            getHeadersApi();
            $datos = Reporte::fetchArray("SELECT ope_id, ope_identificador, ope_sit  from codemar_asig_personal inner join codemar_operaciones on asi_operacion = ope_id where asi_catalogo = user and asi_sit = 1 and ope_sit = 1 and ope_nacional = 'N'");
            echo json_encode($datos);     
            
            
        } catch (Exception $e) {
            echo json_encode(["error"=>$e->getMessage()]);
        }
       
    }
}

