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



        $identificador = static::generaIdentificador();
        //  echo json_encode($identificador);
        // exit;
        // $tipo = $_POST['ope_tipo'];

        $sql = "SELECT dep_llave as dependencia from mper inner join morg on per_plaza = org_plaza inner join mdep on org_dependencia = dep_llave where per_catalogo = user ";
        $dependencia = Operacion::fetchFirst($sql);
        // echo json_encode($fecha_atraque);
        // exit;
        $dependencias = $dependencia['dependencia'];
        $fecha_atraque = str_replace('T', ' ', $_POST['ope_fecha_atraque']);
        $fecha_zarpe  = str_replace('T', ' ', $_POST['ope_fecha_zarpe']);
        // echo json_encode($fecha_atraque);
        // exit;
        try {
            $operaciones = new Operacion([

                'ope_tipo' => $_POST['ope_tipo'],
                'ope_fecha_zarpe' => $fecha_zarpe,
                'ope_fecha_atraque' => $fecha_atraque,
                'ope_situacion' => $_POST['ope_situacion'],
                'ope_mision' => $_POST['ope_mision'],
                'ope_ejecucion' => $_POST['ope_ejecucion'],
                'ope_identificador' => $identificador,
                'ope_dependencia' => $dependencias,
                'ope_reutilizar' => $_POST['ope_reutilizar'],

            ]);

            // echo json_encode( $operaciones);
            // exit;
            $ope = $operaciones->crear();


            $resultados = $ope['resultado'];
            $id = $ope['id'];

            // echo json_encode($ope);
            // exit;
       

            $unidad = new Asigunidad([

                'asi_operacion'=> $id,
                'asi_unidad'=> $_POST['asi_unidad'],
                
            ]);
       
            $guardado = $unidad->crear();
      

            $resultados[] = $guardado['resultado'];
            // echo json_encode($unidad);
            //  exit;

            $cantidadpersonal = count($_POST['catalogo']);
            $resultados = [];
           $catalogos =  $_POST['catalogo'];
            for ($i = 0; $i < $cantidadpersonal; $i++) {
                $personas = new Asigpersonal([
                'asi_operacion' => $id,
                'asi_catalogo' => $catalogos[$i],
                ]);

//   echo json_encode($catalogos);
//             exit;

                $guardadopersona = $personas->crear();
                $resultados[] = $guardadopersona['resultado'];
            }
 


          

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

    public static function  getComandante()
    {
        $sql = "SELECT trim(per_nom1) || ' ' || trim(per_nom2) || ' ' || trim(per_ape1) || ' ' || trim(per_ape2) as nombre  from mper where per_plaza = (select org_plaza from morg where org_dependencia in (select org_dependencia from mper inner join morg on per_plaza = org_plaza where per_catalogo = user) and org_ceom like '%90' and org_plaza_desc = 'COMANDANTE' and org_grado > 87)";
        $resultado = Operacion::fetchArray($sql);
        // return $sql;
        return $resultado[0]['nombre']; 
    }

    function getNumeroOperacion()
    {
        $sql = "SELECT  nvl(count(ope_id),0)  + 1 as numero from codemar_operaciones where year(ope_fecha_zarpe) = year(current) and ope_dependencia = (select org_dependencia from mper inner join morg on per_plaza = org_plaza where per_catalogo = USER) and ope_sit != 0 and ope_nacional = 'N'";
        $resultado = Operacion::fetchArray($sql);
        return $resultado[0]['numero'];
    }
    function getIniciales($cadena = "", $separador = "")
    {
        $iniciales = '';
        $explode = explode($separador, $cadena);
        foreach ($explode as $x) {
            $iniciales .=  $x[0];
        }
        return $iniciales;
    }

    function nombre($catalogo)
    {
        $sql = "SELECT trim(per_nom1) || ' ' || trim(per_nom2) || ' ' || trim(per_ape1) || ' ' || trim(per_ape2) as nombre , per_catalogo as catalogo  FROM mper inner join grados on per_grado = gra_codigo  inner join morg on per_plaza = org_plaza where per_catalogo = $catalogo";
        $resultado =  Operacion::fetchArray($sql);
        return $resultado[0]['nombre'];
    }

    function generaIdentificador()
    {

        $sql = "SELECT dep_desc_ct as depCorto FROM mdep  inner join morg on org_dependencia = dep_llave inner join mper on per_plaza = org_plaza where per_catalogo = user";
        $resultado =  Operacion::fetchFirst($sql);
        $nombreDependencia = trim($resultado['depcorto']);
        
        
        $nombreComandante = static::getComandante();
        
        $nombreUsuario = static::nombre('user');
        $numero = static::getNumeroOperacion();
        $numero = str_pad($numero, 3, "0", STR_PAD_LEFT);

        $nombreDependencia = strpos($nombreDependencia, '.') ? static::getIniciales($nombreDependencia, '.') : $nombreDependencia;
        $nombreComandante = static::getIniciales($nombreComandante, ' ');
        $nombreUsuario = strtolower(static::getIniciales($nombreUsuario, ' '));

        if (strpos($nombreDependencia, '.')) {
            $nombreDependencia = static::getIniciales($nombreDependencia);
        }
        $nombreDependencia = str_replace(".", "", $nombreDependencia);

        $identificador = "RR/OZ-$nombreDependencia-O-$numero-$nombreComandante-$nombreUsuario";
        return $identificador;
    }

    function getIdUltimaOperacion(){
        $sql = "SELECT first 1 ope_id as id from codemar_operaciones where ope_dependencia = (select org_dependencia from mper inner join morg on per_plaza = org_plaza where per_catalogo = USER) and ope_sit != 0 order by ope_id desc";
        $resultado =  Operacion::fetchArray($sql);
        return $resultado[0]['ID']; 
    }

    function getUltimaOperacionReutilizable(){
        $sql = "SELECT first 1 * from codemar_operaciones where ope_dependencia = (select org_dependencia from mper inner join morg on per_plaza = org_plaza where per_catalogo = USER) and ope_sit != 0 and ope_reutilizar = 1 and ope_nacional = 'N' order by ope_id desc";
        $resultado =  Operacion::fetchArray($sql);
        return $resultado[0]; 
    }

}
