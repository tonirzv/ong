angular
.module('gestiongrupoApp',[])
.controller('gestiongrupoCtrl', function($scope,$http,$location){
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


	$scope.obtenergrupos = function(){
			var peticion = {
			"url" : "php/gestor/obtenerGrupos.php",
			"method" : "POST"
			};
		waitingDialog.show('Cargando...');	
		$http(peticion).then(function(resp){
			waitingDialog.hide();
			$scope.grupos = resp.data.grupos;
		},function(fail){
			alert("ERROR");
		});
	}
	
	$scope.obtenergrupos();

	$scope.datos = {id:'',cif:'',nombre:'',domiciliofiscal:'',web:''};

	$scope.modifica = function() {
		$scope.miThis = this;
		(['id','cif','nombre','domiciliofiscal','web']).forEach(function(valor,indice){
            $scope.datos[valor]=$scope.miThis.grupo[valor];
		});

		$scope.modal = {
			'opcion' : ' Editar grupo',
			'boton' : 'Guardar cambios',
			'bcolor' : 'bg-primary',
			'pcolor' : 'panel-default',
			'cboton' : 'btn-primary',
			'id' : this.grupo.id,
			'datos' : $scope.datos,
			'fecha' : ('').hoy()
		};
		$('#mRef').modal('show');
	}

	$scope.agregar = function() {
		$scope.datos = {id:'',cif:'',nombre:'',domiciliofiscal:'',web:''};
		$scope.modal = {
			'opcion' : ' Agregar grupo',
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
				(['id','cif','nombre','domiciliofiscal','web']).forEach(function(valor,indice){
		            $scope.miThis.grupo[valor]=$scope.datos[valor];
				});

				$('#mRef').modal('toggle');
				var peticion = {
						"url" : "php/gestor/modificarGrupo.php",
						"method" : "POST",
						"data" : {
								grupo: $scope.miThis.grupo
							}};			
				$http(peticion).then(function(resp){
					$scope.obtenergrupos();
				},function(fail){
					alert("ERROR");
				});
				break;
			case 'Agregar':
				var insertGrupo ={
								'cif':$scope.datos.cif,
								'nombre':$scope.datos.nombre,
								'domiciliofiscal':$scope.datos.domiciliofiscal,
								'web':$scope.datos.web
									}
				var peticion = {
						"url" : "php/gestor/altaGrupo.php",
						"method" : "POST",
						"data" : {
								grupo: insertGrupo
							}};			
				$http(peticion).then(function(resp){
					$scope.obtenergrupos();
				},function(fail){
					alert("ERROR");
				});
				$('#mRef').modal('toggle');
				break;
			default:
				break;
		}
	}

});