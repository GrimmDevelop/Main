grimmApp.filter('joinObject', ['$filter', function($filter) {
    return function(items, field, seperator) {
        if(typeof seperator == 'undefined') {
            seperator = ',';
        }
        return items.map(function(item) {
            return item[field];
        }).join(seperator);
    }
}]);