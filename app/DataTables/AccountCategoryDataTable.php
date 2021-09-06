<?php

namespace App\DataTables;

use App\Models\AccountCategory;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class AccountCategoryDataTable extends DataTable
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
        $dataTable->editColumn('icon_img', function ($data) {
            return '<img src="/' . $data['icon_img'] .'"  width="24"/>';
        });
        $dataTable->rawColumns(['action', 'icon_img']);
        return $dataTable->addColumn('action', 'admin.account_categories.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AccountCategory $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(AccountCategory $model)
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
                'buttons'   => [],
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
                'data' => 'name_ru',
                'title' => 'Name',
            ],
            [
                'data' => 'icon_img',
                'title' => 'Icon',
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
        return 'account_categoriesdatatable_' . time();
    }
}
