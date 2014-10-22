
grimmApp.controller('locationPreviewController', ['$scope', '$interval', '$modalInstance', 'GoogleMapApi'.ns(), 'location', function ($scope, $interval, $modalInstance, GoogleMapApi, location) {

    var marker;
    $scope.showMap = false;
    $scope.location = location;

    $scope.center = {
        latitude: location.latitude,
        longitude: location.longitude
    };

    $scope.zoom = 13;

    $scope.mapInstance = {};

    $scope.ok = function () {
        $modalInstance.dismiss('cancel');
    }

    GoogleMapApi.then(function(maps) {
        $scope.showMap = true;

        marker = new google.maps.Marker({
            position: new google.maps.LatLng(location.latitude, location.longitude),
            map: null,
            title: location.name
        });
    });

    $scope.marker = function () {
        marker.setMap($scope.mapInstance.getGMap());
    }
}]);
