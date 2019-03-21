<?php  namespace eventoClases;
class Evento implements  \jsonSerializable, \abmInterfaces\abm {
	use \updateClases\update;
	private $id;
	private $nombre;
	private $descripcion;
	private $ruta;
	private $participantes;
	private $entradasdisponibles;
	private $aforo;
	private $precioentrada;
	private $lugar;
	private $fecha;
	private $fechasPosibles;

	function __construct($id,$nombre,$descripcion,$ruta,$participantes,$entradasdisponibles,$aforo,$precioentrada,$lugar,$fecha,$fechasPosibles){
		$this->id=$id;
		$this->nombre=$nombre;
		$this->descripcion=$descripcion;
		$this->ruta=$ruta;
		$this->participantes=$participantes;
		$this->entradasdisponibles=$entradasdisponibles;
		$this->aforo=$aforo;
		$this->precioentrada=$precioentrada;
		$this->lugar=$lugar;
		$this->fecha=$fecha;
		$this->fechasPosibles=$fechasPosibles;
	}

	function jsonSerialize(){
   	return [
   		'id'=>$this->id,
		'nombre'=>$this->nombre,
		'descripcion'=>$this->descripcion,
		'ruta'=>$this->ruta,
		'participantes'=>$this->participantes,
		'entradasdisponibles'=>$this->entradasdisponibles,
		'aforo'=>$this->aforo,
		'precioentrada'=>$this->precioentrada,
		'lugar'=>$this->lugar,
		'fecha'=>$this->fecha,
		'fechasPosibles'=>$this->fechasPosibles
   	];
   }

	function alta(){
		$datos = array();
		$params=array(':nombre' => $this->nombre,
					  ':descripcion' => $this->descripcion,
					  ':ruta'=>'sinimagen.jpg');

		$sql="INSERT INTO evento (id,nombre, descripcion, ruta_imagen) VALUES (null, :nombre,:descripcion, :ruta);";
		array_push($datos, array('query'=>$sql,'params'=>$params));
		
		$params=array(':idlugar' => $this->lugar,
					  ':aforo'=>$this->aforo,
					  ':precioentrada'=>$this->precioentrada,
					  ':entradasdisponibles'=>$this->entradasdisponibles);

		$sql="INSERT INTO evento_lugar (id_evento_lugar, id_evento,id_lugar,aforo,precio_entrada,entradas_disponibles) VALUES (null,LAST_INSERT_ID(),:idlugar, :aforo,:precioentrada,:entradasdisponibles);";
		array_push($datos, array('query'=>$sql,'params'=>$params));

		$params=array(':fecha' =>$this->fechasPosibles);
		$sql="INSERT INTO fechas_posibles (id_evento,fecha) VALUES (LAST_INSERT_ID(),:fecha);";
		array_push($datos, array('query'=>$sql,'params'=>$params));
		
		$statement=\funcionesClases\Funciones::doInsert($datos);
	}

	function altaExistente(){
			$datos=array();
	   		$params=array(':idevento'=>$this->id, ':idlugar' => $this->lugar,':aforo'=>$this->aforo,':entradasdisponibles'=>$this->entradasdisponibles,':precioentrada'=>$this->precioentrada);
			$sql="INSERT INTO evento_lugar (id_evento_lugar, id_evento,id_lugar,aforo,precio_entrada,entradas_disponibles) VALUES (null,:idevento,:idlugar,:aforo,:precioentrada,:entradasdisponibles);";
			array_push($datos, array('query'=>$sql,'params'=>$params));
		
		

			$params=array(':fecha' =>$this->fechasPosibles);
			$sql="INSERT INTO fechas_posibles (id_evento,fecha) VALUES (LAST_INSERT_ID(),:fecha);";
			array_push($datos, array('query'=>$sql,'params'=>$params));
		
			$statement=\funcionesClases\Funciones::doInsert($datos);
	}

	function baja($id){}

    function getEventos($fecha1='', $fecha2=''){
		$sql = "
			SELECT
		       evento_lugar.id_evento_lugar as id,    
		       nombre,
               descripcion,
               ruta_imagen as ruta,
               evento_lugar.aforo as aforo,
			   evento_lugar.entradas_disponibles as entradasdisponibles,
		       evento_lugar.precio_entrada as precioentrada,
		       fecha,
		       evento_lugar.id_lugar
		FROM evento inner join evento_lugar ON evento.id = evento_lugar.id_evento";


		if (!empty($fecha1) && !empty($fecha2)) {
			$sql .= " where fecha between :fecha1 and :fecha2";
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
		
		$eventos=array();
  		while ($aux=$statement->fetchObject()) {
  			$participantes = \participanteClases\Participante::getParticipantes($aux->id);
  			$lugar = array('id'=>$aux->id_lugar,'nombre'=>\lugarClases\Lugar::getLugar($aux->id_lugar));
  			$fechasPosibles = Evento::getFechasPosibles($aux->id);

  			$evento = new Evento($aux->id,$aux->nombre,$aux->descripcion,$aux->ruta,$participantes,$aux->entradasdisponibles,$aux->aforo,$aux->precioentrada,$lugar,$aux->fecha,$fechasPosibles);
      		array_push($eventos,$evento);
    	}
      return $eventos;
	}

	 function getEventosExistentes(){
		$sql = "SELECT * FROM evento";
		$statement=\funcionesClases\Funciones::doQuery($sql);
		$eventos=array();
  		while ($aux=$statement->fetchObject()) {
  			$evento = new Evento($aux->id,$aux->nombre,$aux->descripcion,$aux->ruta_imagen,null,null,null,null,null,null,null);
      		array_push($eventos,$evento);
    	}
      return $eventos;
	}

	function getFechasPosibles($idEvento){
		$params=array(':id' => $idEvento);
		$sql = "SELECT fecha FROM fechas_posibles where id_evento = :id";
		$statement=\funcionesClases\Funciones::doQuery($sql,$params);

		$fechas=array();
		while ($aux=$statement->fetch(\PDO::FETCH_NUM)) {
			array_push($fechas,$aux[0]);
		}
		return $fechas;
	}

	function establecerFecha($idEvento, $fecha){
	try{
			$mensaje='';
			$sqlComp = "SELECT id_evento_lugar as res from evento_lugar 
													WHERE id_evento = (SELECT id_evento from evento_lugar
													WHERE id_evento_lugar =:idevento limit 1) AND 
													fecha = :fecha AND id_lugar =(SELECT id_lugar from evento_lugar
													WHERE id_evento_lugar =:idevento limit 1) limit 1";
			$paramsComp = array(':idevento' => $idEvento, ':fecha' => $fecha);
			$statementComp=\funcionesClases\Funciones::doQuery($sqlComp,$paramsComp);
			$auxComp=$statementComp->fetchObject();
		
			if (empty($auxComp->res)) {
				$params=array(':id' => $idEvento, ':fecha' => $fecha);
				$sql="UPDATE evento_lugar SET fecha=:fecha WHERE id_evento_lugar = :id;";
				$datos=array();
				array_push($datos, array('query'=>$sql,'params'=>$params));
				$statement=\funcionesClases\Funciones::doUpdate($datos);
				$mensaje = 'Has establecido la fecha correctamente';
			} else {
				throw new \Exception("Error, no puede añadir un mismo evento dos veces en un mismo día en el mismo lugar");
			}
		} catch (\Exception $e){
			$mensaje = $e->getMessage();
		} finally {
			echo json_encode(array('resp' => $mensaje ));
		}
	}


	function proponerFecha($idEvento, $fecha){
		$datos=array();
		$params=array(':idevento'=>$idEvento,':fecha' =>$fecha);
		$sql="INSERT INTO fechas_posibles (id_evento,fecha) VALUES (:idevento,:fecha);";
		array_push($datos, array('query'=>$sql,'params'=>$params));
		$statement=\funcionesClases\Funciones::doInsert($datos);
	}

	function insertarImagen($idEvento,$rutas){
		$datos=array();
		for ($i=0; $i < count($rutas); $i++) { 
			$sql ="UPDATE evento SET ruta_imagen = :ruta WHERE id=
			(SELECT id_evento from evento_lugar WHERE id_evento_lugar=:id limit 1);";	
			$params=array(':id'=>$idEvento,':ruta'=>$rutas[$i]);
			array_push($datos, array('query'=>$sql,'params'=>$params));
		}
		$statement=\funcionesClases\Funciones::doInsert($datos);
	}


	
}



 ?>
