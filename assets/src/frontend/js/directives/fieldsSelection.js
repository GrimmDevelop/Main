grimmApp.directive("fieldsSelection", [function () {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            scope.fieldsTmp = {};

            for (var i = 0; i < scope.fields.length; i++) {
                scope.fieldsTmp[scope.fields[i]] = true;
            }

            scope.toggleField = function(code) {
                var index = scope.fields.indexOf(code);

                if(index != -1) {
                    scope.fields.splice(index, 1);
                } else {
                    scope.fields.push(code);
                }
            };

            scope.$watch('fields', function(newVal, oldVal) {
                scope.fieldsTmp = {};

                for (var i = 0; i < scope.fields.length; i++) {
                    scope.fieldsTmp[scope.fields[i]] = true;
                }
            }, true);

        },
        template: "<div class=\"row display-filters\">\n<div class=\"checkbox col-md-3\" ng-repeat=\"(code, name) in codes\" ng-if=\"code != ''\">\n" +
                  "<label><input type=\"checkbox\" ng-model=\"fieldsTmp[code]\" ng-change=\"toggleField(code)\"> {{ name }}</label></div></div>",
        scope: {
            fields: '=fieldsSelection',
            codes: '=fieldsCodes'
        }
    };
}]);
