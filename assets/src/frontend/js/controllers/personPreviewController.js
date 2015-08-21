grimmApp.controller('personPreviewController', ['$scope', '$interval', '$modalInstance', 'Letters', function ($scope, $interval, $modalInstance, Letters) {
    $scope.person = person;

    $scope.ok = function () {
        $modalInstance.dismiss('cancel');
    }
}]);
