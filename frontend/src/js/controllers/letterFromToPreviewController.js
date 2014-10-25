grimmApp.controller('letterFromToPreviewController', ['$scope', '$interval', '$modalInstance', 'GoogleMapApi'.ns(), 'letter', function ($scope, $interval, $modalInstance, GoogleMapApi, letter) {

    $scope.showMap = false;

    $scope.letter = letter;

    $scope.letterIsValid = true;
    if ($scope.letter.from == null || $scope.letter.to == null) {
        $scope.letterIsValid = false;
        return;
    }

    $scope.center = {
        latitude: 51.1758057,
        longitude: 10.4541194
    };
    $scope.zoom = 6;

    $scope.mapInstance = {};

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    }

    var LatLngList, bounds;

    GoogleMapApi.then(function (maps) {
        $scope.showMap = true;

        LatLngList = new Array(
            new google.maps.LatLng(letter.from.latitude, letter.from.longitude),
            new google.maps.LatLng(letter.to.latitude, letter.to.longitude)
        );

        bounds = new google.maps.LatLngBounds();

        for (var i = 0, LtLgLen = LatLngList.length; i < LtLgLen; i++) {
            bounds.extend(LatLngList[i]);
        }

        $interval(loadPolyline, 50, 1);
    });

    function loadPolyline() {
        if(typeof $scope.mapInstance.getGMap == 'function') {
            if(letter.from.id != letter.to.id) {
                new google.maps.Polyline({
                    path: LatLngList,
                    geodesic: true,
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                    strokeWeight: 2,
                    map: $scope.mapInstance.getGMap()
                });
            } else {
                new google.maps.Circle({
                    strokeColor: '#FF0000',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#FF0000',
                    fillOpacity: 0.35,
                    map: $scope.mapInstance.getGMap(),
                    center: LatLngList[0],
                    radius: 2500
                });
            }

            $scope.zoomFit();
        } else {
            $interval(loadPolyline, 50, 1);
        }
    }

    $scope.zoomFit = function () {
        if(letter.from.id != letter.to.id) {
            var map = $scope.mapInstance.getGMap();

            map.fitBounds(bounds);
        } else {
            $scope.center = angular.copy(letter.from);
            $scope.zoom = 8;
        }
    }
}]);
