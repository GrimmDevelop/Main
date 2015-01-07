
grimmApp.directive('messages', ['$interval', function($interval) {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            scope.messages = [];

            scope.$on('message', function(e, message) {
                scope.messages.push(message);
                $interval(removeFromList(message, scope), 1500, 1);
            });

            function removeFromList(message, scope) {
                return function() {
                    var index = scope.messages.indexOf(message);

                    if (index != -1) {
                        scope.messages.splice(index, 1);
                    }
                }
            }
        }
    };
}]);
