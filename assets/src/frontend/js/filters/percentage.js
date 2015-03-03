grimmApp.filter('percentage', function () {
    return function (value) {
        return Math.floor(value * 100);
    };
});