grimmApp.directive("personEdit", ['$modal', function ($modal) {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            element.on('click', function () {
                $modal.open({
                    templateUrl: 'admin/partials/personEdit',
                    controller: 'personEditController',
                    size: 'lg',
                    resolve: {
                        id: function () {
                            return scope.personId;
                        }
                    }
                });
            });
        },
        scope: {
            personId: '=personEdit'
        }
    };
}]);
