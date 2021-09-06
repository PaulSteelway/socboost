{!! Form::open(['route' => ['admin.products.destroy', $id], 'method' => 'delete']) !!}
<div>

    <a href="{{ route('admin.products.edit', $id) }}" class='btn btn-primary btn-xs' title="Edit">
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'title' => 'Delete',
        'onclick' => "return confirm('Are you sure?')"
    ]) !!}
</div>
{!! Form::close() !!}
