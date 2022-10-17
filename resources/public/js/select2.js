$(document).ready(function() {

    $('.generic.select2').select2({
        placeholder: "Please select an option"
    });

    $('.select2.single').select2({
        placeholder: "Please select an option",
        id: '-1'
    });
    $('.select2.auto').select2({
        placeholder: "Please select an option",
        id: 'auto'
    });
    $('.select2.first').select2({
        placeholder: "Please select an option",
        id: '-1'
    });

    $('.select2.first-option').select2({
        placeholder: "Please select an option",
        id: '-1'
    });

    $('.allow-new.select2').select2({
        placeholder: "Please select an option or create a new item",
        tags: true
    });

});
