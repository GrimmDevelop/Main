
grimmApp.directive('sortingColumn', ['BASE_URL', function(BASE_URL) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            element.on('click', function() {
                console.log('sort ' + scope.theColumn);

                scope.onSort();
            });
        },
        scope: {
            theColumn: '=sortingColumn',
            onSort: '&'
        }
    };
}]);