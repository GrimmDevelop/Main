grimmApp.directive("letterCreate", ['$modal', function ($modal) {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            element.on('click', function () {
                $modal.open({
                    templateUrl: 'admin/partials/letters.create',
                    controller: 'letterCreateController',
                    size: 'lg',
                    resolve: {}
                });
            });
        }
    };
}]);
