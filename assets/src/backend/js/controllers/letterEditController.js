grimmApp.controller('letterEditController', ['$scope', '$modal', '$modalInstance', 'Letters', 'id', function ($scope, $modal, $modalInstance, Letters, id) {

    $scope.letter = {};

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };

    $scope.load = function (id) {
        Letters.get(id).success(function (data) {
            $scope.letter = data;
            $scope.letter.information.map(function (item) {
                item.state = 'keep';
            })
        }).error(function () {
            $scope.cancel();
        });
    };

    $scope.removeInformation = function (information) {
        if (information.state != 'add') {
            information.state = information.state == 'remove' ? 'keep' : 'remove';
        } else {
            var index = $scope.letter.information.indexOf(information);

            if (index != -1) {
                $scope.letter.information.splice(index, 1);
            }
        }
    };

    $scope.save = function () {
        // save letter changes

        Letters.save($scope.letter).success(function () {
            $scope.load(id);
        });
    };

    $scope.delete = function() {
        Letters.delete($scope.letter).success(function() {
            $scope.cancel();
        });
    };

    $scope.addCode = function () {
        var modalInstance = $modal.open({
            templateUrl: 'admin/partials/letterEditAddCode',
            controller: 'letterEditAddCodeController'
        });

        modalInstance.result.then(function (result) {
            $scope.letter.information.push({
                state: 'add',
                code: result,
                data: null
            });
        }, function () {

        });
    };


    $scope.openPersonModal = function (assignMode) {
        $modal.open({
            templateUrl: 'admin/partials/assign.' + assignMode.toLowerCase(),
            controller: 'assignPersonController',
            resolve: {
                letter: function () {
                    return $scope.letter;
                },
                mode: function () {
                    return assignMode.toLowerCase();
                }
            }
        });
    };

    $scope.openLocationModal = function (assignMode) {
        $modal.open({
            templateUrl: 'admin/partials/assign.' + assignMode.toLowerCase(),
            controller: 'assignLocationController',
            resolve: {
                letter: function () {
                    return $scope.letter;
                },
                mode: function () {
                    return assignMode.toLowerCase();
                }
            }
        });
    };


    $scope.load(id);
}]);

grimmApp.controller('letterEditAddCodeController', ['$scope', '$modalInstance', 'Search', function ($scope, $modalInstance, Search) {

    $scope.code = null;

    $scope.codes = [];

    Search.codes().success(function (data) {
        $scope.codes = data.data;
    });

    $scope.ok = function () {
        if ($scope.code != null && $scope.code != '') {
            $modalInstance.close($scope.code);
        }
    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
}]);