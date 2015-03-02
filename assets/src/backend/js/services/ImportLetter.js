grimmApp.service("ImportLetter", ['$http', 'BASE_URL', function ($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/admin/import/letters';

    this.start = function (selectedFile) {
        return $http.post(serviceBackend, {
            "data": selectedFile
        });
    };
}]);