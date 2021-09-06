@push('styles')
  <link href='https://fonts.googleapis.com/css?family=Ubuntu:400,500,400italic,700,500italic' rel='stylesheet' type='text/css'>

  <link href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css" rel='styesheet'>
@endpush

<div class="col-lg-3"></div>
<div class="col-md-6" style="margin-top:100px;">
    <!-- START PANEL -->
    <div class="panel panel-default">
        <div class="panel-heading ui-draggable-handle">
            <h3 class="panel-title">{{ __('Recharge balance with').' '.$currency->name }}</h3>
        </div>
        <div class="panel-body">
            <strong>{{ __('You should send:') }} <input type="text" style="background:rgb(200,200,200); border:1px solid rgb(150,150,150);" value="{{ $transaction->amount }}" readonly> {{ $currency->code }}</strong>
            <hr>
            <strong>{{ __('To') }} {{ $currency->code }} {{ __('address:') }} <input type="text" style="background:rgb(200,200,200); border:1px solid rgb(150,150,150);" value="{{ $sendTo }}" readonly></strong>
            <hr>
            <div style="text-align: center;">
                <a rel='nofollow' href='{{ $paymentSystem->code.':'.$sendTo.'?amount='.$transaction->amount }}' border='0'><img src='https://chart.googleapis.com/chart?cht=qr&chl={{ urlencode($paymentSystem->code.':'.$sendTo.'?amount='.$transaction->amount) }}&chs=180x180&choe=UTF-8&chld=L|2' alt=''></a>
            </div>
            <br><br>
            <span>{{ __('All operations processing automatically. It can take up to couple of hours.') }}</span>
        </div>
    </div>
</div>
