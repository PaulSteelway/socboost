<?php

namespace App\DataTables;

use App\Models\Voucher;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class VoucherDataTable extends DataTable
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

        $dataTable->addColumn('user', function ($row) {
            return empty($row->user_id) ? null : '<a href="' . route('admin.users.show', $row->user_id) . '" target="_blank">' . $row->email . '</a>';
        });

        $dataTable->addColumn('action', 'admin.vouchers.datatables_actions');

        $dataTable->rawColumns(['user', 'action']);

        return $dataTable->filter(function ($query) {
            if (request()->search['value']) {
                $query->where(function ($q) {
                    $q->where('vouchers.code', 'like', "%" . request()->search['value'] . "%")
                        ->orWhereRaw("CONCAT(vouchers.amount, ' ', currencies.symbol) like ?", ["%" . request()->search['value'] . "%"])
                        ->orWhere('users.email', 'like', "%" . request()->search['value'] . "%");
                });
            }
        })
            ->order(function ($query) {
                if (request()->has('order')) {
                    switch (request('order')[0]['column']) {
                        case '1':
                            $query->orderBy('vouchers.code', request('order')[0]['dir']);
                            break;
                        case '2':
                            $query->orderBy('vouchers.amount', request('order')[0]['dir']);
                            break;
                        case '3':
                            $query->orderBy('users.email', request('order')[0]['dir']);
                            break;
                    }
                }
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Voucher $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Voucher $model)
    {
        return $model->newQuery()->selectRaw("vouchers.*, CONCAT(vouchers.amount, ' ', currencies.symbol) AS vAmount, users.email")
            ->join('currencies', 'currencies.id', '=', 'vouchers.currency_id')
            ->leftJoin('users', 'users.id', '=', 'vouchers.user_id');
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
                'dom' => 'lfrtip',
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
                'visible' => false
            ],
            [
                'data' => 'code',
                'title' => __('Code')
            ],
            [
                'data' => 'vAmount',
                'title' => __('Amount')
            ],
            [
                'data' => 'user',
                'title' => __('User')
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
        return 'vouchersdatatable_' . time();
    }
}
