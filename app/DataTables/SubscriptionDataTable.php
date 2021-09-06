<?php

namespace App\DataTables;

use App\Models\Subscription;
use Carbon\Carbon;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class SubscriptionDataTable extends DataTable
{
    private $statusTypeColor = [
        'new' => 'primary',
        'active' => 'success',
        'close' => 'default',
        'error' => 'danger'
    ];

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

        $dataTable->addColumn('expiry', function ($row) {
            return empty($row->date_at) ? '-' : (new Carbon($row->date_at))->format('Y-m-d');
        });

        $dataTable->addColumn('statusName', function ($row) {
            $typeColor = empty($this->statusTypeColor[$row->status]) ? 'default' : $this->statusTypeColor[$row->status];
            return '<span class="label label-' . $typeColor . '" style="font-size: 14px; font-weight: 600;">' . $row->status . '</span>';
        });

        $dataTable->addColumn('action', 'admin.subscriptions.datatables_actions');

        $dataTable->rawColumns(['user', 'expiry', 'statusName', 'action']);

        return $dataTable->filter(function ($query) {
            if (request()->search['value']) {
                $query->where(function ($q) {
                    $q->where('subscriptions.subscription_id', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('users.email', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('subscriptions.type', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('subscriptions.period', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('subscriptions.date_at', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('subscriptions.status', 'like', "%" . request()->search['value'] . "%");
                });
            }
        })->order(function ($query) {
            if (request()->has('order')) {
                switch (request('order')[0]['column']) {
                    case '0':
                        $query->orderBy('subscriptions.subscription_id', request('order')[0]['dir']);
                        break;
                    case '1':
                        $query->orderBy('users.email', request('order')[0]['dir']);
                        break;
                    case '2':
                        $query->orderBy('subscriptions.type', request('order')[0]['dir']);
                        break;
                    case '3':
                        $query->orderBy('subscriptions.period', request('order')[0]['dir']);
                        break;
                    case '4':
                        $query->orderBy('subscriptions.date_at', request('order')[0]['dir']);
                        break;
                    case '5':
                        $query->orderBy('subscriptions.status', request('order')[0]['dir']);
                        break;
                }
            }
        });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Subscription $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Subscription $model)
    {
        return $model->newQuery()->select(['subscriptions.*', 'users.email'])
            ->join('users', 'users.id', '=', 'subscriptions.user_id');
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
            ->addAction(['width' => '40px', 'printable' => false])
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
                'data' => 'subscription_id',
                'title' => __('Subscription ID')
            ],
            [
                'data' => 'user',
                'title' => __('User')
            ],
            'type',
            'period',
            [
                'data' => 'expiry',
                'title' => __('Expiry')
            ],
            [
                'data' => 'statusName',
                'title' => __('Status')
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
        return 'subscriptionsdatatable_' . time();
    }
}
