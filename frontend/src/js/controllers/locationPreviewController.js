
grimmApp.controller('locationPreviewController', ['$scope', '$interval', '$modalInstance', 'GoogleMapApi'.ns(), 'location', function ($scope, $interval, $modalInstance, GoogleMapApi, location) {
    $scope.location = location;
    $scope.showMap = false;

    $scope.center = {
        latitude: location.latitude,
        longitude: location.longitude
    };
    $scope.zoom = 13;

    function showMap() {
        $scope.showMap = true;
    }

    var showMapTimer = $interval(showMap, 200);

    $scope.ok = function () {
        $interval.cancel(showMapTimer);
        $modalInstance.dismiss('cancel');
    }

    GoogleMapApi.then(function(maps) {

    });
}]);
