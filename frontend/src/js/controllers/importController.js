
grimmApp.controller('importController', ['$scope', 'ImportLetter', 'ImportLocation', 'ImportPerson', function ($scope, letter, location, person) {

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

    $scope.startLetterImport = function(event, selectedLetterFile) {
        if (typeof event !== 'undefined') {
            event.preventDefault();
        }

        letter.start(selectedLetterFile)
            .success(function(data) {
                $scope.message = data;
                $scope.index();
            })
            .error(function(data) {
                $scope.message = data;
            });

        $scope.reset();
    }

    $scope.startLocationImport = function(event, selectedLocationFile) {
        if (typeof event !== 'undefined') {
            event.preventDefault();
        }

        location.start(selectedLocationFile)
            .success(function(data) {
                $scope.message = data;
                $scope.index();
            })
            .error(function(data) {
                $scope.message = data;
            });

        $scope.reset();
    }

    $scope.startPersonImport = function(event, selectedPersonFile) {
        if (typeof event !== 'undefined') {
            event.preventDefault();
        }

        person.start(selectedPersonFile)
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
