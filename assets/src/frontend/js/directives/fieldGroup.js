grimmApp.directive('fieldGroup', ['BASE_URL', function(BASE_URL) {
    return {
        restrict: 'E',
        link: function(scope, element, attrs) {
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
        },
        templateUrl: BASE_URL+'/partials/fieldGroup',
        scope: {
            group: '=',
            codes: '=codes',
            onChange: '&',
            onRemove: '&'
        }
    };
}]);
