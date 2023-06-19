<?php

function debuguear($variable) {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) {
    $s = htmlspecialchars($html);
    return $s;
}

// Función que revisa que el usuario este autenticado
function isAuth() {
    session_start();
    if(!isset($_SESSION['login'])) {
        header('Location: /auth/login');
    }
}
function isAuthApi() {
    session_start();
    if(!isset($_SESSION['login'])) {
        echo json_encode(["error" => "NO AUTENTICADO"]);
        exit;
    }
}

function isNotAuth(){
    session_start();
    if(isset($_SESSION['login'])) {
        header('Location: /auth/');
    }
}

function getHeadersApi(){
    return header("Content-type:application/json; charset=utf-8");
}

function asset($ruta){
    return "/". $_ENV['APP_NAME']."/public/" . $ruta;
}

function formatearGrado ($grado, $codigogrado , $arma, $codigoarma){
    $gradoArma = $grado;



    if($codigoarma != 6){
        if($codigogrado != 93 && $codigogrado != 97 && $codigogrado != 92 && $codigogrado != 96){
            $gradoArma .= " DE " . $arma; 

        }
    }
    // OFICIALES SUPERIORES 
    $gradoOficialesSuperiores = [80,82,89,85,88,77,79,81];

    if(array_search($codigogrado, $gradoOficialesSuperiores)){
        switch ($codigoarma) {
            case '6':
                $gradoArma .= " D.E.M.N."; 
                break;
            case '7':
                $gradoArma .= " D.E.M.A."; 

                break;
                
            default:
                $gradoArma .= " D.E.M."; 
             
                break;
        }
    } 

    return $gradoArma;
}
