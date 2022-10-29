$(document).ready(function() {

    $('.slug-unlock-button').click(function(){

        bootbox.confirm({
            size: "medium",
            title: "Warning!",
            message: "Changing this value can have unintended consequences<br/>Only proceed if you know what you are doing!",
            callback: function(result){
                if(result){
                    $('#slug-unlock-button').toggle();
                    $('#slug-lock-button').toggle();
                    $("#slug_field").prop("readonly", false);
                }
            }
        })
    });

    $('.slug-lock-button').click(function(){

        $('#slug-unlock-button').toggle();
        $('#slug-lock-button').toggle();
        $("#slug_field").prop("readonly", true);

    });

})
