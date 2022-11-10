(function ($, DataTable) {
    "use strict";

    DataTable.ext.buttons.download = {
        className: 'buttons-download',

        text: function (dt) {
            return '<i class="fa fa-download"></i> ' + dt.i18n('buttons.download', 'Download');
        },

        action: function (e, dt, button, config) {
            var url = _buildUrl(dt, 'csv');
            window.location = url;
        }
    };

    DataTable.ext.buttons.postDownloadVisibleColumns = {
        className: 'buttons-download',

        text: function (dt) {
            return '<i class="fa fa-download"></i> ' + dt.i18n('buttons.download', 'Download (only visible columns)');
        },

        action: function (e, dt, button, config) {
            var url = dt.ajax.url() || window.location.href;
            var params = _buildParams(dt, 'csv', true);

            _downloadFromUrl(url, params);
        }
    };

    DataTable.ext.buttons.postDownload = {
        className: 'buttons-download',

        text: function (dt) {
            return '<i class="fafa-download"></i> ' + dt.i18n('buttons.download', 'Download');
        },

        action: function (e, dt, button, config) {
            var url = dt.ajax.url() || window.location.href;
            var params = _buildParams(dt, 'csv');

            _downloadFromUrl(url, params);
        }
    };


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
