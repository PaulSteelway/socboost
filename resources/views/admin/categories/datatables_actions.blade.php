@if (in_array($id, [1, 2, 3, 4, 5, 6, 7]))
    <div>
        <a href="{{ route('admin.categories.edit', $id) }}" class='btn btn-primary btn-xs' title="Edit">
            <i class="glyphicon glyphicon-edit"></i>
        </a>
    </div>
@else
    {!! Form::open(['route' => ['admin.categories.destroy', $id], 'method' => 'delete']) !!}
    <div>
        {{--    <a href="{{ route('admin.categories.show', $id) }}" class='btn btn-default btn-xs'>--}}
        {{--        <i class="glyphicon glyphicon-eye-open"></i>--}}
        {{--    </a>--}}
        <a href="{{ route('admin.categories.edit', $id) }}" class='btn btn-primary btn-xs' title="Edit">
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
@endif
