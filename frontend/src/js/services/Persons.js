
grimmApp.service("Persons", ['$http', 'BASE_URL', function($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/api/';

    this.search = function(name) {
        return $http.post(serviceBackend + 'persons/search', {
            "name": name
        });
    };

}]);
