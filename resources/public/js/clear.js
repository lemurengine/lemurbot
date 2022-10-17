$(document).ready(function() {

    $('.clear-button').click(function(){

        var field = "#"+$(this).attr('data-field');
        $(field).val("");

    });

})
