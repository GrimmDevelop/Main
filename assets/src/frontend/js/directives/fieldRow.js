// TODO: Fix Typeahead stuff and add onchange event
grimmApp.directive('fieldRow', ['BASE_URL', function(BASE_URL) {
    return {
        restrict: 'E',
        link: function(scope, element, attrs) {

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