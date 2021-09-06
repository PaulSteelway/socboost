@extends('admin/layouts.app')
@section('title')
    {{ __('Reviews') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Reviews') }}</li>
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
                    <h1 class="custom-font">{{ __('Reviews') }}</h1>
                    <ul class="controls">
{{--                        <li>--}}
{{--                            <a role="button" href="{{ route('admin.reviews.create') }}">[{{ __('create new review') }}]--}}
{{--                            </a>--}}
{{--                        </li>--}}
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
                    <table id="reviews" class="table table-striped table-bordered hover form-inline dt-bootstrap no-footer">
                        <thead>
                        <tr>
                            <th class="text-center">{{ __('Date') }}</th>
                            <th class="text-center">{{ __('Client name') }}</th>
                            <th class="text-center">{{ __('Review text') }}</th>
                            <th class="text-center">{{ __('Review type') }}</th>
                            <th class="text-center">{{ __('Video link') }}</th>
                            <th class="text-center">{{ __('Status') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reviews as $review)
                            <tr>
                                <td>{{ $review->created_at }}</td>
                                <td>{{ $review->user->name }}</td>
                                <td>
{{--                                    <textarea class="form-control" readonly>{{ $review['text'] ?? 'no text' }}</textarea>--}}
                                    {{ $review->text }}
                                </td>
                                <td>{{$review->type_id == 1 ? __('Text') : __('Video')}}</td>
                                <td>
{{--                                    <input type="text" class="form-control" readonly value="{{ $review->video ?? '-'}}">--}}
                                    {{ $review->video ?? '-'}}
                                </td>
                                <td class="text-center">
                                    {!! $review->status == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-warning">Inactive</span>' !!}
                                </td>
                                <td>
                                    <a type="button" class="btn btn-primary btn-xs" title="{{ __('edit') }}"
                                       href="{{ route('admin.reviews.edit', $review->id) }}">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <a type="button" class="btn btn-danger btn-xs" href="#" title="{{ __('delete') }}" onclick="
                                            var result = confirm('{{ __('Please confirm deletion') }}');
                                            if(result) {
                                            event.preventDefault();
                                            document.getElementById('delete-{{ $review->id }}').submit()
                                            }"><i class="glyphicon glyphicon-trash"></i></a>
                                    <form action="{{ route('admin.reviews.destroy',$review->id) }}"
                                          method="POST"
                                          id="delete-{{ $review->id }}" style="display: none;">
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
                            $('#reviews').DataTable({
                                responsive: true,
                                order: [[0, 'desc']],
                                columnDefs: [
                                    {
                                        targets: 5,
                                        orderable: false,
                                    }
                                ]
                            });
                        </script>
                    @endpush
                </div>
                <!-- /tile body -->

            </section>
            <!-- /tile -->

        </div>
        <!-- /col -->
    </div>
    <!-- /row -->

@endsection
