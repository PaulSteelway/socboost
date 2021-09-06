<?php

namespace App\DataTables;

use App\Models\BlogEntry;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class BlogEntryDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable->addColumn('action', 'admin.blog_entries.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\BlogEntry $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(BlogEntry $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '120px', 'printable' => false])
            ->parameters([
                'dom'       => 'Bfrtip',
                'stateSave' => true,
                'order'     => [[0, 'desc']],
                'buttons'   => [ ],
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
//            'blog',
//            'publish_after',
//            'slug',
            [
                'title' => 'Title',
                'data' => 'title_ru'
            ],
//            'author_email',
//            'author_url',
//            'image',
//            'content',
//            'summary',
//            'page_title',
//            'description',
//            'meta_tags',
//            'class',
//            'display_full_content_in_feed'
        ];
    }

}
