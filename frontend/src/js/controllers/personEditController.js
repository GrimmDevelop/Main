grimmApp.controller('personEditController', ['$scope', '$modal', '$modalInstance', 'Persons', 'id', function ($scope, $modal, $modalInstance, Persons, id) {

    $scope.person = {};

    $scope.cancel = function() {
        $modalInstance.dismiss('cancel');
    }

    $scope.load = function(id) {
        Persons.get(id).success(function(data) {
            $scope.person = data;
            $scope.person.information.map(function(item) {
                item.state = 'keep';
            })
        }).error(function(data) {
            $scope.cancel();
        });
    }

    $scope.removeInformation = function(information) {
        if(information.state != 'add') {
            information.state = information.state == 'remove' ? 'keep' : 'remove';
        } else {
            var index = $scope.person.information.indexOf(information);

            if(index != -1) {
                $scope.person.information.splice(index, 1);
            }
        }
    }

    $scope.save = function() {
        // save person changes

        Persons.save($scope.person).success(function(data) {
            $scope.load(id);
        });
    }

    $scope.addCode = function() {
        var modalInstance = $modal.open({
            templateUrl: 'admin/partials/personEditAddCode',
            controller: 'personEditAddCodeController'
        });

        modalInstance.result.then(function (result) {
            $scope.person.information.push({
                state: 'add',
                code: result,
                data: null
            });
        }, function () {

        });
    }

    $scope.load(id);
}]);

grimmApp.controller('personEditAddCodeController', ['$scope', '$modalInstance', 'Search', function($scope, $modalInstance, Search) {

    $scope.code = null;

    $scope.codes = [];

    Search.codes().success(function(data) {
        $scope.codes = data;
    });

    $scope.ok = function() {
        if($scope.code != null && $scope.code != '') {
            $modalInstance.close($scope.code);
        }
    }

    $scope.cancel = function() {
        $modalInstance.dismiss('cancel');
    }
}]);