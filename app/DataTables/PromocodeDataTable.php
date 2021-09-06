<?php

namespace App\DataTables;

use App\Models\Promocode;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class PromocodeDataTable extends DataTable
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

        return $dataTable
            ->addColumn('action', 'admin.promocodes.datatables_actions')
            ->addColumn('from_amount',function ($row){
                return $row->data['apply_from'];
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Promocode $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Promocode $model)
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
                'dom'     => 'Bfrtip',
                'searching' => true,
                'order'   => [[0, 'desc']],
                'buttons' => [
                    ['extend' => 'create', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'export', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'print', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'reset', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'reload', 'className' => 'btn btn-default btn-sm no-corner',],
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
                'data' => 'code',
                'title' => __('Code')
            ],
            [
                'data' => 'reward',
                'title' => __('Reward')
            ],
            [
                'data' => 'from_amount',
                'title' => __('Apply from amount')
            ],
            [
                'data' => 'is_disposable',
                'title' => __('Quantity')
            ],
            [
                'data' => 'expires_at',
                'title' => __('Expires at')
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
        return 'promocodesdatatable_' . time();
    }
}
