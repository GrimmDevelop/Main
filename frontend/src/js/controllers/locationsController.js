grimmApp.controller('locationsController', ['$scope', 'Locations', function ($scope, Locations) {

    $scope.mode = 'index';
    $scope.locations = [];
    $scope.currentLocation = {};
    $scope.zoom = 13;

    $scope.itemsPerPage = 25;
    $scope.currentPage = 1;
    $scope.itemsPerPageOptions = [10, 15, 20, 25, 30, 35, 40, 50, 60, 70, 80, 90, 100, 150];

    $scope.reload = function (itemsPerPage, currentPage) {
        Locations.all(itemsPerPage, currentPage).success(function (data) {
            $scope.locations = data;
        });
    }

    $scope.index = function (event) {
        $scope.mode = 'index';
        $scope.currentLocation = {};

        if (typeof event !== 'undefined') {
            event.preventDefault();
        }
    };

    $scope.show = function (location) {
        $scope.mode = 'show';
        $scope.currentLocation = location;
    };

    $scope.reload($scope.itemsPerPage, $scope.currentPage);
}]);
