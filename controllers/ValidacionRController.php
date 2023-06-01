<?php

namespace Controllers;

use Model\Derrota;

use MVC\Router;
use Exception;
use Model\Ingreso;

class ValidacionRController
{

    public static function index(Router $router)
    {


        $router->render('validacionR/index', []);
    }

    //hace el data table
    public static function BuscarDatosAPI()
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray("SELECT * from codemar_operaciones INNER JOIN codemar_tipos_operaciones on tipo_id = ope_tipo  
                where ope_dependencia = (select org_dependencia from mper inner join morg on per_plaza = org_plaza 
                  where per_catalogo = USER) and ope_sit = 2 order by ope_id asc");

            if ($datos) {
                echo json_encode($datos);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }



    // consulta puntos


    public static function BuscarPuntos($operacion)
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray("SELECT * FROM codemar_derrota  WHERE der_ope = $operacion AND der_situacion = 1");



            if ($datos != null) {
                $puntos = [];
                foreach ($datos as $vuelta) {
                    $puntos[] = [
                        'latitud' =>   $vuelta['der_latitud'],
                        'longitud' =>  $vuelta['der_longitud'],
                        'fecha' =>  $vuelta['der_fecha'],
                    ];
                }
            } else {
            }
            return ($puntos);
        } catch (Exception $e) {
            //  echo json_encode(["error"=>$e->getMessage()]);
        }
    }

    //buscar personal 

    public static function BuscarPersonal($operacion)
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray("
    SELECT trim(gra_desc_ct) || ' ' || trim(per_nom1) || ' ' || trim(per_nom2) || ' ' || trim(per_ape1) || ' ' || trim(per_ape2) as nombre, per_catalogo as catalogo, asi_id FROM codemar_asig_personal inner join mper on  per_catalogo = asi_catalogo inner join grados on per_grado = gra_codigo where asi_sit = 1 and asi_operacion = $operacion order by per_grado, per_catalogo 
    ");



            if ($datos != null) {
                $personal = [];
                foreach ($datos as $vuelta) {
                    $personal[] = [
                        'nombre' =>   $vuelta['nombre'],
                        'catalogo' =>  $vuelta['catalogo'],

                    ];
                }
            } else {
            }
            return ($personal);
        } catch (Exception $e) {
            //  echo json_encode(["error"=>$e->getMessage()]);
        }
    }


// buscar unidades
    public static function BuscarUnidad($operacion)
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray(" SELECT tipo_desc as tipo , emb_nombre as nombre, emb_id as id , asi_id FROM codemar_asig_unidad inner join codemar_embarcaciones on  emb_id = asi_unidad inner join codemar_tipos_embarcaciones on tipo_id = emb_tipo where asi_sit = 1 and asi_operacion = $operacion ");



            if ($datos != null) {
                $personal = [];
                foreach ($datos as $vuelta) {
                    $personal[] = [
                        'tipo' =>   $vuelta['tipo'],
                        'nombre' =>  $vuelta['nombre'],

                    ];
                }
            } else {
            }
            return ($personal);
        } catch (Exception $e) {
            //  echo json_encode(["error"=>$e->getMessage()]);
        }
    }

    //buscar trabajo motores
    public static function BuscarTrabajoMotores($operacion)
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray("SELECT * FROM codemar_trabajo_motores INNER JOIN codemar_motores on mot_id = tra_motor where tra_operacion = $operacion and tra_situacion = 1 ");



            if ($datos != null) {
                $motores = [];
                foreach ($datos as $vuelta) {
                    $motores[] = [
                        'serie' => $vuelta['mot_serie'],
                        'horas' => $vuelta['tra_horas'],
                        'rpm' => $vuelta['tra_rpm'],
                        'fallas' => $vuelta['tra_fallas'],
                        'observaciones' => $vuelta['tra_observacion'],
                    ];
                }
            } else {
            }
            return ($motores);
        } catch (Exception $e) {
            //  echo json_encode(["error"=>$e->getMessage()]);
        }
    }


    // buscar comunicaciones totales 
    public static function BuscarComunicaciones($operacion)
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray("SELECT * FROM codemar_comunicaciones inner join codemar_medios_comunicacion on com_medio = medio_id inner join codemar_receptor_comunicacion on com_receptor = rec_id where com_operacion = $operacion and com_situacion = 1");



            if ($datos != null) {
                $comunicaciones = [];
                foreach ($datos as $vuelta) {
                    $comunicaciones[] = [
                        'medio' => $vuelta['medio_desc'],
                        'receptor' => $vuelta['rec_desc'],
                        'calidad' => $vuelta['com_calidad'],
                        'observaciones' => $vuelta['com_observacion'],
                    ];
                }
            } else {
            }
            return ($comunicaciones);
        } catch (Exception $e) {
            //  echo json_encode(["error"=>$e->getMessage()]);
        }
    }


    //buscar novedades

    public static function BuscarNovedades($operacion)
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray("SELECT * FROM codemar_novedades where nov_operacion = $operacion and nov_situacion = 1 order by nov_fechahora asc");



            if ($datos != null) {
                $novedad = [];
                foreach ($datos as $vuelta) {
                    $novedad[] = [
                        'fecha' => strtoupper(strftime( '%d%b%G',strtotime($vuelta['nov_fechahora']))),
                        'hora' => date('Hi',strtotime($vuelta['nov_fechahora'])),
                        'novedad' => $vuelta['nov_novedad'],
                                 ];
                }
            } else {
            }
            return ($novedad);
        } catch (Exception $e) {
            //  echo json_encode(["error"=>$e->getMessage()]);
        }
    }


    //buscar consumos 

    public static function BuscarConsumos($operacion)
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray("SELECT con_insumo, sum(con_cantidad) as con_cantidad, insumo_desc as insumo, uni_desc as unidad FROM codemar_consumos inner join codemar_insumos_operaciones on con_insumo = insumo_id inner join codemar_unidades_medida on insumo_unidad = uni_id where con_operacion = $operacion and con_situacion = 1 group by con_insumo, insumo, unidad");



            if ($datos != null) {
                $consumos = [];
                foreach ($datos as $vuelta) {
                    $consumos[] = [
                        'insumo' => $vuelta['insumo'],
                        'cantidad' => $vuelta['con_cantidad'],
                        'unidad' => $vuelta['unidad'],
                   
                    ];
                }
            } else {
            }
            return ($consumos);
        } catch (Exception $e) {
            //  echo json_encode(["error"=>$e->getMessage()]);
        }
    }

    // recomendaciones 

    public static function BuscarRecomendaciones($operacion)
    {
        getHeadersApi();

        try {
            getHeadersApi();
            $datos = Derrota::fetchArray("SELECT * FROM codemar_recomendaciones where rec_operacion = $operacion and rec_situacion = 1");



            if ($datos != null) {
                $recomendaciones = [];
                foreach ($datos as $vuelta) {
                    $recomendaciones[] = [
                        'recomendacion' => $vuelta['rec_recomendacion'],                  
                    ];
                }
            } else {
            }
            return ($recomendaciones);
        } catch (Exception $e) {
            //  echo json_encode(["error"=>$e->getMessage()]);
        }
    }

//inteligencia

public static function BuscarInteligencia($operacion)
{
    getHeadersApi();

    try {
        getHeadersApi();
        $datos = Derrota::fetchArray("SELECT * FROM codemar_informacion where info_operacion = $operacion and info_situacion = 1");



        if ($datos != null) {
            $inteligencia = [];
            foreach ($datos as $vuelta) {
                $inteligencia[] = [
                    'informacion' => $vuelta['info_descripcion'],                  
                ];
            }
        } else {
        }
        return ($inteligencia);
    } catch (Exception $e) {
        //  echo json_encode(["error"=>$e->getMessage()]);
    }
}




    //retornar informacion
    public static function BuscarInformacionAPI()
    {
        getHeadersApi();
        try {
            $operacion = $_GET['id'];
            $data = [];


            $datos = Derrota::fetchArray("SELECT * from codemar_operaciones INNER JOIN codemar_tipos_operaciones on tipo_id = ope_tipo  where ope_id = $operacion");
            $data['identificador'] = $datos[0]['ope_identificador'];
            $data['zarpe'] = $datos[0]['ope_fecha_zarpe'];
            $data['atraque'] = $datos[0]['ope_fecha_atraque'];
            $data['tipo'] = $datos[0]['tipo_desc'];


            $puntos = static::BuscarPuntos($operacion);
            $personal = static::BuscarPersonal($operacion);
            $unidades = static::BuscarUnidad($operacion);
            $motores = static::BuscarTrabajoMotores($operacion);
            $consumos = static::BuscarConsumos($operacion);
            $comunicaciones = static::BuscarComunicaciones($operacion);
            $novedades = static::BuscarNovedades($operacion);
            $recomendaciones = static::BuscarRecomendaciones($operacion);
            $inteligencia = static::BuscarInteligencia($operacion);


            $data['unidades'] = $unidades;
            $data['puntos'] = $puntos;
            $data['personal'] = $personal;
            $data['motores'] = $motores;
            $data['consumos'] = $consumos;
            $data['comunicaciones'] = $comunicaciones;
            $data['novedades'] = $novedades;
            $data['recomendaciones'] = $recomendaciones;
            $data['inteligencia'] = $inteligencia;
    
            echo json_encode($data);


          
        } catch (Exception $e) {

            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public static function GuardarAPI()
    {

        getHeadersApi();

        // echo json_encode($_POST);
        // exit;


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
