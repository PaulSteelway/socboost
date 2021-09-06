@extends('admin/layouts.app')
@section('title')
    {{ __('Telegram bots') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Telegram bots') }}</li>
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
                    <h1 class="custom-font">{{ __('Telegram bots') }}</h1>
                    <ul class="controls">
                        <li>
                            <a role="button" href="{{ route('admin.telegram.bots.create') }}">
                                [<strong>{{ __('create new bot') }}</strong>]
                            </a>
                        </li>
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
                    <table id="bots" class="table hover form-inline dt-bootstrap no-footer">
                        <thead>
                        <tr>
                            <th>{{ __('Bot name') }}</th>
                            <th>{{ __('Bot username') }}</th>
                            <th>{{ __('Keyword') }}</th>
                            <th>{{ __('Actions') }}</th>
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
        var table = $('#bots').width('100%').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": '{{ route('admin.telegram.bots.datatable') }}',
            "columns": [
                {"data": "first_name", "name": "telegram_bots.first_name"},
                {
                    "data": 'action', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                        return '<a href="tg://resolve?domain='+ row['username'] +'" style="font-weight:bold;"> @'+ row['username'] +'</a>';
                    }
                },
                {"data": "keyword", "name": "telegram_bots.keyword"},
                {
                    "data": 'action', "orderable": false, "searchable": false, "render": function (data, type, row, meta) {
                        return '<a href="/admin/telegram/bots/' + row['id'] + '/edit" class="btn btn-xs btn-primary" target="_blank"><i class="glyphicon glyphicon-edit"></i> {{ __('edit bot') }}</a>';
                    }
                }
            ],
            "dom": 'Rlfrtip',
            initComplete: function () {
                this.api().columns([0, 1, 2]).every(function () {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                });
            }
        });

        $('#bots tbody').on('click', 'tr', function () {
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