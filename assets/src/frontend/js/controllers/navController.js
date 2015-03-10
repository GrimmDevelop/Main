grimmApp.controller('navController', ['$scope', '$interval', 'Cluster', function ($scope, $interval, Cluster) {

    $scope.changes = {
        letter: 0,
        persons: 0,
        total: 0
    };

    var loadChanges = function() {
        Cluster.haveChanges('2014-01-01').success(function (response) {
            $scope.changes = response;
        });
    }

    $scope.publish = function() {
        Cluster.publish().success(function(response) {
            $scope.changes = {
                letter: 0,
                persons: 0,
                total: 0
            };
        });
    };

    loadChanges();
    $interval(loadChanges, 5000);
}]);
