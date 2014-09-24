grimmApp.controller('assignLocationController', ['$scope', 'Locations', '$modalInstance', 'letter', 'mode', function ($scope, Locations, $modalInstance, letter, mode) {
    $scope.letter = letter;
    $scope.mode = mode;

    $scope.message = null;
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
        $scope.resultList = [];
        Locations.search(name, true).success(function (result) {
            $scope.resultList = result.data;
        }).error(function (data) {
            $scope.selectedItem = null;
            $scope.message = data;
            // hide loader show red cross
        });
    }

    $scope.select = function (item) {
        $scope.selectedItem = item.id;
        $scope.message = {
            type: "success",
            message: "Location selected with geo id " + $scope.selectedItem
        };
    }
}]);
