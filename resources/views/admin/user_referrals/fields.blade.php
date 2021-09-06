{!! Form::hidden('id', empty($userReferral) ? $user->id : $userReferral->id) !!}

<!-- Referral Field -->
<div class="form-group col-sm-6">
    {!! Form::label('referral_id', __('Referral:')) !!}
    {!! Form::select('referral_id', $referrals, null, ['class' => 'form-control',  'placeholder' => 'Select referral...']) !!}
</div>

<!-- Link Field -->
<div class="form-group col-sm-6">
    {!! Form::label('link', __('Link:')) !!}
    <input type="text" class="form-control" id="referral_link" readonly
           value="{!! empty($userReferral) ? null : route('referral.route', $userReferral->link) !!}">
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.userReferrals.index') }}" class="btn btn-default">Cancel</a>
    <a href="{{ route('admin.userReferrals.generate', empty($userReferral) ? $user->id : $userReferral->id) }}"
       class="btn btn-success">
        {{ empty($userReferral->link) ? __('Generate link') : __('Regenerate link') }}
    </a>
    @if (!empty($userReferral->link))
        <button type="button" class="btn btn-success" onclick="copyToClipboardById('referral_link')">
            {{ __('Copy link') }}
        </button>
    @endif
</div>
