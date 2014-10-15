
grimmApp.controller('personPreviewController', ['$scope', '$interval', '$modalInstance', 'person', function ($scope, $interval, $modalInstance, person) {
    $scope.person = person;

    $scope.ok = function () {
        $modalInstance.dismiss('cancel');
    }
}]);
