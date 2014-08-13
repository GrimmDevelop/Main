grimmApp.service("FileBrowser", ['$http', 'BASE_URL', function($http, BASE_URL) {
    var serviceBackend = BASE_URL + '/admin/files';

    var currentPath = [''];

    /**
     * Changes into the subdir
     * @param  {[type]} subdir [description]
     * @return {[type]}        [description]
     */
    this.cd = function(subdir) {
        var newPath = this.makeAbsolute(subdir);

        return _getFilesInFolder(_merge(newPath)).success(function() {
            currentPath = newPath;
        });
    };

    this.makeAbsolute = function(path) {
        if (path == '/') {
            return [''];
        }

        if (path[0] == '/') {
            // Absolute path
            return path.split('/');
        } else {
            // Relative path
            var components = path.split('/');

            // Copy current path array
            var workingPath = currentPath.slice();

            for (var i = 0; i < components.length; i++) {
                if (components[i] == '..') {
                    // Don't pop out of the root level!
                    if (workingPath.length > 1) {
                        workingPath.pop();
                    }
                } else if(components[i] != '.') {
                    workingPath.push(components[i]);
                }
            }

            return workingPath;
        }
    };

    this.move = function(srcPath, destPath) {
        return $http.get(serviceBackend + '/move', {
            params: {
                'src': srcPath,
                'dest': destPath
            }
        })
    }

    this.mkdir = function(basepath, name) {
        return $http.get(serviceBackend + '/mkdir', {
            params: {
                'basepath': basepath,
                'name': name
            }
        });
    };

    this.deleteFile = function(path) {
        return $http.get(serviceBackend + '/delete', {
            params: {
                'path': path
            }
        });
    };

    this.deleteFolder = function(path) {
        return $http.get(serviceBackend + '/deleteFolder', {
            params: {
                'path': path
            }
        });
    };

    this.rename = function(src, dest) {
        console.log(_dirname(src) + '/' + dest);
        return $http.get(serviceBackend + '/rename', {
            params: {
                'src': src,
                'dest': dest
            }
        })
    }

    /**
     * Returns the currently loaded path
     * The root level is always '/'
     * @return {string} The path
     */
    this.cwd = function(stacked) {
        stacked = (typeof stacked == 'undefined' ? false : stacked);

        if (stacked) {
            return currentPath;
        }

        return _merge(currentPath);
    };

    var _merge = function(pathArray) {
        var path = pathArray.join('/');
        if (path === '') {
            return '/';
        }
        return path;
    };

    var _dirname = function(path) {
        // Adapted from PHP.js
        //  discuss at: http://phpjs.org/functions/dirname/
        //        http: //kevin.vanzonneveld.net
        // original by: Ozh
        // improved by: XoraX (http://www.xorax.info)
        if (!path) {
            return '/';
        }
        return path.replace(/\\/g, '/').replace(/\/[^\/]*\/?$/, '');
    }

    this.dirname = _dirname;

    var _getFilesInFolder = function(folder) {
        return $http.get(serviceBackend + '/list', {
            params: {
                'path': folder
            }
        });
    };
}]);