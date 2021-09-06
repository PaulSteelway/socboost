@extends('admin/layouts.app')
@section('title')
    {{ __('System load') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('System load') }}</li>
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-md-12">
            <section class="tile tile-simple">
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font">
                        {{ __('System load [0% - 100%]') }}
                    </h1>
                </div>
                <div class="tile-body">
                    <div id="line-load" style="height: 250px;width:80%;"></div>
                </div>
            </section>
        </div>
    </div>
    <!-- /row -->
@endsection

@push('load-scripts')
    <script>
        Morris.Line({
            element: 'line-load',
            data: [
                    @foreach($sysLoads as $load)
                {
                    y: '{{ \Carbon\Carbon::parse($load->created_at)->format('Y-m-d H:i:s') }}', a: {{ $load->cpu ?? 0 }}, b: {{ $load->ram ?? 0 }} },
                    @endforeach
            ],
            xkey: 'y',
            ykeys: ['a', 'b'],
            labels: ['{{ __('CPU') }}', '{{ __('RAM') }}'],
            lineColors: ['#16a085', '#FF0066']
        });
    </script>
@endpush