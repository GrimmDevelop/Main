grimmApp.controller('searchController', ['$scope', '$modal', 'Search', 'Letters', 'Locations', 'Persons', function ($scope, $modal, Search, Letters, Locations, Persons) {

    $scope.filters = [];
    $scope.codes = [];
    $scope.results = {};

    $scope.itemsPerPage = 100;
    $scope.currentPage = 1;
    $scope.itemsPerPageOptions = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 150, 300, 500];

    $scope.dateCodeBounds = {};

    $scope.addFilter = function () {
        $scope.result = {};

        $scope.filters.push({
            code: "",
            compare: "equals",
            value: ""
        });
    }

    $scope.removeFilter = function (filter) {
        $scope.result = {};

        var index = $scope.filters.indexOf(filter);

        if (index > -1) {
            $scope.filters.splice(index, 1);
        }
    }

    $scope.loadFilters = function () {
        $scope.result = {};
        Search.loadFilters().success(function (data) {
            $scope.filters = data;
        });
    }

    $scope.saveFilters = function () {
        Search.saveFilters($scope.filters).success(function () {
            // saved
        });
    }

    $scope.search = function () {
        Search.search($scope.filters, $scope.itemsPerPage, $scope.currentPage).success(function (data) {
            $scope.results = data;
        });
    }

    $scope.viewDistanceMap = function () {
        Search.distanceMap($scope.filters).success(function (data) {
            $modal.open({
                templateUrl: 'partials/distanceMap',
                controller: 'distanceMapController',
                size: 'lg',
                resolve: {
                    mapData: function () {
                        return data;
                    }
                }
            });
        });
    }

    Search.codes().success(function (codes) {
        codes.unshift("");
        $scope.codes = codes;
    });

    $scope.loadFilters();
}]);
