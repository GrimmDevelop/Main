grimmApp.service("Display", ['$http', 'BASE_URL', function ($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/user/display';

    this.views = function (section) {
        return $http.get(serviceBackend + '/views' + _section(section));
    }

    this.defaultView = function (section) {
        return $http.get(serviceBackend + '/defaultView' + _section(section));
    }

    this.changeView = function (section, view) {
        return $http.put(serviceBackend + '/changeView' + _section(section), {
            view: view
        });
    }

    var _section = function(value) {
        if (typeof value != 'undefined') {
            return '/' + value;
        } else {
            return '';
        }
    }
}]);
