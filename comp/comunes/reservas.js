angular
.module('reserApp',[])
.controller('reserCtrl', function($scope,$http,$location){
	var peticion = {
					"url" : "php/comunes/comprobarSesion.php",
					"method" : "POST"
					}
	$http(peticion).then(function(resp){
		if (resp.data.resp='OK') {
			if (resp.data.permisos!='1' && resp.data.permisos!='3') {
				$location.path('/home');
			}}
	},function(fail){
		alert("ERROR");
	});


	$scope.mostrarReservas = function(){
		var peticion = {
			"url" : "php/comunes/getReservas.php",
			"method" : "POST"
			};
		waitingDialog.show('Cargando...');	
		$http(peticion).then(function(resp){
			waitingDialog.hide();
			$scope.reservasEventos = resp.data.reservasEventos;
			$scope.reservasProductos = resp.data.reservasProductos;
		},function(fail){
			alert("Error en la petición");
		});	
	}
	$scope.mostrarReservas();
})
.filter('euros', function() {
	return function (texto,moneda) {
		return parseFloat(texto).formato(moneda);
		}
})
.filter('vfecha', function() {
	return function vfecha(texto) {
		return (texto)?texto.fv():'';
	}
});