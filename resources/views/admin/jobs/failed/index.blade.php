@extends('admin.layouts.app')
@section('title')
    {{ __('Failed jobs') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Failed jobs') }}</li>
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
                    <h1 class="custom-font">{{ __('Failed jobs') }}</h1>
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
                    <table class="table table-custom" id="jobs-table">
                        <thead>
                        <tr>
                            <th>{{ __('JOB type') }}</th>
                            <th>{{ __('Connection') }}</th>
                            <th>{{ __('Queue') }}</th>
                            <th>{{ __('Exception') }}</th>
                            <th>{{ __('Failed at') }}</th>
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
        var table = $('#jobs-table').width('100%').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [[4, "desc"]],
            "ajax": '{{route('admin.failedjobs.datatable')}}',
            "columns": [
                {"data": "type"},
                {"data": "connection"},
                {"data": "queue"},
                {
                    "data": 'exception', "render": function (data, type, row, meta) {
                        return '<textarea class="form-control" readonly>' + row['exception'] + '</textarea>';
                    }
                },
                {"data": "failed_at"},
            ],
            "aoColumnDefs": [
                {'bSortable': false, 'aTargets': ["no-sort"]}
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

        $('#jobs-table tbody').on('click', 'tr', function () {
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