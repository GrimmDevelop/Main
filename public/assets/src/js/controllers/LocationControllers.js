
grimmApp.controller('LocationIndexCtrl', ['$scope', '$http', '$location', 'BASE_URL', function ($scope, $http, $location, BASE_URL) {
    serviceBackend = BASE_URL + '/api/';

    $http.get(serviceBackend + 'locations').success(function(data) {
        $scope.locations = data;
    });

    $scope.go = function(path) {
        $location.path(path);
    };
}]);

grimmApp.controller('LocationShowCtrl',['$scope', '$http', '$routeParams', 'BASE_URL', function ($scope, $http, $routeParams, BASE_URL) {
    serviceBackend = BASE_URL + '/api/';

    $scope.map = {

    };

    $http.get(serviceBackend + 'locations/' + $routeParams.locationId).success(function(data) {
        $scope.location = data;

        $scope.map = {
            center: {
                latitude: "42",
                longitude: "-72"
            },
            zoom: 8
        };
    });
}]);
