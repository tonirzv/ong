angular
.module('tiendApp',['google-maps'])
.factory('MarkerCreatorService', function () {
    var markerId = 0;

    function create(latitude, longitude) {
        var marker = {
            options: {
                animation: 1,
                labelAnchor: "28 -5",
                labelClass: 'markerlabel'    
            },
            latitude: latitude,
            longitude: longitude,
            id: ++markerId          
        };
        return marker;        
    }

    function invokeSuccessCallback(successCallback, marker) {
        if (typeof successCallback === 'function') {
            successCallback(marker);
        }
    }

    function createByCoords(latitude, longitude, successCallback) {
        var marker = create(latitude, longitude);
        invokeSuccessCallback(successCallback, marker);
    }

    function createByAddress(address, successCallback) {
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'address' : address}, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                var firstAddress = results[0];
                var latitude = firstAddress.geometry.location.lat();
                var longitude = firstAddress.geometry.location.lng();
                var marker = create(latitude, longitude);
                invokeSuccessCallback(successCallback, marker);
            } else {
                alert("Unknown address: " + address);
            }
        });
    }

    function createByCurrentLocation(successCallback) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var marker = create(position.coords.latitude, position.coords.longitude);
                invokeSuccessCallback(successCallback, marker);
            });
        } else {
            alert('Unable to locate current position');
        }
    }

    return {
        createByCoords: createByCoords,
        createByAddress: createByAddress,
        createByCurrentLocation: createByCurrentLocation
    };

})
.controller('tiendCtrl', function($scope,MarkerCreatorService,$http){
		var peticion = {
			"url" : "php/comunes/getTiendas.php",
			"method" : "POST"
			};
			waitingDialog.show('Cargando...');	
		$http(peticion).then(function(resp){
			waitingDialog.hide();
			$scope.tiendas = angular.copy(resp.data.tiendas);
		},function(fail){
			alert("Error en la petición");
		});

		$scope.mostrarMapa= function(){
				$scope.tienda = this.tienda;
		        MarkerCreatorService.createByCoords($scope.tienda.latitud,$scope.tienda.longitud, function (marker) {
		            marker.options.labelContent = 'Tienda '+$scope.tienda.nombre;
		            $scope.autentiaMarker = marker;
		        });
		        
		        $scope.address = '';

		        $scope.map = {
		            center: {
		                latitude: $scope.autentiaMarker.latitude,
		                longitude: $scope.autentiaMarker.longitude
		            },
		            zoom: 12,
		            markers: [],
		            control: {},
		            options: {
		                scrollwheel: false
		            }
		        };

		        $scope.map.markers.push($scope.autentiaMarker);	

		        $('#mMapa').modal({
					show:true,
					background:false,
					keyboard:false});
		}

		var latitud =42.2487304;
		var longitud =-8.6790253;

		MarkerCreatorService.createByCoords(latitud,longitud, function (marker) {
		            marker.options.labelContent = 'Tienda';
		            $scope.autentiaMarker = marker;
		        });
		        
		        $scope.address = '';

		        $scope.map = {
		            center: {
		                latitude: $scope.autentiaMarker.latitude,
		                longitude: $scope.autentiaMarker.longitude
		            },
		            zoom: 12,
		            markers: [],
		            control: {},
		            options: {
		                scrollwheel: false
		            }
		        };

		        $scope.map.markers.push($scope.autentiaMarker);	

 
})
.component('mapa',{
	templateUrl:'views/mapa.view.html',
	controller:'tiendCtrl'
})


