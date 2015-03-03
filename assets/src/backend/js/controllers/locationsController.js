grimmApp.controller('locationsController', ['$scope', '$modal', 'Locations', function ($scope, $modal, Locations) {

    $scope.locations = [];
    $scope.zoom = 13;

    $scope.itemsPerPage = 25;
    $scope.currentPage = 1;
    $scope.itemsPerPageOptions = [10, 15, 20, 25, 30, 35, 40, 50, 60, 70, 80, 90, 100, 150];

    $scope.reload = function (itemsPerPage, currentPage) {
        Locations.all(itemsPerPage, currentPage).success(function (data) {
            $scope.locations = data;
        });
    };

    $scope.reload($scope.itemsPerPage, $scope.currentPage);
}]);
