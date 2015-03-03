grimmApp.service("UsersService", ['$http', 'BASE_URL', function ($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/api/';

    this.all = function () {
        return $http.get(serviceBackend + 'users');
    };

    this.getGroup = function (id) {
        return $http.get(serviceBackend + 'groups/' + id);
    };

    this.create = function (user) {
        return $http.post(serviceBackend + 'users', user);
    };

    this.update = function (user) {
        return $http.put(serviceBackend + 'users/' + user.id, user);
    };

    this.delete = function (user) {
        return $http.delete(serviceBackend + 'users/' + user.id);
    };
}]);
