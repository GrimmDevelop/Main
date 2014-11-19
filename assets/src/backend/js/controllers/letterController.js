grimmApp.controller('letterController', ['$scope', '$modal', 'Letters', function ($scope, $modal, Letters) {

    $scope.message = null;
    $scope.closeMessage = function () {
        $scope.message = null;
    };

    $scope.letters = [];

    $scope.itemsPerPage = 25;
    $scope.currentPage = 1;
    $scope.itemsPerPageOptions = [10, 15, 20, 25, 30, 35, 40, 50, 60, 70, 80, 90, 100, 150];
    $scope.showLettersWithErrors = {
        from: false,
        to: false,
        senders: false,
        receivers: false
    };

    $scope.show = function (id) {
        var modalInstance = $modal.open({
            templateUrl: 'admin/partials/letterEdit',
            controller: 'letterEditController',
            size: 'lg',
            resolve: {
                id: function () {
                    return id;
                }
            }
        });
    };

    $scope.reload = function () {

        var itemsPerPage = $scope.itemsPerPage;
        var currentPage = $scope.currentPage;
        var showLettersWithErrors = $scope.showLettersWithErrors;

        Letters.page(itemsPerPage, currentPage, showLettersWithErrors).success(function (data) {
            $scope.letters = data;
        });
    }

    $scope.reload();

    $scope.openLetterId = null;
    $scope.openLetterWithId = function () {
        $scope.show($scope.openLetterId);
    }
}]);
