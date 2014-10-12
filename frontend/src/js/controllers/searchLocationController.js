
grimmApp.controller('searchLocationController', ['$scope', '$modal', 'Locations', '$modalInstance', 'name', function ($scope, $modal, Locations, $modalInstance, name) {
    $scope.locationName = name;

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

    $scope.show = function (item) {
        $modal.open({
            templateUrl: 'admin/partials/locationPreview',
            controller: 'locationPreviewController',
            resolve: {
                location: function () {
                    return item;
                }
            }
        });
    };

    $scope.typeSearch = function(name) {
        return Locations.searchAhead(name).then(function(response) {
            return response.data.map(function(item) {
                return item.name;
            });
        });
    }
}]);
