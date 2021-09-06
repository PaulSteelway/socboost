@extends('admin.layouts.app')
@section('title')
    {{ __('Withdrawal requests') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Withdrawal requests') }}</li>
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
                    <h1 class="custom-font">{{ __('Withdrawal requests') }}</h1>
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
                        <form method="POST"
                              action="{{ route('admin.requests.approve-many') }}">
                            {{ csrf_field() }}
                            <table class="table table-custom" id="wrs-table">
                                <thead>
                                <tr>
                                    <th>{{ __('Currency') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Payment system') }}</th>
                                    <th>{{ __('Wallet') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Action') }}</th>

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
                                    <td class="tdinput">
                                        <b>{{ __('Selected requests') }}:</b>
                                        <button id="singlebutton" name="approve" value="true"
                                                class="btn btn-xs btn-primary sure">{{ __('Approve') }}</button>
                                        <button id="singlebutton" name="approveManually" value="true"
                                                class="btn btn-xs btn-default sure">{{ __('Approve manually') }}</button>
                                        <button id="singlebutton" name="reject" value="true"
                                                class="btn btn-xs btn-warning sure">{{ __('reject') }}</button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </form>
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
    var table = $('#wrs-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": '{{route('admin.requests.dtdata')}}',
        "columns": [
            {"data": "currency.code", "name": "currency.code"},
            {
                "data": "amount", "name": "withdrawal_requests.amount", "render": function (data, type, row, meta) {
                    return '<strong>'+ row['amount'] +'</strong>';
                }
            },
            {"data": "payment_system.name", "name": "paymentSystem.name"},
            {"data": "wallet.external", "name": "wallet.external"},
            {"data": "status", "name": "status"},
            {"data": "user.name", "name": "user.name"},
            {
                "data": 'action', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                    return '<input type="checkbox" name="list[]" value="' + row['id'] + '"> &nbsp; <a href="/admin/requests/' + row['id'] + '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i> {{ __('show') }}</a> &nbsp; <a href="/admin/requests/approve/' + row['id'] + '" class="btn btn-xs btn-success sure"><i class="glyphicon glyphicon-check"></i> {{ __('Approve') }}</a> &nbsp; <a href="/admin/requests/approveManually/'+ row['id'] +'" class="btn btn-xs btn-default sure"><i class="glyphicon glyphicon-check"></i> {{ __('Approve manually') }}</a> &nbsp; <a href="/admin/requests/reject/' + row['id'] + '" class="btn btn-xs btn-warning sure"><i class="glyphicon glyphicon-check"></i> {{ __('reject') }}</a>';
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

    $('#wrs-table tbody').on('click', 'tr', function () {
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