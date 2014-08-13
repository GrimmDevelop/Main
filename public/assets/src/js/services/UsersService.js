
grimmApp.service("UsersService", ['$http', 'BASE_URL', function($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/api/';

    this.all = function() {
        return $http.get(serviceBackend + 'letters');
    };

    this.create = function(user) {
        return $http.post(serviceBackend + 'users', user);
    }

    this.update = function(user) {
        return $http.put(serviceBackend + 'users/' + user.id, user);
    }
}]);
