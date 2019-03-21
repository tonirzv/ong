angular
.module('gestionevenApp',[])
.controller('gestionevenCtrl', function($scope,$http,$location){
	var peticion = {
					"url" : "php/comunes/comprobarSesion.php",
					"method" : "POST"
					}
	$http(peticion).then(function(resp){
		if (resp.data.resp='OK') {
			if (resp.data.permisos!='2') {
				$location.path('/home');
			}}
	},function(fail){
		alert("ERROR");
	});


	 $scope.obtenerEventos = function (){	
			$scope.fecha1='';
			$scope.fecha2='';
			var peticion = {
				"url" : "php/gestor/obtenerEventos.php",
				"method" : "POST",
				"data" : {fecha1: $scope.fecha1, fecha2:$scope.fecha2}};
			waitingDialog.show('Cargando...');		
		$http(peticion).then(function(resp){
				waitingDialog.hide();
				for (var i = 0; i < resp.data.eventos.length; i++) {
					if (resp.data.eventos[i].fecha != undefined) {	
					resp.data.eventos[i].fecha = new Date(resp.data.eventos[i].fecha);
					} else {
						resp.data.eventos[i].fecha = '';
					}
					for (var j = 0; j < resp.data.eventos[i].fechasPosibles.length; j++) {
						if (resp.data.eventos[i].fechasPosibles[j] != undefined) {	
						resp.data.eventos[i].fechasPosibles[j] = new Date(resp.data.eventos[i].fechasPosibles[j]);
						} else {
							resp.data.eventos[i].fechasPosibles[j] = '';
						}
					}
				}
				$scope.eventos = angular.copy(resp.data.eventos);
				$scope.lugares = angular.copy(resp.data.lugares);
				$scope.eventosexistentes = angular.copy(resp.data.eventosexistentes);
		},function(fail){
			alert("Error en la petición");
		});

		$scope.datos = {
			id:'',nombre:'',ruta:'',descripcion:'',participantes:'',entradasdisponibles:'',precioentrada:'',lugar:'',fecha:'',fechasPosibles:''
		}
	 }

	$scope.obtenerEventos();

	$scope.disabledInputs = function(dNombre,dDescripcion,dID,dRuta,dEntradasDisp,dPrecioEntrada,dLugar,dFechaDef,dFechasPosibles,dFechas){
		$scope.disabledNombre = dNombre;
		$scope.disabledDescripcion = dDescripcion;
		$scope.disabledID = dID;
		$scope.disabledRuta = dRuta;
		$scope.disabledEntradasDisp = dEntradasDisp;
		$scope.disabledPrecioEntrada = dPrecioEntrada;
		$scope.disabledLugar = dLugar;
		$scope.disabledFechaDef = dFechaDef;
		$scope.disabledFechasPosibles = dFechasPosibles;
		$scope.disabledFechas = dFechas;
	}

	$scope.modifica = function() {
		// Nombre Descripcion ID Ruta EntradasDisp PrecioEntrada Lugar FechaDef FechasPosibles
		$scope.disabledInputs(true,true,false,true,true,true,false,false,false,false);
		$scope.miThis = this;
		(['id','nombre','ruta','descripcion','participantes','entradasdisponibles','aforo','precioentrada','lugar','fecha','fechasPosibles']).forEach(function(valor,indice){
            $scope.datos[valor]=$scope.miThis.evento[valor];
		});
		$scope.datos.fecha = new Date($scope.miThis.evento.fecha);

		for (var i = 0; i < $scope.datos.fechasPosibles.length; i++) {
			$scope.datos.fechasPosibles[i]=new Date($scope.miThis.evento.fechasPosibles[i]);
		}

		$scope.modal = {
			'opcion' : ' Editar evento',
			'boton' : 'Guardar cambios',
			'bcolor' : 'bg-primary',
			'pcolor' : 'panel-default',
			'cboton' : 'btn-primary',
			'id' : this.evento.id,
			'datos' : $scope.datos,
			'fecha' : ('').hoy()
		};
		$('#mRef').modal('show');
	}


	$scope.escogeFecha = function() {
		// Nombre Descripcion ID Ruta EntradasDisp PrecioEntrada Lugar FechaDef FechasPosibles
		$scope.disabledInputs(false,false,false,false,false,false,false,true,true,true);
		$scope.miThis = this;
		(['id','nombre','ruta','descripcion','participantes','entradasdisponibles','aforo','precioentrada','lugar','fecha','fechasPosibles']).forEach(function(valor,indice){
            $scope.datos[valor]=$scope.miThis.evento[valor];
		});
		
		if ($scope.miThis.evento.fecha =='' || $scope.miThis.evento.fecha.getFullYear()=='1970') {
			$scope.datos.fecha ='';
		} else {
			$scope.datos.fecha = new Date($scope.miThis.evento.fecha);
		}

		for (var i = 0; i < $scope.datos.fechasPosibles.length; i++) {
			$scope.datos.fechasPosibles[i]=new Date($scope.miThis.evento.fechasPosibles[i]);
		}

		$scope.modal = {
			'opcion' : ' Proponer fecha',
			'boton' : 'Proponer fecha',
			'bcolor' : 'bg-primary',
			'pcolor' : 'panel-default',
			'cboton' : 'btn-primary',
			'id' : this.evento.id,
			'datos' : $scope.datos,
			'fecha' : ('').hoy()
		};
		$('#mRef').modal('show');
	}

	$scope.agregar = function() {
		// Nombre Descripcion ID Ruta EntradasDisp PrecioEntrada Lugar FechaDef FechasPosibles
		$scope.disabledInputs(true,true,true,true,true,true,true,true,true,false);
		$scope.datos = {
			id:'',nombre:'',ruta:'',descripcion:'',participantes:'',entradasdisponibles:'',precioentrada:'',lugar:'',fecha:'',fechapropuesta:''
		};
		$scope.modal = {
			'opcion' : ' Agregar evento',
			'boton' : 'Agregar',
			'bcolor' : 'bg-success',
			'pcolor' : 'panel-default',
			'cboton' : 'btn-success',
			'id' : $scope.datos.id,
			'datos' : $scope.datos,
			'fecha' : ('').hoy()
		};
		$('#mRef').modal({
			show:true,
			background:false,
			keyboard:false});
	}

	$scope.agregarExistente = function() {
		$scope.datos = {eventoid:'',lugarid:'',entradasdisponibles:'',precioentrada:''};
		$scope.modal = {
			'opcion' : ' Agregar producto existente',
			'boton' : 'Agregar existente',
			'bcolor' : 'bg-success',
			'pcolor' : 'panel-default',
			'cboton' : 'btn-success',
			'id' : $scope.datos.eventoid,
			'datos' : $scope.datos,
			'fecha' : ('').hoy()
		};
		$('#mRef3').modal({
			show:true,
			background:false,
			keyboard:false});
	}

	$scope.guardarParticipantes = function(idParticipante){
		var peticion = {
			"url" : "php/gestor/gestionarParticipantes.php",
			"method" : "POST",
			"data" : {aEliminar:$scope.aEliminar, aAgregar:$scope.aAgregar, idEvento:$scope.miThis.evento.id}
			};	
		$http(peticion).then(function(resp){
			$scope.aEliminar=[];
			$scope.aAgregar=[];
			$scope.obtenerEventos();
		},function(fail){
			alert("Error en la petición");
		});
	}

	
	$scope.agregarAeliminar = function(){
		$scope.aEliminar.push(this.participanteEvento.id);
	}

	$scope.agregarAagregar = function(){
		$scope.aAgregar.push(this.participanteAgregar.id);
	}

	$scope.adminParticipantes = function(){
		$scope.aEliminar=[];
		$scope.aAgregar=[];
		$scope.obtenerEventos();
		$scope.miThis = this;
		var peticion = {
			"url" : "php/gestor/obtenerParticipantes.php",
			"method" : "POST",
			"data": {idEvento:$scope.miThis.evento.id}
			};
		waitingDialog.show('Cargando...');		
		$http(peticion).then(function(resp){
			waitingDialog.hide();
				$scope.participantesAgregar = angular.copy(resp.data);
		},function(fail){
			alert("Error en la petición");
		});
		$scope.datos.participantesEvento=angular.copy($scope.miThis.evento.participantes);
		$scope.modal = {
			'opcion' : ' Gestionar participantes',
			'boton' : 'Agregar participante',
			'bcolor' : 'bg-success',
			'pcolor' : 'panel-default',
			'cboton' : 'btn-success',
			'datos' : $scope.datos,
			'fecha' : ('').hoy()
		};


		$('#mRef2').modal({
			show:true,
			background:false,
			keyboard:false});
	 }


	$scope.subirImagen = function () {
		$scope.miThis = this;
		(['id','nombre','ruta','descripcion','participantes','entradasdisponibles','aforo','precioentrada','lugar','fecha','fechasPosibles']).forEach(function(valor,indice){
            $scope.datos[valor]=$scope.miThis.evento[valor];
		});

		$scope.modal = {
			'opcion' : ' Editar imágenes',
			'boton' : 'Subir imagen',
			'bcolor' : 'bg-info',
			'pcolor' : 'panel-info',
			'cboton' : 'btn-success',
			'id' : '',
			'datos' : $scope.datos,
			'fecha' : ('').hoy()
		};
		$('#mRef4').modal({
			show:true,
			background:false,
			keyboard:false});
	}

	$scope.accion = function(){
		switch ($scope.modal.boton) {
			case 'Guardar cambios':
				(['id','nombre','ruta','descripcion','participantes','entradasdisponibles','aforo','precioentrada','lugar','fecha','fechasPosibles']).forEach(function(valor,indice){
		            $scope.miThis.evento[valor]=$scope.datos[valor];
				});
				$scope.miThis.evento.fecha = new Date($scope.datos.fecha);

				for (var i = 0; i < $scope.datos.fechasPosibles.length; i++) {
					$scope.miThis.evento.fechasPosibles[i]=new Date($scope.datos.fechasPosibles[i]);
				}

				$('#mRef').modal('toggle');
				var peticion = {
						"url" : "php/gestor/modificarEvento.php",
						"method" : "POST",
						"data" : {
								evento: $scope.miThis.evento,
								participanteSupr: $scope.participantesSupr,
								participantesInsert: $scope.participantesInsert
							}};				
				$http(peticion).then(function(resp){
					$scope.obtenerEventos();
				},function(fail){
					alert("ERROR");
				});
				break;
			case 'Proponer fecha':
			(['id','nombre','ruta','descripcion','participantes','entradasdisponibles','aforo','precioentrada','lugar','fecha','fechapropuesta']).forEach(function(valor,indice){
		        $scope.miThis.evento[valor]=$scope.datos[valor];
				});

					var peticion = {
						"url" : "php/gestor/proponerFecha.php",
						"method" : "POST",
						"data" : {
								idEvento: $scope.miThis.evento.id,
								fecha: $('#fechapropuesta').val()
							}};
					$http(peticion).then(function(resp){
						$scope.modal.datos.fechapropuesta='';
						$('#mRef').modal('toggle');	
						$scope.obtenerEventos();
					},function(fail){
						alert("ERROR");
					});	
			
				break;
			case 'Agregar':
				var fechapropuesta = new Date($scope.datos.fechapropuesta);
				var insertEve ={
								'nombre':$scope.datos.nombre,
								'descripcion':$scope.datos.descripcion,
								'ruta':$scope.datos.ruta,
								'entradasdisponibles':$scope.datos.entradasdisponibles,
								'aforo':$scope.datos.aforo,
								'precioentrada':$scope.datos.precioentrada,
								'lugar':$scope.datos.lugar,
								'fechapropuesta': $('#fechapropuesta').val()
									};
				var peticion = {
						"url" : "php/gestor/altaEvento.php",
						"method" : "POST",
						"data" : {
								evento: insertEve
							}};		
					$http(peticion).then(function(resp){
						$scope.obtenerEventos();
					},function(fail){
						alert("ERROR");
					});
				$('#mRef').modal('toggle');
				break;
			case 'Agregar existente':
				var insertEve ={
								'idevento':$scope.datos.eventoid,
								'lugar':$scope.datos.lugar,
								'fecha':$scope.datos.fechapropuesta,
								'aforo':$scope.datos.aforo,
								'entradasdisponibles':$scope.datos.entradasdisponibles,
								'precioentrada':$scope.datos.precioentrada
								}
				var peticion = {
						"url" : "php/gestor/proponerEventoExistente.php",
						"method" : "POST",
						"data" : {
								evento: insertEve
							}};			
				$http(peticion).then(function(resp){
					$('#mRef3').modal('toggle');
					$scope.obtenerEventos();
				},function(fail){
					alert("ERROR");
				});
			break;
			case 'Subir imagen':
					var file = $scope.file;
					var myID = $scope.modal.datos.id;
					var formData = new FormData();
					formData.append('id',myID);
					for (var i = 0; i < file.length; i++) {
						formData.append(i, file[i]);
					}
					return $http.post('php/gestor/subirImagenesEv.php',formData, {
						headers: {
							'Content-type': undefined
						},
						transformRequest: angular.identity
					}).then(function(resp){
						$('#mRef4').modal('toggle');
						$scope.obtenerEventos();
					},function(fail){
						alert("ERROR");
					});
				break;
			default:
				break;
		}
	}

})
.filter('vfecha', function() {
	return function vfecha(texto) {
		return (texto)?texto.fv():'';
	}
})
.filter('euros', function() {
	return function (texto,moneda) {
		return parseFloat(texto).formato(moneda);
	}
})
.directive('subirImagen', function($parse){
	return {
		restrict:'A',
		link: function(scope,iElement,iAttrs){
			iElement.on("change",function(e){
				$parse(iAttrs.subirImagen).assign(scope,iElement[0].files);
			});
		}
	}
});