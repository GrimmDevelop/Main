grimmApp.directive("letterFromToPreview", ['$modal', 'Letters', function ($modal, Letters) {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            element.on('click', function () {
                Letters.get(scope.letterId).success(function (data) {
                    $modal.open({
                        templateUrl: 'partials/letterFromToPreview',
                        controller: 'letterFromToPreviewController',
                        size: 'lg',
                        resolve: {
                            letter: function () {
                                return data;
                            }
                        }
                    });
                });
            });
        },
        scope: {
            letterId: '=letterFromToPreview'
        }
    };
}]);
