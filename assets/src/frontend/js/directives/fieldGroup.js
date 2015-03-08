grimmApp.directive('fieldGroup', ['BASE_URL', function(BASE_URL) {
    return {
        restrict: 'E',
        link: function(scope, element, attrs) {
            scope.operators = {
                'OR': 'or',
                'AND': 'and'
            };

            scope.addField = function() {
                scope.group.fields.push({
                    code: '',
                    compare: 'equals',
                    value: '',
                    type: 'field'
                });
            };

            scope.addGroup = function() {
                scope.group.fields.push({
                    type: 'group',
                    properties: {
                        operator: 'OR'
                    },
                    fields: []
                });
            };

            scope.onFieldRemove = function(field) {
                var index = scope.group.fields.indexOf(field);

                if (index > -1) {
                    scope.group.fields.splice(index, 1);
                }
            };
        },
        templateUrl: BASE_URL+'/partials/fieldGroup',
        scope: {
            group: '=',
            codes: '=codes',
            onChange: '&',
            onRemove: '&',
            notRemovable: '='
        }
    };
}]);
