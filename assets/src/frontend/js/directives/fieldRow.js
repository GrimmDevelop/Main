// TODO: Fix Typeahead stuff and add onchange event
grimmApp.directive('fieldRow', ['BASE_URL', '$compile', function(BASE_URL, $compile) {
    return {
        restrict: 'E',
        link: function(scope, element, attrs) {
            element.find('.code-selector').focus();

            if (scope.theField.type == 'group') {
                $compile('<field-group group="theField" not-removable="false" on-remove="onRemove(theField)" codes="codes"></field-group>')(scope, function(cloned, scope) {
                    element.empty();
                    element.append(cloned);
                });
            }
        },
        templateUrl: BASE_URL+'/partials/fieldRow',
        scope: {
            theField: '=field',
            codes: '=codes',
            onChange: '&',
            onRemove: '&'
        }
    };
}]);