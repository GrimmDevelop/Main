grimmApp.service("Exporter", ['$http', 'BASE_URL', function ($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/admin/export/';

    this.formats = function () {
        return $http.get(serviceBackend + "formats");
    }

    this.letterCodes = function () {
        return $http.get(serviceBackend + "letters/codes");
    }
}]);