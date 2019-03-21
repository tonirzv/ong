angular.module('cabeceraApp',[])
.controller('cabeceraCtrl', function($scope,$http,$location){
	$scope.admin = false;
	$scope.gestor = false;
	$scope.loged = false;
	$scope.registro = true;
	$scope.mylogin = true;

	var peticion = {
					"url" : "php/comunes/comprobarSesion.php",
					"method" : "POST"
					}
	$http(peticion).then(function(resp){
		if (resp.data.resp='OK') {
			$scope.datosUsuario = angular.copy(resp.data);
			$scope.permisos($scope.datosUsuario.permisos);
		} else {
			$scope.admin = false;
			$scope.gestor = false;
			$scope.loged = false;
			$scope.registro = true;
			$scope.mylogin = true;
			$scope.misreservas = false; 
		}
	},function(fail){
		alert("ERROR");
	});

	$scope.logout = function(){
		var peticion = {
						"url" : "php/comunes/logout.php",
						"method" : "POST"
					}
		$http(peticion).then(function(resp){
				$scope.admin = false;
				$scope.gestor = false;
				$scope.loged = false;
				$scope.registro = true;
				$scope.mylogin = true;
				$scope.misreservas = false; 
				$location.path('/home');
		},function(fail){
			alert("ERROR");
		});
	}

	$scope.login = function(){
		var peticion = {
					"url" : "php/comunes/login.php",
					"method" : "POST",
					"data" : {
							email: $scope.email,
							password: $scope.password
						}};				
		$http(peticion).then(function(resp){
			if (resp.data.resultado==true) {
				 $scope.datosUsuario = angular.copy(resp.data.datosUsuario);
				 	 $scope.permisos($scope.datosUsuario.permisos);
				 $('#mLog').modal('toggle');
				 $location.path('/productos');	
			} else {
				alert('Credenciales incorrectas');
			}
		},function(fail){
			alert("ERROR");
		});
	}

	$scope.permisos = function(permisos){
		switch (permisos) {
		 	case '1':
		 		$scope.admin = true;
		 		$scope.gestor = false;
		 		$scope.loged = true;
		 		$scope.registro = false;
		 		$scope.reservas = true;
		 		$scope.mylogin = false;
		 		$scope.misreservas = false; 
		 		break;
		    case '2':
		 		$scope.admin = false;
		 		$scope.gestor = true;
		 		$scope.loged = true;
		 		$scope.registro = false;
		 		$scope.reservas = true;
		 		$scope.mylogin = false;
		 		$scope.misreservas = false; 
		 		break;
		 	case '3':
		 		$scope.admin = false;
		 		$scope.gestor = false;
		 		$scope.loged = true;
		 		$scope.registro = false;
		 		$scope.reservas = false;
		 		$scope.mylogin = false;
		 		$scope.misreservas = true; 
		 		break;
		 	}
	}
	$scope.cerrar = function (){
		$('#mLog').modal('toggle');
	}

	$scope.logear = function(){
		$('#mLog').modal({
			show:true,
			background:false,
			keyboard:false});
	}
}).component('cabecera',{
		templateUrl:'views/cabecera.view.html',
		controller:'cabeceraCtrl'
	});