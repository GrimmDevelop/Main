grimmApp.controller('letterPreviewController', ['$scope', '$interval', '$modalInstance', 'letter', function ($scope, $interval, $modalInstance, letter) {
    $scope.letter = letter;

    $scope.ok = function () {
        $modalInstance.dismiss('cancel');
    }
}]);
