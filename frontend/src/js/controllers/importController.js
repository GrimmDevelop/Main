
grimmApp.controller('importController', ['$scope', 'ImportLetter', 'ImportPerson', function ($scope, letter, person) {

    $scope.message = {};
    $scope.closeMessage = function() {
        $scope.message = null;
    };

    $scope.mode = 'index';

    $scope.selectedLetterFile = null;
    $scope.selectedLocationFile = null;
    $scope.selectedPersonFile = null;

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

        letter.start($scope.selectedLetterFile)
            .success(function(data) {
                $scope.message = data;
                $scope.index();
            })
            .error(function(data) {
                $scope.message = data;
            });

        $scope.reset();
    }

    $scope.startLocationImport = function() {
        // todo

        $scope.reset();
    }

    $scope.startPersonImport = function(event) {
        if (typeof event !== 'undefined') {
            event.preventDefault();
        }

        person.start($scope.selectedPersonFile)
            .success(function(data) {
                $scope.message = data;
                $scope.index();
            })
            .error(function(data) {
                $scope.message = data;
            });

        $scope.reset();
    }

    $scope.reset = function() {
        $scope.mode = 'index';

        $scope.selectedLetterFile = null;
        $scope.selectedLocationFile = null;
        $scope.selectedPersonFile = null;
    }
}]);
