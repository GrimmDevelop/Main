grimmApp.controller('assignController', ['$scope', '$modal', 'Assigner', 'Persons', function ($scope, $modal, Assigner, Persons) {

    $scope.message = null;
    $scope.closeMessage = function () {
        $scope.message = null;
    };

    $scope.locationsToCheck = [];
    $scope.personsToCheck = [];

    $scope.from = function () {
        Assigner.from().success(function (data) {
            $scope.message = data;
            $scope.locationsToCheck = data.failed;
        }).error(function (data) {
            $scope.message = data;
        });
    }

    $scope.to = function () {
        Assigner.to().success(function (data) {
            $scope.message = data;
            $scope.locationsToCheck = data.failed;
        }).error(function (data) {
            $scope.message = data;
        });
    }

    $scope.senders = function () {
        Assigner.senders().success(function (data) {
            $scope.message = data;
            $scope.personsToCheck = data.failed;
        }).error(function (data) {
            $scope.message = data;
        });
    }

    $scope.receivers = function () {
        Assigner.receivers().success(function (data) {
            $scope.message = data;
            $scope.personsToCheck = data.failed;
        }).error(function (data) {
            $scope.message = data;
        });
    }

    $scope.search = function (location) {
        var modalInstance = $modal.open({
            templateUrl: 'admin/partials/searchLocation',
            controller: 'searchLocationController',
            resolve: {
                name: function () {
                    return location.name;
                }
            }
        });

        modalInstance.result.then(function (result) {
            Assigner.cacheLocation(location.name, result).success(function (result) {
                removeLocationFromList(location);
            });
        }, function () {

        });
    }

    $scope.searchPerson = function (person) {
        var modalInstance = $modal.open({
            templateUrl: 'admin/partials/searchPerson',
            controller: 'searchPersonController',
            resolve: {
                name: function () {
                    return person.name;
                }
            }
        });

        modalInstance.result.then(function (result) {
            Assigner.cachePerson(person.name, result).success(function (result) {
                removePersonFromList(person);
            });
        }, function () {

        });
    }

    $scope.autoGenerate = function(person) {
        Persons.autoGenerate(person.name).success(function(data) {
            removePersonFromList(person);
        });
    }

    function removeLocationFromList(location) {
        $scope.locationsToCheck = $scope.locationsToCheck.filter(function(item) {
            return item.name != location.name;
        });
    }

    function removePersonFromList(person) {
        $scope.personsToCheck = $scope.personsToCheck.filter(function(item) {
            return item.name != person.name;
        });
    }
}]);
