<?php  namespace participanteClases;
class Participante extends \personaClases\Persona implements \jsonSerializable{
	private $grupo;
	function __construct($id,$identificador,$nombre,$apellido1,$apellido2,$email,$telefono,$direccion,$localidad,$provincia,$grupo){
		parent::__construct($id,$identificador,$nombre,$apellido1,$apellido2,$email,$telefono,$direccion,$localidad,$provincia);
		$this->grupo=$grupo;
	}

	function jsonSerialize(){
		return [
			'id'=>$this->id,
			'nombre'=>$this->nombre,
			'nif'=>$this->identificador, 
			'apellido1'=>$this->apellido1, 
			'apellido2'=>$this->apellido2,
			'email'=>$this->email,
			'telefono'=>$this->telefono,
			'direccion'=>$this->direccion,
			'localidad'=>$this->localidad,
			'provincia'=>$this->provincia,
			'grupo'=>$this->grupo
		];
	}
	
   function __set($var,$valor){
   	$this->$var=$valor;
   }

   function __get($var){
   	return $this->$var;
   }

   function getParticipantes($evento='null'){
		$sql ="SELECT id,NIF,nombre, apellido1,apellido2,email,telefono,direccion,localidad,provincia,grupo
				FROM participante";

		if ($evento !='null') {
			$sql .=" WHERE  id IN (SELECT id_participante  FROM   evento_participante WHERE  id_evento=:evento)";
			$params=array(':evento'=>$evento);
			$statement=\funcionesClases\Funciones::doQuery($sql,$params);
		} else {
			$statement=\funcionesClases\Funciones::doQuery($sql);
		}

		$participantes=array();
  		while ($aux=$statement->fetchObject()) {
  			$grupo=array('id'=>$aux->grupo,'nombre'=>\grupoClases\Grupo::getGrupo($aux->grupo));
  			$participante = new Participante($aux->id,$aux->NIF,$aux->nombre, $aux->apellido1, $aux->apellido2,$aux->email,$aux->telefono,$aux->direccion,$aux->localidad,$aux->provincia,$grupo);
      		array_push($participantes,$participante);
    	}
		return $participantes;
	}

	function getParticipantesWhereNotIn($evento){
		$sql ="SELECT id,NIF,nombre, apellido1,apellido2,email,telefono,direccion,localidad,provincia,grupo
				FROM   participante
				WHERE  id NOT IN (SELECT id_participante  FROM   evento_participante inner join evento_lugar ON  evento_participante.id_evento=evento_lugar.id_evento_lugar WHERE  evento_participante.id_evento=:evento or evento_lugar.fecha =(SELECT fecha from evento_lugar where id_evento_lugar = :evento));";
		
		$params=array(':evento'=>$evento);
		$statement=\funcionesClases\Funciones::doQuery($sql,$params);

		$participantes=array();
  		while ($aux=$statement->fetchObject()) {
  			$grupo=array('id'=>$aux->grupo,'nombre'=>\grupoClases\Grupo::getGrupo($aux->grupo));
  			$participante = new Participante($aux->id,$aux->NIF,$aux->nombre, $aux->apellido1, $aux->apellido2,$aux->email,$aux->telefono,$aux->direccion,$aux->localidad,$aux->provincia,$grupo);
      		array_push($participantes,$participante);
    	}

		return $participantes;
	}

	function eliminarParticipantes($idEvento,$idsParticipantes){
		for ($i=0; $i < count($idsParticipantes); $i++) { 
			$sql ="DELETE FROM evento_participante 
						WHERE id_evento = :idevento AND id_participante = :idparticipante;";
			$params=array(':idevento'=>$idEvento,'idparticipante'=>$idsParticipantes[$i]);
			$statement=\funcionesClases\Funciones::doDelete($sql,$params);	
		}
	}

	function agregarParticipantes($idEvento,$idsParticipantes){
		$datos=array();
		for ($i=0; $i < count($idsParticipantes); $i++) { 
			$sql ="INSERT INTO evento_participante (id_evento,id_participante) 
						VALUES (:idevento,:idparticipante);";	
			$params=array(':idevento'=>$idEvento,'idparticipante'=>$idsParticipantes[$i]);
			array_push($datos, array('query'=>$sql,'params'=>$params));
		}
			$statement=\funcionesClases\Funciones::doInsert($datos);
	}

	function altaParticipante(Participante $participante){
		$datos=array();
		$params = array(':nombre'=>$participante->nombre,':nif'=>$participante->identificador,':apellido1'=>$participante->apellido1,':apellido2'=>$participante->apellido2,':email'=>$participante->email,':telefono'=>$participante->telefono,':direccion'=>$participante->direccion,':localidad'=>$participante->localidad,':provincia'=>$participante->provincia,':grupo'=>$participante->grupo);

		$sql = "INSERT INTO participante (id, NIF, nombre, apellido1, apellido2, email, telefono, direccion, localidad, provincia, grupo) VALUES (NULL,:nif,:nombre,:apellido1,:apellido2,:email,:telefono,:direccion,:localidad, :provincia,:grupo);";
		
		array_push($datos, array('query'=>$sql,'params'=>$params));
		$statement=\funcionesClases\Funciones::doInsert($datos);

	}

	function modificarParticipante(Participante $participante){
		$datos=array();
		$params = array(':id'=>$participante->id,':nombre'=>$participante->nombre,':nif'=>$participante->identificador,':apellido1'=>$participante->apellido1,':apellido2'=>$participante->apellido2,':email'=>$participante->email,':telefono'=>$participante->telefono,':direccion'=>$participante->direccion,':localidad'=>$participante->localidad,':provincia'=>$participante->provincia,':grupo'=>$participante->grupo);

		$sql = "UPDATE participante SET NIF=:nif, nombre=:nombre, apellido1=:apellido1, apellido2=:apellido2, email=:email, telefono=:telefono, direccion=:direccion, localidad=:localidad, provincia=:provincia, grupo=:grupo WHERE id=:id;";
		var_dump($participante);
		array_push($datos, array('query'=>$sql,'params'=>$params));
		$statement=\funcionesClases\Funciones::doUpdate($datos);

	}
}
 ?>