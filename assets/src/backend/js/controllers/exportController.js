grimmApp.controller('exportController', ['$scope', '$interval', '$modal', function ($scope, $interval, $modal) {

    $scope.letters = function() {
        $modal.open({
            templateUrl: 'admin/partials/export.letters',
            controller: 'exportLettersController',
            size: 'lg'
        });
    }

}]);
