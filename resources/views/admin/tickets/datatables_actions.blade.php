{!! Form::open(['route' => ['admin.tickets.destroy', $id], 'method' => 'delete']) !!}
<div>
    <a href="{{ route('admin.tickets.edit', $id) }}" class='btn btn-primary btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => "return confirm('Are you sure?')"
    ]) !!}
</div>
{!! Form::close() !!}
