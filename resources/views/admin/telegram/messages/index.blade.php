@extends('admin/layouts.app')
@section('title')
    {{ __('Messages') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Messages') }}</li>
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
                    <h1 class="custom-font">{{ __('Messages') }}</h1>
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

                    <table id="messages" class="table hover form-inline dt-bootstrap no-footer">
                        <thead>
                        <tr>
                            <th>{{ __('Sender') }}</th>
                            <th>{{ __('Receiver') }}</th>
                            <th>{{ __('Message') }}</th>
                            <th>{{ __('Scope') }}</th>
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
        var table = $('#messages').width('100%').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": '{{ route('admin.telegram.messages.datatable') }}',
            "columns": [
                {
                    "data": 'sender', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                        return '<a href="tg://resolve?domain='+ row['sender'] +'">@'+ row['sender'] +'</a>';
                    }
                },
                {
                    "data": 'receive', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                        return '<a href="tg://resolve?domain='+ row['receive'] +'">@'+ row['receive'] +'</a>';
                    }
                },
                {
                    "data": 'message', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                        return '<textarea class="form-control" readonly>'+ row['message'] +'</textarea>';
                    }
                },
                {"data": "scope", "name": "telegram_bot_messages.scope_id"},
                {"data": "created_at", "name": "telegram_bot_messages.created_at"},
            ],
            "dom": 'Rlfrtip',
            initComplete: function () {
                this.api().columns([4]).every(function () {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                });
            }
        });

        $('#messages tbody').on('click', 'tr', function () {
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