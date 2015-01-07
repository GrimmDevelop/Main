grimmApp.service("Search", ['$http', 'BASE_URL', function ($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/search';

    this.search = function (filters, perPage, page) {
        return $http.post(serviceBackend, {
            filters: filters,
            items_per_page: perPage,
            page: page
        });
    }

    this.distanceMap = function (filters) {
        return $http.post(serviceBackend + '/distanceMap', {
            filters: filters
        });
    }

    this.dateCodeBounds = function () {
        return $http.post(serviceBackend + '/dataCodeBounds');
    }

    this.codes = function () {
        return $http.get(serviceBackend + '/codes');
    }

    this.loadFilters = function () {
        return $http.get(serviceBackend + '/filters');
    }

    this.loadFilter = function (key) {
        return $http.get(serviceBackend + '/filters/' + key);
    }

    this.newFilter = function (filter) {
        return $http.post(serviceBackend + '/filters', {
            filter: filter
        });
    }

    this.saveFilter = function (filter) {
        return $http.put(serviceBackend + '/filters', {
            filter: filter
        });
    }

    this.publicFilter = function (filter) {
        return $http.put(serviceBackend + '/filters/public', {
            filter: filter
        });
    }

    this.deleteFilter = function(filter) {
        return $http.delete(serviceBackend + '/filters/' + filter.id);
    }
}]);
