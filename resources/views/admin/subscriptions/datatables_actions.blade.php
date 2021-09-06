{{--{!! Form::open(['route' => ['subscriptions.destroy', $id], 'method' => 'delete']) !!}--}}
{{--<div class='btn-group'>--}}
{{--    <a href="{{ route('subscriptions.show', $id) }}" class='btn btn-default btn-xs'>--}}
{{--        <i class="glyphicon glyphicon-eye-open"></i>--}}
{{--    </a>--}}
<div class="text-center">
    <a href="{{ route('admin.subscriptions.edit', $id) }}" class='btn btn-primary btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
</div>
{{--    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [--}}
{{--        'type' => 'submit',--}}
{{--        'class' => 'btn btn-danger btn-xs',--}}
{{--        'onclick' => "return confirm('Are you sure?')"--}}
{{--    ]) !!}--}}
{{--</div>--}}
{{--{!! Form::close() !!}--}}
