<?php
namespace Model;

class Trabajo_motores extends ActiveRecord{
    protected static $idTabla = 'tra_id';
    protected static $tabla = 'codemar_trabajo_motores';
    protected static $columnasDB = [ 'tra_operacion', 'tra_motor', 'tra_horas', 'tra_rpm', 'tra_fallas', 'tra_observacion', 'tra_situacion'];

    public $tra_id;
    public $tra_operacion;
    public $tra_motor;
    public $tra_horas;
    public $tra_rpm;
    public $tra_fallas;
    public $tra_observacion;
    public $tra_situacion;

    public function __construct($args = []){
        $this->tra_id = $args['tra_id'] ?? null;
        $this->tra_operacion = $args['tra_operacion'] ?? '';
        $this->tra_motor = $args['tra_motor'] ?? '';
        $this->tra_horas = $args['tra_horas'] ?? '';
        $this->tra_rpm = $args['tra_rpm'] ?? '';
        $this->tra_fallas = $args['tra_fallas'] ?? '';
        $this->tra_observacion = $args['tra_observacion'] ?? '';
        $this->tra_situacion = $args['tra_situacion'] ?? 1;
    }
}

?>