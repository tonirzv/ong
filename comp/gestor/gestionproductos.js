angular
.module('gestionprodApp',[])
.controller('gestionprodCtrl', function($scope,$http,$location){
	
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



	$scope.miscategorias='todas';
	$scope.mistiendas='todas';
	
	$scope.mostrarproductos = function(){
		var peticion = {
		"url" : "php/gestor/obtenerProductos.php",
		"method" : "POST",
		"data" : {categoria: $scope.miscategorias,tienda: $scope.mistiendas}};

		waitingDialog.show('Cargando...');	
		$http(peticion).then(function(resp){
			waitingDialog.hide();
			for (var i = 0; i < resp.data.productos.length; i++) {
				resp.data.productos[i].fecha = new Date(resp.data.productos[i].fecha);
			}
			$scope.tiendas = resp.data.tiendas;
			$scope.productos = resp.data.productos;
			$scope.categorias = resp.data.categorias;
			$scope.productosexistentes = resp.data.productosexistentes;
			$scope.cantidad = $scope.productos.length;

		},function(fail){
			alert("ERROR");
		});
	}

	$scope.mostrarproductos();

	$scope.datos = {
			id:'',rutas:'',nombre:'',stock:'',precio:'',categoria:'',fecha:'',tienda:'',descripcion:'',estado:''
		};

	$scope.disabledInputs = function(dNombre,dDescripcion,dPrecio,dStock,dCategoria,dFecha,dTiendaSelect,dTiendaSelected,dImgs){
		$scope.disabledNombre = dNombre;
		$scope.disabledDescripcion = dDescripcion;
		$scope.disabledPrecio = dPrecio;
		$scope.disabledStock = dStock;
		$scope.disabledCategoria = dCategoria;
		$scope.disabledFecha = dFecha;
		$scope.disabledTiendaSelect = dTiendaSelect;
		$scope.disabledTiendaSelected = dTiendaSelected;
		$scope.disabledImgs = dImgs;
	}
		
	$scope.modifica = function() {
		// dNombre,dDescripcion,dPrecio,dStock,dCategoria,dFecha,dTiendaSelect,dTiendaSelected
		$scope.disabledInputs(true,true,true,true,true,true,false,true,false);
		$scope.miThis = this;
		(['id','rutas','nombre','stock','precio','categoria','tienda','descripcion','estado']).forEach(function(valor,indice){
            $scope.datos[valor]=$scope.miThis.producto[valor];
		});
		$scope.datos.fecha = new Date($scope.miThis.producto.fecha);

		$scope.modal = {
			'opcion' : ' Editar producto',
			'boton' : 'Guardar cambios',
			'bcolor' : 'bg-primary',
			'pcolor' : 'panel-default',
			'cboton' : 'btn-primary',
			'id' : this.producto.id,
			'datos' : $scope.datos,
			'fecha' : ('').hoy()
		};
		$('#mRef').modal('show');
	}

	$scope.agregar = function() {
		$scope.disabledInputs(true,true,true,true,true,true,true,false,true);
		$scope.datos = {
			id:'',rutas:'',nombre:'',stock:'',precio:'',categoria:'',fecha:'',tienda:'',descripcion:'',estado:''
		};
		$scope.modal = {
			'opcion' : ' Agregar producto',
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
		$scope.datos = {productoid:'',tiendaid:'',stock:'',precio:''};
		$scope.modal = {
			'opcion' : ' Agregar producto existente',
			'boton' : 'Agregar existente',
			'bcolor' : 'bg-success',
			'pcolor' : 'panel-default',
			'cboton' : 'btn-success',
			'id' : $scope.datos.productoid,
			'datos' : $scope.datos,
			'fecha' : ('').hoy()
		};
		$('#mRef2').modal({
			show:true,
			background:false,
			keyboard:false});
	}

	$scope.aEliminar =[];
	$scope.modificaImg = function () {
		$scope.aEliminar =[];
		$scope.miThis = this;
		(['id','rutas','nombre','stock','precio','categoria','tienda','descripcion','estado']).forEach(function(valor,indice){
            $scope.datos[valor]=$scope.miThis.producto[valor];
		});
		$scope.datos.fecha = new Date($scope.miThis.producto.fecha);
		$scope.modal = {
			'opcion' : ' Editar imágenes',
			'boton' : 'Subir imágenes',
			'bcolor' : 'bg-warning',
			'pcolor' : 'panel-default',
			'cboton' : 'btn-success',
			'id' : '',
			'datos' : $scope.datos,
			'fecha' : ('').hoy()
		};
		$('#mRef3').modal({
			show:true,
			background:false,
			keyboard:false });
	}

	$scope.agregarAeliminar = function(){
		$scope.aEliminar.push(this.ruta);
	}

	$scope.eliminarImagenes = function(){
		var peticion = {
						"url" : "php/gestor/eliminarImagenes.php",
						"method" : "POST",
						"data" : {
								idproducto:$scope.modal.datos.id,
								rutas: $scope.aEliminar
							}};					
		$http(peticion).then(function(resp){
			$scope.aEliminar=[];
			alert("Imagenes eliminadas correctamente");
			$scope.mostrarproductos();
		},function(fail){
			alert("ERROR");
		});
	}
	
	$scope.accion = function(){
		switch ($scope.modal.boton) {
			case 'Guardar cambios':
				(['id','rutas','nombre','stock','precio','categoria','tienda','descripcion','estado']).forEach(function(valor,indice){
		            $scope.miThis.producto[valor]=$scope.datos[valor];
				});
				$scope.miThis.producto.fecha = new Date($scope.datos.fecha);

				$('#mRef').modal('toggle');
				var peticion = {
						"url" : "php/gestor/modificarProducto.php",
						"method" : "POST",
						"data" : {
								producto: $scope.miThis.producto,
							}};			
				$http(peticion).then(function(resp){
					$scope.mostrarproductos();
				},function(fail){
					alert("ERROR");
				});
				break;
			case 'Agregar':
				var insertProd ={
								'rutas':$scope.datos.rutas,
								'nombre':$scope.datos.nombre,
								'stock':$scope.datos.stock,
								'precio':$scope.datos.precio,
								'categoria':$scope.datos.categoria,
								'fecha':$scope.datos.fecha,
								'tienda':$scope.datos.tienda,
								'descripcion':$scope.datos.descripcion,
								'estado':$scope.datos.estado
									}
				var peticion = {
						"url" : "php/gestor/proponerProducto.php",
						"method" : "POST",
						"data" : {
								producto: insertProd
							}};
					$http(peticion).then(function(resp){
						$('#mRef').modal('toggle');
						$scope.mostrarproductos();
						},function(fail){
							alert("ERROR");
						});
				break;
			case 'Agregar existente':
				var insertProd ={
								'id':$scope.datos.productoid,
								'tienda':$scope.datos.tiendaid,
								'stock':$scope.datos.stock,
								'precio':$scope.datos.precio
								}
				var peticion = {
						"url" : "php/gestor/proponerProductoExistente.php",
						"method" : "POST",
						"data" : {
								producto: insertProd
							}};
				
				$http(peticion).then(function(resp){
					$('#mRef2').modal('toggle');
					$scope.mostrarproductos();
				},function(fail){
					alert("ERROR");
				});
			break;
			case 'Subir imágenes':
				var file = $scope.file;
				var myID = $scope.modal.datos.id;
				var formData = new FormData();
				formData.append('folder',myID);
				for (var i = 0; i < file.length; i++) {
					formData.append(i, file[i]);
				}
				return $http.post('php/gestor/subirImagenes.php',formData, {
					headers: {
						'Content-type': undefined
					},
					transformRequest: angular.identity
				}).then(function(resp){
					$scope.mostrarproductos();
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

