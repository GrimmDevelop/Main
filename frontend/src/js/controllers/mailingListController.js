grimmApp.controller('mailingListController', ['$scope', 'MailingList', function ($scope, MailingList) {

    $scope.mailList = [];

    MailingList.list().success(function (data) {
        $scope.mailList = data;
    });

}]);
