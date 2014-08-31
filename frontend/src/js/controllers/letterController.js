
grimmApp.controller('letterController', ['$scope', 'Letters', function ($scope, Letters) {

    $scope.mode = 'index';
    $scope.letters = [];
    $scope.currentLetter = {};

    Letters.all().success(function(data) {
        $scope.letters = data;
    });

    $scope.index = function(event) {
        $scope.mode = 'index';
        $scope.currentLetter = {};

        if (typeof event !== 'undefined') {
            event.preventDefault();
        }
    };

    $scope.show = function(letter) {
        $scope.mode = 'show';
        $scope.currentLetter = letter;
    };

}]);
