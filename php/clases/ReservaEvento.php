<?php  namespace reservaEventoClases;
class ReservaEvento extends \reservaClases\Reserva implements \jsonSerializable {	
	private $evento;
	function __construct($id,$usuario,$cantidad,$precioTotal,$fecha,$evento){
		parent::__construct($id,$usuario,$cantidad,$precioTotal,$fecha);
		$this->evento=$evento;
	}

	function jsonSerialize(){
	   	return [
	   		'id'=>$this->id,
			'usuario'=>$this->usuario,
			'evento'=>$this->evento,
			'cantidad'=>$this->cantidad,
			'precioTotal'=>$this->precioTotal,
	    	'fecha'=>$this->fecha
	   		];
    }

    function getReservas($fecha1='',$fecha2='',$id=''){
    	if ($id=='') {
			 $sql = "SELECT * from reserva_event";
			if (!empty($fecha1) && !empty($fecha2)) {
				$sql .= " where fecha >= :fecha1 and  fecha <= :fecha2";
				$params=array(':fecha1' => $fecha1,':fecha2'=>$fecha2);
				$statement=\funcionesClases\Funciones::doQuery($sql,$params);
			} elseif (empty($fecha1) && !empty($fecha2)) {
				$sql .= " where fecha <= :fecha2";
				$params=array(':fecha2'=>$fecha2);
				$statement=\funcionesClases\Funciones::doQuery($sql,$params);
			} elseif (!empty($fecha1) && empty($fecha2)) {
				$sql .= " where fecha >= :fecha1";
				$params=array(':fecha1'=>$fecha1);
				$statement=\funcionesClases\Funciones::doQuery($sql,$params);
			} else {
				$statement=\funcionesClases\Funciones::doQuery($sql);
			}
    	} else {
    		$sql = "SELECT * from reserva_event WHERE id_usuario = :id ORDER BY fecha DESC";
			$params=array(':id' => $id);
			$statement=\funcionesClases\Funciones::doQuery($sql,$params);
    	}

		$reservasEventos=array();
  		while ($aux=$statement->fetchObject()) {
			$sqlUsuario = "SELECT concat(nombre,' ',apellido1,' Tlfn: ', telefono,' e-mail: ', email) as datos from usuario where id =:idusuario;";
  			$paramsUsuario=array(':idusuario' => $aux->id_usuario);
  			$statementUsuario=\funcionesClases\Funciones::doQuery($sqlUsuario,$paramsUsuario);
  			$usuario = $statementUsuario->fetchObject();

  			$sqlEvento = "SELECT nombre from evento where id =(SELECT id_evento from evento_lugar where id_evento_lugar=:idevento limit 1);";
  			$paramsEvento=array(':idevento' => $aux->id_evento);
  			$statementEvento=\funcionesClases\Funciones::doQuery($sqlEvento,$paramsEvento);
  			$evento = $statementEvento->fetchObject();

  			$reserva = new ReservaEvento($aux->id_reserva,$usuario->datos,$aux->cantidad,$aux->precio_total,$aux->fecha,$evento->nombre);
      		array_push($reservasEventos,$reserva);
    	}
      return $reservasEventos;
    }

     function reservarEvento(ReservaEvento $reservaEvento,$precioEvento){
    	$datos = array();
    	$precioTotal = ($reservaEvento->cantidad * $precioEvento);

    	$params = array(':idusuario'=> $reservaEvento->usuario,
    					':idevento'=>$reservaEvento->evento,
    					':cantidad'=>$reservaEvento->cantidad,
    					':preciototal'=>$precioTotal
    					);
		var_dump($params);

    	$sql="INSERT INTO reserva_event (id_reserva, id_usuario, id_evento, cantidad, precio_total,fecha) VALUES (NULL, :idusuario, (SELECT id_evento from evento_lugar where id_evento_lugar=:idevento),:cantidad, :preciototal,CURRENT_DATE);";

    	array_push($datos, array('query'=>$sql,'params'=>$params));
		
		$params = array(
    					':idevento'=>$reservaEvento->evento,
    					':cantidad'=>$reservaEvento->cantidad
    					);
    	$sql="UPDATE evento_lugar SET entradas_disponibles =(entradas_disponibles - :cantidad) WHERE id_evento_lugar=:idevento";
    	array_push($datos, array('query'=>$sql,'params'=>$params));

		$statement=\funcionesClases\Funciones::doInsert($datos);
	}
}
 ?>