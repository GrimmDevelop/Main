grimmApp.controller('searchController', ['$scope', '$modal', 'BASE_URL', 'Search', 'Letters', 'Locations', 'Persons', 'hotkeys', 'focus', '$rootScope', function ($scope, $modal, BASE_URL, Search, Letters, Locations, Persons, hotkeys, focus, $rootScope) {

    $scope.currentFilter = {
        id: null,
        filter_key: null,
        filter: {
            type: 'group',
            properties: {
                operator: 'AND'
            },
            fields: []
        }
    };

    $scope.letterInfo = {
        codes: []
    };

    $scope.displayCodes = ['absender', 'empfaenger', 'absendeort', 'absort_ers', 'empf_ort'];

    $scope.results = {};

    $scope.itemsPerPage = 30;
    $scope.currentPage = 1;
    $scope.itemsPerPageOptions = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 150, 300, 500];

    $scope.dateCodeBounds = {};

    $scope.quicksearch = {
        id : null,
        code: null
    };

    $scope.tabstatus = {
        filter: true,
        quicksearch: false,
        display: false
    };

    $scope.savedFilters = {
        showFilterNameInput: false
    };

    $scope.fieldChanged = function() {
        $rootScope.$broadcast('filters.changed');
    };

    $scope.removeTopGroup = function(group) {
        console.log('Cannot remove parent group');
    };

    $scope.addField = function () {
        $scope.result = {};

        $scope.currentFilter.filter.fields.push({
            code: "",
            compare: "equals",
            value: ""
        });
        $scope.fieldChanged();
    };

    $scope.removeField = function (field) {
        $scope.result = {};

        var index = $scope.currentFilter.filter.fields.indexOf(field);

        if (index > -1) {
            $scope.currentFilter.filter.fields.splice(index, 1);
        }
        $scope.fieldChanged();
    };

    $scope.removeLastField = function () {
        $scope.result = {};

        $scope.currentFilter.filter.fields.pop();
        $scope.fieldChanged();
    };

    $scope.search = function () {
        Search.search($scope.currentFilter.filter, $scope.itemsPerPage, $scope.currentPage, undefined, undefined, [$scope.startDate.date, $scope.endDate.date]).success(function (data) {
            $scope.results = data;
        });
    };

    $scope.findByIdentifierOrCode = function() {
        if ($scope.quicksearch.id != null && $scope.quicksearch.id != '') {
            $scope.quicksearch.code = null;
            Search.findById(parseInt($scope.quicksearch.id)).success(function(data) {
                if ($scope.displayCodes.indexOf('nr_1992') == -1) {
                    $scope.displayCodes.push('nr_1992');
                }
                if ($scope.displayCodes.indexOf('nr_1997') == -1) {
                    $scope.displayCodes.push('nr_1997');
                }
                $scope.results = data;
            });
        } else if ($scope.quicksearch.code != null) {
            Search.findByCode($scope.quicksearch.code).success(function(data) {
                $scope.results = data;
            });
        }
    };

    $scope.viewDistanceMap = function () {
        Search.distanceMap($scope.currentFilter.filter.fields).success(function (data) {
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
    };

    $scope.fieldTypeahead = function (value, field) {
        if (field.code == '') {
            return [];
        }

        return [];
    };

    $scope.startDate = {};

    $scope.endDate = {};

    Search.dateRange().success(function(response) {
        var data = response.data;

        $scope.startDate.minDate = new Date(data.min);
        $scope.startDate.maxDate = new Date(data.max);
        $scope.startDate.date = new Date(data.min);

        $scope.endDate.minDate = new Date(data.min);
        $scope.endDate.maxDate = new Date(data.max);
        $scope.endDate.date = new Date(data.max);
    });

    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };

    $scope.open = function(date, $event) {
        $event.preventDefault();
        $event.stopPropagation();

        date.opened = true;
    };

    Search.codes(true).success(function(data) {
        $scope.letterInfo.codes = data.data;
    });

    // Hotkeys
    hotkeys.add({
        combo: 'ctrl+alt+n',
        description: 'Add new field to outermost group',
        allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
        callback: function(event) {
            if ($scope.tabIsActive('filter')) {
                event.preventDefault();
                $scope.addField();
            }
        }
    });

    hotkeys.add({
        combo: 'ctrl+alt+d',
        description: 'Delete last field from outermost group',
        allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
        callback: function(event) {
            if ($scope.tabIsActive('filter')) {
                event.preventDefault();
                $scope.removeLastField();
            }
        }
    });

    hotkeys.add({
        combo: 'ctrl+alt+i',
        description: 'Enable Quicksearch',
        allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
        callback: function(event) {
            event.preventDefault();
            if ($scope.tabIsActive('quicksearch')) {
                focus('quicksearch.Id');
            }
            $scope.tabstatus.quicksearch = true;
        }
    });

    hotkeys.add({
        combo: 'ctrl+alt+f',
        description: 'Search by filters',
        allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
        callback: function(event) {
            event.preventDefault();
            if ($scope.tabIsActive('filter')) {
                focus('filter.start');
            }

            $scope.tabstatus.filter = true;
        }
    });

    $scope.$watch('tabstatus.quicksearch', function(newVal, oldVal) {
        if (newVal) {
            focus('quicksearch.Id');
        }
    });

    $scope.$watch('tabstatus.filter', function(newVal) {
        if (newVal) {
            focus('filter.start');
        }
    });

    $scope.tabIsActive = function(tabname) {
        return !!$scope.tabstatus[tabname];
    };
}]);
