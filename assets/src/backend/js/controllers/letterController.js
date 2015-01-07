grimmApp.controller('letterController', ['$scope', '$modal', 'MessagesService', 'Display', 'Letters', function ($scope, $modal, MessagesService, DisplayService, Letters) {

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

    $scope.display = {
        currentView: null,
        views: [],
        shortEdit: true
    };

    DisplayService.views('letters').success(function(data) {
        $scope.display.views = data;
    });

    DisplayService.defaultView('letters').success(function(data) {
        $scope.display.currentView = data;
    });

    $scope.changeView = function(view) {
        DisplayService.changeView('letters', view).success(function(data) {
            $scope.display.currentView = data;
        });
    }

    $scope.view = {
        current: 'admin/partials/views.letters.data',
        default: 'admin/partials/views.letters.data',
        all: []
    };
    $scope.fields = ['absendeort','absort_ers','absender','empf_ort','empfaenger','dr','hs'];

    $scope.editField = function(letterId, field) {
        MessagesService.broadcast('success', 'Edit ' + field + " from letter #" + letterId);
    }

    $scope.show = function (id) {
        $modal.open({
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
        if($scope.openLetterId) {
            $scope.show($scope.openLetterId);
        }
    }
}]);
