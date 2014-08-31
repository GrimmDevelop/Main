
grimmApp.controller('importController', ['$scope', 'ImportLetter', function ($scope, letter) {

    $scope.message = {};
    $scope.closeMessage = function() {
        $scope.message = null;
    };

    $scope.mode = 'index';

    $scope.selectedLetterFile = null;
    $scope.selectedLocationFile = null;

    $scope.index = function(event) {
        $scope.mode = 'index';

        if (typeof event !== 'undefined') {
            event.preventDefault();
        }
    }

    $scope.startLetterImport = function(event) {
        if (typeof event !== 'undefined') {
            event.preventDefault();
        }
        
        $scope.mode = 'letterImport';

        letter.start($scope.selectedLetterFile)
            .success(function(data) {
                $scope.message = data;
                $scope.index();
            })
            .error(function(data) {
                $scope.message = data;
            });
    }

    $scope.startLocationImport = function() {
        $scope.mode = 'locationImport';
    }
}]);
