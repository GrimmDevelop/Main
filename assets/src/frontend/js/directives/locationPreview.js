grimmApp.directive("locationPreview", ['$modal', 'Locations', function ($modal, Locations) {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            element.on('click', function () {
                Locations.get(scope.locationId).success(function (data) {
                    $modal.open({
                        templateUrl: 'partials/locationPreview',
                        controller: 'locationPreviewController',
                        size: 'lg',
                        resolve: {
                            location: function () {
                                return data;
                            }
                        }
                    });
                });
            });
        },
        scope: {
            locationId: '=locationPreview'
        }
    };
}]);
