<?php

namespace LemurEngine\LemurBot\DataTables;

use LemurEngine\LemurBot\Models\Bot;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class BotDataTable extends DataTable
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
                ['id'=>$id,
                    'title'=>$this->title,
                    'htmlTag'=>$this->htmlTag,
                    'link'=>$this->link,
                    'deletedAt'=>$row['deleted_at']]
            );
        });
    }

    /**
     * Get query source of dataTable.
     *
     * @param Bot $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Bot $model)
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
                    addRowFeatures(settings, json, "'.$this->link.'","inline")
                }',
                'initComplete' => 'function(settings, json) {

                    var maxColumn = 9
                    var dateFields =[maxColumn-1]
                    var exactSearchFields = [0]
                    var noSearchFields = [maxColumn]

                    runAutoSearch(settings, json)
                    addFooterSearch(settings, json, dateFields ,exactSearchFields,noSearchFields)
                }',
                'dom'       => 'Bfrtip',
                'pageLength' => 25,
                'stateSave' => true,
                'order'     => [[0, 'desc']],
                'buttons'   => [
                    ['extend' => 'create', 'className' => 'btn btn-default btn-sm no-corner create-item',],
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
            'id'=> ['name'=>'bots.id','data'=>'id','title'=>'Id'],
            'slug'=> ['name'=>'bots.slug','data'=>'slug','title'=>'slug'],
            'language'=> ['name'=>'languages.name','data'=>'language','title'=>'Lang'],
            'name',
            'summary' => ['name'=>'summary','data'=>'summary','title'=>'Summary','searchable'=>true,
                'printable'=>true, 'exportable'=>true,'defaultContent'=>''],
            'status',
            'is_public' => ['name'=>'is_public','data'=>'is_public','title'=>'Public?','searchable'=>true,
                'printable'=>true, 'exportable'=>true,'defaultContent'=>'null', 'render' =>
                function () {
                    return 'function(data, type, full, meta)
                {
                    return getFormattedItem(data, \'is_public\'); //
                 }
                 ';
                }],
            'email'=> ['name'=>'users.email','data'=>'email','title'=>'Owner', 'exportable'=>false],
            'updated_at'=> ['name'=>'updated_at','data'=>'updated_at', 'title'=>'Updated',
                'defaultContent'=>'', 'render' =>
                function () {
                    return 'function(data, type, full, meta)
                {
                    return moment(data).format("lll"); // "02 Nov 16 12:00AM"
                 }
                 ';
                }],
            'created_at'=> ['name'=>'created_at','data'=>'created_at', 'title'=>'Created', 'visible'=>false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'bots_datatable_' . time();
    }
}
