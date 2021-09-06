@extends('admin/layouts.app')
@section('title')
    {{ __('Statistics') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Statistics') }}</li>
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <!-- col -->
        <div class="col-md-6">
            <!-- tile -->
            <section class="tile">
                <div class="tile-body">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td>
                                {{ __('Active days') }}
                            </td>
                            <td>
                                <strong>{{ getRunningDays() }}<label style="float:right;">({{ getDateOfLaunch() }}
                                        )</label></strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ __('Total accounts') }}
                            </td>
                            <td>
                                <strong>{{ getTotalAccounts() }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ __('Active accounts') }}
                            </td>
                            <td>
                                <strong>{{ getActiveAccounts() }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ __('Visitors online') }}
                            </td>
                            <td>
                                <strong>{{ getVisitorsOnline() }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ __('Member online') }}
                            </td>
                            <td>
                                <strong>{{ getMembersOnline() }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ __('Last update') }}
                            </td>
                            <td>
                                <strong>{{ getLastUpdate() }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ __('Summary deposits') }}
                            </td>
                            <td>
                                <strong>{{ getDepositsCount() }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ __('Active deposits') }}
                            </td>
                            <td>
                                <strong>{{ getActiveDepositsCount() }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ __('Closed deposits') }}
                            </td>
                            <td>
                                <strong>{{ getClosedDepositsCount() }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ __('Total transactions') }}
                            </td>
                            <td>
                                <strong>{{ getAdminTransactionsCount() }}</strong>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <!-- /col -->
        <div class="col-md-6">
            <!-- tile -->
            <section class="tile bg-greensea">

                <!-- tile header -->
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font">{{ __('Summary statistic') }}</h1>
                    <span class="help-block"
                          style="color:white;">{{ __('Calculated different between investments and withdrawals') }}</span>
                </div>
                <!-- /tile header -->
            @if (!empty($mergeDepositedAndWithdrew = getAdminMergeDepositedAndWithdrew()))
                <!-- tile body -->
                    <div class="tile-body p-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>{{ __('Currency') }}</th>
                                    <th>{{ __('Invested') }}</th>
                                    <th>{{ __('Withdrew') }}</th>
                                    <th>{{ __('Different') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(current($mergeDepositedAndWithdrew) as $currency => $data)
                                    <?php
                                    $d = $mergeDepositedAndWithdrew['deposited'][$currency];
                                    $w = $mergeDepositedAndWithdrew['withdrew'][$currency];
                                    ?>
                                    <tr>
                                        <td>{{ $currency }}</td>
                                        <td style="font-weight: bold;">{{ $d }}</td>
                                        <td style="font-weight: bold;">{{ $w }}</td>
                                        <td style="font-weight: bold;">{{ $d-$w }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /tile body -->
                @else
                    <div class="alert alert-warning alert-dismissable">{{ __('No summary statistic data exists ..') }}</div>
                @endif
            </section>
            <!-- /tile -->

            <!-- tile -->
            <section class="tile" fullscreen="isFullscreen02">
                <!-- tile header -->
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font"><strong>{{ __('Tariff plan popularity') }}</strong></h1>
                    <ul class="controls">
                        <li>
                            <a role="button" tabindex="0" class="tile-fullscreen">
                                <i class="fa fa-expand"></i> {{ __('Fullscreen') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /tile header -->

                <!-- tile widget -->
                <div class="tile-widget">
                    <div id="plan-usage" style="width: 60%; margin-left:20%;"></div>
                </div>
                <!-- /tile widget -->

            </section>
            <!-- /tile -->

        </div>
    </div>
    <!-- /row -->
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
            lineWidth: '0',
            grid: false,
            fillOpacity: '0.5'
        });
        // Morris line area chart
    </script>
@endpush