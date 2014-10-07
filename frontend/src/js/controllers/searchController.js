grimmApp.controller('searchController', ['$scope', '$modal', 'Search', 'Letters', 'Locations', 'Persons', function ($scope, $modal, Search, Letters, Locations, Persons) {

    $scope.filters = [];
    $scope.codes = [];
    $scope.results = {};
    $scope.currentPage = 1;

    $scope.addFilter = function() {
        $scope.filters.push({
            code: "",
            compare: "equals",
            value: ""
        });
    }

    $scope.removeFilter = function(filter) {
        var index = $scope.filters.indexOf(filter);

        if (index > -1) {
            $scope.filters.splice(index, 1);
        }
    }

    $scope.loadFilters = function() {
        Search.loadFilters($scope.filters).success(function(data) {
            $scope.filters = data;
        });
    }

    $scope.saveFilters = function() {
        Search.saveFilters($scope.filters).success(function() {
            // saved
        });
    }

    $scope.search = function() {
        Search.search($scope.filters, $scope.currentPage).success(function(data) {
            $scope.results = data;
        });
    }

    Search.codes().success(function(codes) {
        codes.unshift("");
        $scope.codes = codes;
    });

    $scope.loadFilters();
}]);
