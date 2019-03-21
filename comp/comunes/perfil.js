angular
.module('perfilApp',[])
.controller('perfilCtrl', function($scope,$http,$location){
	var peticion = {
					"url" : "php/comunes/comprobarSesion.php",
					"method" : "POST"
					}
	$http(peticion).then(function(resp){
		if (resp.data.resp='OK') {
			if (resp.data.permisos!='3' && resp.data.permisos!='2' && resp.data.permisos!='1') {
				$location.path('/home');
			}}
	},function(fail){
		alert("ERROR");
	});


	$scope.obtenerPerfil = function(){
			var peticion = {
			"url" : "php/comunes/obtenerPerfil.php",
			"method" : "POST"
			};
		 waitingDialog.show('Cargando...');	
		$http(peticion).then(function(resp){
			waitingDialog.hide();
			if (resp.data.resp=='OK') {	
			$scope.perfil = angular.copy(resp.data.usuario);
			}
		},function(fail){
			alert("ERROR");
		});
	}
	
	$scope.obtenerPerfil();

	$scope.datos = {id:'',nif:'',nombre:'',apellido1:'',apellido2:'',telefono:'',email:'',direccion:'',localidad:'',provincia:'',password:'',tipo:'',fechaSesion:''
	};

	$scope.modifica = function() {
		(['id','nif','nombre','apellido1','apellido2','telefono','email','direccion','localidad','provincia']).forEach(function(valor,indice){
            $scope.datos[valor]=$scope.perfil[valor];
		});

		$scope.modal = {
			'opcion' : ' Editar perfil',
			'boton' : 'Guardar cambios',
			'bcolor' : 'bg-primary',
			'pcolor' : 'panel-default',
			'cboton' : 'btn-primary',			
			'datos' : $scope.datos,
			'fecha' : ('').hoy()
		};
		$('#mPerfil').modal('show');
	}

	$scope.modificaPassword = function() {
		$scope.modal = {
			'opcion' : ' Cambiar contraseña',
			'boton' : 'Cambiar password',
			'bcolor' : 'bg-primary',
			'pcolor' : 'panel-default',
			'cboton' : 'btn-primary',			
			'datos' : $scope.datos,
			'fecha' : ('').hoy()
		};
		$('#mPassword').modal('show');
	}

	$scope.accion = function(){
		switch ($scope.modal.boton) {
			case 'Guardar cambios':
				$('#mRef').modal('toggle');
				var peticion = {
						"url" : "php/comunes/modificarPerfil.php",
						"method" : "POST",
						"data" : {
								usuario: $scope.modal.datos
							}};			
				$http(peticion).then(function(resp){
					$('#mPerfil').modal('toggle');
					alert("Perfil modificado correctamente");
					$scope.obtenerPerfil();
				},function(fail){
					alert("ERROR");
				});
				break;

			case 'Cambiar password':
			if ($scope.modal.datos.nuevapassword2==$scope.modal.datos.nuevapassword1) {
				var peticion = {
						"url": "php/comunes/modificarPassword.php",
						"method" : "POST",
						"data" : {
								password: $scope.modal.datos.password,
								nuevapassword: $scope.modal.datos.nuevapassword2
								}};
				
				$http(peticion).then(function(resp){
					$('#mPassword').modal('toggle');
					$scope.obtenerPerfil();
				},function(fail){
					alert("ERROR");
				});
			} else {
				alert("Las contraseñas deben coincidir");
			}
				break;
			default:
				break;
		}
	}
});
