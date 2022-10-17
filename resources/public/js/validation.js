$(document).ready(function() {

    //prevent double submit
    $('form').submit(function(){
        $(this).children('input[type=submit]').prop('disabled', true);
    });

    $.validate({
        modules : 'date',
        form : '.validate',
        errorElementClass : 'has-feedback has-error',
        scrollToTopOnError:true,
        validateOnBlur : false,
        showHelpOnFocus : false,
        addSuggestions : false,
        errorMessagePosition: "inline",
    });

    $.validate({
        modules : 'date, file',
        form : '.validate-modal',
        errorElementClass : 'has-feedback has-error',
        scrollToTopOnError:false,
        validateOnBlur : false,
        showHelpOnFocus : false,
        addSuggestions : false,
        errorMessagePosition: "inline",
    });


    $('.form-control').on('input', function() {



        $(this).closest('.form-group').addClass('has-revalidate');
        $(this).closest('.form-group').removeClass('has-error');
        $(this).closest('.form-control').removeClass('has-error');
        $(this).closest('input').removeClass('has-error');
        $(this).closest('input').removeClass('has-feedback');
        $(this).closest('input').removeAttr('style');
        $(this).closest('input').removeAttr('current-error');


        $(this).next('span.form-error').html('&nbsp;');
        $(this).parent('div').next('span.form-error').html('&nbsp;');



    });




})
