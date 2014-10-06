
grimmApp.service("Persons", ['$http', 'BASE_URL', function($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/api/';

    this.all = function(itemsPerPage, page) {

        var params = {};

        if(typeof itemsPerPage != 'undefined') {
            params.items_per_page = itemsPerPage;
        }

        if(typeof page != 'undefined') {
            params.page = page;
        }

        return $http.get(serviceBackend + 'persons', {
            params: params
        });
    };

    this.search = function(name) {
        return $http.post(serviceBackend + 'persons/search', {
            "name": name
        });
    };

}]);
