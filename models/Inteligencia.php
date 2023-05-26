<?php
namespace Model;

class Inteligencia extends ActiveRecord {
    protected static $idTabla = 'info_id';
    protected static $tabla = 'codemar_informacion';
    protected static $columnasDB = ['info_operacion', 'info_descripcion', 'info_situacion'];

    public $info_id;
    public $info_operacion;
    public $info_descripcion;
    public $info_situacion;

    public function __construct($args = []) {
        $this->info_id = $args['info_id'] ?? null;
        $this->info_operacion = $args['info_operacion'] ?? '';
        $this->info_descripcion = $args['info_descripcion'] ?? '';
        $this->info_situacion = $args['info_situacion'] ?? 1;
    }
}
?>
