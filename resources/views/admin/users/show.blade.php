@extends('admin/layouts.app')

@section('title'){{ __('User profile') }}: {{ $user->name }}@endsection

@section('breadcrumbs')
    <li><a href="{{route('admin.users.index')}}">{{ __('Users list') }}</a></li>
    <li> {{ __('User profile') }}: {{ $user->login }}</li>
@endsection

@section('content')
    <!-- row -->
    <div class="row">
        <!-- col -->
        <div class="col-md-4">
            <!-- tile -->
            <section class="tile">

                <!-- tile header -->
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font">{{ __('User profile') }}</h1>
                </div>
                <!-- /tile header -->

                <!-- tile body -->
                <div class="tile-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-lg-5">
                                    {{ __('Name:') }}
                                </div>
                                <div class="col-lg-7" style="font-weight:bold;">
                                    {{ $user->name }}
                                </div>
                            </div>
                        </li><li class="list-group-item">
                            <div class="row">
                                <div class="col-lg-5">
                                    {{ __('Last name:') }}
                                </div>
                                <div class="col-lg-7" style="font-weight:bold;">
                                    {{ $user->lastname }}
                                </div>
                            </div>
                        </li><li class="list-group-item">
                            <div class="row">
                                <div class="col-lg-5">
                                    {{ __('Telegram') }}
                                </div>
                                <div class="col-lg-7" style="font-weight:bold;">
                                    {{ $user->telegram }}
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-lg-5">
                                    {{ __('E-mail:') }}
                                </div>
                                <div class="col-lg-7" style="font-weight:bold;">
                                    <a href="mailto:{{ $user->email }}" target="_blank">{{ $user->email }}</a>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-lg-5">
                                    {{ __('Login:') }}
                                </div>
                                <div class="col-lg-7" style="font-weight:bold;">
                                    {{ $user->login }}
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-lg-5">
                                    {{ __('Phone:') }}
                                </div>
                                <div class="col-lg-7" style="font-weight:bold;">
                                    <a href="tel:{{ $user->phone }}" target="_blank">{{ $user->phone }}</a>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-lg-5">
                                    {{ __('Skype:') }}
                                </div>
                                <div class="col-lg-7" style="font-weight:bold;">
                                    <a href="skype:{{ $user->skype }}" target="_blank">{{ $user->skype }}</a>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-lg-5">
                                    {{ __('API Key:') }}
                                </div>
                                <div class="col-lg-7" style="font-weight:bold;word-break:break-word;">
                                    {{ $user->api_key }}
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-lg-5">
                                    {{ __('Partner ID:') }}
                                </div>
                                <div class="col-lg-7" style="font-weight:bold;">
                                    <a href="{{ route('admin.users.show', $user->getPartnerOnLevel(1)->id??'') ?? '' }}"
                                       target="_blank">{{ $user->getPartnerOnLevel(1)->login ?? '' }}</a>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-lg-5">
                                    {{ __('Registration:') }}
                                </div>
                                <div class="col-lg-7" style="font-weight:bold;">
                                    <strong data-toggle="tooltip" data-placement="top"
                                            title="{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}">{{ $user->created_at }}</strong>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <strong>{{ __('Referral link:') }}</strong>
                    <input type="text" class="form-control" value="{{ route('partner',['partner_id'=>$user->my_id]) }}"
                           readonly>
                    <hr>
                    <a href="{{ route('admin.users.edit', $user->id) }}" target="_top"
                       class="btn btn-primary btn-large" style="display: block;">{{ __('Edit user') }}</a>
                    <a href="{{ route('admin.impersonate', $user->id) }}" style="margin-top:3px; width:100%;" target="_top"
                       class="btn btn-default btn-large" style="display: block;">{{ __('Impersonate user') }}</a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" target="_top">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" class="btn btn-danger btn-large sure form-control"
                               style="display: block; margin-top:5px;" value="{{ __('Destroy user') }}">
                    </form>
                    <hr>
                    @if ($user->getRoleNames()->count() > 0)
                        <p>{{ __('Roles') }}:
                            @foreach($user->getRoleNames() as $role)
                                <strong>{{ $role }}</strong>{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </p>
                    @endif
                </div>
                <!-- /tile body -->
            </section>
            <!-- /tile -->
        </div>
        <div class="col-md-8">
            <!-- tile -->
            <section class="tile">

                <!-- tile header -->
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font">{{ __('Wallets') }}</h1>
                    <ul class="controls">
                        <li>
                            <a role="button" tabindex="0" class="tile-fullscreen">
                                <i class="fa fa-expand"></i> {{ __('Fullscreen') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /tile header -->

                <!-- tile body -->
                <div class="tile-body">
                    <table id="wallets" class="dataTables_wrapper hover form-inline dt-bootstrap no-footer">
                        <thead>
                        <tr>
                            <th>{{ __('Balance') }}</th>
                            <th>{{ __('Payment system') }}</th>
                            <th>{{ __('Currency') }}</th>
                            <th>{{ __('Wallet address') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->wallets as $wallet)
                            <tr>
                                <td><strong data-toggle="tooltip" data-placement="top"
                                            title="{{ __('Current balance') }}">{{ $wallet->balance }}{{$wallet->currency->symbol}}</strong>
                                    @if ($wallet->requestedAmount())
                                        <br> <strong class="help-block" data-toggle="tooltip" data-placement="left"
                                                     title="{{ __('Requested amount') }}">{{ $wallet->requestedAmount() }}{{ $wallet->currency->symbol }}</strong>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.payment-systems.edit', $wallet->paymentSystem->id) }}"
                                       target="_blank">{{ $wallet->paymentSystem->name }}</a></td>
                                <td><a href="{{ route('admin.currencies.edit', $wallet->currency->id) }}"
                                       target="_blank">{{ $wallet->currency->name }}</a></td>
                                <td>{{ $wallet->external }}</td>
                                <td>
                                    <form class="form-horizontal" method="POST"
                                          action="{{ route('admin.users.bonus') }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="wallet_id" value="{{ $wallet->id }}">
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <fieldset>
                                            <div class="form-group">
                                                <div class="col-sm-10">
                                                    <div class="input-group mb-10">
                                                    <span class="input-group-btn">
                                                         <button class="btn btn-default"
                                                                 type="submit">{{ __('send bonus') }}</button>
                                                    </span>
                                                        <input id="amount" type="number" step="any" min="0"
                                                               class="form-control"
                                                               name="amount" placeholder="{{ __('amount') }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                    <form class="form-horizontal" method="POST"
                                          action="{{ route('admin.users.penalty') }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="wallet_id" value="{{ $wallet->id }}">
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <fieldset>
                                            <div class="form-group">
                                                <div class="col-sm-10">
                                                    <div class="input-group mb-10">
                                                    <span class="input-group-btn">
                                                         <button class="btn btn-default"
                                                                 type="submit">{{ __('send penalty') }}</button>
                                                    </span>
                                                        <input id="amount" type="number" step="any" min="0"
                                                               class="form-control"
                                                               name="amount" placeholder="{{ __('amount') }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                <!-- /tile body -->
            </section>

            @push('load-scripts')
                <script>
                    $('#wallets').DataTable();
                </script>
        @endpush
        <!-- /tile -->
        </div>

        <div class="col-md-12">
            <!-- tile -->
            <section class="tile">

                <!-- tile header -->
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font">{{ __('Withdraw requests') }}</h1>
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
                        <form method="POST"
                              action="{{ route('admin.requests.approve-many') }}">
                            {{ csrf_field() }}
                            <table class="table table-custom" id="wrs-table">
                                <thead>
                                <tr>
                                    <th>{{ __('Payment system') }}</th>
                                    <th>{{ __('Currency') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Status') }}</th>
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
                                    <td class="tdinput">
                                        <b>{{ __('selected orders') }}:</b>
                                        <button id="singlebutton" name="approve" value="true"
                                                class="btn btn-xs btn-primary">{{ __('approve') }}</button>
                                        <button id="singlebutton" name="reject" value="true"
                                                class="btn btn-xs btn-warning">{{ __('reject') }}</button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </div>
                <!-- /tile body -->

            </section>

            @push('load-scripts')
                <script>
                    var table = $('#wrs-table').width('100%').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": '{{route('admin.users.dt-wrs',['user_id'=>$user->id])}}',
                        "columns": [
                            {"data": "payment_system.name", "name": "paymentSystem.name"},
                            {"data": "currency.code", "name": "currency.code"},
                            {"data": "amount", "name": "transaction.amount"},
                            {"data": "status", "name": "transaction.status"},
                            {
                                "data": 'actions',
                                "orderable": false,
                                "searchable": false,
                                "render": function (data, type, row) {
                                    return '<input type="checkbox" name="list[]" value="' + row['id'] + '"> &nbsp; <a href="/admin/requests/' + row['id'] + '" class="btn btn-xs btn-primary" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> {{ __('show') }}</a> &nbsp; <a href="/admin/requests/approve/' + row['id'] + '" class="btn btn-xs btn-success" target="_blank"><i class="glyphicon glyphicon-check"></i> {{ __('approve') }}</a> &nbsp;<a href="/admin/requests/reject/' + row['id'] + '" class="btn btn-xs btn-warning" target="_blank"><i class="glyphicon glyphicon-check"></i> {{ __('reject') }}</a>';
                                }
                            },
                        ],
                        "aoColumnDefs": [
                            {'bSortable': false, 'aTargets': ["no-sort"]}
                        ],
                        "dom": 'Rlfrtip',
                        initComplete: function () {
                            this.api().columns([0, 1, 2, 3]).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                $(input).appendTo($(column.footer()).empty())
                                    .on('change', function () {
                                        column.search($(this).val(), false, false, true).draw();
                                    });
                            });
                        }
                    });

                    $('#wrs-table tbody').on('click', 'tr', function () {
                        if ($(this).hasClass('row_selected')) {
                            $(this).removeClass('row_selected');
                        }
                        else {
                            table.$('tr.row_selected').removeClass('row_selected');
                            $(this).addClass('row_selected');
                        }
                    });
                </script>
            @endpush
        <!-- /tile -->

            <!-- tile -->
            <section class="tile">

                <!-- tile header -->
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font"> {{ __('Deposits') }}</h1>
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
                        <table class="table table-custom" id="deposits-table">
                            <thead>
                            <tr>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Currency') }}</th>
                                <th>{{ __('Invested') }}</th>
                                <th>{{ __('Duration days') }}</th>
                                <th>{{ __('Plan') }}</th>
                                <th>{{ __('Opened') }}</th>
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
                                <td class="tdinput"></td>
                                <td class="tdinput"></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /tile body -->
            </section>
            @push('load-scripts')
                <script>
                    var table = $('#deposits-table').width('100%').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "order": [[5, "desc"]],
                        "ajax": '{{route('admin.users.dt-deposits',['user_id'=>$user->id])}}',
                        "columns": [
                            {"data": "condition"},
                            {"data": "currency.code"},
                            {"data": "invested"},
                            {"data": "duration"},
                            {"data": "rate.name"},
                            {"data": "created_at"},
                            {
                                "data": 'actions',
                                "orderable": false,
                                "searchable": false,
                                "render": function (data, type, row) {
                                    return '<a href="/admin/deposits/' + row['id'] + '" class="btn btn-xs btn-primary"  target="_blank"><i class="glyphicon glyphicon-eye-open"></i> {{ __('show') }}</a>';
                                }
                            },
                        ],
                        "aoColumnDefs": [
                            {'bSortable': false, 'aTargets': ["no-sort"]}
                        ],
                        "dom": 'Rlfrtip',
                        initComplete: function () {
                            this.api().columns([0, 1, 2, 3, 4, 5]).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                $(input).appendTo($(column.footer()).empty())
                                    .on('change', function () {
                                        column.search($(this).val(), false, false, true).draw();
                                    });
                            });
                        }
                    });

                    $('#deposits-table tbody').on('click', 'tr', function () {
                        if ($(this).hasClass('row_selected')) {
                            $(this).removeClass('row_selected');
                        }
                        else {
                            table.$('tr.row_selected').removeClass('row_selected');
                            $(this).addClass('row_selected');
                        }
                    });
                </script>
            @endpush
            <!-- /tile -->

            <!-- tile -->
            <section class="tile">

                <!-- tile header -->
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font">{{ __('Transactions') }}</h1>
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
                        <table class="table table-custom" id="transactions-table">
                            <thead>
                            <tr>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Currency') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Approved') }}</th>
                                <th>{{ __('Created') }}</th>
                                <th>{{ __('Action') }}</th>

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
                                <td class="tdinput"></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /tile body -->
            </section>
            @push('load-scripts')
                <script>
                    //initialize basic datatable
                    var table = $('#transactions-table').width('100%').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "order": [[4, "desc"]],
                        "ajax": '{{route('admin.users.dt-transactions',['user_id'=>$user->id])}}',
                        "columns": [
                            {"data": "type_name"},
                            {"data": "currency.code"},
                            {"data": "amount"},
                            {"data": "approved"},
                            {"data": "created_at"},
                            {
                                "data": 'actions',
                                "orderable": false,
                                "searchable": false,
                                "render": function (data, type, row) {
                                    return '<a href="/admin/transactions/' + row['id'] + '" class="btn btn-xs btn-primary" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> {{ __('show') }}</a>';
                                }
                            },
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

                    $('#transactions-table tbody').on('click', 'tr', function () {
                        if ($(this).hasClass('row_selected')) {
                            $(this).removeClass('row_selected');
                        }
                        else {
                            table.$('tr.row_selected').removeClass('row_selected');
                            $(this).addClass('row_selected');
                        }
                    });
                    // *initialize basic datatable
                </script>
            @endpush
            <!-- /tile -->

            <!-- tile -->
            <section class="tile">
                <!-- tile header -->
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font">{{ __('Referrals') }}</h1>
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
                    <style>

                        .node {
                            cursor: pointer;
                        }

                        .node circle {
                            fill: #fff;
                            stroke: steelblue;
                            stroke-width: 1.5px;
                        }

                        .node text {
                            font: 10px sans-serif;
                        }

                        .link {
                            fill: none;
                            stroke: #ccc;
                            stroke-width: 1.5px;
                        }

                    </style>
                    <ref></ref>
                    <script src="/admin_assets/js/vendor/d3/d3.min.js"></script>
                    <script>

                        var margin = {top: 10, right: 10, bottom: 10, left: 10},
                            width = 900 - margin.right - margin.left,
                            height = 500 - margin.top - margin.bottom;

                        var i = 0,
                            duration = 750,
                            root;

                        var tree = d3.layout.tree()
                            .size([height, width]);

                        var diagonal = d3.svg.diagonal()
                            .projection(function (d) {
                                return [d.y, d.x];
                            });

                        var svg = d3.select("ref").append("svg")
                            .attr("width", width + margin.right + margin.left)
                            .attr("height", height + margin.top + margin.bottom)
                            .append("g")
                            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

                        d3.json("{{route('admin.users.reftree',['id'=>$user->id])}}", function (error, flare) {
                            if (error) throw error;

                            root = flare;
                            root.x0 = height / 2;
                            root.y0 = 0;

                            function collapse(d) {
                                if (d.children) {
                                    d._children = d.children;
                                    d._children.forEach(collapse);
                                    d.children = null;
                                }
                            }

                            /*root.children.forEach(collapse);*/
                            update(root);
                        });

                        d3.select(self.frameElement).style("height", "500px");

                        function update(source) {

                            // Compute the new tree layout.
                            var nodes = tree.nodes(root).reverse(),
                                links = tree.links(nodes);

                            // Normalize for fixed-depth.
                            nodes.forEach(function (d) {
                                d.y = d.depth * 100;
                            });

                            // Update the nodes…
                            var node = svg.selectAll("g.node")
                                .data(nodes, function (d) {
                                    return d.id || (d.id = ++i);
                                });

                            // Enter any new nodes at the parent's previous position.
                            var nodeEnter = node.enter().append("g")
                                .attr("class", "node")
                                .attr("transform", function (d) {
                                    return "translate(" + source.y0 + "," + source.x0 + ")";
                                })
                                .on("click", click);

                            nodeEnter.append("circle")
                                .attr("r", 1e-6)
                                .style("fill", function (d) {
                                    return d._children ? "lightsteelblue" : "#fff";
                                });

                            nodeEnter.append("text")
                                .attr("x", function (d) {
                                    return d.children || d._children ? -10 : 10;
                                })
                                .attr("dy", ".35em")
                                .attr("text-anchor", function (d) {
                                    return d.children || d._children ? "end" : "start";
                                })
                                .text(function (d) {
                                    return d.name;
                                })
                                .style("fill-opacity", 1e-6);

                            // Transition nodes to their new position.
                            var nodeUpdate = node.transition()
                                .duration(duration)
                                .attr("transform", function (d) {
                                    return "translate(" + d.y + "," + d.x + ")";
                                });

                            nodeUpdate.select("circle")
                                .attr("r", 4.5)
                                .style("fill", function (d) {
                                    return d._children ? "lightsteelblue" : "#fff";
                                });

                            nodeUpdate.select("text")
                                .style("fill-opacity", 1);

                            // Transition exiting nodes to the parent's new position.
                            var nodeExit = node.exit().transition()
                                .duration(duration)
                                .attr("transform", function (d) {
                                    return "translate(" + source.y + "," + source.x + ")";
                                })
                                .remove();

                            nodeExit.select("circle")
                                .attr("r", 1e-6);

                            nodeExit.select("text")
                                .style("fill-opacity", 1e-6);

                            // Update the links…
                            var link = svg.selectAll("path.link")
                                .data(links, function (d) {
                                    return d.target.id;
                                });

                            // Enter any new links at the parent's previous position.
                            link.enter().insert("path", "g")
                                .attr("class", "link")
                                .attr("d", function (d) {
                                    var o = {x: source.x0, y: source.y0};
                                    return diagonal({source: o, target: o});
                                });

                            // Transition links to their new position.
                            link.transition()
                                .duration(duration)
                                .attr("d", diagonal);

                            // Transition exiting nodes to the parent's new position.
                            link.exit().transition()
                                .duration(duration)
                                .attr("d", function (d) {
                                    var o = {x: source.x, y: source.y};
                                    return diagonal({source: o, target: o});
                                })
                                .remove();

                            // Stash the old positions for transition.
                            nodes.forEach(function (d) {
                                d.x0 = d.x;
                                d.y0 = d.y;
                            });
                        }

                        // Toggle children on click.
                        function click(d) {
                            if (d.children) {
                                d._children = d.children;
                                d.children = null;
                            } else {
                                d.children = d._children;
                                d._children = null;
                            }
                            update(d);
                        }

                    </script>
                </div>
                <!-- /tile body -->
            </section>
            <!-- /tile -->

            <!-- tile -->
            <section class="tile">

                <!-- tile header -->
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font">{{ __('Registered page requests') }}</h1>
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
                        <table class="table table-custom" id="pvs-table">
                            <thead>
                            <tr>
                                <th>{{ __('IP address') }}</th>
                                <th>{{ __('Location') }}</th>
                                <th>{{ __('Request address') }}</th>
                                <th>{{ __('Date') }}</th>
                            </tr>
                            </thead>
                            <style>
                                #pvs-table tbody td div.page_url {
                                    width: 400px;
                                    overflow: auto;
                                }
                            </style>
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
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /tile body -->
            </section>

            @push('load-scripts')
                <script>
                    var table = $('#pvs-table').width('100%').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "order": [[2, "desc"]],
                        "ajax": '{{route('admin.users.dt-pvs',['user_id'=>$user->id])}}',
                        "columns": [
                            {"data": "user_ip"},
                            {"data": "location", "orderable": false, "searchable": false},
                            {
                                "data": "page_url", "render": function (data, type, row) {
                                    return '<textarea class="form-control" readonly>' + row['page_url'] + '</textarea>';
                                }
                            },
                            {"data": "created_at"},
                        ],
                        "aoColumnDefs": [
                            {'bSortable': false, 'aTargets': ["no-sort"]}
                        ],
                        "dom": 'Rlfrtip',
                        initComplete: function () {
                            this.api().columns([0, 2, 3]).every(function () {
                                var column = this;
                                var input = document.createElement("input");
                                $(input).appendTo($(column.footer()).empty())
                                    .on('change', function () {
                                        column.search($(this).val(), false, false, true).draw();
                                    });
                            });
                        }
                    });

                    $('#pvs-table tbody').on('click', 'tr', function () {
                        if ($(this).hasClass('row_selected')) {
                            $(this).removeClass('row_selected');
                        }
                        else {
                            table.$('tr.row_selected').removeClass('row_selected');
                            $(this).addClass('row_selected');
                        }
                    });
                </script>
        @endpush
        <!-- /tile -->

            <!-- tile -->
            <section class="tile">

                <!-- tile header -->
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font">{{ __('Orders') }}</h1>
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
                        <table class="table table-custom" id="ordersTable">
                            <thead>
                            <tr>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Service') }}</th>
                                <th>{{ __('Quantity and price') }}</th>
                                <th>{{ __('Link') }}</th>
                                <th>{{ __('Application status') }}</th>
                                <th>{{ __('Action') }}</th>
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
                                <td class="tdinput"></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /tile body -->
            </section>
            @push('load-scripts')
                <script>
                    var dtOrdersRoute = {!! json_encode(route('admin.users.dt-orders',['user_id'=>$user->id])) !!};
                </script>
                <script src="{{asset('/admin_assets/js/datatables.js')}}"></script>
            @endpush
            <!-- /tile -->

        </div>
        <!-- /col -->
    </div>
    <!-- /row -->

@endsection
