@extends('admin/layouts.app')
@section('title'){{ __('Users list') }}@endsection
@section('breadcrumbs')
    <li> {{ __('Users list') }}</li>
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
                    <h1 class="custom-font">{{ __('Users list') }}</h1>
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
                        <table class="table table-striped table-bordered hover" id="users-table">
                            <thead>
                            <tr>
                                <th>{{ __('Phone') }} <span class="label label-success">{{$countVerifiedPhone}}</span></th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Registration') }}</th>
                                <th>{{ __('Verified') }} <span class="label label-success">{{$countVerified}}</span></th>
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
        var table = $('#users-table').DataTable({
            "iDisplayLength": 25,
            "processing": true,
            "serverSide": true,
            "order": [[2, "desc"]],
            "ajax": '{{ route('admin.users.dtdata') }}',
            "columns": [
                {
                    "data": "phone", "render": function (data, type, row, meta) {
                        return '<a href="' + row['show'] + '" target="_blank">' + data + '</a>';
                    }
                },
                {
                    "data": "email", "render": function (data, type, row, meta) {
                        return '<a href="mailto:' + data + '" target="_blank">' + data + '</a>';
                    }
                },
                {"data": "created_at"},
                {"data": "email_verified_at"},
                {
                    "data": 'action',
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row, meta) {
                        return '<a href="' + row['show'] + '" target="_blank" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-eye-open"></i> {{ __('show') }}</a> <a href="' + row['edit'] + '" target="_blank" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> {{ __('edit') }}</a>';
                    }
                }
            ],
            "aoColumnDefs": [
                {
                    'bSortable': false,
                    'aTargets': ["no-sort"],
                    'render': function (data, type, row) {
                        return data + ' (' + row[1] + ')';
                    },
                }],
            "aoColumnDefs": [
                {'bSortable': false, 'aTargets': ["no-sort"],}
            ],
            "dom": 'Rlfrtip',
            initComplete: function () {
                this.api().columns([0, 1, 2, 3]).every(function () {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).appendTo($(column.footer()).empty()).attr('placeholder', '{{ __('search ...') }}')
                        .on('change', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                });
            }
        });

        $('#users-table tbody').on('click', 'tr', function () {
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
