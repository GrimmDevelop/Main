grimmApp.directive("groupPreview", ['$modal', 'UsersService', function ($modal, Users) {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            element.on('click', function () {
                Users.getGroup(scope.groupId).success(function (data) {
                    $modal.open({
                        templateUrl: 'admin/partials/groupPreview',
                        controller: 'groupPreviewController',
                        size: 'lg',
                        resolve: {
                            group: function () {
                                return data;
                            }
                        }
                    });
                });
            });
        },
        scope: {
            groupId: '=groupPreview'
        }
    };
}]);
