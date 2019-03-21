angular
.module('admintiendApp',[])
.controller('admintiendCtrl', function($scope,$http,$location){
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


	$scope.obtenertiendas = function(){
			var peticion = {
			"url" : "php/admin/obtenerTiendas.php",
			"method" : "POST"
			};
		waitingDialog.show('Cargando...');	
		$http(peticion).then(function(resp){
			waitingDialog.hide();
			$scope.tiendas = resp.data.tiendas;
		},function(fail){
			alert("ERROR");
		});
	}
	
	$scope.obtenertiendas();

	$scope.datos = {id:'',nombre:'',direccion:'',cp:'',ciudad:'',telefono:'',correo:'',fax:'',latitud:'',longitud:''};

	$scope.modifica = function() {
		$scope.miThis = this;
		(['id','nombre','direccion','cp','ciudad','telefono','correo','fax','latitud','longitud']).forEach(function(valor,indice){
            $scope.datos[valor]=$scope.miThis.tienda[valor];
		});

		$scope.modal = {
			'opcion' : ' Editar tienda',
			'boton' : 'Guardar cambios',
			'bcolor' : 'bg-primary',
			'pcolor' : 'panel-default',
			'cboton' : 'btn-primary',
			'id' : this.tienda.id,
			'datos' : $scope.datos,
			'fecha' : ('').hoy()
		};
		$('#mRef').modal('show');
	}


	$scope.borrar = function() {
		$scope.miThis = this;
		(['id','nombre','direccion','cp','ciudad','telefono','correo','fax','latitud','longitud']).forEach(function(valor,indice){
		            $scope.datos[valor]=$scope.miThis.tienda[valor];
				});

		$scope.modal = {
			'opcion' : ' Borrar tienda',
			'boton' : 'Borrar tienda',
			'bcolor' : 'bg-danger',
			'pcolor' : 'panel-default',
			'cboton' : 'btn-danger',
			'id' : this.tienda.id,
			'datos' : $scope.datos,
			'fecha' : ('').hoy()
		};
		$('#mRef').modal({
			show:true,
			background:false,
			keyboard:false});
	}

	$scope.agregar = function() {
		$scope.datos = {id:'',nombre:'',direccion:'',cp:'',ciudad:'',telefono:'',correo:'',fax:'',latitud:'',longitud:''};
		$scope.modal = {
			'opcion' : ' Agregar tienda',
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
				(['id','nombre','direccion','cp','ciudad','telefono','correo','fax','latitud','longitud']).forEach(function(valor,indice){
		            $scope.miThis.tienda[valor]=$scope.datos[valor];
				});

				$('#mRef').modal('toggle');
				var peticion = {
						"url" : "php/admin/modificarTienda.php",
						"method" : "POST",
						"data" : {
								tienda: $scope.miThis.tienda
							}};
			
				$http(peticion).then(function(resp){
					alert("Tienda modificada correctamente");
					$scope.obtenertiendas();
				},function(fail){
					alert("ERROR");
				});
				break;
			case 'Agregar':
				var insertTienda ={
								'nombre':$scope.datos.nombre,
								'direccion':$scope.datos.direccion,
								'cp':$scope.datos.cp,
								'ciudad':$scope.datos.ciudad,
								'telefono':$scope.datos.telefono,
								'correo':$scope.datos.correo,
								'fax':$scope.datos.fax,
								'latitud':$scope.datos.latitud,
								'longitud':$scope.datos.longitud
									}
				var peticion = {
						"url" : "php/admin/altaTienda.php",
						"method" : "POST",
						"data" : {
								tienda: insertTienda
							}};			
				$http(peticion).then(function(resp){
					$('#mRef').modal('toggle');
					$scope.obtenertiendas();
				},function(fail){
					alert("ERROR");
				});
				break;
			default:
				break;
		}
	}

});