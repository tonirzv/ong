angular
.module('registroApp',[])
.controller('registroCtrl', function($scope,$http,$location){
	$scope.registrarse = function(){
		$scope.flag =true;
		var nuevoUsuario = {
						'nif': $scope.nif,
						'nombre': $scope.nombre,
						'apellido1': $scope.apellido1,
						'apellido2': $scope.apellido2,
						'telefono': $scope.telefono,
						'email': $scope.email,
						'direccion': $scope.direccion,
						'localidad': $scope.localidad,
						'provincia': $scope.provincia,
						'password1': $scope.password1,
						'password2': $scope.password2
						};

		if ($scope.password1==$scope.password2) {
			var peticion = {
				"url" : "php/comunes/registrarse.php",
				"method" : "POST",
				"data": {usuario: nuevoUsuario}
				};
			waitingDialog.show('Cargando...');		
			$http(peticion).then(function(resp){
				waitingDialog.hide();
				if (resp.data.resp=='OK') {
					$location.path('/login');
				}
			},function(fail){
				alert("ERROR");
			});
		} else { alert('Las contraseñas deben coincidir');}
	}
});