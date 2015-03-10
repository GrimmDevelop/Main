grimmApp.directive("fieldEdit", ['$modal', function ($modal) {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            element.on('click', function (event) {
                $modal.open({
                    templateUrl: 'admin/partials/edit.field',
                    controller: 'fieldEditController',
                    resolve: {
                        object: function () {
                            return scope.object;
                        },
                        fields: function () {
                            return scope.fields;
                        },
                        field: function () {
                            return scope.field;
                        },
                        onSave: function () {
                            return scope.onSave;
                        }
                    }
                });

                if (typeof event != 'undefined') {
                    event.preventDefault();
                    event.stopPropagation();
                }
            });
        },
        scope: {
            object: '=fieldEdit',
            fields: '=fields',
            field: '=field',
            onSave: '&'
        }
    };
}]);
