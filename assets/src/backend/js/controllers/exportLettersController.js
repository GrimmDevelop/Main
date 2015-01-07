grimmApp.controller('exportLettersController', ['$scope', '$interval', 'Exporter', function ($scope, $interval, Exporter) {

    $scope.codes = [];
    $scope.formats = [];

    $scope.ok = function() {
        // start export
    }

    Exporter.letterCodes().success(function(data) {
        $scope.codes = data;
    });

    Exporter.formats().success(function(data) {
        $scope.formats = data;
        $scope.format = data[0];
    });
}]);
