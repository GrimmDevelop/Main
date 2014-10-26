grimmApp.controller('distanceMapController', ['$scope', '$interval', '$modalInstance', 'GoogleMapApi'.ns(), 'mapData', function ($scope, $interval, $modalInstance, GoogleMapApi, mapData) {

    $scope.showMap = false;

    $scope.center = {
        latitude: 51.1758057,
        longitude: 10.4541194
    };
    $scope.zoom = 6;

    $scope.mapInstance = {};

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    }

    var addLetterPathsToMap = function (polygonList, map) {
        $.each(polygonList, function (index, polygon) {
            var from = new google.maps.LatLng(
                polygon.from.latitude,
                polygon.from.longitude
            );
            var to = new google.maps.LatLng(
                polygon.to.latitude,
                polygon.to.longitude
            );

            new google.maps.Polyline({
                path: [from, to],
                geodesic: true,
                strokeColor: '#FF0000',
                strokeOpacity: 0.5,
                strokeWeight: 2 + polygon.count * 0.15,
                map: map
            });
        });
    }

    var bounds;
    GoogleMapApi.then(function (maps) {
        $scope.showMap = true;

        bounds = new google.maps.LatLngBounds();
        for (var i = 0, LtLgLen = mapData.borderData.length; i < LtLgLen; i++) {
            bounds.extend(
                new google.maps.LatLng(
                    mapData.borderData[i].latitude,
                    mapData.borderData[i].longitude
                )
            );
        }

        $interval(loadPolylines, 50, 1);
    });

    function loadPolylines() {
        if (typeof $scope.mapInstance.getGMap == 'function') {
            addLetterPathsToMap(mapData.polylines, $scope.mapInstance.getGMap());
            $scope.zoomFit();
        } else {
            $interval(loadPolylines, 50, 1);
        }
    }

    $scope.zoomFit = function () {
        if (typeof $scope.mapInstance.getGMap == 'function') {
            $scope.mapInstance.getGMap().fitBounds(bounds);
        }
    }
}]);
