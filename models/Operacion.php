<?php

namespace Model;

class Operacion extends ActiveRecord{

    protected static $tabla = 'codemar_operaciones'; //nombre de la tablaX
    protected static $columnasDB = ['OPE_ID','OPE_TIPO','OPE_FECHA_ZARPE','OPE_FECHA_ATRAQUE','OPE_SITUACION', 'OPE_MISION', 'OPE_EJECUCION', 'OPE_IDENTIFICADOR', 'OPE-DEPENDENCIA', 'OPE-REUTILIZAR', 'OPE-DISTANCIA', 'OPE-NACIONAL', 'OPE-SIT'];

    public $ope_id;
    public $ope_tipo;
    public $ope_fecha_zarpe;
    public $ope_fecha_atraque;
    public $ope_situacion;
    public $ope_mision;
    public $ope_ejecucion;
    public $ope_identificador;
    public $ope_dependencia;
    public $ope_reutilizar;
    public $ope_distancia;
    public $ope_nacional;
    public $ope_sit;


    public function __construct($args = []){
        $this->ope_id = $args['ope_id'] ?? null;
        $this->ope_tipo = $args['ope_tipo'] ?? '';
        $this->ope_fecha_zarpe = $args['ope_fecha_zarpe'] ?? '';
        $this->ope_fecha_atraque = $args['ope_fecha_atraque'] ?? '';
        // $this->ope_situacion = $args['ope_situacion'] ?? '';
        $this->ope_situacion = utf8_decode( preg_replace("[\n|\r|\n\r]", "", htmlspecialchars($args['ope_situacion']))) ?? '';
        // $this->ope_mision = $args['ope_mision'] ?? '';
        $this->ope_mision = utf8_decode( preg_replace("[\n|\r|\n\r]", "", htmlspecialchars($args['ope_mision']))) ?? '';
        // $this->ope_ejecucion = $args['ope_ejecucion'] ?? '';
        $this->ope_ejecucion = utf8_decode( preg_replace("[\n|\r|\n\r]", "", htmlspecialchars($args['ope_ejecucion']))) ?? '';
        $this->ope_identificador = $args['ope_identificador'] ?? '';
        $this->ope_dependencia = $args['ope_dependencia'] ?? '';
        $this->ope_reutilizar = $args['ope_reutilizar'] ?? '';
        $this->ope_distancia = $args['ope_distancia'] ?? '0';
        $this->ope_nacional = $args['ope_nacional'] ?? 'N';
        $this->ope_sit = $args['ope_sit'] ?? '1';
    }

    public function setSituacion($ope_situacion){
        $this->ope_situacion = utf8_decode( preg_replace("[\n|\r|\n\r]", "", htmlspecialchars($ope_situacion)));
    }

    public function setMision($ope_mision){
        $this->ope_mision = utf8_decode( preg_replace("[\n|\r|\n\r]", "", htmlspecialchars($ope_mision)));
    }
    public function setEjecucion($ope_ejecucion){
        $this->ope_ejecucion = utf8_decode( preg_replace("[\n|\r|\n\r]", "", htmlspecialchars($ope_ejecucion)));
    }
}