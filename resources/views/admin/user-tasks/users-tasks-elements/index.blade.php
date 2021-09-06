@extends('admin/layouts.app')
@section('title')
    {{ __('User task elements list') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('User task elements list') }}</li>
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
                    <h1 class="custom-font">{{ __('User task elements list') }}</h1>
                    <ul class="controls">
                        {{--<li>--}}
                        {{--<a role="button" href="#">--}}
                        {{--[<strong>{{ __('link') }}</strong>]--}}
                        {{--</a>--}}
                        {{--</li>--}}
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

                    <table id="events" class="table hover form-inline dt-bootstrap no-footer">
                        <thead>
                        <tr>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Task action ID') }}</th>
                            <th>{{ __('Last checked time') }}</th>
                            <th>{{ __('Finished') }}</th>
                            <th>{{ __('Created at') }}</th>
                        </tr>
                        </thead>
                        <tfoot>
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
        var table = $('#events').width('100%').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": '{{ route('admin.user-tasks.user_task_elements.datatable') }}',
            "columns": [
                {
                    "data": 'task', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                        return '<a href="/admin/users/' + row['user_id'] + '" class="btn btn-xs btn-default" target="_blank"><i class="glyphicon glyphicon-eye"></i> {{ __('open user details') }}</a>';
                    }
                },
                {"data": "task_action_id", "name": "user_task_actions.task_action_id"},
                {"data": "last_check_datetime", "name": "user_task_actions.last_check_datetime"},
                {
                    "data": 'finished', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                        if (row['finished'] == 1) {
                            return "{{ __('yes') }}";
                        } else {
                            return "{{ __('no') }}";
                        }
                    }
                },
                {"data": "created_at", "name": "user_task_actions.created_at"},
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

        $('#events tbody').on('click', 'tr', function () {
            if ($(this).hasClass('row_selected')) {
                $(this).removeClass('row_selected');
            } else {
                table.$('tr.row_selected').removeClass('row_selected');
                $(this).addClass('row_selected');
            }
        });
        //*initialize basic datatable
    </script>
@endpush