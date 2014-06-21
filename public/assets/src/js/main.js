
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

var grimmApp = angular.module('grimmApp', ['ngRoute']);

grimmApp.controller('UserIndexCtrl',['$scope', '$http', 'BASE_URL', function ($scope, $http, BASE_URL) {
    serviceBackend = BASE_URL + '/api/';

    $http.get(serviceBackend + 'users').success(function(data) {
        console.log(data);
        $scope.users = data;
    });
}]);

grimmApp.controller('UserShowCtrl',['$scope', '$http', '$routeParams', 'BASE_URL', function ($scope, $http, $routeParams, BASE_URL) {
    serviceBackend = BASE_URL + '/api/';

    $http.get(serviceBackend + 'users/' + $routeParams.userId).success(function(data) {
        console.log(data);
        $scope.user = data;
    });
}]);

grimmApp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/users', {
                templateUrl: 'partials/users.index',
                controller: 'UserIndexCtrl'
            }).
            when('/users/:userId', {
                templateUrl: 'partials/users.show',
                controller: 'UserShowCtrl'
            }).
            otherwise({
                redirectTo: '/users'
            });
    }
]);
