@extends('admin.layouts.app')
@section('title')
    {{ __('Users social meta') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Users social meta') }}</li>
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
                    <h1 class="custom-font">{{ __('Users social meta') }}</h1>
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
                        <table class="table table-custom" id="meta-table">
                            <thead>
                            <tr>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Key') }}</th>
                                <th>{{ __('Value') }}</th>
                                <th>{{ __('Created at') }}</th>
                                <th>{{ __('Updated at') }}</th>

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
        var table = $('#meta-table').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [[3, "desc"]],
            "ajax": '{{route('admin.social_meta.dtdata')}}',
            "columns": [
                {
                    "data": 'user.login', "orderable": true, "searchable": true, "render": function (data, type, row, meta) {
                        return '<a href="/admin/users/' + row.user.id + '" target="_blank">'+ row.user.login +'</a>';
                    }
                },
                {"data": "s_key", "name":"s_key"},
                {"data": "s_value", "name":"s_value"},
                {"data": "created_at", "name":"created_at"},
                {"data": "updated_at", "name":"updated_at"},
            ],
            "dom": 'Rlfrtip',
            initComplete: function () {
                this.api().columns([0, 1, 2, 3, 4]).every(function () {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                });
            }
        });

        $('#meta-table tbody').on('click', 'tr', function () {
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