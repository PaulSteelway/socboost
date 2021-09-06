@extends('admin.layouts.app')
@section('title')
    {{ __('Deposits') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Deposits') }}</li>
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <!-- col -->
        <div class="col-md-12">

            <!-- tile -->
            <section class="tile">

                <!-- tile header -->
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font">{{ __('Deposits') }}</h1>
                    <ul class="controls">
                        <li>
                            <a role="button" class="tile-fullscreen">
                                <i class="fa fa-expand"></i> {{ __('Fullscreen') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /tile header -->

                <!-- tile body -->
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-custom" id="deposits-table">
                            <thead>
                            <tr>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Currency') }}</th>
                                <th>{{ __('Invested') }}</th>
                                <th>{{ __('Rate') }}</th>
                                <th>{{ __('Opened') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <style>
                                td.tdinput input {
                                    width: 100%;
                                }
                            </style>
                            <tr>
                                <td class="tdinput"></td>
                                <td class="tdinput"></td>
                                <td class="tdinput"></td>
                                <td class="tdinput"></td>
                                <td class="tdinput"></td>
                                <td class="tdinput"></td>
                                <td class="tdinput"></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /tile body -->

            </section>
            <!-- /tile -->

        </div>
        <!-- /col -->
    </div>
    <!-- /row -->

@endsection

@push('load-scripts')
    <script>
    //initialize basic datatable
    var table = $('#deposits-table').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [[5, "desc"]],
        "ajax": '{{route('admin.deposits.dtdata')}}',
        "columns": [
            {"data": "user.name"},
            {"data": "condition"},
            {"data": "currency.code"},
            {"data": "invested"},
            {"data": "rate.name"},
            {"data": "created_at"},
            {
                "data": 'action', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                    return '<a href="/admin/deposits/' + row['id'] + '" class="btn btn-xs btn-primary" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> {{ __('show') }}</a>';
                }
            }
        ],
        "aoColumnDefs": [
            {'bSortable': false, 'aTargets': ["no-sort"]}
        ],
        "dom": 'Rlfrtip',
        initComplete: function () {
            this.api().columns([0, 1, 2, 3, 4, 5]).every(function () {
                var column = this;
                var input = document.createElement("input");
                $(input).appendTo($(column.footer()).empty())
                    .on('change', function () {
                        column.search($(this).val(), false, false, true).draw();
                    });
            });
        }
    });

    $('#deposits-table tbody').on( 'click', 'tr', function () {
        if ($(this).hasClass('row_selected')) {
            $(this).removeClass('row_selected');
        }
        else {
            table.$('tr.row_selected').removeClass('row_selected');
            $(this).addClass('row_selected');
        }
    });
    //*initialize basic datatable
    </script>
@endpush
