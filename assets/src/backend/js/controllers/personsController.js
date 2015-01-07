grimmApp.controller('personsController', ['$scope', '$modal', 'Persons', function ($scope, $modal, Persons) {

    $scope.persons = [];

    $scope.itemsPerPage = 25;
    $scope.currentPage = 1;
    $scope.itemsPerPageOptions = [10, 15, 20, 25, 30, 35, 40, 50, 60, 70, 80, 90, 100, 150];

    $scope.currentOrderBy = 'name_2013';
    $scope.currentOrderByDirection = 'asc';

    $scope.startsWith = '';
    $scope.autoGenerated = false;

    $scope.reload = function () {
        var itemsPerPage = $scope.itemsPerPage;
        var currentPage = $scope.currentPage;
        var currentOrderBy = $scope.currentOrderBy;
        var currentOrderByDirection = $scope.currentOrderByDirection;
        var startsWith = $scope.startsWith;
        var autoGenerated = $scope.autoGenerated;

        Persons.all(itemsPerPage, currentPage, currentOrderBy, currentOrderByDirection, startsWith, autoGenerated).success(function (data) {
            $scope.persons = data;
        });
    }

    $scope.reload();

    $scope.show = function (id) {
        $modal.open({
            templateUrl: 'admin/partials/personEdit',
            controller: 'personEditController',
            size: 'lg',
            resolve: {
                id: function () {
                    return id;
                }
            }
        });
    };

    $scope.orderBy = function (field) {
        if ($scope.currentOrderBy != field) {
            $scope.currentOrderBy = field;
            $scope.currentOrderByDirection = 'asc';
        } else {
            $scope.currentOrderByDirection = $scope.currentOrderByDirection == 'asc' ? 'desc' : 'asc';
        }

        $scope.reload();
    }
}]);
