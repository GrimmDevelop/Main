jQuery(function ($) {
    $('[data-toggle=tooltip]').tooltip();
});

var grimmApp = angular.module('grimmApp', ['ngRoute', "ui.bootstrap", "google-maps".ns(), "flow", "dialogs", "ngDragDrop"]);

grimmApp.config(['$routeProvider', '$httpProvider', 'flowFactoryProvider', 'GoogleMapApiProvider'.ns(),
    function ($routeProvider, $httpProvider, flowFactoryProvider, GoogleMapApi) {
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
                templateUrl: 'admin/partials/locations',
                controller: 'locationsController'
            })
            .when('/persons', {
                templateUrl: 'admin/partials/persons',
                controller: 'personsController'
            })
            .when('/import', {
                templateUrl: 'admin/partials/import',
                controller: 'importController'
            })
            .when('/assign', {
                templateUrl: 'admin/partials/assign',
                controller: 'assignController'
            })
            .when('/mailing', {
                templateUrl: 'admin/partials/mailingList',
                controller: 'mailingListController'
            })
            .when('/', {
                templateUrl: 'admin/partials/dashboard',
                controller: 'dashboardController'
            })
            .otherwise({
                redirectTo: '/'
            });

        $httpProvider.interceptors.push(['$q', '$rootScope', '$location', 'BASE_URL', function ($q, $rootScope, $location, BASE_URL) {
            return {
                'request': function (config) {
                    $rootScope.$broadcast('loading-started');
                    return config || $q.when(config);
                },

                'response': function (response) {
                    $rootScope.$broadcast('loading-complete');
                    return response || $q.when(response);
                },

                'responseError': function (rejection) {
                    var status = rejection.status;
                    $rootScope.$broadcast('loading-complete');
                    if (status == 401 && rejection.data === 'Grimm Unauthorized') {
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

        GoogleMapApi.configure({
            v: '3.17',
            libraries: 'weather,geometry,visualization'
        });
    }
]);

grimmApp.run(['$http', function ($http) {
    $http.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}]);