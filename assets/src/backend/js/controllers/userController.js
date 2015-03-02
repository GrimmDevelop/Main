grimmApp.controller('userController', ['$scope', 'UsersService', function ($scope, users) {

    $scope.mode = 'index';
    $scope.currentUser = {};

    $scope.message = {};
    $scope.closeMessage = function () {
        $scope.message = null;
    };

    $scope.refresh = function () {
        users.all().success(function (data) {
            $scope.users = data;
        });
    };

    $scope.index = function () {
        $scope.mode = 'index';
        $scope.currentUser = {};
    };

    $scope.edit = function (user) {
        $scope.mode = 'edit';
        $scope.currentUser = user;
    };

    $scope.create = function () {
        $scope.mode = 'create';
        $scope.currentUser = {};
    };

    $scope.save = function () {
        if ($scope.mode == 'edit') {
            users.update($scope.currentUser)
                .success(function (data) {
                    $scope.message = data;
                    $scope.index();
                })
                .error(function (data) {
                    $scope.message = data;
                });
        } else if ($scope.mode == 'create') {
            users.create($scope.currentUser)
                .success(function (data) {
                    $scope.message = data;
                })
                .error(function (data) {
                    $scope.message = data;
                });
        }
    };

    $scope.cancel = function (event) {
        $scope.refresh();
        $scope.index();

        event.preventDefault();
    };

    $scope.delete = function (event) {
        // TODO: remove bootbox
        bootbox.confirm("Sure to delete " + $scope.currentUser.username + "?", function (result) {
            if (result) {
                console.log('delete ' + $scope.currentUser.username);
            }
        });

        event.preventDefault();
    };

    $scope.refresh();
}]);
