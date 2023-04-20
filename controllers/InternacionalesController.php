<?php
namespace Controllers;

use MVC\Router;

class InternacionalesController {

    public static function index(Router $router){

        

        $router->render('internacionales/index',[

            
        ]);
    }

}