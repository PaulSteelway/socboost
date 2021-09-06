<li>{{ $user->name }}
@if ($user->hasReferrals())
    <ul>
        @foreach($user->referrals()->get() as $referral)
            @include('admin.users.referrals', ['user' => $referral])
        @endforeach
    </ul>
@endif
</li>