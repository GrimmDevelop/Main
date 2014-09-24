grimmApp.filter('countCode', ['$filter', function($filter) {
    return function(items, code) {
        return $filter('filterCode')(items, [code]).length;
    }
}]);