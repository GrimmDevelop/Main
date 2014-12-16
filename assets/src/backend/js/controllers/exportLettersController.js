grimmApp.controller('exportLettersController', ['$scope', '$interval', '$modalInstance', 'Exporter', function ($scope, $interval, $modalInstance, Exporter) {

    $scope.codes = [];

    $scope.ok = function() {
        // start export
    }

    $scope.cancel = function() {
        // cancel
    }

    Exporter.letterCodes().success(function(data) {
        $scope.codes = data;
    });
}]);
