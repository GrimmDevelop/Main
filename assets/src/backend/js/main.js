
grimmApp.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider
            .when('/files', {
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
            .when('/export', {
                templateUrl: 'admin/partials/export',
                controller: 'exportController'
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

    }
]);
