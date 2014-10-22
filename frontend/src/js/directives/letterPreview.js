
grimmApp.directive("letterPreview", ['$modal', function($modal) {
    return {
        restrict: "A",
        link: function(scope, element, attrs) {
            element.on('click', function() {
                $modal.open({
                    templateUrl: 'partials/letterPreview',
                    controller: 'letterPreviewController',
                    size: 'lg',
                    resolve: {
                        letter: function () {
                            return scope.letter;
                        }
                    }
                });
            });
        },
        scope: {
            letter: '=letterPreview'
        }
    };
}]);
