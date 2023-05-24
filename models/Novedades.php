<?php
namespace Model;

class Novedades extends ActiveRecord {
    protected static $idTabla = 'nov_id';
    protected static $tabla = 'codemar_novedades';
    protected static $columnasDB = ['nov_operacion', 'nov_fechahora', 'nov_novedad', 'nov_situacion'];

    public $nov_id;
    public $nov_operacion;
    public $nov_fechahora;
    public $nov_novedad;
    public $nov_situacion;

    public function __construct($args = []) {
        $this->nov_id = $args['nov_id'] ?? null;
        $this->nov_operacion = $args['nov_operacion'] ?? '';
        $this->nov_fechahora = $args['nov_fechahora'] ?? '';
        $this->nov_novedad = $args['nov_novedad'] ?? '';
        $this->nov_situacion = $args['nov_situacion'] ?? 1;
    }
}
?>
