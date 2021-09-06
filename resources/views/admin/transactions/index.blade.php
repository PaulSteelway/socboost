@extends('admin.layouts.app')
@section('title')
    {{ __('Transactions') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Transactions') }}</li>
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
                    <h1 class="custom-font">{{ __('Transactions') }}</h1>
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
                        <table class="table table-custom" id="transactions-table">
                            <thead>
                            <tr>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Currency') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Approved') }}</th>
                                <th>{{ __('Created') }}</th>
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
        var table = $('#transactions-table').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [[5, "desc"]],
            "ajax": '{{route('admin.transactions.dtdata')}}',
            "columns": [
                {"data": "user.login"},
                {"data": "type_name", "orderable": false, "searchable": false},
                {"data": "currency.code"},
                {"data": "amount"},
                {
                    "data": "approved", "render": function (data, type, row, meta) {
                        if (row['approved'] == 1) {
                            return '{{ __('yes') }}';
                        }
                        return '{{ __('no') }}';
                    }
                },
                {"data": "created_at"},
                {
                    "data": 'action',
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row, meta) {
                        return '<a href="/admin/transactions/' + row['id'] + '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i> {{ __('show')}}</a>';
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

        $('#transactions-table tbody').on('click', 'tr', function () {
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