grimmApp.controller('assignPersonController', ['$scope', '$modalInstance', 'Persons', 'Letters', 'letter', 'mode', function ($scope, $modalInstance, Persons, Letters, letter, mode) {
    $scope.letter = letter;
    $scope.mode = mode;

    $scope.message = null;
    $scope.showMessage = function (message) {
        $scope.closeMessage();
        $scope.message = message;
    }
    $scope.closeMessage = function () {
        $scope.message = null;
    };

    $scope.selectedItem = null;
    $scope.resultList = null;

    $scope.ok = function () {
        $modalInstance.close($scope.selectedItem);
    };
    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    }

    $scope.search = function (name) {
        // show loader
        $scope.selectedItem = null;
        Persons.search(name).success(function (result) {
            $scope.selectedItem = result[0].id;
            $scope.message = {
                type: "success",
                message: "Person found with id " + $scope.selectedItem
            };
        }).error(function (data) {
            $scope.selectedItem = null;
            $scope.message = data;
            // hide loader show red cross
        });
    }

    $scope.unassign = function(sender) {
        Letters.unassign().success(function(data) {

        });
    }

    $scope.select = function (item) {
        $scope.selectedItem = item;
    }

    $scope.typeSearch = function (name) {
        return Persons.searchAhead(name).then(function (response) {
            return response.data.map(function (item) {
                return item.name;
            });
        });
    }
}]);
