📥 {{ __('Top up') }} {{ strtoupper($currency->code) }}

{{ __('All operations handle automatically') }}
{{ __('Please, send') }} <b>{{ number_format($transaction->amount, 8, '.', '') }}</b>
{{ __('To wallet') }}: