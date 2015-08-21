grimmApp.controller('letterController', ['$scope', '$modal', 'MessagesService', 'Display', 'Letters', 'Search', 'hotkeys', 'focus', function ($scope, $modal, MessagesService, DisplayService, Letters, Search, hotkeys, focus) {

    $scope.message = null;
    $scope.closeMessage = function () {
        $scope.message = null;
    };

    $scope.letters = [];
    $scope.trashedLetters = [];

    $scope.pagination = {
        itemsPerPage: 25,
        currentPage: 1,
        itemsPerPageOptions: [10, 15, 20, 25, 30, 35, 40, 50, 60, 70, 80, 90, 100, 150]
    };

    $scope.showLettersWithErrors = {
        from: false,
        to: false,
        senders: false,
        receivers: false
    };

    $scope.display = {
        currentView: null,
        views: []
    };

    $scope.quicksearch = {
        id: null,
        code: null
    };

    $scope.tabstatus = {
        filter: true,
        quicksearch: false,
        display: false,
        trashed: false
    };

    $scope.trashedChanged = false;

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

    Search.codes(true).success(function (data) {
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

    /*$scope.editColumn = function (field) {
        MessagesService.broadcast('success', 'Edit complete column ' + field);
    };*/

    $scope.editField = function (letter, field) {
        var letterObj = {
            id: letter.id,
            code: letter.code,
            date: letter.date,
            information: []
        };

        letter.information.forEach(function(value, index) {
            if(value.code == field) {
                if(value.state == 'add' && value.data == '') {
                    return;
                }

                letterObj.information.push({
                    id: value.state == 'add' ? null : value.id,
                    code: value.code,
                    data: value.data,
                    state: value.data != '' ? value.state : 'remove'
                });
            }
        });

        Letters.save(letterObj).success(function(response) {
            MessagesService.broadcast('success', response.message);
        }).error(function(response) {
            MessagesService.broadcast('danger', response.message);
        });
    };

    $scope.reload = function () {
        Search.search(
            $scope.currentFilter.fields,
            $scope.pagination.itemsPerPage,
            $scope.pagination.currentPage,
            ['information', 'senders', 'receivers', 'from', 'to'],
            $scope.showLettersWithErrors
        ).success(function (data) {
                $scope.letters = data;
            });
    };

    $scope.loadTrashedLetters = function () {
        Letters.trashed().success(function (response) {
            $scope.trashedLetters = response;
            $scope.trashedChanged = false;
        });
    };

    $scope.restoreLetter = function (letter) {
        Letters.restore(letter).success(function () {
            letter.deleted_at = null;
            $scope.trashedChanged = true;
        });
    };

    $scope.findByIdentifierOrCode = function () {
        if ($scope.quicksearch.id != null && $scope.quicksearch.id != '') {
            $scope.quicksearch.code = null;
            Search.findById(parseInt($scope.quicksearch.id)).success(function (data) {
                $scope.letters = data;
            });
        } else if ($scope.quicksearch.code != null) {
            Search.findByCode($scope.quicksearch.code).success(function (data) {
                $scope.letters = data;
            });
        }
    };

    hotkeys.add({
        combo: 'ctrl+alt+i',
        description: 'Enable Quicksearch',
        allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
        callback: function (event) {
            event.preventDefault();
            if ($scope.tabIsActive('quicksearch')) {
                focus('quicksearch.Id');
            }
            $scope.tabstatus.quicksearch = true;
        }
    });

    hotkeys.add({
        combo: 'ctrl+alt+f',
        description: 'Search by filters',
        allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
        callback: function (event) {
            event.preventDefault();
            if ($scope.tabIsActive('filter')) {
                $scope.reload();
            }

            $scope.tabstatus.filter = true;
        }
    });

    hotkeys.add({
        combo: 'ctrl+alt+t',
        description: 'Display trash',
        allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
        callback: function (event) {
            event.preventDefault();
            if ($scope.tabIsActive('trashed')) {
                $scope.loadTrashedLetters();
            }

            $scope.tabstatus.trashed = true;
        }
    });

    hotkeys.add({
        combo: 'ctrl+alt+r',
        description: 'Reload letter list',
        allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
        callback: function (event) {
            event.preventDefault();
            if (!$scope.tabIsActive('trashed')) {
                $scope.reload();
            } else {
                $scope.loadTrashedLetters();
            }
        }
    });

    $scope.$watch('tabstatus.quicksearch', function (newVal, oldVal) {
        if (newVal) {
            focus('quicksearch.Id');
        }
    });

    $scope.$watch('tabstatus.filter', function (newVal) {
        if (newVal) {
            // ...
        }
    });

    $scope.$watch('tabstatus.trashed', function (newVal) {
        if (newVal) {
            $scope.loadTrashedLetters();
        }
    });

    $scope.tabIsActive = function (tabname) {
        return !!$scope.tabstatus[tabname];
    };

    $scope.reload();
}]);