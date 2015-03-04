grimmApp.controller('letterController', ['$scope', '$modal', 'MessagesService', 'Display', 'Letters', 'Search', function ($scope, $modal, MessagesService, DisplayService, Letters, Search) {

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
        shortEdit: false
    };

    $scope.currentFilter = {};
    $scope.currentFilter.id = null;
    $scope.currentFilter.filter_key = null;
    $scope.currentFilter.fields = [];

    $scope.letterInfo = {
        codes: []
    };

    $scope.addField = function () {
        $scope.result = {};

        $scope.currentFilter.fields.push({
            code: "",
            compare: "equals",
            value: ""
        });
    };

    $scope.removeField = function (field) {
        $scope.result = {};

        var index = $scope.currentFilter.fields.indexOf(field);

        if (index > -1) {
            $scope.currentFilter.fields.splice(index, 1);
        }
    };

    Search.codes(1).success(function (data) {
        $scope.letterInfo.codes = data.data;
    });

    DisplayService.viewsAndDefault('letters').success(function (data) {
        $scope.display.views = data.views;
        $scope.display.currentView = data.default;
    });

    $scope.changeView = function (view) {
        DisplayService.changeView('letters', view).success(function (data) {
            //$scope.display.currentView = data;
        });
    };

    $scope.fields = ['absendeort', 'absort_ers', 'absender', 'empf_ort', 'empfaenger', 'dr', 'hs'];

    $scope.editColumn = function (field, event) {
        MessagesService.broadcast('success', 'Edit complete column ' + field);
        event.preventDefault();
        event.stopPropagation();
    };

    $scope.editField = function (letterId, field) {
        MessagesService.broadcast('success', 'Edit ' + field + " from letter #" + letterId);
    };

    $scope.reload = function () {
        Search.search(
            $scope.currentFilter.fields,
            $scope.itemsPerPage,
            $scope.currentPage,
            ['information', 'senders', 'receivers', 'from', 'to'],
            $scope.showLettersWithErrors
        ).success(function (data) {
            $scope.letters = data;
        });

        /*
         var itemsPerPage = $scope.itemsPerPage;
         var currentPage = $scope.currentPage;
         var showLettersWithErrors = ;
         var filters = $scope.currentFilter.fields;

         Letters.page(itemsPerPage, currentPage, showLettersWithErrors, filters).success(function (data) {
         $scope.letters = data;
         });*/
    };

    $scope.reload();

    $scope.openLetterId = null;
    $scope.openLetterWithId = function () {
        if ($scope.openLetterId) {
            $scope.show($scope.openLetterId);
        }
    };
}]);