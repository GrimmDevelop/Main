grimmApp.service("Assigner", ['$http', 'BASE_URL', function($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/admin/assign/';

    this.from = function(take) {
        return $http.post(serviceBackend + "from", {
            take: take
        });
    }

    this.to = function(take) {
        return $http.post(serviceBackend + "to", {
            take: take
        });
    }

    this.senders = function(take) {
        return $http.post(serviceBackend + "senders", {
            take: take
        });
    }

    this.receivers = function(take) {
        return $http.post(serviceBackend + "receivers", {
            take: take
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