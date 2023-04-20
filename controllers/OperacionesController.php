<?php

namespace Controllers;

use Exception;
use Model\Asigpersonal;
use Model\Asigunidad;
use Model\Operacion;
use MVC\Router;

class OperacionesController
{

    public static function index(Router $router)
    {
        $busqueda =  Operacion::fetchArray('SELECT * FROM codemar_tipos_operaciones');

        $embarcacion =  Operacion::fetchArray('SELECT * FROM codemar_embarcaciones');

        $router->render('operaciones/index', [

            'busqueda' => $busqueda,
            'embarcacion' => $embarcacion,
        ]);
    }

    public static function catalogoAPI()
    {
        getHeadersApi();
        $catalago = $_GET['catalogo'];

        try {
            $sql = "SELECT per_nom1, per_nom2, per_ape1, per_ape2, per_ape3, per_situacion, gra_desc_lg as grado from mper inner join grados on per_grado= gra_codigo where per_catalogo = $catalago  ";
            $info = Operacion::fetchArray($sql);
            echo json_encode($info);
        } catch (Exception $e) {
            return [
                "detalle" => $e->getMessage(),
                "mensaje" => "Ocurrió un error en la base de datos.",

                "codigo" => 4,
            ];
        }
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        $tipo = $_POST['ope_tipo'];

        $sql = "SELECT dep_llave as dependencia from mper inner join morg on per_plaza = org_plaza inner join mdep on org_dependencia = dep_llave where per_catalogo = user ";
        
      
        $dependencia = Operacion::fetchFirst($sql);
        
        // echo json_encode($fecha_atraque);
        // exit;

        $dependencias =$dependencia['dependencia'];
        
        
        
        $_POST['ope_fecha_atraque'] = str_replace('T', ' ', $_POST['ope_fecha_atraque']);
        $fecha_atraque = $_POST['ope_fecha_atraque'];
        
        $_POST['ope_fecha_zarpe'] = str_replace('T', ' ', $_POST['ope_fecha_zarpe']);
        $fecha_zarpe = $_POST['ope_fecha_zarpe'];
        
        
        // echo json_encode($fecha_atraque);
        // exit;
        
        try {
            $operaciones = new Operacion($_POST);
            $operaciones->ope_tipo = $_POST['ope_tipo'];
            $operaciones->ope_fecha_zarpe = $fecha_zarpe;
            $operaciones->ope_fecha_atraque = $fecha_atraque;
            $operaciones->setSituacion($_POST['ope_situacion']);
            $operaciones->setMision($_POST['ope_mision']);
            $operaciones->setEjecucion($_POST['ope_ejecucion']);
            $operaciones->ope_identificador =  "";
            $operaciones->ope_dependencia =  $dependencias;
            $operaciones->ope_reutilizar = $_POST['ope_reutilizar'];
            $operaciones->ope_distancia = "";
            $operaciones->ope_nacional =  "";
            echo json_encode($operaciones);
            exit;
            $operaciones->guardar();
            

            echo json_encode($operaciones);
            exit;

            $sql = "SELECT ope_id from codemar_operaciones where ope_tipo = $tipo ";
            $valor = Operacion::fetchFirst($sql);
            // echo json_encode($valor);
            // exit;


            $personal = new Asigpersonal();
            $personal->asi_operacion = $_POST['asi_operacion'];
            $personal->asi_catalogo = $_POST['asi_catalogo'];
            $personal->guardar();


            $unidad = new Asigunidad();
            $unidad->asi_operacion = $_POST['asi_operacion'];
            $unidad->asi_unidad = $_POST['asi_unidad'];
            $unidad->guardar();



            $cantidadpersonal = count($_POST['catalogo']);
            $resultados = [];
            for ($i = 0; $i < $cantidadpersonal; $i++) {
                $personas = new Asigpersonal([
                    'asi_id' =>  $_POST['asi_id'][$i] != '' ? $_POST['asi_id'][$i] : null,
                    'asi_operacion' => $_POST['asi_operacion'],
                    'catalogo' => $_POST['catalogo'][$i],

                ]);


                $guardado = $personas->guardar();
                $resultados[] = $guardado['resultado'];
            }

            // echo json_encode($resultados);
            // exit;


            if (!array_search(0, $resultados)) {
                echo json_encode([
                    "mensaje" => "El registro se guardó.",
                    "codigo" => 1,
                ]);
            } else {
                echo json_encode([
                    "mensaje" => "Ocurrió  un error.",
                    "codigo" => 0,
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "detalle" => $e->getMessage(),
                "mensaje" => "Ocurrió  un error en base de datos.",

                "codigo" => 4,
            ]);
        }
    }

    // function getComandante(){
    //     $sql = "SELECT trim(per_nom1) || ' ' || trim(per_nom2) || ' ' || trim(per_ape1) || ' ' || trim(per_ape2) as nombre from mper where per_plaza = (select org_plaza from morg where org_dependencia in (select org_dependencia from mper inner join morg on per_plaza = org_plaza where per_catalogo = user) and org_ceom like '%90' and org_plaza_desc = 'COMANDANTE' and org_grado > 87)";
    //     $resultado = $this->exec_query($sql);
    //     return $resultado[0]['NOMBRE']; 
    // }

    // function getNumeroOperacion(){
    //     $sql = "SELECT  nvl(count(ope_id),0)  + 1 as numero from codemar_operaciones where year(ope_fecha_zarpe) = year(current) and ope_dependencia = (select org_dependencia from mper inner join morg on per_plaza = org_plaza where per_catalogo = USER) and ope_sit != 0 and ope_nacional = 'N'";
    //     $resultado = $this->exec_query($sql);
    //     return $resultado[0]['NUMERO']; 
    // }
    // function getIniciales($cadena, $separador){
    //     $iniciales = '';
    //     $explode = explode($separador,$cadena);
    //     foreach($explode as $x){
    //         $iniciales .=  $x[0];
    //     }
    //     return $iniciales;    
    // }

    // function generaIdentificador(){
    //     $ClsZarpe = new ClsZarpe();
    //     $nombreDependencia = $_SESSION["depCorto"];
    //     // $nombreDependencia = 'COFEN';
    //     $nombreComandante = $ClsZarpe->getComandante();
    //     $nombreUsuario = $ClsZarpe->nombre('user');
    //     $numero = $ClsZarpe->getNumeroOperacion();
    //     $numero = str_pad($numero, 3, "0", STR_PAD_LEFT);

    //     $nombreDependencia = strpos($nombreDependencia,'.') ? getIniciales($nombreDependencia, '.') : $nombreDependencia;
    //     $nombreComandante = getIniciales($nombreComandante, ' ');
    //     $nombreUsuario = strtolower(getIniciales($nombreUsuario, ' '));

        // if(strpos($nombreDependencia,'.')){
        //     $nombreDependencia = getIniciales($nombreDependencia);

        // }
        // $nombreDependencia = str_replace(".","",$nombreDependencia);

    //     $identificador = "RR/OZ-$nombreDependencia-O-$numero-$nombreComandante-$nombreUsuario";
    //     return $identificador;
    // }
}
