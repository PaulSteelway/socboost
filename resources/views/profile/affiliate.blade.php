@extends('layouts.profile')

@section('title', __('Affiliate program') . ' - ' . __('site.site'))

@section('content')
    <div class="card card-outline-secondary">
        <div class="card-header">
            <strong>{{ __('Hi') }}, {{ getUserName() }}</strong>
            <strong style="float:right;">{{ __('Your balance') }}:
                @foreach(getUserBalancesByCurrency(true) as $symbol => $balance)
                    {{ $symbol }} {{ number_format($balance, 2) }}{{ !$loop->last ? ',' : '' }}
                @endforeach
            </strong>
        </div>

        <div class="card-body">
            @include('partials.inform')
            @if(!empty(getPartnerArray()))
                <p class="help-block">
                    {{ __('You invited by') }}: {{ getPartnerArray()['login'] }}
                    <br>Email: <a href="mailto:{{ getPartnerArray()['email'] }}"
                                  target="_blank">{{ getPartnerArray()['email'] }}</a>
                    @if(getPartnerArray()['skype'])
                        Skype: <a href="skype:{{ getPartnerArray()['skype'] }}">{{ getPartnerArray()['skype'] }}</a>
                    @endif
                    @if(getPartnerArray()['phone'])
                        {{ __('Phone') }}: <a
                                href="skype:{{ getPartnerArray()['phone'] }}">{{ getPartnerArray()['phone'] }}</a>
                    @endif
                </p>
            @endif
            <hr>
            @if(getUserReferrals(1))
                <h3>{{ __('Referrals tree') }}</h3>

                @push('styles')
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
                @endpush

                <ref></ref>

                @push('scripts')
                    <script src="https://d3js.org/d3.v3.min.js"></script>

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

                        d3.json("{{route('users.reftree')}}", function (error, flare) {
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

                @endpush
            @else
                <div class="alert alert-danger"
                     role="alert">{{ __('Referrals tree visualization can not be created. You don\'t have any referrals.') }}</div>
            @endif
            <hr>
            <h3>{{ __('Affiliate earnings operations list') }}</h3>
            <table class="table table-striped" id="operations-table" style="width:100%;">
                <thead>
                <tr>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Currency') }}</th>
                    <th>{{ __('From') }}</th>
                    <th>{{ __('Approved') }}</th>
                    <th>{{ __('Date') }}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- /.card -->
@endsection

@push('scripts')
    <script>
        //initialize basic datatable
        jQuery('#operations-table').width('100%').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [[4, "desc"]],
            "ajax": '{{route('profile.operations.dataTable', ['type' => 'partner'])}}',
            "columns": [
                {
                    "data": 'amount',
                    "orderable": true,
                    "searchable": true,
                    "render": function (data, type, row, meta) {
                        return row['amount'] + row['currency']['symbol'];
                    }
                },
                {"data": "currency.name"},
                {"data": "partner_from"},
                {
                    "data": "approved", "render": function (data, type, row, meta) {
                        if (row['approved'] == 1) {
                            return '{{ __('yes') }}';
                        }
                        return '{{ __('no') }}';
                    }
                },
                {"data": "created_at"},
            ],
        });
        //*initialize basic datatable
    </script>
@endpush
