<?php

namespace Model;

class Internacionales extends ActiveRecord{
    protected static $idTabla = 'int_ope';
    protected static $tabla = 'codemar_internacionales'; //nombre de la tablaX
    protected static $columnasDB = ['INT_OPE','INT_PAIS', 'INT_DOCUMENTO', 'INT_SITUACION'];



    public $int_ope;
    public $int_pais;
    public $int_documento;
    public $int_situacion;
    

    public function __construct($args = []){
        $this->int_ope = $args['int_ope'] ?? null;
        $this->int_pais = $args['int_pais'] ?? '';
        $this->int_documento = $args['int_documento'] ?? '';
        $this->int_situacion = $args['int_situacion'] ?? '1';

    }
}