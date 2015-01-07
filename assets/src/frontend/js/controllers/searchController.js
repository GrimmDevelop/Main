grimmApp.controller('searchController', ['$scope', '$modal', 'Search', 'Letters', 'Locations', 'Persons', function ($scope, $modal, Search, Letters, Locations, Persons) {

    $scope.filters = [];
    $scope.currentFilter = {};
    $scope.currentFilter.id = null;
    $scope.currentFilter.filter_key = null;
    $scope.currentFilter.fields = [];

    $scope.codes = [];

    $scope.displayCodes = ['absender', 'empfaenger', 'absendeort', 'absort_ers', 'empf_ort'];

    $scope.results = {};

    $scope.itemsPerPage = 30;
    $scope.currentPage = 1;
    $scope.itemsPerPageOptions = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 150, 300, 500];

    $scope.dateCodeBounds = {};

    $scope.addField = function () {
        $scope.result = {};

        $scope.currentFilter.fields.push({
            code: "",
            compare: "equals",
            value: ""
        });
    }

    $scope.removeField = function (field) {
        $scope.result = {};

        var index = $scope.currentFilter.fields.indexOf(field);

        if (index > -1) {
            $scope.currentFilter.fields.splice(index, 1);
        }
    }

    $scope.loadFilters = function () {
        $scope.result = {};
        Search.loadFilters().success(function (data) {
            $scope.filters = data;

            if ($scope.filters.length > 0) {
                // $scope.loadFilter($scope.filters[0]);
            }
        });
    }

    $scope.loadFilter = function (filter) {
        if (filter != null) {
            if (typeof filter == 'string') {
                if (filter != '') {
                    Search.loadFilter(filter).success(function (data) {
                        if (data != '') {
                            $scope.currentFilter = data;
                        }
                    });
                }
            } else {
                $scope.currentFilter = filter;
            }
        }
    }

    $scope.sendMail = function () {
        if ($scope.currentFilter.filter_key != null) {
            return 'mailto:?subject=Grimm%20Database%20-%20Filter&body=Filter%20link:%20' + $scope.currentFilter.filter_key;
        }
    }

    $scope.newFilter = function () {
        Search.newFilter($scope.currentFilter).success(function (data) {
            $scope.filters = data;
        });
    }

    $scope.saveFilter = function () {
        if ($scope.currentFilter.id != null) {
            Search.saveFilter($scope.currentFilter).success(function (data) {
                $scope.filters = data;
            });
        }
    }

    $scope.deleteFilter = function () {
        if ($scope.currentFilter.id != null) {
            Search.deleteFilter($scope.currentFilter).success(function (data) {
                $scope.currentFilter.id = null;
                $scope.currentFilter.public_key = null;
                $scope.currentFilter.fields = [];
                $scope.filters = data;
            });
        }
    }

    $scope.publicFilter = function () {
        if ($scope.currentFilter.id != null) {
            Search.publicFilter($scope.currentFilter).success(function (data) {
                $scope.loadFilters();
            });
        }
    }

    $scope.search = function () {
        Search.search($scope.currentFilter.fields, $scope.itemsPerPage, $scope.currentPage).success(function (data) {
            $scope.results = data;
        });
    }

    $scope.viewDistanceMap = function () {
        Search.distanceMap($scope.currentFilter.fields).success(function (data) {
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

    $scope.fieldTypeahead = function (value, field) {
        if (field.code == '') {
            return [];
        }

        return [];
    }

    Search.codes().success(function(data) {
        $scope.codes = data;
    });

    $scope.loadFilters();
}]);
