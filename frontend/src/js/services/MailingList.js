
grimmApp.service("MailingList", ['$http', 'BASE_URL', function($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/admin/mailing/';

    this.list = function() {
        return $http.get(serviceBackend + 'list')
    };
}]);
