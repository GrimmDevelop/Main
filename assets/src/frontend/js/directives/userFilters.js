// TODO: Fix Typeahead stuff and add onchange event
grimmApp.directive('userFilters', ['BASE_URL', 'Search', function(BASE_URL, Search) {
    return {
        restrict: 'E',
        link: function(scope, element, attrs) {
            scope.directiveStatus = {
                showFilterNameInput: false,
                nameInput: '',
                changed: false
            };

            scope.savedFilters = [];

            scope.selectedFilter = {};

            scope.onNewFilterInput = function(ev) {
                if (ev.keyCode == 13) {
                    ev.preventDefault();
                    scope.newFilter();
                    return false;
                }
            };

            scope.newFilter = function () {
                var filterName = scope.directiveStatus.nameInput;
                if (filterName == '') {
                    filterName = 'Filter #' + (scope.savedFilters.length + 1);
                }

                Search.newFilter(filterName, scope.filters).success(function(data) {
                    scope.savedFilters = data.data;
                    scope.directiveStatus.nameInput = '';
                });
            };

            scope.loadFilters = function () {
                Search.loadFilters().success(function (data) {
                    scope.savedFilters = data.data;
                });
            };

            scope.selectFilter = function (filter) {
                scope.selectedFilter = filter;

                scope.filters = filter.filters;

                scope.directiveStatus.changed = false;
            };

            scope.sendMail = function () {
                if (scope.selectedFilter.filter_key != null) {
                    window.location = 'mailto:?subject=Grimm%20Database%20-%20Filter&body=Filter%20link:%20' + encodeURIComponent(BASE_URL + '/search/' + scope.selectedFilter.filter_key);
                }
            };

            scope.sendMailForFilter = function() {
                if (scope.selectedFilter.filter_key != null) {
                    scope.sendMail();
                } else {
                    Search.publicFilter(scope.selectedFilter).success(function(data) {
                        scope.selectedFilter.filter_key = data.data.filter_key;
                        scope.sendMail();
                    });
                }
            };

            scope.saveFilter = function() {
                if (scope.selectedFilter.id != null) {
                    Search.saveFilter(scope.selectedFilter).success(function (data) {
                        scope.savedFilters = data.data;
                        scope.directiveStatus.changed = false;
                    });
                }
            };

            scope.deleteFilter = function() {
                if (scope.selectedFilter.id != null) {
                    Search.deleteFilter(scope.selectedFilter).success(function(data) {
                        scope.selectedFilter = null;
                        scope.savedFilters = data.data;
                    });
                }
            };

            scope.$on('filters.changed', function() {
                if (scope.selectedFilter.id != null) {
                    scope.directiveStatus.changed = true;
                }
            });

            scope.loadFilters();
        },
        templateUrl: BASE_URL + '/partials/userFilters',
        scope: {
            filters: '='
        }
    };
}]);
