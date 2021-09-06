@extends('admin/layouts.app')
@section('title')
    {{ __('Webhooks') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Webhooks') }}</li>
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
                    <h1 class="custom-font">{{ __('Webhooks') }}</h1>
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
                            <th>{{ __('Bot') }}</th>
                            <th>{{ __('URL') }}</th>
                            <th>{{ __('Max connections') }}</th>
                            <th>{{ __('Created at') }}</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
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
            "ajax": '{{ route('admin.telegram.webhooks.datatable') }}',
            "columns": [
                {
                    "data": 'telegram_bot_id', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                        return '<a href="/admin/telegram/bots/' + row['telegram_bot_id'] + '/edit" target="_blank">'+ row['bot_username'] +'</a>';
                    }
                },
                {
                    "data": 'url', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                        return '<input type="text" class="form-control" value="'+ row['url'] +'" readonly>';
                    }
                },
                {"data": "max_connections", "name": "telegram_webhooks.max_connections"},
                {"data": "created_at", "name": "telegram_webhooks.created_at"},
            ],
            "dom": 'Rlfrtip',
            initComplete: function () {
                this.api().columns([2, 3]).every(function () {
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