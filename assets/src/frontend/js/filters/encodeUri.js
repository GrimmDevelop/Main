grimmApp.filter('encodeUri', ['$window', function ($window) {
    return $window.encodeURIComponent;
}]);