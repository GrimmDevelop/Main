grimmApp.service("Locations", ['$http', 'BASE_URL', function ($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/api/';

    this.all = function (itemsPerPage, page) {

        var params = {};

        if (typeof itemsPerPage != 'undefined') {
            params.items_per_page = itemsPerPage;
        }

        if (typeof page != 'undefined') {
            params.page = page;
        }

        return $http.get(serviceBackend + 'locations', {
            params: params
        });
    };

    this.get = function (id) {
        return $http.get(serviceBackend + 'locations/' + id);
    }

    this.search = function (name, in_alternate_names) {
        if (typeof in_alternate_names == 'undefined') {
            in_alternate_names = false;
        }

        return $http.post(serviceBackend + 'locations/search', {
            name: name,
            in_alternate_names: in_alternate_names
        });
    }

    this.searchAhead = function (name) {
        return $http.post(serviceBackend + 'locations/search', {
            name: name,
            in_alternate_names: false,
            ahead: true
        });
    }

}]);
