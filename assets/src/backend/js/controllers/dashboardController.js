grimmApp.controller('dashboardController', ['$scope', '$http', '$location', 'BASE_URL', function ($scope, $http, $location, BASE_URL) {
    var serviceBackend = BASE_URL + '/api/';

    $scope.go = function (path) {
        $location.path(path);
    };
}]);
