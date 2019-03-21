angular
.module('adminevenApp',[])
.controller('adminevenCtrl', function($scope,$http,$location){
	var peticion = {
					"url" : "php/comunes/comprobarSesion.php",
					"method" : "POST"
					}
	$http(peticion).then(function(resp){
		if (resp.data.resp='OK') {
			if (resp.data.permisos!='1') {
				$location.path('/home');
			}}
	},function(fail){
		alert("ERROR");
	});

			$scope.fecha1='';
			$scope.fecha2='';
	 $scope.obtenerEventos = function (){	
			var peticion = {
				"url" : "php/admin/obtenerEventos.php",
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
		},function(fail){
			alert("Error en la petición");
		});

		$scope.datos = {
			id:'',nombre:'',ruta:'',descripcion:'',participantes:'',entradasdisponibles:'',precioentrada:'',lugar:'',fecha:'',fechasPosibles:''
		}
	 }

	$scope.obtenerEventos();

	$scope.disabledInputs = function(dNombre,dDescripcion,dID,dRuta,dEntradasDisp,dPrecioEntrada,dLugar,dFechaDef,dFechasPosibles){
		$scope.disabledNombre = dNombre;
		$scope.disabledDescripcion = dDescripcion;
		$scope.disabledID = dID;
		$scope.disabledRuta = dRuta;
		$scope.disabledEntradasDisp = dEntradasDisp;
		$scope.disabledPrecioEntrada = dPrecioEntrada;
		$scope.disabledLugar = dLugar;
		$scope.disabledFechaDef = dFechaDef;
		$scope.disabledFechasPosibles = dFechasPosibles;
	}

	$scope.modifica = function() {
		// Nombre Descripcion ID Ruta EntradasDisp PrecioEntrada Lugar FechaDef FechasPosibles
		$scope.disabledInputs(true,true,false,true,true,true,false,false,false);
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
		$scope.disabledInputs(false,false,false,false,false,false,false,true,true);
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
			'opcion' : ' Escoger fecha',
			'boton' : 'Establecer fecha',
			'bcolor' : 'bg-primary',
			'pcolor' : 'panel-default',
			'cboton' : 'btn-primary',
			'id' : this.evento.id,
			'datos' : $scope.datos,
			'fecha' : ('').hoy()
		};
		$('#mRef').modal('show');
	}


	$scope.borrar = function() {
		$scope.miThis = this;
		(['id','nombre','ruta','descripcion','participantes','entradasdisponibles','precioentrada','lugar','fecha','fechasPosibles']).forEach(function(valor,indice){
            $scope.datos[valor]=$scope.miThis.evento[valor];
		});
		$scope.datos.fecha = new Date($scope.miThis.evento.fecha);
		
		for (var i = 0; i < $scope.datos.fechasPosibles.length; i++) {
			$scope.datos.fechasPosibles[i]=new Date($scope.miThis.evento.fechasPosibles[i]);
		}

		$scope.modal = {
			'opcion' : ' Borrar evento',
			'boton' : 'Borrar evento',
			'bcolor' : 'bg-danger',
			'pcolor' : 'panel-default',
			'cboton' : 'btn-danger',
			'id' : this.evento.id,
			'datos' : $scope.datos,
			'fecha' : ('').hoy()
		};
		$('#mRef').modal({
			show:true,
			background:false,
			keyboard:false});
	}

	$scope.agregar = function() {
		$scope.datos = {
			id:'',nombre:'',ruta:'',descripcion:'',participantes:'',entradasdisponibles:'',precioentrada:'',lugar:'',fecha:'',fechasPosibles:''
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

	$scope.guardarParticipantes = function(idParticipante){
		var peticion = {
			"url" : "php/admin/administrarParticipantes.php",
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
			"url" : "php/admin/obtenerParticipantes.php",
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
			'opcion' : ' Administrar participantes',
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
					$scope.miThis.evento.fechasPosibles[i]=$scope.miThis.evento.fechasPosibles[i].toLocaleDateString('en-US');
					$scope.miThis.evento.fechasPosibles[i]=new Date($scope.datos.fechasPosibles[i]);
				}

				$('#mRef').modal('toggle');
				var peticion = {
						"url" : "php/admin/modificarEvento.php",
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
			case 'Establecer fecha':
			(['id','nombre','ruta','descripcion','participantes','entradasdisponibles','aforo','precioentrada','lugar','fecha','fechasPosibles']).forEach(function(valor,indice){
		        $scope.miThis.evento[valor]=$scope.datos[valor];
				});
			$scope.miThis.evento.fecha = $scope.miThis.evento.fecha.toLocaleDateString('en-US');
			$scope.miThis.evento.fecha = new Date($scope.datos.fecha);

			var flag = false;
			for (var i = 0; i < $scope.modal.datos.fechasPosibles.length; i++) {
				if ($scope.modal.datos.fechasPosibles[i].getFullYear()==$scope.modal.datos.fecha.getFullYear()
					&& $scope.modal.datos.fechasPosibles[i].getMonth()==$scope.modal.datos.fecha.getMonth()
					&& $scope.modal.datos.fechasPosibles[i].getDate()==$scope.modal.datos.fecha.getDate()) {
						flag = true;
					var peticion = {
						"url" : "php/admin/establecerFecha.php",
						"method" : "POST",
						"data" : {
								evento: $scope.miThis.evento,
								fechaEstablecida: $('#fechadefinitiva').val()
							}};			
					$http(peticion).then(function(resp){
						$scope.obtenerEventos();
						alert(resp.data.resp);
					},function(fail){
						alert("ERROR");
					});
				}
			}

			 if (flag==false) {
			 		alert("La fecha que indica no está entre las posibles");
					}else {
						$('#mRef').modal('toggle');
					}
				break;
				case 'Subir imagen':
					var file = $scope.file;
					var myID = $scope.modal.datos.id;
					var formData = new FormData();
					formData.append('id',myID);
					for (var i = 0; i < file.length; i++) {
						formData.append(i, file[i]);
					}
					return $http.post('php/admin/subirImagenesEv.php',formData, {
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

}).filter('euros', function() {
	return function (texto,moneda) {
		return parseFloat(texto).formato(moneda);
		}
	})
.filter('vfecha', function() {
	return function vfecha(texto) {
		return (texto)?texto.fv():'';
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

