function getFormattedItem(item, field){

    if(field !== 'is_banned'){
        if(item=='' || typeof item === 'undefined'){
            return '';
        }
    }

    if(field == 'avatar_params' ){

        return "<img src='https://lemurtar.com/?"+item+"' width=50px height=50px />";

    }


    item = item.toString()

    if(field == 'description' ){

        let res = item.substring(0, 100);
        if(res!=item){
            res+="...";
        }

        return res;

    }else if(field == 'long_id' ){

        let res = item.substring(0, 8);
        if(res!=item){
            res+="...";
        }

        return res;

    }else if(field == 'is_banned' ){

        console.log(item);
        if(item== "true"){
            return "<small class='label bg-red'><i class='fa fa-thumbs-o-up'></i></small>"
        }else {
            return "<small class='label bg-green'><i class='fa fa-thumbs-o-down'></i></small>"
        }

    }else{
        if(item== "false"){
            return "<small class='label bg-red'><i class='fa fa-thumbs-o-down'></i></small>"
        }else {
            return "<small class='label bg-green'><i class='fa fa-thumbs-o-up'></i></small>"
        }
    }

}

function addRowFeatures(settings, json, linkModel, linkAction){

    var actionId = 'edit'

    $('table#dataTableBuilder td').each(function (k,v) {



        if(linkModel=='searchDatatable'){

            $(v).click(function(){
                if(!$(this).is(':last-child'))
                {
                    var searchValue = $(this).closest('td').nextAll(':has(input.searchValue):first').find('input.searchValue').val();
                    var searchCol = $(this).closest('td').nextAll(':has(input.searchCol):first').find('input.searchCol').val();
                    window.location.href = "/"+linkAction+"?q="+searchValue+"&col="+searchCol;
                }
            })


        }else{

            $(v).click(function(){
                if(!$(this).is(':last-child'))
                {
                    var rowId = $(this).closest('td').nextAll(':has(input.rowId):first').find('input.rowId').val();
                    if(linkAction=='inline'){
                        actionId = $(this).closest('td').nextAll(':has(input.actionId):first').find('input.actionId').val();
                        if(actionId=='warning_restore'){
                            /* Alert the copied text */
                            bootbox.alert("This item has been deleted - please restore before continuing");
                        }else{
                            window.location.href = "/"+linkModel+"/"+rowId+"/"+actionId;
                        }



                     }else{
                        window.location.href = "/"+linkModel+"/"+rowId+"/"+linkAction;
                    }

                }
            })

        }


    });


    $('table#dataTableBuilder tr').each(function (k,v) {
        $(v).attr('data-test','table-row-'+k)
    });
    $('table#dataTableBuilder td').each(function (k,v) {
        $(v).attr('data-test','table-cell-'+k)
    });
    $('table#dataTableBuilder th').each(function (k,v) {
        $(v).attr('data-test','table-heading-'+k)
    });

    $('table#dataTableBuilder a.show-button').each(function (k,v) {
        $(v).attr('data-test','show-button-'+k)
    });

    $('table#dataTableBuilder a.edit-button').each(function (k,v) {
        $(v).attr('data-test','edit-button-'+k)
    });

    $('table#dataTableBuilder a.delete-button').each(function (k,v) {
        $(v).attr('data-test','delete-button-'+k)
    });

    $('table#dataTableBuilder a.by-coin-button').each(function (k,v) {
        $(v).attr('data-test','by-coin-button-'+k)
    });

    $('div#dataTableBuilder_filter input[type=search]').attr('data-test','search-table')


}



function addFooterSearch(settings, json, dateFields, exactSearchFields, noSearchFields){


        $('#dataTableBuilder tfoot tr th').each( function (i) {

            if(!noSearchFields.includes(i)){

                var title = $(this).text();

                if(dateFields && dateFields.includes(i)){
                    $(this).html( '<input type="input" format="" class="col-xs-12 date-search" data-test="footer-search-'+i+'" id="footer-search-'+i+'" />' );
                }else{
                    $(this).html( '<input type="input" class="col-xs-12 normal-search" data-test="footer-search-'+i+'" id="footer-search-'+i+'" />' );
                }
                var table = $('#dataTableBuilder').DataTable();

                $( 'input.normal-search', this ).on( 'change keyup', function () {
                    if ( table.column(i).search() !== this.value ) {


                        if(exactSearchFields && exactSearchFields.includes(i) && isNaN(this.value)){
                            table
                                .column(i)
                                .search( this.value ? '^'+this.value+'$' : '', true, false )
                                .draw();
                        }else if(exactSearchFields && exactSearchFields.includes(i) && !isNaN(this.value)){
                            table
                                .column(i)
                                .search( this.value.toString()? '^'+this.value.toString()+'$' : '', true, false )
                                .draw();
                        }else{
                            table
                                .column(i)
                                .search( this.value )
                                .draw();
                        }
                    }
                });

                $( 'input.date-search').on( 'blur', function () {
                    if ( table.column(i).search() !== this.value ) {
                        table
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                });
            }else{
                $(this).html( '' );
            }


            $(".date-search").datetimepicker({
                format: 'YYYY-MM-DD',
                useCurrent: true,
                sideBySide: true
            })

        } );

}

function runAutoSearch(settings, json){

    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });

    var searchTerm = decodeURIComponent(vars.q);
    var searchCol = decodeURIComponent(vars.col);


    if(searchCol!='' && searchCol!='undefined'){
        columnAutoSearch(searchTerm, searchCol);
    }

    if(searchTerm!='' && searchTerm!='undefined'){
        generalAutoSearch(searchTerm);

    }

}

function columnAutoSearch(searchTerm,searchCol){


    var table = $('#dataTableBuilder').DataTable();
    setTimeout( function () {
        table
            .column(searchCol)
            .search( searchTerm )
            .draw();
        table.ajax.reload();
        $('#footer-search-'+searchCol).val(searchTerm);
        $('div#dataTableBuilder_filter input[type=search]').val('')
    });

}

function generalAutoSearch(searchTerm){

    var table = $('#dataTableBuilder').DataTable();
    setTimeout( function () {
        table
            .search( searchTerm )
            .draw();
        table.ajax.reload();
    });

}

function is_numeric(str){
    return /^\d+$/.test(str);
}
