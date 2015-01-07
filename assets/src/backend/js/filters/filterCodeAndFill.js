grimmApp.filter('filterCodeAndFill', ['$filter', function ($filter) {
    return function (items, codes) {
        codes.sort();

        var filtered = $filter('filterCode')(items, codes);

        var object = {};
        filtered.map(function(item) {
            if(typeof object[item.code] == 'undefined') {
                object[item.code] = [];
            }
            object[item.code].push(item.data);
        });

        return object;
    }
}]);