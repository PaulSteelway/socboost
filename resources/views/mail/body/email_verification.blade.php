<p>{{__('Подтвердите ваш email')}}:</p>
{{__('Код')}}: {{ $email_verification_hash }}<br>
{{__('Ссылка для подтверждения')}}: {{ route('email.confirm', ['hash' => $email_verification_hash]) }}