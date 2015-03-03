grimmApp.service("Display", ['$http', 'BASE_URL', function ($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/user/display';

    this.viewsAndDefault = function (section) {
        return $http.get(serviceBackend + '/viewsAndDefault/' + section, {});
    };

    this.views = function (section) {
        return $http.get(serviceBackend + '/views/' + section, {});
    };

    this.defaultView = function (section) {
        return $http.get(serviceBackend + '/defaultView/' + section, {});
    };

    this.changeView = function (section, view) {
        return $http.put(serviceBackend + '/changeView/' + section, {
            view: view
        });
    };
}]);
