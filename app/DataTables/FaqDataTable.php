<?php

namespace App\DataTables;

use App\Models\Faq;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class FaqDataTable extends DataTable
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

        $dataTable->addColumn('action', 'admin.faqs.datatables_actions');

        $dataTable->rawColumns(['action']);

        return $dataTable->filter(function ($query) {
            if (request()->search['value']) {
                $query->where(function ($q) {
                    $q->where('fq.name_en', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('faqs.question_ru', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('faqs.question_en', 'like', "%" . request()->search['value'] . "%");
                });
            }
        });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Faq $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Faq $model)
    {
        return $model->newQuery()->select(['faqs.*', 'fq.name_en AS category'])
            ->join('faq_categories AS fq', 'fq.id', '=', 'faqs.category_id');
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
            ->addAction(['width' => '45px', 'printable' => false])
            ->parameters([
                'dom' => 'lfrtip',
                'stateSave' => true,
                'order' => [[1, 'asc']],
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
                'visible' => false
            ],
            [
                'data' => 'category',
                'title' => __('Category')
            ],
            [
                'data' => 'order',
                'title' => __('Order'),
                'orderable' => false
            ],
            [
                'data' => 'question_ru',
                'title' => __('Question (RU)'),
                'orderable' => false
            ],
            [
                'data' => 'question_en',
                'title' => __('Question (EN)'),
                'orderable' => false
            ]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'faqsdatatable_' . time();
    }
}
