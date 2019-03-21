<?php  namespace personaClases;
abstract class Persona {
	protected $id;
	protected $identificador;
	protected $nombre;
	protected $apellido1;
	protected $apellido2;
	protected $email;
	protected $telefono;
	protected $direccion;
	protected $localidad;
	protected $provincia;

	function __construct($id,$identificador,$nombre,$apellido1,$apellido2,$email,$telefono,$direccion,$localidad,$provincia){
		$this->id=$id;
		$this->identificador=$identificador;
		$this->nombre=$nombre;
		$this->apellido1=$apellido1;
		$this->apellido2=$apellido2;
		$this->email=$email;
		$this->telefono=$telefono;
		$this->direccion=$direccion;
		$this->localidad=$localidad;
		$this->provincia=$provincia;
	}

	
	function __set($atr,$val){
		$this->$atr = $val;
	}

	function __get($var){
		return $this->$var;
	}
}
 ?>