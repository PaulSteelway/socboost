<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class UserReferralDataTable extends DataTable
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

        $dataTable->addColumn('full_link', function ($row) {
            return empty($row->link) ? null : route('referral.route', $row->link);
        });

        $dataTable->addColumn('action', 'admin.user_referrals.datatables_actions');

        $dataTable->rawColumns(['action']);

        return $dataTable->filter(function ($query) {
            if (request()->search['value']) {
                $query->where(function ($q) {
                    $q->where('user_referrals.link', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('users.email', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('u2.email', 'like', "%" . request()->search['value'] . "%");
                });
            }
        });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\UserReferral $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $referrals = $model->newQuery()
            ->select(['users.id', 'users.email AS user_name', 'user_referrals.link', 'u2.email AS referral_name'])
            ->leftJoin('user_referrals', 'user_referrals.id', '=', 'users.id')
            ->leftJoin('users AS u2', 'u2.id', '=', 'user_referrals.referral_id');

        return $this->applyScopes($referrals);
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
                'data' => 'user_name',
                'title' => __('User')
            ],
            [
                'data' => 'full_link',
                'title' => __('Referral link')
            ],
            [
                'data' => 'referral_name',
                'title' => __('Refferal')
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
        return 'user_referralsdatatable_' . time();
    }
}
