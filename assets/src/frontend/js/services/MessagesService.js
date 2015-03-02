grimmApp.service("MessagesService", ['$rootScope', function ($rootScope) {

    this.broadcast = function(type, text) {
        $rootScope.$broadcast('message', {
            type: type,
            text: text
        });
    };

}]);
