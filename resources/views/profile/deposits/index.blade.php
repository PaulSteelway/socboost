@extends('layouts.profile')

@section('title', __('Deposits') . ' - ' . __('site.site'))

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
            <h3>{{ __('Open new deposit') }}<label style="float:right;">{{ __('Choose your tariff plan') }}</label></h3>
            <div class="row">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <ul class="list-group">
                            @foreach(getTariffPlans() as $plan)
                                <li class="list-group-item">
                                    <a href="{{ route('profile.deposits.create', ['rate_id' => $plan['id']]) }}">{{ $plan['name'] }}
                                        - {{ $plan['currency']['code'] }}</a><br>
                                    {{ __('Minimum investment') }}: {{ $plan['min'] }}, {{ __('Maximum investment') }}
                                    : {{ $plan['max'] }}, {{ __('Daily interest') }}: {{ $plan['daily'] }}
                                    %, {{ __('Plan duration') }}: {{ $plan['duration'] }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <hr style="margin-top:30px;">
            <h3>{{ __('Active deposits list') }}</h3>
            <table class="table table-striped" id="deposits-table-active" style="width:100%;">
                <thead>
                <tr>
                    <th>{{ __('Currency') }}</th>
                    <th>{{ __('Rate') }}</th>
                    <th>{{ __('Earnings') }}</th>
                    <th>{{ __('Invested') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Closing') }}</th>
                </tr>
                </thead>
            </table>
            <hr>
            <h3>{{ __('Closed deposits list') }}</h3>
            <table class="table table-striped" id="deposits-table-closed" style="width:100%;">
                <thead>
                <tr>
                    <th>{{ __('Currency') }}</th>
                    <th>{{ __('Rate') }}</th>
                    <th>{{ __('Earnings') }}</th>
                    <th>{{ __('Invested') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Closing') }}</th>
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
        jQuery('#deposits-table-active').width('100%').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [[5, "desc"]],
            "ajax": '{{route('profile.deposits.dataTable', ['active' => 1])}}',
            "columns": [
                {"data": "currency.name"},
                {"data": "rate.name"},
                {
                    "data": 'daily',
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row, meta) {
                        return row['daily'] + '% {{ __('per day') }}';
                    }
                },
                {
                    "data": 'invested',
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row, meta) {
                        return row['invested'] + row['currency']['symbol'];
                    }
                },
                {
                    "data": 'active',
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row, meta) {
                        return row['active'] == 1 ? '{{ __('active') }}' : '{{ __('closed') }}';
                    }
                },
                {"data": "closing_at"},
            ],
        });
        //*initialize basic datatable
    </script>
@endpush

@push('scripts')
    <script>
        //initialize basic datatable
        jQuery('#deposits-table-closed').width('100%').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [[5, "desc"]],
            "ajax": '{{route('profile.deposits.dataTable', ['active' => 0])}}',
            "columns": [
                {"data": "currency.name"},
                {"data": "rate.name"},
                {
                    "data": 'daily',
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row, meta) {
                        return row['daily'] + '% {{ __('per day') }}';
                    }
                },
                {
                    "data": 'invested',
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row, meta) {
                        return row['invested'] + row['currency']['symbol'];
                    }
                },
                {
                    "data": 'active',
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row, meta) {
                        return row['active'] == 1 ? '{{ __('active') }}' : '{{ __('closed') }}';
                    }
                },
                {"data": "closing_at"},
            ],
        });
        //*initialize basic datatable
    </script>
@endpush
