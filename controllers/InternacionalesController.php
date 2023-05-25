<?php
namespace Controllers;

use MVC\Router;
use Model\Internacionales;
use Model\Operaciones;
use Model\Asignar_personal;
use Model\Derrota;
use Exception;


class InternacionalesController {


    public static function index(Router $router){

        $paises = Internacionales::fetchArray("SELECT * from paises order by pai_desc_lg asc");



        $router->render('internacionales/index',[

            'paises'=>$paises,

            
        ]);
    }


    public static function getCatalogo(){

        $catalogo = $_GET['catalogo'];
        // echo json_encode($catalogo);
        // exit;

        $informacion= Internacionales::fetchArray("SELECT trim(gra_desc_ct) as grado, trim(per_nom1) || ' ' || trim(per_nom2) || ' ' || trim(per_ape1) || ' ' || trim(per_ape2) as nombre  FROM mper inner join grados on per_grado = gra_codigo inner join morg on per_plaza = org_plaza where per_catalogo = $catalogo and org_dependencia = (select org_dependencia from mper inner join morg on per_plaza = org_plaza where per_catalogo = user)");

        echo json_encode($informacion);
        
    }

    public static function guardar(){

      
        
       
        $_POST['atraque'] = str_replace('T', ' ', $_POST['atraque']);
        $_POST['zarpe'] = str_replace('T', ' ', $_POST['zarpe']);

         
       try {
        $dependencias = Internacionales::fetchArray("SELECT org_dependencia from mper inner join morg on per_plaza = org_plaza where per_catalogo = USER");

        // foreach ($dependencias as $key => $value) {

        //     $dependencia =$value['org_dependencia'];
        // }

   
        $guardar = new Operaciones([

            'ope_fecha_zarpe' => $_POST['zarpe'],
            'ope_fecha_atraque' => $_POST['atraque'],
            'ope_dependencia' => $dependencias[0]['org_dependencia'],
            'ope_distancia' => $_POST['distancia'],

        ]);


        $resultado = $guardar->crear();

        if($resultado['resultado'] == 1){

            $id= $resultado['id'];
            $asignar_personal = new Asignar_personal([

                'asi_operacion' => $id,
                'asi_catalogo' => $_POST['catalogo']


            ]);

            $resultado2 = $asignar_personal->crear();

    


            if($resultado2['resultado'] == 1){

                
                foreach ($_POST['puntos'] as $key => $punto) {
                    $arrayPunto = explode(',',$punto);
                    $latitud = $arrayPunto[0];
                    $longitud = $arrayPunto[1];



          

                    $derrota = new Derrota (
                        [
                            'der_ope'=>$id,
                            'der_latitud' => $latitud,
                            'der_longitud' => $longitud
                        ]
                    );
                    //                     echo json_encode($derrota);
                    // exit;

                    $resultado3 = $derrota->guardar();


                    }

                    if ($resultado3['resultado'] == 1){

                

                        

                        $uniqid = uniqid();
                        $ruta = 'storage/'. $uniqid. ".pdf";

                        $temporal = $_FILES['documento']['tmp_name'];

                        $subido = move_uploaded_file($temporal, $ruta);
                                  
                     

                        if($subido){

                            $internacional = new Internacionales([
    
                                'int_ope' => $id,
                                'int_pais' => $_POST['pais'],
                                'int_documento' => $ruta
    
    
    
    
                            ]);

                            // echo json_encode($internacional);
                            // exit;
                  
                            $resultado4 = $internacional->crear();
                            
                         
                        }else{
                            echo json_encode([
                                "mensaje" => "Ocurrió  un error subiendo el archivo.",
                                "codigo" => 0,
                            ]);
                            exit;
                        }



  
                }
            //     // echo json_encode($latlng);
            //     // exit;



            }


        }


        
        if($resultado4['resultado'] == 1){
            echo json_encode([
                "mensaje" => "Se guardo la operacion.",
                "codigo" => 1,
            ]);
            
        }else{
            echo json_encode([
                "mensaje" => "Ocurrió  un error.",
                "codigo" => 0,
            ]);

        }
    } catch (Exception $e) {
        echo json_encode([
            "detalle" => $e->getMessage(),       
            "mensaje" => "Ocurrió un error en base de datos",

            "codigo" => 4,
        ]);
    }
    }

    public static function buscarAPI(){

        
   

        try {
           $operaciones = Operaciones::fetchArray("SELECT * from codemar_operaciones inner join codemar_internacionales on ope_id = int_ope inner join codemar_asig_personal on ope_id = asi_operacion inner join paises on pai_codigo = int_pais where ope_nacional = 'I' and asi_catalogo = '644112' and ope_sit = 1");

           echo json_encode($operaciones);


        } catch (Exception $e) {
            echo json_encode([
                "detalle" => $e->getMessage(),       
                "mensaje" => "Ocurrió un error en base de datos",
    
                "codigo" => 4,
            ]);
        }


    }

    public static function colocarInfo(){

   $id = $_GET['id'];

//    echo json_encode('zac!!');
//    exit;

        
   

        try {

            $operacion = Operaciones::fetchArray("SELECT * from codemar_operaciones inner join codemar_internacionales on ope_id = int_ope inner join codemar_asig_personal on ope_id = asi_operacion inner join paises on pai_codigo = int_pais where ope_id = $id");


            $datapuntos = Derrota::fetchArray("SELECT der_latitud, der_longitud from codemar_derrota where der_ope = $id and der_situacion != 0");
            foreach ($datapuntos as $punto) {
                $puntos[] = [
                    $punto['der_latitud'],
                    $punto['der_longitud'],
                ];
            }
           

            echo json_encode([

                "operacion" => $operacion,
                "puntos" => $puntos
            ]);



        } catch (Exception $e) {
            echo json_encode([
                "detalle" => $e->getMessage(),       
                "mensaje" => "Ocurrió un error en base de datos",
    
                "codigo" => 4,
            ]);
        }


    }

    
    public static function modificar(){


        

    

       $codigo = $_POST['codigo'];
    
        $_POST['atraque'] = str_replace('T', ' ', $_POST['atraque']);
        $_POST['zarpe'] = str_replace('T', ' ', $_POST['zarpe']);

        if($_FILES['documento']['name'] !=""){

     
    

            try {
                $dependencias = Internacionales::fetchArray("SELECT org_dependencia from mper inner join morg on per_plaza = org_plaza where per_catalogo = USER");
        
   
           
                $guardar = new Operaciones([
                    
                    'ope_id' => $codigo,
                    'ope_fecha_zarpe' => $_POST['zarpe'],
                    'ope_fecha_atraque' => $_POST['atraque'],
                    'ope_dependencia' => $dependencias[0]['org_dependencia'],
                    'ope_distancia' => $_POST['distancia'],
        
                ]);
        
        
                $resultado = $guardar->actualizar();

                if($resultado['resultado'] == 1){
        
                  
                    $idasi = $guardar->getIdasi($_POST['codigo']);

                    $asignar_personal = new Asignar_personal([


                        'asi_id'=> $idasi,
                        'asi_operacion' => $_POST['codigo'],
                        'asi_catalogo' => $_POST['catalogo']
        
        
                    ]);
        
                    $resultado2 = $asignar_personal->actualizar();


        
                    if($resultado2['resultado'] == 1){
                        



                        $idDerrota = $guardar->getIdderrota($_POST['codigo']);
                        
    
                        foreach ($idDerrota as $key => $value) {
                            
                       

                            $idDerrotas= Derrota::find($value['der_id']);
                            $idDerrotas->der_situacion = '0';

                            $idDerrotas->actualizar();



                        }


                        
                        foreach ($_POST['puntos'] as $key => $punto) {
                            $arrayPunto = explode(',',$punto);
                            $latitud = $arrayPunto[0];
                            $longitud = $arrayPunto[1];

                            
                            $derrota1 = new Derrota (
                                [
                       
                                    'der_ope'=>$_POST['codigo'],
                                    'der_latitud' => $latitud,
                                    'der_longitud' => $longitud,
                           
                                ]
                            );


                               
                        $resultado4 = $derrota1->guardar();
        

                            }



                            if ($resultado4['resultado'] == 1){
        
                                $uniqid = uniqid();
                                $ruta = 'storage/'. $uniqid. ".pdf";
                                $temporal = $_FILES['documento']['tmp_name'];
        
                                $subido = move_uploaded_file($temporal, $ruta);
        
                                if($subido){


                                    $nuevoDocumento = Internacionales::find( $_POST['codigo']);
                                    $nuevoDocumento->int_documento= $ruta;
                                    $resultado5= $nuevoDocumento->actualizar();
                                
        
                            
            
                                    
                                }else{
                                    echo json_encode([
                                        "mensaje" => "Ocurrió  un error subiendo el archivo.",
                                        "codigo" => 0,
                                    ]);
                                    exit;
                                }
        
        
        
          
                        }
                  
        
        
                    }
        
        
                }
        
        
                
                if($resultado5['resultado'] == 1){
                    echo json_encode([
                        "mensaje" => "Se modifico la operación exitosamente.",
                        "codigo" => 1,
                    ]);
                    
                }else{
                    echo json_encode([
                        "mensaje" => "Ocurrió  un error.",
                        "codigo" => 0,
                    ]);
        
                }
            } catch (Exception $e) {
                echo json_encode([
                    "detalle" => $e->getMessage(),       
                    "mensaje" => "Ocurrió un error en base de datos",
        
                    "codigo" => 4,
                ]);
            }
            }else{
                try {
                    $dependencias = Internacionales::fetchArray("SELECT org_dependencia from mper inner join morg on per_plaza = org_plaza where per_catalogo = USER");
            
      
                    $guardar = new Operaciones([
                        
                        'ope_id' => $codigo,
                        'ope_fecha_zarpe' => $_POST['zarpe'],
                        'ope_fecha_atraque' => $_POST['atraque'],
                        'ope_dependencia' => $dependencias[0]['org_dependencia'],
                        'ope_distancia' => $_POST['distancia'],
            
                    ]);
            
            
                    $resultado = $guardar->actualizar();
    
              
                    if($resultado['resultado'] == 1){
            
                      
                        $idasi = $guardar->getIdasi($_POST['codigo']);
    
                        $asignar_personal = new Asignar_personal([
    
    
                            'asi_id'=> $idasi,
                            'asi_operacion' => $_POST['codigo'],
                            'asi_catalogo' => $_POST['catalogo']
            
            
                        ]);
            
                        $resultado2 = $asignar_personal->actualizar();
    
            
                        if($resultado2['resultado'] == 1){
                            
    
                            $idDerrota = $guardar->getIdderrota($_POST['codigo']);
                            
                          
                            foreach ($idDerrota as $key => $value) {
                                
                           
    
                                $idDerrotas= Derrota::find($value['der_id']);
                                $idDerrotas->der_situacion = '0';
    
                                $idDerrotas->actualizar();
    
    

                            }
    

                            
                            foreach ($_POST['puntos'] as $key => $punto) {
                                $arrayPunto = explode(',',$punto);
                                $latitud = $arrayPunto[0];
                                $longitud = $arrayPunto[1];
    
                                
                                $derrota1 = new Derrota (
                                    [
                           
                                        'der_ope'=>$_POST['codigo'],
                                        'der_latitud' => $latitud,
                                        'der_longitud' => $longitud,
                               
                                    ]
                                );
    
    
                                   
                            $resultado4 = $derrota1->guardar();
            
    
                                }
            
            
                        }

                        
             
                                
                            if ($resultado4['resultado'] == 1){


                                    $nuevoDocumento = Internacionales::find( $_POST['codigo']);
                                    $nuevoDocumento->int_pais= $_POST['pais'];
                                    $resultado5= $nuevoDocumento->actualizar();
                                

          
                 
        
        
        
          
                        }
            
            
                    }
            
            
                    
                    if($resultado['resultado'] == 1){
                        echo json_encode([
                            "mensaje" => "Se modifico la operación exitosamente.",
                            "codigo" => 1,
                        ]);
                        
                    }else{
                        echo json_encode([
                            "mensaje" => "Ocurrió  un error.",
                            "codigo" => 0,
                        ]);
            
                    }
                } catch (Exception $e) {
                    echo json_encode([
                        "detalle" => $e->getMessage(),       
                        "mensaje" => "Ocurrió un error en base de datos",
            
                        "codigo" => 4,
                    ]);
                }

                
            }
  
 
    }

    public static function eliminar(){


 
             try {
              
                    $operacion= Operaciones::find($_POST['codigo']);
                    $operacion->ope_sit=0;
                    $resultado= $operacion->actualizar();

 
                 if($resultado['resultado'] == 1){

                    $modificar= new Operaciones();
         
                   
                     $idasi = $modificar->getIdasi($_POST['codigo']);

                     $asi_personal= Asignar_personal::find($idasi);
                     $asi_personal->asi_sit = 0;
                     $resultado2= $asi_personal->actualizar();


                     if($resultado2['resultado'] == 1){
                         
 
 
 
                         $idDerrota = $modificar->getIdderrota($_POST['codigo']);
                         
     
                         foreach ($idDerrota as $key => $value) {
                             
                        
 
                             $idDerrotas= Derrota::find($value['der_id']);
                             $idDerrotas->der_situacion = '0';
 
                            $resultado4= $idDerrotas->actualizar();
 
 
 
                         }
 

 
                             if ($resultado4['resultado'] == 1){
     
         
 
                                     $nuevoDocumento = Internacionales::find( $_POST['codigo']);
                                     $nuevoDocumento->int_situacion= 0;
                                     $resultado5= $nuevoDocumento->actualizar();
                                 
         
                             
             
                                     
                                 
         
         
         
           
                         }
                   
         
         
                     }
         
         
                 }
         
         
                 
                 if($resultado5['resultado'] == 1){
                     echo json_encode([
                         "mensaje" => "Se elimino la operación exitosamente.",
                         "resultado" => 1,
                     ]);
                     
                 }else{
                     echo json_encode([
                         "mensaje" => "Ocurrió  un error.",
                         "resultado" => 0,
                     ]);
         
                 }
             } catch (Exception $e) {
                 echo json_encode([
                     "detalle" => $e->getMessage(),       
                     "mensaje" => "Ocurrió un error en base de datos",
         
                     "resultado" => 4,
                 ]);
             }
             
  
     }

    


         
       


}