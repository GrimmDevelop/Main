grimmApp.filter('population', function () {
    return function (item) {
        if (item > 1000000) {
            return Math.round(item * 100 / 1000000) / 100 + " mio.";
        }
        if (item > 1000) {
            return Math.round(item * 100 / 1000, 2) / 100 + " tsd.";
        }
        if (item > 0) {
            return Math.round(item * 100) / 100;
        }

        return "unkn.";
    };
});