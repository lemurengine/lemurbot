$(document).ready(function() {


    $('.edit .select2.single').select2({
        placeholder: "Please Select An Item",
        id: '-1',
        dropdownParent: $('#editModal')
    });

    $('.edit .generic.select2').select2({
        placeholder: "Please Select An Item",
        dropdownParent: $('#editModal')
    });


    $('.add .select2.single').select2({
        placeholder: "Please Select An Item",
        id: '-1',
        dropdownParent: $('#addModal')
    });

    $('.add .generic.select2').select2({
        placeholder: "Please Select An Item",
        dropdownParent: $('#addModal')
    });


    $('.select2.auto').select2({
        placeholder: "Please Select An Option",
        id: 'auto'
    });

    $('#addTask .generic.select2').select2({
        placeholder: "Please Select An Item",
        dropdownParent: $('#addModal.tasks')
    });


    $('#addWebLink .generic.select2').select2({
        placeholder: "Please Select An Item",
        dropdownParent: $('#addModal.web-links')
    });

    $('#addMarker .generic.select2').select2({
        placeholder: "Please Select An Item",
        dropdownParent: $('#addModal.markers')
    });

});
