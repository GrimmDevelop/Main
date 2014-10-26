grimmApp.directive("browserSidebar", ['FileBrowser', '$rootScope', '$dialogs', 'BASE_URL', function (browser, $root, $dialogs, BASE_URL) {
    return {
        restrict: "A",
        templateUrl: 'admin/partials/browserSidebar',
        link: function (scope, element, attrs) {
            scope.file = [];
            scope.deleteFile = function ($event, path) {
                var dlg = $dialogs.confirm(attrs.deleteModalTitle, _format(attrs.deleteFileText, path));
                dlg.result.then(function () {
                    browser.deleteFile(path).success(function () {
                        scope.$emit('browser-needs-refresh');
                    });
                });

                $event.preventDefault();
            };

            scope.deleteFolder = function ($event, path) {
                var dlg = $dialogs.confirm(attrs.deleteModalTitle, _format(attrs.deleteDirectoryText, path));
                dlg.result.then(function () {
                    browser.deleteFolder(path).success(function () {
                        scope.$emit('directory-deleted', path);
                    });
                });
            };

            scope.rename = function ($event, file) {
                var dlg = $dialogs.create(BASE_URL + '/admin/partials/rename', 'renameFileController', {
                    'src': file.path,
                    'name': file.filename
                }, {key: false});
                dlg.result.then(function (dest) {
                    browser.rename(file.path, dest).success(function () {
                        scope.$emit('browser-needs-refresh');
                    });
                });

                $event.preventDefault();
            };

            var _format = function (input) {
                var formatted = input;
                for (var i = 1; i < arguments.length; i++) {
                    var regexp = new RegExp('\\{' + (i - 1) + '\\}', 'gi');
                    formatted = formatted.replace(regexp, arguments[i]);
                }
                return formatted;
            };

            scope.$on('file-clicked', function (ev, file) {
                scope.file = file;
            });
        }
    };
}]).controller('renameFileController', ['$scope', '$modalInstance', 'data', function ($scope, $modalInstance, data) {
    $scope.file = {'src': data.src, 'dest': data.name};

    $scope.cancel = function () {
        $modalInstance.dismiss('canceled');
    }; // end cancel

    $scope.save = function () {
        if ($scope.file.dest == '') {
            $modalInstance.dismiss('invalid dest path');
            return;
        }
        $modalInstance.close($scope.file.dest);
    }; // end save

    $scope.hitEnter = function (evt) {
        if (angular.equals(evt.keyCode, 13) && !(angular.equals($scope.name, null) || angular.equals($scope.name, '')))
            $scope.save();
    }; // end hitEnter
}]);