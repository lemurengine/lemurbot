<?php

namespace LemurEngine\LemurBot\DataTables;

use LemurEngine\LemurBot\Models\Wildcard;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class WildcardDataTable extends DataTable
{

    //to help with data testing and form settings
    public string $link;
    public string $htmlTag;
    public string $title;
    public string $resourceFolder;

    /**
     * receive the value from the controller to parameterize the display of the table
     * @param $array
     */
    public function setDrawParams($array)
    {

        $this->link = $array['link'];
        $this->htmlTag = $array['htmlTag'];
        $this->title = $array['title'];
        $this->resourceFolder = $array['resourceFolder'];
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable->addColumn('action', function ($row) {

            if (isset($row['slug'])) {
                $id = $row['slug'];
            } else {
                $id = $row['id'];
            }

            return view(
                $this->resourceFolder.'.datatables_actions',
                ['id'=>$id, 'title'=>$this->title, 'htmlTag'=>$this->htmlTag,
                'link'=>$this->link,
                'bot'=>$row['bot'],
                'conversation'=>$row['conversation']]
            );
        });
    }

    /**
     * Get query source of dataTable.
     *
     * @param Wildcard $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Wildcard $model)
    {
        return $model->dataTableQuery();
    }


    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {

        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
             ->addAction(['width' => '120px', 'printable' => false,'searchable'=>false, 'exportable'=>false])
            ->parameters([
                'drawCallback' => 'function(settings, json) {
                    addRowFeatures(settings, json, "'.$this->link.'","edit")
                }',
                'initComplete' => 'function(settings, json) {

                    var dateFields = [3]
                    var exactSearchFields = []
                    var noSearchFields = [4]

                    runAutoSearch(settings, json)
                    addFooterSearch(settings, json, dateFields, exactSearchFields, noSearchFields)

                }',
                'dom'       => 'Bfrtip',
                 'pageLength' => 25,
                'stateSave' => true,
                'order'     => [[0, 'desc']],
                'buttons'   => [
                    ['extend' => 'export', 'className' => 'btn btn-default btn-sm no-corner export-items',],
                    ['extend' => 'print', 'className' => 'btn btn-default btn-sm no-corner print-items',],
                    ['extend' => 'reset', 'className' => 'btn btn-default btn-sm no-corner reset-table',],
                    ['extend' => 'reload', 'className' => 'btn btn-default btn-sm no-corner reload-table',],
                ],
            ]);
    }


    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'conversation'=> ['name'=>'conversations.slug','data'=>'conversation','title'=>'ConversationId',
                'searchable'=>true, 'printable'=>true, 'exportable'=>true,'defaultContent'=>'', 'render' =>
                function () {
                    return 'function(data, type, full, meta)
                {
                    return getFormattedItem(data, \'long_id\'); //
                 }
                 ';
                }],
            'type',
            'value',
            'created_at'=> ['name'=>'created_at','data'=>'created_at', 'title'=>'Created',
                'defaultContent'=>'', 'render' =>
                function () {
                    return 'function(data, type, full, meta)
                {
                    return moment(data).format("lll"); // "02 Nov 16 12:00AM"
                 }
                 ';
                }],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'wildcards_datatable_' . time();
    }
}
