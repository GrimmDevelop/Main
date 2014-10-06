grimmApp.controller('personsController', ['$scope', 'Persons', function ($scope, Persons) {

    $scope.mode = 'index';
    $scope.persons = [];
    $scope.currentPerson = {};
    $scope.zoom = 13;

    $scope.itemsPerPage = 25;
    $scope.currentPage = 1;
    $scope.itemsPerPageOptions = [10, 15, 20, 25, 30, 35, 40, 50, 60, 70, 80, 90, 100, 150];

    $scope.reload = function (itemsPerPage, currentPage) {
        Persons.all(itemsPerPage, currentPage).success(function (data) {
            $scope.persons = data;
        });
    }

    $scope.index = function (event) {
        $scope.mode = 'index';
        $scope.currentPerson = {};

        if (typeof event !== 'undefined') {
            event.preventDefault();
        }
    };

    $scope.show = function (person) {
        $scope.mode = 'show';
        $scope.currentPerson = person;
    };

    $scope.reload($scope.itemsPerPage, $scope.currentPage);
}]);
