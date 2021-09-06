@extends('admin/layouts.app')
@section('title')
    {{ __('Telegram users') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Telegram users') }}</li>
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
                    <h1 class="custom-font">{{ __('Telegram users') }}</h1>
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

                    <table id="users" class="table hover form-inline dt-bootstrap no-footer">
                        <thead>
                        <tr>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Bot') }}</th>
                            <th>{{ __('Created at') }}</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
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
        var table = $('#users').width('100%').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": '{{ route('admin.telegram.users.datatable') }}',
            "columns": [
                {
                    "data": 'user_name', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                        return '<a href="/admin/users/' + row['user_id'] + '" target="_blank" style="font-weight:bold;"> '+ row['user_name'] +'</a>';
                    }
                },
                {
                    "data": 'bot_username', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                        return '<a href="/admin/telegram/bots/' + row['bot_id'] + '/edit" target="_blank"> '+ row['bot_username'] +'</a>';
                    }
                },
                {"data": "created_at", "name": "telegram_users.created_at"},
            ],
            "dom": 'Rlfrtip',
            initComplete: function () {
                this.api().columns([2]).every(function () {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                });
            }
        });

        $('#users tbody').on('click', 'tr', function () {
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