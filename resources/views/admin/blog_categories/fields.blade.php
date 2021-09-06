
<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name_ru', 'Name:') !!}
    {!! Form::text('name_ru', null, ['class' => 'form-control']) !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.blogEntries.index') }}" class="btn btn-default">Cancel</a>
</div>
