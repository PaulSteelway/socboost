@extends('admin/layouts.app')
@section('title')
    {{ __('Telegram events') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Telegram events') }}</li>
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
                    <h1 class="custom-font">{{ __('Telegram events') }}</h1>
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
                            <th>{{ __('From username') }}</th>
                            <th>{{ __('Lang code') }}</th>
                            <th>{{ __('Text') }}</th>
                            <th>{{ __('Created at') }}</th>
                            <th>{{ __('Bot ID') }}</th>
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
            "ajax": '{{ route('admin.telegram.events.datatable') }}',
            "columns": [
                {"data": "from_username", "name": "telegram_bot_events.from_username"},
                {"data": "from_language_code", "name": "telegram_bot_events.from_language_code"},
                {
                    "data": 'text', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                        return '<textarea class="form-control" readonly>'+ row['text'] +'</textarea>';
                    }
                },
                {"data": "created_at", "name": "telegram_bot_events.created_at"},
                {
                    "data": 'action', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                        return '<a href="/admin/telegram/bots/' + row['bot_id'] + '/edit" class="btn btn-xs btn-primary" target="_blank"><i class="glyphicon glyphicon-edit"></i> {{ __('bot details') }}</a>';
                    }
                }
            ],
            "dom": 'Rlfrtip',
            initComplete: function () {
                this.api().columns([0, 1, 3]).every(function () {
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