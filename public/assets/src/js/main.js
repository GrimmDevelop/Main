
var modelHelper = {};

modelHelper.delete = function(url, id) {
    bootbox.confirm("Sure to delete this model?", function(result) {
        if(result) {
            console.log('delete ' + url + "/" + id);
        }
    });
};

jQuery(function($) {
    $('[data-toggle=tooltip]').tooltip();

    /*$('.typeahead').each(function() {
        var $this = $(this);
        $this.typeahead({
            "source": function(query, process) {
                $.getJSON("{{ url('search/typeahead') }}?field=" + $this.attr('name') + "&query=" + query, function(data) {
                    process(data);
                });
            }
        });
    });*/
});

var grimmApp = angular.module('grimmApp', ['ngRoute', "ui.bootstrap", "google-maps"]);

grimmApp.config(['$routeProvider', 'BASE_URL',
    function($routeProvider, BASE_URL) {
        $routeProvider.
            when('/users', {
                templateUrl: BASE_URL + '/admin/partials/users.index',
                controller: 'UserIndexCtrl'
            })
            .when('/users/create', {
                templateUrl: BASE_URL + '/admin/partials/users.create',
                controller: 'UserCreateCtrl'
            })
            .when('/users/:userId', {
                templateUrl: BASE_URL + '/admin/partials/users.show',
                controller: 'UserShowCtrl'
            })
            .when('/users/:userId/edit', {
                templateUrl: BASE_URL + '/admin/partials/users.edit',
                controller: 'UserEditCtrl'
            })
            .when('/letters', {
                templateUrl: BASE_URL + '/admin/partials/letters.index',
                controller: 'LetterIndexCtrl'
            })
            .when('/locations', {
                templateUrl: BASE_URL + '/admin/partials/locations.index',
                controller: 'LocationIndexCtrl'
            })
            .when('/locations/:locationId', {
                templateUrl: BASE_URL + '/admin/partials/locations.show',
                controller: 'LocationShowCtrl'
            })
            .when('/', {
                templateUrl: BASE_URL + '/admin/partials/dashbord.index',
                controller: 'AdminIndexCtrl'
            })
            .otherwise({
                redirectTo: '/'
            });
    }
]);

grimmApp.run(['$http', function($http) {
    $http.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}]);