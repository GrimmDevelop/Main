grimmApp.directive("loadingIndicator", function () {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            scope.queries = 0;

            scope.$on("loading-started", function (e) {
                scope.queries++;
                if(scope.queries > 0) {
                    element.css({"display": ""});
                }
            });
            scope.$on("loading-complete", function (e) {
                scope.queries--;
                if(scope.queries <= 0) {
                    element.css({"display": "none"});
                }
            });
        }
    };
});