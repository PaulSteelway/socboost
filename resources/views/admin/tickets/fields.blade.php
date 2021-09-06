<!-- User Id Field -->
<div class="form-group col-sm-4">
    {!! Form::label('user_id', 'User:') !!}
    <div class="form-control" disabled>
        <a href="{{route('admin.users.show', $ticket->user_id)}}" target="_blank">{{$ticket->user->email}}</a>
    </div>
</div>

<!-- Subject Field -->
<div class="form-group col-sm-4">
    {!! Form::label('subject', 'Subject:') !!}
    {!! Form::select('subject', config('enumerations')['tickets']['subjects'], null, ['class' => 'form-control']) !!}
</div>


<!-- Status Field -->
<div class="form-group col-sm-4">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::select('status', config('enumerations')['tickets']['statuses'], null, ['class' => 'form-control']) !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.tickets.index') }}" class="btn btn-default">Cancel</a>
</div>
