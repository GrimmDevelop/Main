grimmApp.service("Search", ['$http', 'BASE_URL', function ($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/search';

    this.search = function (filters, perPage, page, _with, onlyWithErrors, dateRange) {
        var params = {};

        params.filters = filters;
        params.items_per_page = perPage;
        params.page = page;


        if (typeof _with != 'undefined') {
            params.with = _with;
        }

        if (angular.isArray(dateRange)) {
            params.dateRange = dateRange
        }

        if (typeof onlyWithErrors != 'undefined') {
            var with_errors = [];

            if (onlyWithErrors.from) {
                with_errors.push('from');
            }
            if (onlyWithErrors.to) {
                with_errors.push('to');
            }
            if (onlyWithErrors.senders) {
                with_errors.push('senders');
            }
            if (onlyWithErrors.receivers) {
                with_errors.push('receivers');
            }

            params.with_errors = with_errors;
        }

        return $http.post(serviceBackend, params);
    };

    this.findById = function(id) {
        return $http.get(serviceBackend + '/find/' + id);
    };

    this.findByCode = function(code) {
        return $http.get(serviceBackend + '/code/' + code);
    };

    this.distanceMap = function (filters) {
        return $http.post(serviceBackend + '/distanceMap', {
            filters: filters
        });
    };

    /*this.dateCodeBounds = function () {
        return $http.post(serviceBackend + '/dataCodeBounds');
    };*/

    this.codes = function () {
        if (arguments.length > 0 && arguments[0]) {
            var params = {'localized': 1};
        } else {
            var params = {};
        }
        return $http.get(serviceBackend + '/codes', {params: params});
    };

    this.loadFilters = function () {
        return $http.get(serviceBackend + '/filters');
    };

    this.loadFilter = function (key) {
        return $http.get(serviceBackend + '/filters/' + key);
    };

    this.newFilter = function (filter) {
        return $http.post(serviceBackend + '/filters', {
            filter: filter
        });
    };

    this.saveFilter = function (filter) {
        return $http.put(serviceBackend + '/filters', {
            filter: filter
        });
    };

    this.publicFilter = function (filter) {
        return $http.put(serviceBackend + '/filters/public', {
            filter: filter
        });
    };

    this.deleteFilter = function(filter) {
        return $http.delete(serviceBackend + '/filters/' + filter.id);
    };

    this.dateRange = function() {
        return $http.get(serviceBackend + '/dateRange');
    };
}]);
