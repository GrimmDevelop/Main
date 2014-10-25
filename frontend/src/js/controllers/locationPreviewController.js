
grimmApp.controller('locationPreviewController', ['$scope', '$interval', '$modalInstance', 'GoogleMapApi'.ns(), 'location', function ($scope, $interval, $modalInstance, GoogleMapApi, location) {

    $scope.showMap = false;
    $scope.location = location;

    $scope.center = {
        latitude: location.latitude,
        longitude: location.longitude
    };

    $scope.zoom = 10;

    $scope.mapInstance = {};

    $scope.ok = function () {
        $modalInstance.dismiss('cancel');
    }

    GoogleMapApi.then(function(maps) {
        $scope.showMap = true;

        $interval(loadMarker, 50, 1);
    });

    function loadMarker() {
        if(typeof $scope.mapInstance.getGMap == 'function') {
            new google.maps.Marker({
                position: new google.maps.LatLng(location.latitude, location.longitude),
                map: $scope.mapInstance.getGMap(),
                title: location.name
            });
        } else {
            $interval(loadMarker, 50, 1);
        }
    }

    $scope.marker = function() {
        $scope.center = {
            latitude: location.latitude,
            longitude: location.longitude
        };
    }
}]);
