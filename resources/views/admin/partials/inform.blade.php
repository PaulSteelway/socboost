@if(session()->has('success'))
    <div class="alert alert-success" role="alert">
        @lang(session()->get('success'))
    </div>
@endif

@if(session()->has('error'))
    <div class="alert alert-danger" role="alert">
        @lang(session()->get('error'))
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif