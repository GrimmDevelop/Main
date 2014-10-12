grimmApp.service("Letters", ['$http', 'BASE_URL', function ($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/api/';

    this.all = function () {
        return this.page;
    };

    this.page = function (itemsPerPage, page, onlyWithErrors) {

        var params = {};
        if(typeof onlyWithErrors != 'undefined') {
            var with_errors = [];

            if(onlyWithErrors.from) {
                with_errors.push('from');
            }
            if(onlyWithErrors.to) {
                with_errors.push('to');
            }
            if(onlyWithErrors.senders) {
                with_errors.push('senders');
            }
            if(onlyWithErrors.receivers) {
                with_errors.push('receivers');
            }

            params = {
                "with_errors[]": with_errors
            };
        }


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
