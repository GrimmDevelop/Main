
grimmApp.service("Locations", ['$http', 'BASE_URL', function($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/api/';

    this.all = function() {
        return $http.get(serviceBackend + 'locations');
    };

}]);