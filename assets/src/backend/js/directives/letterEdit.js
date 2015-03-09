grimmApp.directive("letterEdit", ['$modal', function ($modal) {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            element.on('click', function () {
                $modal.open({
                    templateUrl: 'admin/partials/letters.edit',
                    controller: 'letterEditController',
                    size: 'lg',
                    resolve: {
                        codes: function() {
                            return scope.codes;
                        },
                        id: function () {
                            return scope.letterId;
                        }
                    }
                });
            });
        },
        scope: {
            letterId: '=letterEdit',
            codes: '='
        }
    };
}]);
