<?php

namespace Model;

class Reporte extends ActiveRecord{

    protected static $tabla = 'corres_asigancion_unidades'; //nombre de la tablaX
    protected static $columnasDB = ['ID','SUBUNIDAD','CATALOGO','SITUACION'];

    public $id;
    public $subunidad;
    public $catalogo;
    public $situacion;


    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->subunidad = $args['subunidad'] ??'';
        $this->catalogo = $args['catalogo'] ??'';
        $this->situacion = $args['situacion'] ?? '1';
    }

}
?>