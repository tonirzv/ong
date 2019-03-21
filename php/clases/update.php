<?php namespace updateClases;
trait update {
	function modificar($objeto,$datosBorrar='',$datosInsertar=''){
		switch (get_class($objeto)) {
			case 'tiendaClases\Tienda':
				$datos = array();
				$params = array(
						':id'=>$objeto->id,
						':nombre'=>$objeto->nombre,
						':direccion'=>$objeto->direccion,
						':cp'=>$objeto->cp,
						':ciudad'=>$objeto->ciudad,
						':telefono'=>$objeto->telefono,
						':correo'=>$objeto->correo,
						':fax'=>$objeto->fax,
						':latitud'=>$objeto->latitud,
						':longitud'=>$objeto->longitud
						);

				$sql = "UPDATE tienda SET 
							nombre =:nombre,
							direccion =:direccion,
							codigo_postal =:cp,
							ciudad =:ciudad,
							telefono =:telefono,
							email =:correo,
							fax =:fax,
							latitud = :latitud,
							longitud = :longitud
							WHERE id = :id;";
				array_push($datos,array('query' =>$sql ,'params'=>$params));
				$statement=\funcionesClases\Funciones::doUpdate($datos);				
				break;

			case 'productoClases\Producto':
				$datos = array();
				$params = array(
							':id'=>$objeto->id,
							':nombre'=>$objeto->nombre,
							':categoria'=>$objeto->categoria,
							':fecha'=>$objeto->fecha,
							':descripcion'=>$objeto->descripcion
							);

				$sql = "UPDATE producto SET 
							nombre = :nombre,
							descripcion = :descripcion,
							categoria = :categoria,
							fecha_fin_campana = :fecha WHERE id =(SELECT id_prod FROM producto_tienda WHERE id_producto_tienda =:id)";
				array_push($datos,array('query' =>$sql ,'params'=>$params));
				
				$params = array(
							':stock'=>$objeto->stock,
							':precio'=>$objeto->precio,
							':id'=>$objeto->id
								);
				$sql = "UPDATE producto_tienda SET
							stock =:stock,
							precio=:precio WHERE id_producto_tienda =:id;";			
				array_push($datos,array('query' =>$sql ,'params'=>$params));
				// - Ejecuto la insercción
				var_dump($datos);
				$statement=\funcionesClases\Funciones::doUpdate($datos);
				break;
			case 'usuarioClases\Usuario':
				$datos = array();
				// - Actualizo los datos de la tabla usuario
				$params = array(
				':id'=>$objeto->id,
				':NIF'=>$objeto->identificador,
				':nombre'=>$objeto->nombre,
				':apellido1'=>$objeto->apellido1,
				':apellido2'=>$objeto->apellido2,
				':email'=>$objeto->email,
				':telefono'=>$objeto->telefono,
				':direccion'=>$objeto->direccion,
				':localidad'=>$objeto->localidad,
				':provincia'=>$objeto->provincia,
				':fecha_sesion'=>$objeto->fechaSesion);

				$sql ="UPDATE usuario SET 
								NIF=:NIF,
								nombre=:nombre,
								apellido1=:apellido1,
								apellido2=:apellido2,
								email=:email,
								telefono=:telefono,
								direccion=:direccion,
								localidad=:localidad,
								provincia=:provincia,
								fecha_sesion=:fecha_sesion WHERE id=:id";

				array_push($datos,array('query' =>$sql ,'params'=>$params));
				$statement=\funcionesClases\Funciones::doUpdate($datos);
				break;
				case 'eventoClases\Evento':
					$datos = array();
					// - Actualizo los datos de la tabla evento
					$params = array(
								':id'=>$objeto->id,
								':nombre'=>$objeto->nombre,
								':descripcion'=>$objeto->descripcion,
								':ruta'=>$objeto->ruta
								);

					$sql = "UPDATE evento SET 
								nombre = :nombre,
								descripcion = :descripcion,
								ruta_imagen = :ruta 
								WHERE id = (SELECT id_evento from evento_lugar where id_evento_lugar=:id);";
					array_push($datos,array('query' =>$sql ,'params'=>$params));
					
					// - Actualizo los datos de la tabla evento_lugar
					$params2 = array(
								':id' => $objeto->id,
								':idlugar' => $objeto->lugar->id,
								':aforo' => $objeto->aforo,
								':precioentrada' => $objeto->precioentrada,
								':entradasdisponibles' => $objeto->entradasdisponibles
								);
					var_dump($params2);

					$sql2 ="UPDATE evento_lugar SET 
								entradas_disponibles = :entradasdisponibles,
								id_lugar=:idlugar,
								aforo = :aforo,
								precio_entrada = :precioentrada
								 WHERE id_evento_lugar = :id;
								";
					array_push($datos,array('query' =>$sql2 ,'params'=>$params2));
					$statement=\funcionesClases\Funciones::doUpdate($datos);
				break;
			default:
				return false;
				break;
		}
	}
}
?>
