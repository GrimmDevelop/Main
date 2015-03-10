grimmApp.service("Cluster", ['$http', 'BASE_URL', function ($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/api/cluster/';

    this.haveChanges = function () {
        return $http.get(serviceBackend + 'changes');
    };

    this.publish = function() {
        return $http.post(serviceBackend + 'publish', {

        });
    }
}]);
