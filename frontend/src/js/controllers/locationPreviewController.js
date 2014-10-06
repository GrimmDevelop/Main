
grimmApp.controller('locationPreviewController', ['$scope', '$modalInstance', 'location', function ($scope, $modalInstance, location) {
    $scope.location = location;
    $scope.center = {
        latitude: location.latitude,
        longitude: location.longitude
    };
    $scope.zoom = 13;

    $scope.ok = function () {
        $modalInstance.dismiss('cancel');
    }
}]);
