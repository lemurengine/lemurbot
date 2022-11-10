(function ($, DataTable) {
    "use strict";

    DataTable.ext.buttons.upload = {
        className: 'buttons-upload',

        text: function (dt) {
            return '<i class="fa fa-upload"></i> ' + dt.i18n('buttons.upload', 'Upload');
        },

        action: function (e, dt, button, config) {
            window.location = window.location.href.replace(/\/+$/, "") + 'Upload';
        }
    };

})(jQuery, jQuery.fn.dataTable);
