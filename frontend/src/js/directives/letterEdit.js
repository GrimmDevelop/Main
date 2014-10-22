
grimmApp.directive("letterEdit", ['$modal', function($modal) {
    return {
        restrict: "A",
        link: function(scope, element, attrs) {
            element.on('click', function() {
                $modal.open({
                    templateUrl: 'admin/partials/letterEdit',
                    controller: 'letterEditController',
                    size: 'lg',
                    resolve: {
                        id: function () {
                            return scope.letterId;
                        }
                    }
                });
            });
        },
        scope: {
            letterId: '=letterEdit'
        }
    };
}]);
