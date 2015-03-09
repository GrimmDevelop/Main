grimmApp.controller('fieldEditController', ['$scope', '$modal', '$modalInstance', 'hotkeys', 'focus', 'object', 'fields', 'field', 'onSave', function ($scope, $modal, $modalInstance, hotkeys, focus, object, fields, field, onSave) {

    // Object.create() is introduced in ES5 (IE9+)
    $scope.object = Object.create(object);
    $scope.fields = fields;
    $scope.field = field;

    $scope.object.information = [];
    object.information.forEach(function(information) {
        if(information.data != '') {
            var infomationClone = Object.create(information);
            infomationClone.state = 'keep';
            $scope.object.information.push(infomationClone);
        }
    });

    $scope.addField = function() {
        $scope.object.information.push({
            state: 'add',
            code: field,
            data: ''
        });

        focus('input.field');
    }

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };

    $scope.ok = function () {
        object.information = $scope.object.information;

        onSave();

        $modalInstance.close();
    };

    hotkeys.add({
        combo: 'ctrl+alt+a',
        description: 'Add field',
        allowIn: ['INPUT', 'SELECT', 'TEXTAREA'],
        callback: function(event) {
            $scope.addField();
        }
    });
}]);