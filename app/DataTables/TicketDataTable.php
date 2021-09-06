<?php

namespace App\DataTables;

use App\Models\Ticket;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class TicketDataTable extends DataTable
{
    private $statusTypeColor = [
        1 => 'danger',
        2 => 'primary',
        3 => 'success'
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

        $dataTable->addColumn('subjectName', function ($row) {
            return config('enumerations')['tickets']['subjects'][$row->subject];
        });

        $dataTable->addColumn('statusName', function ($row) {
            $typeColor = empty($this->statusTypeColor[$row->status]) ? 'default' : $this->statusTypeColor[$row->status];
            return '<span class="label label-' . $typeColor . '">' . config('enumerations')['tickets']['statuses'][$row->status] . '</span>';
        });

        $dataTable->addColumn('action', 'admin.tickets.datatables_actions');

        $dataTable->rawColumns(['user', 'statusName', 'action']);

        return $dataTable->filter(function ($query) {
            if (request()->search['value']) {
                $query->where(function ($q) {
                    $q->where('tickets.id', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('users.email', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('tickets.updated_at', 'like', "%" . request()->search['value'] . "%");
                    foreach (config('enumerations')['tickets']['subjects'] as $key => $subject) {
                        $q->orWhereRaw("tickets.subject = CASE WHEN '{$subject}' LIKE '%" . request()->search['value'] . "%' THEN {$key} END");
                    }
                    foreach (config('enumerations')['tickets']['statuses'] as $key => $status) {
                        $q->orWhereRaw("tickets.status = CASE WHEN '{$status}' LIKE '%" . request()->search['value'] . "%' THEN {$key} END");
                    }
                });
            }
        })->order(function ($query) {
            if (request()->has('order')) {
                switch (request('order')[0]['column']) {
                    case '0':
                        $query->orderBy('tickets.id', request('order')[0]['dir']);
                        break;
                    case '1':
                        $query->orderBy('users.email', request('order')[0]['dir']);
                        break;
                    case '2':
                        $query->orderBy('tickets.subject', request('order')[0]['dir']);
                        break;
                    case '3':
                        $query->orderBy('tickets.status', request('order')[0]['dir']);
                        break;
                    case '4':
                        $query->orderBy('tickets.updated_at', request('order')[0]['dir']);
                        break;
                }
            }
        });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Ticket $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Ticket $model)
    {
        return $model->newQuery()->select(['tickets.*', 'users.email'])
            ->join('users', 'users.id', '=', 'tickets.user_id');
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
                'title' => __('ID')
            ],
            [
                'data' => 'user',
                'title' => __('User')
            ],
            [
                'data' => 'subjectName',
                'title' => __('Subject')
            ],
            [
                'data' => 'statusName',
                'title' => __('Status')
            ],
            [
                'data' => 'updated_at',
                'title' => __('Last update')
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
        return 'ticketsdatatable_' . time();
    }
}
