grimmApp.directive("jobProgress", [function () {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {

            scope.lastOfList = function(list) {
                return list[list.length - 1];
            };
        },
        scope: {
            progress: '=theProgress'
        },
        template: '<div><span ng-if="progress.length > 0">{{ lastOfList(progress)[0] }} Uhr: {{ lastOfList(progress)[1] }}</span><span ng-if="progress.length == 0">No Progress yet</span></div>'
    }
}]);