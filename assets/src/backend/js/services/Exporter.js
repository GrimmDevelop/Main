grimmApp.service("Exporter", ['$http', 'BASE_URL', function ($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/admin/export/';

    this.letterCodes = function () {
        return $http.get(serviceBackend + "letters/codes");
    }

    this.to = function (take) {
        return $http.post(serviceBackend + "to/" + take, {});
    }
}]);