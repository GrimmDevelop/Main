
var modelHelper = {};

modelHelper.delete = function(url, id) {
    bootbox.confirm("Sure to delete this model?", function(result) {
        if(result) {
            console.log('delete ' + url + "/" + id);
        }
    });
};

jQuery(function($) {
    $('[data-toggle=tooltip]').tooltip();
});

