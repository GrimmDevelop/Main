grimmApp.service("ImportLocation", ['$http', 'BASE_URL', function ($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/admin/import/locations';

    this.start = function (selectedFile) {
        return $http.post(serviceBackend, {
            "data": selectedFile
        });
    }
}]);