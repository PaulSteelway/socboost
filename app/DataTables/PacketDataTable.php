<?php

namespace App\DataTables;

use App\Models\Packet;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class PacketDataTable extends DataTable
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

        $dataTable->addColumn('checkbox', function ($row) {
            return '<input type="checkbox" class="pCheckBox" name="selected_packets[]" value="' . $row->id . '">';
        });

        $dataTable->addColumn('category', function ($row) {
            return implode(' / ', [$row->parent, $row->category]);
        });

        $dataTable->addColumn('name', function ($row) {
            return session('lang') == 'en' ? $row->name_en : $row->name_ru;
        });

        $dataTable->addColumn('action', 'admin.packets.datatables_actions');

        $dataTable->rawColumns(['checkbox', 'action']);

        return $dataTable->filter(function ($query) {
            if (request()->search['value']) {
                $query->where(function ($q) {
                    $q->where('packets.id', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('c1.name_en', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('c2.name_en', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('packets.service_id', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('packets.name_en', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('packets.name_ru', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('packets.min', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('packets.max', 'like', "%" . request()->search['value'] . "%")
                        ->orWhere('packets.price', 'like', "%" . request()->search['value'] . "%");
                });
            }
        });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Packet $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Packet $model)
    {
        $query = $model->newQuery()->select(['packets.*', 'c1.name_en AS category', 'c2.name_en AS parent'])
            ->join('categories AS c1', 'c1.id', '=', 'packets.category_id')
            ->leftJoin('categories AS c2', 'c2.id', '=', 'c1.parent_id');
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
            ->setTableId('packetsDataTable')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '40px', 'printable' => false])
            ->drawCallback('function() { redrawPacketsCheckboxes() }')
            ->parameters([
                'dom' => "lftrip",
                'searching' => true,
                'order' => [[1, 'desc']],
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
                'title' => '<input type="checkbox" id="packetsSelectAll" />',
                'data' => 'checkbox',
                'orderable' => false,
                'searchable' => false,
                'width' => '10px',
            ],
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
                'data' => 'service_id',
                'title' => __('Service ID')
            ],
            [
                'data' => 'name',
                'title' => __('Name')
            ],
            [
                'data' => 'min',
                'title' => __('Min Quantity')
            ],
            [
                'data' => 'max',
                'title' => __('Max Quantity')
            ],
            [
                'data' => 'price',
                'title' => __('Price')
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
        return 'packetsdatatable_' . time();
    }
}
