grimmApp.filter('filterCode', function () {
    return function (items, codes) {
        return items.filter(function (item) {
            return codes.indexOf(item.code) != -1;
        });
    }
});