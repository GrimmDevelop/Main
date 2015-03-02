grimmApp.filter('personName', function () {
    return function (items) {
        return items.map(function (item) {
            return item.name_2013;
        });
    };
});