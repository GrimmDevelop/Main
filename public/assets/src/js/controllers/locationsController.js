
grimmApp.controller('locationsController', ['$scope', 'Locations', function ($scope, Locations) {

    $scope.mode = 'index';
    $scope.locations = [];
    $scope.currentLocation = {};

    Locations.all().success(function(data) {
        $scope.locations = data;
    });

    $scope.index = function(event) {
        $scope.mode = 'index';
        $scope.currentLocation = {};

        if (typeof event !== 'undefined') {
            event.preventDefault();
        }
    };

    $scope.show = function(location) {
        $scope.mode = 'show';
        $scope.currentLocation = location;
    };

}]);
