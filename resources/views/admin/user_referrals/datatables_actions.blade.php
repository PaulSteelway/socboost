{!! Form::open(['route' => ['admin.userReferrals.destroy', $id], 'method' => 'delete']) !!}
<div>
    {{--    <a href="{{ route('admin.userReferrals.show', $id) }}" class='btn btn-default btn-xs'>--}}
    {{--        <i class="glyphicon glyphicon-eye-open"></i>--}}
    {{--    </a>--}}
    <a href="{{ route('admin.userReferrals.edit', $id) }}" class="btn btn-primary btn-xs" title="Edit">
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => "return confirm('Are you sure?')",
        'title' => 'Delete'
    ]) !!}
</div>
{!! Form::close() !!}
