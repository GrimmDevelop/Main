grimmApp.controller('dashboardController', ['$scope', '$interval', '$location', 'BASE_URL', 'Tasks', function ($scope, $interval, $location, BASE_URL, Tasks) {
    var serviceBackend = BASE_URL + '/api/';

    $scope.tasks = [];

    Tasks.getTasks().success(function(data) {
        $scope.tasks = data.data;
    });

    var intervalTimer;

    intervalTimer = $interval(function() {
        Tasks.getTasks().success(function(data) {
            $scope.tasks = data.data;
        });
    }, 5000);

    $scope.go = function (path) {
        $location.path(path);
    };

    $scope.$on('$destroy', function() {
        if (angular.isDefined(intervalTimer)) {
            $interval.cancel(intervalTimer);
            intervalTimer = undefined;
        }
    });

    /*$scope.lastOfList = function(list) {
        return list[list.length - 1];
    }*/
}]);
