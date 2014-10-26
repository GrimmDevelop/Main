grimmApp.directive("letterPreview", ['$modal', 'Letters', function ($modal, Letters) {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            element.on('click', function () {
                Letters.get(scope.letterId).success(function (data) {
                    $modal.open({
                        templateUrl: 'partials/letterPreview',
                        controller: 'letterPreviewController',
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
            letterId: '=letterPreview'
        }
    };
}]);
