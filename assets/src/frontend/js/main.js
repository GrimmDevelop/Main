jQuery(function ($) {
    $('[data-toggle=tooltip]').tooltip();
});

var grimmApp = angular.module('grimmApp', ['ngRoute', "ui.bootstrap", "google-maps".ns(), "flow", "dialogs", "ang-drag-drop"]);

grimmApp.config(['$httpProvider', 'flowFactoryProvider', 'GoogleMapApiProvider'.ns(),
    function ($httpProvider, flowFactoryProvider, GoogleMapApi) {
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