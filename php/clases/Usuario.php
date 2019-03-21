<?php  namespace usuarioClases;
class Usuario extends \personaClases\Persona implements \JsonSerializable {
	use \updateClases\update;
	private $password;
	private $tipo;
	private $fechaSesion;
	function __construct($id,$identificador,$nombre,$apellido1,$apellido2,$email,$telefono,$direccion,$localidad,$provincia,$password,$tipo,$fechaSesion){
		parent::__construct($id,$identificador,$nombre,$apellido1,$apellido2,$email,$telefono,$direccion,$localidad,$provincia);
		$this->password=$password;
		$this->tipo=$tipo;
		$this->fechaSesion=$fechaSesion;
	}

	function jsonSerialize(){
		return [
			'id'=>$this->id,
			'nif'=>$this->identificador,
			'nombre'=>$this->nombre,
			'apellido1'=>$this->apellido1,
			'apellido2'=>$this->apellido2,
			'email'=>$this->email,
			'telefono'=>$this->telefono,
			'direccion'=>$this->direccion,
			'localidad'=>$this->localidad,
			'provincia'=>$this->provincia,
			'password'=>$this->password,
			'tipo'=>$this->tipo,
			'fechaSesion'=>$this->fechaSesion
		];
	}

	function getUsuario($id){
		$params = array(':id' => $id);
		$sql = "SELECT * from usuario where id=:id";
		$statement=\funcionesClases\Funciones::doQuery($sql,$params);
  		$aux=$statement->fetchObject();
  			$usuario = new Usuario($aux->id,$aux->NIF,$aux->nombre,$aux->apellido1,$aux->apellido2,$aux->email,$aux->telefono,$aux->direccion,$aux->localidad,$aux->provincia,null,$aux->tipo,$aux->fecha_sesion);
      return $usuario;
	}

	function modificarPassword($id,$password,$nuevaPassword){
		if (isset($_SESSION['id'])) {
	      $params=array(':id'=> $id);
	      $sql ="SELECT id, tipo, password, nombre FROM usuario
	        WHERE id=:id";
	      $statement=\funcionesClases\Funciones::doQuery($sql,$params);
	      $datosUsuario=array();
	      while ($aux=$statement->fetchObject()) {
	        $datosUsuario['id']=$aux->id;
	        $datosUsuario['password']=$aux->password;
	      }
	      if (isset($datosUsuario['id'])) {
	        if (password_verify($password,$datosUsuario['password'])) {
	         $nuevaPasswordHasheada = password_hash($nuevaPassword,PASSWORD_BCRYPT);
	         $datos=array();
	         $params = array(
				':id'=>$id,
				':password'=>$nuevaPasswordHasheada);
				$sql ="UPDATE usuario SET password=:password WHERE id=:id";
				array_push($datos,array('query' =>$sql ,'params'=>$params));
				$statement=\funcionesClases\Funciones::doUpdate($datos);
	          return true;
	        } else return false;
	      } else return false;
	    }    
	}


	function __get($var){
		return $this->$var;
	}

	function __set($atr,$val){
		$this->$atr=$val;
	}

	function alta(Usuario $usuario){
		$datos=array();
   		$params=array(':nif'=>$usuario->identificador,
   					  ':nombre'=>$usuario->nombre,
   					  ':apellido1'=>$usuario->apellido1,
   					  ':apellido2'=>$usuario->apellido2, 
   					  ':email'=>$usuario->email, 
   					  ':telefono'=>$usuario->telefono, 
   					  ':direccion'=>$usuario->direccion, 
   					  ':localidad'=>$usuario->localidad,
   					  ':provincia'=>$usuario->provincia,
   					  ':password'=>$usuario->password);

		$sql ="INSERT INTO usuario (id, NIF, nombre, apellido1, apellido2, telefono, email, direccion, localidad, provincia, password, tipo, fecha_sesion) VALUES (NULL,:nif ,:nombre ,:apellido1 ,:apellido2 , :email,:telefono ,:direccion ,:localidad ,:provincia ,:password ,3, CURRENT_TIMESTAMP);";
		
		array_push($datos, array('query'=>$sql,'params'=>$params));
		$statement=\funcionesClases\Funciones::doInsert($datos);
	}
}
 ?>