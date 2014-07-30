
grimmApp.controller('UserIndexCtrl', ['$scope', '$http', '$location', 'BASE_URL', function ($scope, $http, $location, BASE_URL) {
    serviceBackend = BASE_URL + '/api/';

    $http.get(serviceBackend + 'users').success(function(data) {
        $scope.users = data;
    });

    $scope.go = function(path) {
        $location.path(path);
    };
}]);

grimmApp.controller('UserCreateCtrl',['$scope', '$http', '$routeParams', 'BASE_URL', function ($scope, $http, $routeParams, BASE_URL) {
    serviceBackend = BASE_URL + '/api/';

    $scope.user = {};

    $scope.save = function() {
        console.log('save');
        console.log($scope.user);
    };
}]);

grimmApp.controller('UserShowCtrl',['$scope', '$http', '$routeParams', 'BASE_URL', function ($scope, $http, $routeParams, BASE_URL) {
    serviceBackend = BASE_URL + '/api/';

    $http.get(serviceBackend + 'users/' + $routeParams.userId).success(function(data) {
        $scope.user = data;
    });
}]);

grimmApp.controller('UserEditCtrl',['$scope', '$http', '$routeParams', 'BASE_URL', function ($scope, $http, $routeParams, BASE_URL) {
    serviceBackend = BASE_URL + '/api/';

    $http.get(serviceBackend + 'users/' + $routeParams.userId)
        .success(function(data) {
            $scope.user = data;
        });

    $scope.save = function() {
        $http.put(serviceBackend + 'users/' + $routeParams.userId, $scope.user)
            .success(function(data) {
                bootbox.alert(data);
            })
            .error(function(data) {
                bootbox.alert(data.error.message);
            });
    };
}]);
