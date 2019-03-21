angular
.module('gestionpartApp',[])
.controller('gestionpartCtrl', function($scope,$http,$location){
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


	$scope.obtenerparticipantes = function(){
			var peticion = {
			"url" : "php/gestor/getParticipantes.php",
			"method" : "POST"
			};
		waitingDialog.show('Cargando...');		
		$http(peticion).then(function(resp){
			waitingDialog.hide();
			$scope.participantes = resp.data.participantes;
			$scope.grupos = resp.data.grupos;
		},function(fail){
			alert("ERROR");
		});
	}
	
	$scope.obtenerparticipantes();

	$scope.datos = {id:'',nombre:'',nif:'',apellido1:'',apellido2:'',email:'',telefono:'',direccion:'',localidad:'',provincia:'',grupo:''};

	$scope.modifica = function() {
		$scope.mostrarB = false;
		$scope.mostrarA = true;
		$scope.miThis = this;
		(['id','nombre','nif','apellido1','apellido2','email','telefono','direccion','localidad','provincia','grupo']).forEach(function(valor,indice){
            $scope.datos[valor]=$scope.miThis.participante[valor];
		});

		$scope.modal = {
			'opcion' : ' Editar participante',
			'boton' : 'Guardar cambios',
			'bcolor' : 'bg-primary',
			'pcolor' : 'panel-default',
			'cboton' : 'btn-primary',
			'id' : this.participante.id,
			'datos' : $scope.datos,
			'fecha' : ('').hoy()
		};
		$('#mRef').modal('show');
	}

	$scope.agregar = function() {
		$scope.mostrarB = true;
		$scope.mostrarA = false;
		$scope.datos = {id:'',nombre:'',nif:'',apellido1:'',apellido2:'',email:'',telefono:'',direccion:'',localidad:'',provincia:'',grupo:''};
		$scope.modal = {
			'opcion' : ' Agregar participante',
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

	$scope.accion = function(){
		switch ($scope.modal.boton) {
			case 'Guardar cambios':
				(['id','nombre','nif','apellido1','apellido2','email','telefono','direccion','localidad','provincia','grupo']).forEach(function(valor,indice){
		            $scope.miThis.participante[valor]=$scope.modal.datos[valor];
				});

				var peticion = {
						"url" : "php/gestor/modificarParticipante.php",
						"method" : "POST",
						"data" : {
								participante: $scope.miThis.participante
							}};
								
				$http(peticion).then(function(resp){
					$('#mRef').modal('toggle');
					$scope.obtenerparticipantes();
				},function(fail){
					alert("ERROR");
				});
				break;
			case 'Agregar':
				var insertParticipante ={
								'nombre':$scope.modal.datos.nombre,
								'nif':$scope.modal.datos.nif,
								'apellido1':$scope.modal.datos.apellido1,
								'apellido2':$scope.modal.datos.apellido2,
								'email':$scope.modal.datos.email,
								'telefono':$scope.modal.datos.telefono,
								'direccion':$scope.modal.datos.direccion,
								'localidad':$scope.modal.datos.localidad,
								'provincia':$scope.modal.datos.provincia,
								'grupo':$scope.modal.datos.idgrupo
									}
				var peticion = {
						"url" : "php/gestor/altaParticipante.php",
						"method" : "POST",
						"data" : {
								participante: insertParticipante
							}};

				$http(peticion).then(function(resp){
					$('#mRef').modal('toggle');
					$scope.obtenerparticipantes();
				},function(fail){
					alert("ERROR");
				});
				break;
			default:
				break;
		}
	}

});