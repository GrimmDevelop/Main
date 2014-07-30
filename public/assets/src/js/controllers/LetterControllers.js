
grimmApp.controller('LetterIndexCtrl', ['$scope', '$http', '$location', 'BASE_URL', function ($scope, $http, $location, BASE_URL) {
    serviceBackend = BASE_URL + '/api/';

    $http.get(serviceBackend + 'letters').success(function(data) {
        $scope.letters = data;
    });

    $scope.go = function(path) {
        $location.path(path);
    };
}]);
