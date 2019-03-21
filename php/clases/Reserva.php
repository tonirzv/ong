<?php  namespace reservaClases;
class Reserva {
	use \updateClases\update;
	protected $id;
	protected $usuario;	
	protected $cantidad;
	protected $precioTotal;
   protected $fecha;

   function __construct($id,$usuario,$cantidad,$precioTotal,$fecha){
		$this->id=$id;
		$this->usuario=$usuario;
		$this->cantidad=$cantidad;
		$this->precioTotal=$precioTotal;
      $this->fecha=$fecha;
   }
   
   function __set($var,$valor){
   	$this->$var=$valor;
   }

   function __get($var){
   	return $this->$var;
   }
}
 ?>