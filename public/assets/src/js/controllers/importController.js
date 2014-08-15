
grimmApp.controller('importController', ['$scope', 'ImportLetter', function ($scope, letter) {

    $scope.mode = 'index';


    $scope.index = function(event) {
        $scope.mode = 'index';

        if (typeof event !== 'undefined') {
            event.preventDefault();
        }
    }

    $scope.startLetterImport = function() {
        $scope.mode = 'letterImport';
    }

    $scope.startLocationImport = function() {
        $scope.mode = 'locationImport';
    }
}]);
