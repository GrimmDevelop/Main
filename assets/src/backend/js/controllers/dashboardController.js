grimmApp.controller('dashboardController', ['$scope', '$interval', '$location', '$modal', 'BASE_URL', 'Tasks', function ($scope, $interval, $location, $modal, BASE_URL, Tasks) {
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

    $scope.$on('$destroy', function() {
        if (angular.isDefined(intervalTimer)) {
            $interval.cancel(intervalTimer);
            intervalTimer = undefined;
        }
    });

    $scope.go = function (path) {
        $location.path(path);
    };

    $scope.openTaskDetails = function(task) {
        var modalInstance = $modal.open({
            templateUrl: 'progress-view-modal-content.html',
            controller: 'ProgressModalInstanceCtrl',
            resolve: {
                task: function() {
                    return task;
                }
            }
        });
    };
}]);

grimmApp.controller('ProgressModalInstanceCtrl', ['$scope', '$modalInstance', 'task', function($scope, $modalInstance, task) {
    $scope.task = task;

    $scope.close = function() {
        $modalInstance.dismiss('cancel');
    };
}]);