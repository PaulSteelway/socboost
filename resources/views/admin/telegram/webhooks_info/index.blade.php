@extends('admin/layouts.app')
@section('title')
    {{ __('Webhooks info') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Webhooks info') }}</li>
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
                    <h1 class="custom-font">{{ __('Webhooks info') }}</h1>
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
                            <th>{{ __('BOT') }}</th>
                            <th>{{ __('Has SSL') }}</th>
                            <th>{{ __('Pending updates') }}</th>
                            <th>{{ __('Last error') }}</th>
                            <th>{{ __('Max connections') }}</th>
                            <th>{{ __('Updated at') }}</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
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
            "ajax": '{{ route('admin.telegram.webhooks_info.datatable') }}',
            "columns": [
                {
                    "data": 'bot_username', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                        return '<a href="tg://resolve?domain=' + row['bot_username'] + '" style="font-weight:bold;"> '+ row['bot_username'] +'</a>';
                    }
                },
                {
                    "data": 'has_custom_certificate', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                        if (row['has_custom_certificate'] == 1) {
                            return "{{ __('yes') }}";
                        } else {
                            return "{{ __('no') }}";
                        }
                    }
                },
                {"data": "pending_update_count", "name": "telegram_webhooks_info.pending_update_count"},
                {
                    "data": 'last_error', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                        var err = row['last_error_message'] == null ? '' : row['last_error_message'];
                        err     += row['last_error_date'] == null ? '' : row['last_error_date'];
                        return '<textarea class="form-control" readonly>'+ err +'</textarea>';
                    }
                },
                {"data": "max_connections", "name": "telegram_webhooks_info.max_connections"},
                {"data": "updated_at", "name": "telegram_webhooks_info.updated_at"},
            ],
            "dom": 'Rlfrtip',
            initComplete: function () {
                this.api().columns([2, 3, 4, 5]).every(function () {
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