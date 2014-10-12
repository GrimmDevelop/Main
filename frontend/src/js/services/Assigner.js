grimmApp.service("Assigner", ['$http', 'BASE_URL', function($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/admin/assign/';

    this.from = function() {
        return $http.post(serviceBackend + "from", {
            take: 500
        });
    }

    this.to = function() {
        return $http.post(serviceBackend + "to", {
            take: 500
        });
    }

    this.senders = function() {
        return $http.post(serviceBackend + "senders", {
            take: 500
        });
    }

    this.receivers = function() {
        return $http.post(serviceBackend + "receivers", {
            take: 500
        });
    }

    this.cacheLocation = function(name, id) {
        return $http.post(serviceBackend + "cache/location", {
            name: name,
            geo_id: id
        });
    }

    this.cachePerson = function(name, id) {
        return $http.post(serviceBackend + "cache/person", {
            name: name,
            person_id: id
        });
    }
}]);