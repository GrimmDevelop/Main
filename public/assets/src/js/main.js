
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

var grimmApp = angular.module('grimmApp', ['ngRoute', "ui.bootstrap", "google-maps", "flow", "dialogs", "ngDragDrop"]);

grimmApp.config(['$routeProvider', 'BASE_URL', '$httpProvider', 'flowFactoryProvider',
    function($routeProvider, BASE_URL, $httpProvider, flowFactoryProvider) {
        $routeProvider.
            when('/files', {
                controller: 'filesController',
                templateUrl: 'admin/partials/files'
            })
            .when('/users', {
                templateUrl: 'admin/partials/users',
                controller: 'userController'
            })
            .when('/letters', {
                templateUrl: 'admin/partials/letters',
                controller: 'letterController'
            })
            .when('/locations', {
                templateUrl: BASE_URL + '/admin/partials/locations',
                controller: 'locationsController'
            })
            .when('/', {
                templateUrl: BASE_URL + '/admin/partials/dashboard',
                controller: 'dashboardController'
            })
            .otherwise({
                redirectTo: '/'
            });

        $httpProvider.interceptors.push(['$q', '$rootScope', '$location', 'BASE_URL', function($q, $rootScope, $location, BASE_URL) {
            return {
                'request': function(config) {
                    $rootScope.$broadcast('loading-started');
                    return config || $q.when(config);
                },

                'response': function(response) {
                    $rootScope.$broadcast('loading-complete');
                    return response || $q.when(response);
                },

                'responseError': function(rejection) {
                    var status = rejection.status;
                    $rootScope.$broadcast('loading-complete');
                    if (status == 401 && rejection.data === 'ParkCMS Unauthorized') {
                        $rootScope.$broadcast('auth-error');
                        window.location.href = BASE_URL + "/login";
                        return;
                    }
                    return $q.reject(rejection);
                }
            };
        }]);

        flowFactoryProvider.defaults = {
            testChunks: false
        };
    }
]);

grimmApp.run(['$http', function($http) {
    $http.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}]);