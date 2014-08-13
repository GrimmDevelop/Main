
grimmApp.controller('userController', ['$scope', 'UsersService', function ($scope, users) {

    $scope.mode = 'index';
    $scope.currentUser = {};

    $scope.refresh = function() {
        users.all().success(function(data) {
            $scope.users = data;
        });
    }

    $scope.index = function() {
        $scope.mode = 'index';
        $scope.currentUser = {};
    }

    $scope.edit = function(user) {
        $scope.mode = 'edit';
        $scope.currentUser = user;
    }

    $scope.save = function() {
        if($scope.mode == 'edit') {
            users.update($scope.currentUser)
                .success(function(data) {
                    $scope.message = data;
                    $scope.index();
                })
                .error(function(data) {
                    $scope.message = data;
                });
        } else if($scope.mode == 'create') {
            users.create($scope.newUser)
                .success(function(data) {

                })
                .error(function(data) {
                    $scope.message = data;
                });
        }
    }

    $scope.cancel = function(event) {
        $scope.refresh();
        $scope.index();

        event.preventDefault();
    }

    $scope.delete = function() {
        bootbox.confirm("Sure to delete " + $scope.currentUser.username + "?", function(result) {
            if(result) {
                console.log('delete ' + $scope.currentUser.username);
            }
        });

        event.preventDefault();
    }

    $scope.refresh();
}]);
