grimmApp.controller('assignController', ['$scope', '$modal', 'Assigner', 'Persons', function ($scope, $modal, Assigner, Persons) {

    $scope.message = null;
    $scope.closeMessage = function () {
        $scope.message = null;
    };

    $scope.numbersToAssign = 500;

    $scope.locationsToCheck = [];
    $scope.personsToCheck = [];

    $scope.resetLists = function() {
        $scope.locationsToCheck = [];
        $scope.personsToCheck = [];
    }

    $scope.from = function () {
        $scope.resetLists();

        Assigner.from($scope.numbersToAssign).success(function (data) {
            $scope.message = data;
            $scope.locationsToCheck = data.failed;
        }).error(function (data) {
            $scope.message = data;
        });
    }

    $scope.to = function () {
        $scope.resetLists();

        Assigner.to($scope.numbersToAssign).success(function (data) {
            $scope.message = data;
            $scope.locationsToCheck = data.failed;
        }).error(function (data) {
            $scope.message = data;
        });
    }

    $scope.senders = function () {
        $scope.resetLists();

        Assigner.senders($scope.numbersToAssign).success(function (data) {
            $scope.message = data;
            $scope.personsToCheck = data.failed;
        }).error(function (data) {
            $scope.message = data;
        });
    }

    $scope.receivers = function () {
        $scope.resetLists();

        Assigner.receivers($scope.numbersToAssign).success(function (data) {
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
