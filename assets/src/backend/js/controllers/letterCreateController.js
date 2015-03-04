grimmApp.controller('letterCreateController', ['$scope', '$modal', '$modalInstance', 'Letters', function ($scope, $modal, $modalInstance, Letters) {

    $scope.letter = {};
    $scope.letter.code = "18000101.00";

    $scope.letter.information = [
        {
            state: "add",
            code: "absender",
            data: ""
        },
        {
            state: "add",
            code: "empfaenger",
            data: ""
        },
        {
            state: "add",
            code: "absendeort",
            data: ""
        },
        {
            state: "add",
            code: "empf_ort",
            data: ""
        }
    ];

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };

    $scope.removeInformation = function (information) {
        if (information.state != 'add') {
            information.state = information.state == 'remove' ? 'keep' : 'remove';
        } else {
            var index = $scope.letter.information.indexOf(information);

            if (index != -1) {
                $scope.letter.information.splice(index, 1);
            }
        }
    };

    $scope.save = function () {
        Letters.create($scope.letter).success(function () {
            $modalInstance.dismiss('cancel');
        });
    };

    $scope.addCode = function () {
        var modalInstance = $modal.open({
            templateUrl: 'admin/partials/letterEditAddCode',
            controller: 'letterEditAddCodeController'
        });

        modalInstance.result.then(function (result) {
            $scope.letter.information.push({
                state: 'add',
                code: result,
                data: null
            });
        }, function () {

        });
    };
}]);
