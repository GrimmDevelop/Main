
grimmApp.directive("personPreview", ['$modal', 'Persons', function($modal, Persons) {
    return {
        restrict: "A",
        link: function(scope, element, attrs) {
            element.on('click', function() {
                Persons.get(scope.personId).success(function(data) {
                    $modal.open({
                        templateUrl: 'partials/personPreview',
                        controller: 'personPreviewController',
                        resolve: {
                            person: function () {
                                return data;
                            }
                        }
                    });
                })
                
            });
        },
        scope: {
            personId: '=personPreview'
        }
    };
}]);
