grimmApp.controller('letterPreviewController', ['$scope', '$interval', '$modalInstance', 'letter', 'codes', function ($scope, $interval, $modalInstance, letter, codes) {
    $scope.letter = letter;
    $scope.codes = codes;

    $scope.ok = function () {
        $modalInstance.dismiss('cancel');
    }
}]);
