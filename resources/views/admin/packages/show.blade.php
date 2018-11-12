@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        {{ __('packages.editing') }}: {{ $package->name }}
                        <form action="{{ route('admin.packages.versions.store', [$package]) }}" method="post">
                            @csrf
                            <button class="btn btn-sm btn-success" type="submit">
                                {{ __('packages.create_new_versions') }}
                            </button>
                        </form>
                    </div>
                    <table class="table mb-0">
                        <tbody>
                        @forelse($package->versions->sortByDesc('version') as $version)
                        <tr>
                            <td>Version {{ $version->version }}</td>
                            <td>{{ $version->created_at->toRfc7231String() }}</td>
                            <td>
                                @include('widgets.package-version-status-badge', ['status' => $version->status])
                            </td>
                            <td>
                                {{ __('packages.running_on', ['count' => $version->kiosks->count()]) }}
                            </td>
                            <td class="text-right">
                                <a class="btn btn-sm btn-info" href="{{ route('admin.packages.versions.show', [$package, $version]) }}">
                                    {{ __('packages.view_version') }}
                                </a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td class="text-center">
                                    {{ __('packages.no_versions') }}
                                    <form action="{{ route('admin.packages.versions.store', [$package]) }}" method="post">
                                        @csrf
                                        <button class="btn btn-link" type="submit">
                                            {{ __('packages.create_new_versions') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection