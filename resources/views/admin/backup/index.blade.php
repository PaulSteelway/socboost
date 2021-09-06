@extends('admin/layouts.app')
@section('title')
    {{ __('Backups') }}
@endsection
@section('breadcrumbs')
    <li> {{ __('Backups') }}</li>
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
                    <h1 class="custom-font">{{ __('Backups') }}</h1>
                    <ul class="controls">
                        <li>
                            <a role="button" href="{{ route('admin.backup.backupDB') }}"
                               style="font-weight: bold;">{{ __('Backup DB') }}
                            </a>
                        </li>
                        <li>
                            <a role="button" href="{{ route('admin.backup.backupFiles') }}"
                               style="font-weight: bold;">{{ __('Backup files') }}
                            </a>
                        </li>
                        <li>
                            <a role="button" href="{{ route('admin.backup.backupAll') }}"
                               style="font-weight: bold;">{{ __('Backup everything') }}
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
                        <table id="backups" class="table hover form-inline dt-bootstrap no-footer">
                            <thead>
                            <tr>
                                <th>{{ __('File') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            @foreach($files as $file)
                                <tbody>
                                <td>
                                    <form action="{{ route('admin.backup.download') }}" method="POST" target="_blank">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="file" value="{{ $file }}">
                                        <input type="submit"
                                               value="{{ \Illuminate\Support\Facades\Storage::url($file) }}"
                                               class="btn btn-default">
                                    </form>
                                </td>
                                <td><a type="button" class="btn btn-danger btn-xs sure"
                                       href="{{ route('admin.backup.destroy', ['file' => $file]) }}">{{ __('destroy file') }}</a>
                                </td>
                                </tbody>
                            @endforeach
                        </table>
                        @push('load-scripts')
                            <script>
                                $('#backups').DataTable();
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
