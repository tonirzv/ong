angular
.module('ongApp',['ngRoute','perfilApp','admintiendApp',
						 'gestionpartApp','gestionprodApp','gestionevenApp',
						 'gestiongrupoApp','adminevenApp','adminprodApp','prodApp',
						 'evenApp','tiendApp','cabeceraApp','perfilApp','adminReserApp','registroApp','reserApp'])
.controller('mainCtrl', function($scope){})
.constant('vistaext','view.html')
.config(function($routeProvider,$locationProvider,vistaext){
	$locationProvider.hashPrefix('');
	$routeProvider
		.when('/home',{
			templateUrl: 'views/home.' +vistaext
		})
		.when('/productos',{
			templateUrl: 'views/productos.' + vistaext,
			controller:'prodCtrl'
		})
		.when('/eventos',{
			templateUrl: 'views/eventos.' + vistaext,
			controller:'evenCtrl'
		})
		.when('/perfil',{
			templateUrl:'views/perfil.'+ vistaext,
			controller:'perfilCtrl'
		})
		.when('/tiendas',{
			templateUrl: 'views/tiendas.' + vistaext,
			controller:'tiendCtrl'
		})
		.when('/mis-reservas',{
			templateUrl: 'views/misreservas.' + vistaext,
			controller:'reserCtrl'
		})
		.when('/admin-tiendas',{
			templateUrl:'views/administrartiendas.'+ vistaext,
			controller:'admintiendCtrl'
		})
		.when('/admin-eventos',{
			templateUrl: 'views/administrareventos.' + vistaext,
			controller:'adminevenCtrl'
		})	
		.when('/admin-productos',{
			templateUrl: 'views/administrarproductos.' + vistaext,
			controller:'adminprodCtrl'
		})
		.when('/gestion-participantes',{
			templateUrl: 'views/gestionarparticipantes.' + vistaext,
			controller:'gestionpartCtrl'
		})		
		.when('/gestion-productos',{
			templateUrl : 'views/gestionarproductos.' + vistaext,
			controller: 'gestionprodCtrl'
		})
		.when('/gestion-eventos',{
			templateUrl: 'views/gestionareventos.' + vistaext,
			controller:'gestionevenCtrl'
		})
		.when('/gestion-grupos',{
			templateUrl: 'views/gestionargrupos.' + vistaext,
			controller:'gestiongrupoCtrl'
		})
		.when('/reservas',{
			templateUrl: 'views/reservas.' + vistaext,
			controller:'adminReserCtrl'
		})
		.when('/registro',{
			templateUrl: 'views/registro.' + vistaext,
			controller:'registroCtrl'
		})
		.otherwise({
			redirectTo:'/home'
		});
});