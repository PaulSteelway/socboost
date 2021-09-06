@extends('admin.layouts.app')
@section('title')
    {{ __('News list') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('News list') }}</li>
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <!-- col -->
        <div class="col-md-12">

            <!-- tile -->
            <section class="tile">

                <!-- tile header -->
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font">{{ __('News list') }}</h1>
                    <ul class="controls">
                        <li>
                            <a role="button" href="{{ route('admin.news.create') }}">[{{ __('create news') }}]
                            </a>
                        </li>
                        <li>
                            <a role="button" class="tile-fullscreen">
                                <i class="fa fa-expand"></i> {{ __('Fullscreen') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /tile header -->

                <!-- tile body -->
                <div class="tile-body">
                    <ul class="list-group">
                        <table id="news" class="table hover form-inline dt-bootstrap no-footer">
                            <thead>
                            <tr>
                                <th>{{ __('News title') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($allNews as $news)
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" readonly value="{{ $news->title }}">
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-primary btn-xs"
                                           href="{{ route('admin.news.edit', $news->parent->id) }}">{{ __('edit') }}</a>
                                        <a type="button" class="btn btn-warning btn-xs" href="#" onclick="
                                                var result = confirm('{{ __('Please confirm deletion') }}');
                                                if(result) {
                                                event.preventDefault();
                                                document.getElementById('delete-{{ $news->parent->id }}').submit()
                                                }">{{ __('delete') }}</a>
                                        <form action="{{ route('admin.news.destroy', $news->parent->id) }}"
                                              method="POST"
                                              id="delete-{{ $news->parent->id }}" style="display: none;">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @push('load-scripts')
                            <script>
                                $('#news').DataTable();
                            </script>
                        @endpush
                    </ul>
                </div>
                <!-- /tile body -->
            </section>
            <!-- /tile -->
        </div>
        <!-- /col -->
    </div>
    <!-- /row -->
@endsection
