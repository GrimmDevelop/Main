grimmApp.controller('navController', ['$scope', '$interval', 'Cluster', function ($scope, $interval, Cluster) {

    $scope.changes = {
        letter: 0,
        persons: 0,
        locations: 0,
        total: 0
    };

    Cluster.haveChanges('2014-01-01').success(function(response) {
        $scope.changes = response;
    });

    $scope.publish = function() {
        console.log('publishing');
    };
}]);
