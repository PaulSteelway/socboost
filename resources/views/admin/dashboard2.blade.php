@extends('admin/layouts.app')
@section('title', 'Аналитические графики')

@section('breadcrumbs')
    <li> Аналитические графики</li>
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            <section class="tile tile-simple">
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font">
                        {{ __('Financial statistics by days') }}
                    </h1>
                    <strong style="float:right;">{{ __('Last') }} 30 {{ __('days') }}</strong>
                </div>
                <div class="tile-body">
                    @foreach(getCurrencies() as $currency)
                        <h4 class="custom-font"><strong>{{ $currency['name'] }}</strong></h4>
                        <div id="line-{{ $currency['code'] }}" style="height: 250px;width:80%;"></div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <section class="tile tile-simple">
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font">{{ __('Users activity by days') }}</h1>
                    <strong style="float:right;">{{ __('Last') }} 30 {{ __('days') }}</strong>
                </div>
                <div class="tile-body">
                    <div id="line-area-analytics" style="height: 250px;width:80%;"></div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('load-scripts')
    <script>
        // Morris line chart
        <?php
        $faker = \Faker\Factory::create();
        ?>
        @foreach(getCurrencies() as $currency)
        Morris.Line({
            element: 'line-{{ $currency['code'] }}',
            data: [
                    @foreach(getAdminMoneyTrafficStatistic(30, $currency['code']) as $day => $amounts)
                {
                    y: '{{ $day }}', a: {{ $amounts['enter'] ?? 0 }}, b: {{ $amounts['withdrew'] ?? 0 }} },
                @endforeach
            ],
            xkey: 'y',
            ykeys: ['a', 'b'],
            labels: ['{{ __('Enter') }}', '{{ __('Withdraw') }}'],
            lineColors: ['#16a085', '#FF0066']
        });
        @endforeach
        // Morris line chart

        //Initialize morris chart
        Morris.Donut({
            element: 'plan-usage',
            data: [
                <?php
                $popularityList = [];
                $faker = \Faker\Factory::create();

                foreach (getAdminPlanPopularity() as $popularity) {
                    echo "{label: '" . stripslashes($popularity['name']) . "', value: " . $popularity['depositsSum'] . ", color: '" . $faker->hexColor . "'},";
                }
                ?>
            ],
            resize: true,
            formatter: function (y, data) {
                return '{{ __('deposits') }}: ' + y
            }
        });
        //*Initialize morris chart

        // Morris line area chart
        Morris.Area({
            element: 'line-area-analytics',
            data: [
                    @foreach(getAdminUsersActivityStatistic(30) as $date => $day)
                {
                    y: '{{ $date }}', a: {{ $day['visitors'] }}, b: {{ $day['pageViews'] }}},
                @endforeach
            ],
            xkey: 'y',
            ykeys: ['a', 'b'],
            labels: ['Visitors', 'Page Views'],
            lineColors: ['#16a085', '#FF0066'],
            lineWidth: '1',
            grid: true,
            fillOpacity: '0.5',
            width:'100px',
        });
        // Morris line area chart
    </script>
@endpush
