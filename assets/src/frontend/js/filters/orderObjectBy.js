grimmApp.filter('orderObjectBy', function () {
    return function (items, field, reverse, parse) {

        var parseFunc = function (i) {
            return i;
        }

        if (typeof parse != 'undefined') {
            switch (parse) {
                case "float":
                    parseFunc = parseFloat;
                    break;

                case "int":
                    parseFunc = parseInteger;
            }
        }

        var filtered = [];
        angular.forEach(items, function (item) {
            filtered.push(item);
        });
        filtered.sort(function (a, b) {

            if (parseFunc(a[field]) > parseFunc(b[field])) return 1;
            if (parseFunc(a[field]) < parseFunc(b[field])) return -1;
            return 0;
        });
        if (reverse) filtered.reverse();
        return filtered;
    };
});