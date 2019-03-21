angular
.module('evenApp',[])
.controller('evenCtrl', function($scope,$http){
    $scope.getHoy = function(){
	  var hoy = new Date;
	  var dd = hoy.getUTCDate();
	  var mm = hoy.getUTCMonth()+1;
	  var yyyy = hoy.getUTCFullYear();
 	  if(dd<10){
          dd='0'+dd
       } 
      if(mm<10){
          mm='0'+mm
      } 
		hoy = yyyy+'-'+mm+'-'+dd;
	  return hoy;
    }

	$('#fecha1').attr('min',$scope.getHoy());
	$('#fecha2').attr('min',$scope.getHoy());
	$('#fecha1').val($scope.getHoy());
	$scope.filtrar = function(){
		$scope.eventos=[];
		if ($('#fecha1').val() > $('#fecha2').val() && $('#fecha2').val()!='') {
			alert('Introduce las fechas correctamente');
		} else {
			
		var peticion = {
			"url" : "php/comunes/getEventos.php",
			"method" : "POST",
			"data" : {fecha1: $('#fecha1').val(), fecha2:$('#fecha2').val()}};
			waitingDialog.show('Cargando...');	
		$http(peticion).then(function(resp){
			waitingDialog.hide();
			for (var i = 0; i < resp.data.eventos.length; i++) {
				if(resp.data.eventos[i].fecha!=null){
					$scope.eventos.push(resp.data.eventos[i]);
				}
			}
		},function(fail){
			alert("Error en la petición");
		});	
		}
	}
	
	$scope.filtrar();

	$scope.detalles = function(){
		$scope.idevento = this.evento.id;
		$scope.modal = {
			'id':this.evento.id,
			'titulo' : this.evento.nombre,
			'descripcion' : this.evento.descripcion,
			'precio' : this.evento.precioentrada,
			'ruta' : this.evento.ruta,
			'participantes':this.evento.participantes,
			'entradasdisponibles':this.evento.entradasdisponibles
		};
		$('#modaleventos').modal({
			show:true,
			background:false,
			keyboard:false});
	}

	$scope.reservarEvento = function (){
		var peticion = {
			"url" : "php/comunes/reservarEvento.php",
			"method" : "POST",
			"data" : {
						reservaEvento: 
						{
						  evento: $scope.modal.id,
						  cantidad: $scope.modal.cantidad,
						  precio: $scope.modal.precio
					    }
					   }
		   };
		   waitingDialog.show('Cargando...');	
		$http(peticion).then(function(resp){
			waitingDialog.hide();
			if (resp.data.resp=='FAIL') {
				$('#modaleventos').modal('toggle');
				$('#mLog').modal({
					show:true,
					background:false,
					keyboard:false});
				alert('Inicie sesión como cliente o regístrese para reservar productos');
			} else {
				$('#modaleventos').modal('toggle');
				alert('Evento reservado correctamente');
				$scope.filtrar();
			}
		},function(fail){
			alert("Error en la petición");
		});
	}
})
.filter('euros', function() {
	return function (texto,moneda) {
		return parseFloat(texto).formato(moneda);
		}
})
.filter('vfecha', function() {
	return function vfecha(texto) {
		return (texto)?texto.vf():'';
	}
});