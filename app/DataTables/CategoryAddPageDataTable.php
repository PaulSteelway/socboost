<?php

namespace App\DataTables;

use App\Models\CategoryAddPage;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class CategoryAddPageDataTable extends DataTable
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

        $dataTable->addColumn('category', function ($row) {
            return '<a href="/admin/categories/' . $row->category_id . '/edit" target="_blank">' . implode(' / ', [$row->root, $row->category]) . '</a>';
        });

        $dataTable->addColumn('action', 'admin.category_add_pages.datatables_actions');

        $dataTable->rawColumns(['category', 'action']);

        return $dataTable->filter(function ($query) {
            if (request()->search['value']) {
                $query->where(function ($q) {
                    $q->where('category_add_pages.id', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('c1.name_en', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('c2.name_en', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('category_add_pages.title', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('category_add_pages.title_ru', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('category_add_pages.meta_title', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('category_add_pages.meta_title_ru', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('category_add_pages.meta_keywords', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('category_add_pages.meta_keywords_ru', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('category_add_pages.meta_description', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('category_add_pages.meta_description_ru', 'like', "%" . request()->search['value'] . "%");
                });
            }
        });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CategoryAddPage $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CategoryAddPage $model)
    {
        return $model->newQuery()->select(['category_add_pages.*', 'c1.name_en AS category', 'c2.name_en AS root'])
            ->join('categories AS c1', 'c1.id', '=', 'category_add_pages.category_id')
            ->leftJoin('categories AS c2', 'c2.id', '=', 'c1.parent_id');
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
            ->addAction(['width' => '50px', 'printable' => false])
            ->parameters([
                'dom' => 'lftrip',
                'stateSave' => true,
                'order' => [[0, 'desc']],
                'buttons' => [
//                    ['extend' => 'create', 'className' => 'btn btn-default btn-sm no-corner',],
//                    ['extend' => 'export', 'className' => 'btn btn-default btn-sm no-corner',],
//                    ['extend' => 'print', 'className' => 'btn btn-default btn-sm no-corner',],
//                    ['extend' => 'reset', 'className' => 'btn btn-default btn-sm no-corner',],
//                    ['extend' => 'reload', 'className' => 'btn btn-default btn-sm no-corner',],
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
            [
                'data' => 'id',
                'title' => __('ID'),
                'visible' => true
            ],
            [
                'data' => 'category',
                'title' => __('Category')
            ],
            [
                'data' => 'title',
                'title' => __('Title En'),
                'orderable' => false,
            ],
            [
                'data' => 'title_ru',
                'title' => __('Title Ru'),
                'orderable' => false,
            ],
            [
                'data' => 'meta_title',
                'title' => __('Meta Title En'),
                'orderable' => false,
            ],
            [
                'data' => 'meta_title_ru',
                'title' => __('Meta Title Ru'),
                'orderable' => false,
            ],
            [
                'data' => 'meta_keywords',
                'title' => __('Meta Keywords En'),
                'orderable' => false,
            ],
            [
                'data' => 'meta_keywords_ru',
                'title' => __('Meta Keywords Ru'),
                'orderable' => false,
            ],
            [
                'data' => 'meta_description',
                'title' => __('Meta Description En'),
                'orderable' => false,
            ],
            [
                'data' => 'meta_description_ru',
                'title' => __('Meta Description Ru'),
                'orderable' => false,
            ],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'category_add_pages_datatable_' . time();
    }
}
