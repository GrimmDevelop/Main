grimmApp.service("Letters", ['$http', 'BASE_URL', function ($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/api/';

    this.all = function () {
        return $http.get(serviceBackend + 'letters');
    };

    this.page = function (itemsPerPage, page) {

        var params = {};

        if(typeof itemsPerPage != 'undefined') {
            params.items_per_page = itemsPerPage;
        }

        if(typeof page != 'undefined') {
            params.page = page;
        }

        return $http.get(serviceBackend + 'letters', {
            params: params
        });
    }

    this.assign = function (mode, letter_id, person_id) {
        return $http.put(serviceBackend + 'letters/assign/' + mode, {
            object_id: letter_id,
            item_id: person_id
        });
    }

}]);
