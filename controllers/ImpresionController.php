<?php

namespace Controllers;

use Classes\Reporte;
use MVC\Router;
use Exception;
use Model\ActiveRecord;

class ImpresionController
{

    public static function index(Router $router)
    {
        try {
            $user = $_SESSION['auth_user'];
            $ope_id = $_GET['ope_id'];
            $operacion = ActiveRecord::fetchFirst("SELECT * from codemar_operaciones INNER JOIN codemar_tipos_operaciones on tipo_id = ope_tipo  where ope_id = $ope_id");            
            $userInfo = ActiveRecord::fetchFirst("SELECT * from mper inner join morg on per_plaza = org_plaza inner join mdep on org_dependencia = dep_llave where per_catalogo = $user ");
            $firmas = ActiveRecord::fetchArray("SELECT gra_codigo, arm_codigo, trim(gra_desc_lg) as grado, trim(arm_desc_lg) as arma, trim(per_nom1) || ' ' || trim(per_nom2) || ' ' || trim(per_ape1) || ' ' || trim(per_ape2) as nombre , per_catalogo  FROM MORG inner join mper on per_plaza = org_plaza inner join grados on per_grado = gra_codigo inner join armas on per_arma = arm_codigo WHERE ORG_DEPENDENCIA = (select org_dependencia from mper inner join morg on per_plaza = org_plaza where per_catalogo = user) AND ORG_SITUACION = 'A' AND (ORG_CEOM in ('O13D90','O81O52') or ORG_CEOM in ('O11E90','O81O50'))");
            $reporte = new Reporte($router, $userInfo);
            $pdf = $reporte->generatePDF();

            $contenido = $router->load('impresion/zarpe', [
                'user' => $userInfo,
                'firmas' => $firmas,
                'operacion' => $operacion,
            ]);


            $pdf->WriteHTML($contenido);
            


            $pdf->Output();
        } catch (Exception $e) {
            echo json_encode([
                "detalle" => $e->getMessage(),       
                "mensaje" => "Ocurrió  un error en base de datos.",

                "codigo" => 4,
            ]);
            exit;
        }
    }
}