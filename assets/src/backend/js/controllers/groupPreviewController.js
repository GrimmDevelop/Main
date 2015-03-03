grimmApp.controller('groupPreviewController', ['$scope', '$interval', '$modalInstance', 'group', function ($scope, $interval, $modalInstance, group) {
    $scope.group = group;

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
}]);
