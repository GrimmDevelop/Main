grimmApp.service("Tasks", ['$http', 'BASE_URL', function ($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/admin/tasks';

    this.getTasks = function() {
        return $http.get(serviceBackend);
    };
}]);
