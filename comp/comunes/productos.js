angular
.module('prodApp',[])
.controller('prodCtrl', function($scope,$http){
	$scope.miscategorias='todas';
	$scope.mistiendas='todas';

	$scope.filtrar = function(){
		var peticion = {
			"url" : "php/comunes/getProductos.php",
			"method" : "POST",
			"data" : {categoria: $scope.miscategorias,tienda: $scope.mistiendas}};
			waitingDialog.show('Cargando...');	
		$http(peticion).then(function(resp){
			waitingDialog.hide();
			$scope.productos = angular.copy(resp.data.productos);
			$scope.tiendas = angular.copy(resp.data.tiendas);
			$scope.categorias = angular.copy(resp.data.categorias);
		},function(fail){
			alert("Error en la petición");
		});
	}

	$scope.filtrar();

	$scope.detalles = function(){
		$scope.idProducto = this.producto.id;
		console.log(this.producto.rutas);
		$scope.modal = {
			'id':this.producto.id,
			'titulo' : this.producto.nombre,
			'descripcion' : this.producto.descripcion,
			'precio' : this.producto.precio,
			'imagenes' : this.producto.rutas,
			'imagenprincipal':this.producto.rutas.rutas[0],
			'stock':this.producto.stock,
			'tienda':this.producto.tienda
		};
		$('#modalProductos').modal({
			show:true,
			background:false,
			keyboard:false});
	}

	$scope.mostrarImagenes = function (){
		$scope.modal.imagenprincipal = this.imagen;
	}

	$scope.reservarProducto = function (){
		var peticion = {
			"url" : "php/comunes/reservarProducto.php",
			"method" : "POST",
			"data" : {
						reservaProducto: 
						{
						  producto: $scope.modal.id,
						  cantidad: $scope.modal.cantidad,
						  precio: $scope.modal.precio,
						  tienda: $scope.modal.tienda
					    }
					   }
		   };	
		$http(peticion).then(function(resp){
			if (resp.data.resp=='FAIL') {
				$('#modalProductos').modal('toggle');
				$('#mLog').modal({
					show:true,
					background:false,
					keyboard:false});
				alert('Inicie sesión como cliente o regístrese para reservar productos');
			} else {
				$('#modalProductos').modal('toggle');
				alert('Producto reservado correctamente');
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
.component('listaProductos',{
	templateUrl:'views/productos.view.html',
	controller:'prodCtrl'
});