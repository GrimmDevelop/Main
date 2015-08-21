grimmApp.directive('sharedFilter', ['BASE_URL', 'Search', function(BASE_URL, Search) {
    return {
        restrict: 'E',
        link: function(scope, element, attrs) {

            scope.selectedFilter = {};

            // Load shared filter
            if (scope.key && scope.key != '') {
                console.log('Load Shared', scope.key);
                Search.loadFilter(scope.key).success(function(data) {
                    scope.selectedFilter = data.data;
                    scope.selectedFilter.shared = true;
                    scope.filters = scope.selectedFilter.filters;
                });
            }
        },
        templateUrl: BASE_URL + '/partials/sharedFilter',
        scope: {
            filters: '=',
            key: '@'
        }
    };
}]);

