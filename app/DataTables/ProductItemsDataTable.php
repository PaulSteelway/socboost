<?php

namespace App\DataTables;

use App\Models\ProductItem;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class ProductItemsDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'admin.product_items.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ProductItem $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ProductItem $model)
    {
        $query = $model->newQuery()->select(['product_items.*', 'p.name_ru AS product_name'])
            ->leftJoin('products as p', 'p.id', '=', 'product_items.product_id');
        return $query;
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
                'buttons'   => [

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
            'username',
            'password',
            'product_name',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'product_itemsdatatable_' . time();
    }
}
