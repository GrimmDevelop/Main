grimmApp.controller('letterCreateController', ['$scope', '$modal', '$modalInstance', 'Letters', 'id', function ($scope, $modal, $modalInstance, Letters) {

    $scope.letter = {};
    $scope.letter.information = [
        {
            state: "add",
            code: "absender",
            date: ""
        },
        {
            state: "add",
            code: "empfaenger",
            date: ""
        },
        {
            state: "add",
            code: "absendeort",
            date: ""
        },
        {
            state: "add",
            code: "empfangsort",
            date: ""
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
        Letters.save($scope.letter).success(function () {
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
