grimmApp.directive("fieldsSelection", ['Search', function (Search) {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            scope.codes = [];
            scope.fieldsTmp = {};

            Search.codes().success(function (codes) {
                codes.unshift("");
                scope.codes = codes;
            });

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
            }

        },
        template: "display codes:\n<div class=\"checkbox\" ng-repeat=\"code in codes\" ng-if=\"code != ''\">\n" +
                  "<label><input type=\"checkbox\" ng-model=\"fieldsTmp[code]\" ng-change=\"toggleField(code)\"> {{ code }}</label></div>",
        scope: {
            fields: '=fieldsSelection'
        }
    };
}]);
