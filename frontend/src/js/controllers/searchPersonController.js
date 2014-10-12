
grimmApp.controller('searchPersonController', ['$scope', '$modal', 'Persons', '$modalInstance', 'name', function ($scope, $modal, Persons, $modalInstance, name) {
    $scope.personName = name;

    $scope.message = null;
    $scope.showMessage = function(message) {
        $scope.closeMessage();
        $scope.message = message;
    }
    $scope.closeMessage = function () {
        $scope.message = null;
    };

    $scope.selectedItem = null;
    $scope.resultList = null;

    $scope.showCreateButton = false;

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
        Persons.search(name).success(function (result) {
            console.log(result);
            $scope.resultList = result;
        }).error(function (data) {
            $scope.selectedItem = null;
            $scope.showMessage(data);

            $scope.showCreateButton = true;
            // hide loader show red cross
        });
    }

    $scope.select = function (item) {
        $scope.selectedItem = item.id;
        $scope.showMessage({
            type: "success",
            message: "Person selected with id " + $scope.selectedItem
        });
    }

    $scope.typeSearch = function(name) {
        return Persons.searchAhead(name).then(function(response) {
            return response.data.map(function(item) {
                return item.name;
            });
        });
    }

    $scope.autoGenerate = function() {
        console.log($scope.personName);
        $scope.selectedItem = null;
        $scope.resultList = null;
        Persons.autoGenerate($scope.personName).success(function(data) {
            $scope.showMessage(data);
            $scope.showCreateButton = false;
        }).error(function(data) {
            $scope.showMessage(data);
        });
    }
}]);
