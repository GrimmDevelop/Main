grimmApp.service("ImportPerson", ['$http', 'BASE_URL', function ($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/admin/import/persons';

    this.start = function (selectedFile) {
        return $http.post(serviceBackend, {
            "data": selectedFile
        });
    };
}]);