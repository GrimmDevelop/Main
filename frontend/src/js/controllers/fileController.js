grimmApp.controller('filesController',['$scope', '$modal', 'FileBrowser', function($scope, $modal, browser) {
    $scope.files = [];
    $scope.upload = {};
    $scope.upload.flow = null;

    $scope.cd = function(path, ev) {
        browser.cd(path).success(function(data) {
           $scope.files = data;
           $scope.$broadcast('file-clicked', null);
        });

        if (typeof ev !== 'undefined') {
            ev.preventDefault();
        }
    }

    $scope.open_mkdir = function() {
        var modalInstance = $modal.open({
            templateUrl: 'admin/partials/mkdir_modal',
            controller: 'MkdirController',
            resolve: {
                cwd: function() {
                    return browser.cwd();
                }
            }
        });

        modalInstance.result.then(function (dirname) {
            browser.mkdir(browser.cwd(), dirname).success(function() {
                $scope.refresh();
            });
        });
    }

    $scope.refresh = function(ev) {
        $scope.cd(browser.cwd());

        if (typeof ev !== 'undefined') {
            ev.preventDefault();
        }
    };

    $scope.uploadComplete = function() {
        $scope.refresh();
        $scope.upload.flow.cancel();
    }

    $scope.$on('browser-needs-refresh', function() {
        $scope.refresh();
    });

    $scope.$on('directory-deleted', function() {
        $scope.refresh();
    });

    $scope.hideComplete = function(file) {
        return !file.isComplete();
    };

    $scope.fileAdded = function (event, flowFile) {
        flowFile.virtualPath = browser.cwd();
    };

    $scope.startUpload = function(event, files) {
        $scope.upload.flow.upload();
    }

    $scope.queryBuild = function(flowFile, flowChunk) {
        return {'virtualPath': flowFile.virtualPath};
    };

    $scope.preview = function(file, $event) {
        $scope.$broadcast('file-clicked', file);
        $scope.selected = file.path;

        if (typeof ev !== 'undefined') {
            $event.preventDefault();
        }
    }

    $scope.move = function(src, dest) {
        browser.move(src.path, dest.path).success(function() {
            $scope.refresh();
        });
    }

    $scope.cd('/');
}]);

grimmApp.controller('MkdirController',['$scope', '$modalInstance', 'cwd', function($scope, $modalInstance, cwd) {
    $scope.fields = {};
    $scope.fields.dirname = "";
    $scope.cwd = cwd;
    $scope.ok = function() {
        $modalInstance.close($scope.fields.dirname);
    };

    $scope.cancel = function() {
        $modalInstance.dismiss('cancel');
    }

    $scope.hitEnter = function(evt){
        if(angular.equals(evt.keyCode,13) && !(angular.equals($scope.fields.dirname,null) || angular.equals($scope.fields.dirname,''))) {
            $scope.ok();
        }
    };
}]);