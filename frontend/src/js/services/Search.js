grimmApp.service("Search", ['$http', 'BASE_URL', function ($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/search';

    this.search = function (filters, page) {
        return $http.post(serviceBackend, {
            filters: filters,
            page: page
        });
    }

    this.codes = function() {
        return $http.get(serviceBackend + '/codes');
    }

    this.loadFilters = function() {
        return $http.get(serviceBackend + '/filters');
    }

    this.saveFilters = function(filters) {
        return $http.put(serviceBackend + '/filters', {
            filters: filters
        });
    }
}]);
