grimmApp.controller('letterController', ['$scope', '$modal', 'Letters', function ($scope, $modal, Letters) {

    $scope.message = null;
    $scope.closeMessage = function () {
        $scope.message = null;
    };

    $scope.mode = 'index';
    $scope.letters = [];
    $scope.currentLetter = {};

    $scope.itemsPerPage = 25;
    $scope.currentPage = 1;
    $scope.itemsPerPageOptions = [10, 15, 20, 25, 30, 35, 40, 50, 60, 70, 80, 90, 100, 150];
    $scope.showLettersWithErrors = {
        from: false,
        to: false,
        senders: false,
        receivers: false
    };

    $scope.index = function (event) {
        $scope.mode = 'index';
        $scope.currentLetter = {};

        if (typeof event !== 'undefined') {
            event.preventDefault();
        }
    };

    $scope.show = function (letter) {
        $scope.mode = 'show';
        $scope.currentLetter = letter;
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

    $scope.openPersonModal = function (currentLetter, assignMode) {

        var modalInstance = $modal.open({
            templateUrl: 'admin/partials/assign.' + assignMode.toLowerCase(),
            controller: 'assignPersonController',
            resolve: {
                letter: function () {
                    return currentLetter
                },
                mode: function () {
                    return assignMode.toLowerCase();
                }
            }
        });

        modalInstance.result.then(function (result) {
            Letters.assign(assignMode, currentLetter.id, result).success(function (result) {
                $scope.message = result;
                $scope.reload($scope.itemsPerPage, $scope.currentPage);
            }).error(function (result) {
                $scope.message = result;
            });
        }, function () {

        });
    }

    $scope.openLocationModal = function (currentLetter, assignMode) {

        var modalInstance = $modal.open({
            templateUrl: 'admin/partials/assign.' + assignMode.toLowerCase(),
            controller: 'assignLocationController',
            resolve: {
                letter: function () {
                    return currentLetter
                },
                mode: function () {
                    return assignMode.toLowerCase();
                }
            }
        });

        modalInstance.result.then(function (result) {
            Letters.assign(assignMode, currentLetter.id, result).success(function (result) {
                $scope.message = result;
                $scope.reload($scope.itemsPerPage, $scope.currentPage);
            }).error(function (result) {
                $scope.message = result;
            });
        }, function () {

        });
    }
}]);
