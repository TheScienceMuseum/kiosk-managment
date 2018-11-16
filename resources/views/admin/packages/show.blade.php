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
                            <th scope="row">Version {{ $version->version }}</th>
                            <td>{{ $version->created_at->toRfc7231String() }}</td>
                            <td>
                                @include('widgets.package-version-status-badge', ['status' => $version->status])
                            </td>
                            <td>
                                {{ __('packages.running_on', ['count' => $version->kiosks->count()]) }}
                            </td>
                            <td class="w-25 align-middle text-right">
                                @if ($version->status !== 'draft' && $version->progress < 100)
                                    <div class="progress" title="Progress of package build">
                                        <div class="progress-bar bg-secondary progress-bar-striped progress-bar-animated"
                                             role="progressbar"
                                             aria-valuenow="{{ $version->progress }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100"
                                             style="width: {{ $version->progress }}%;"
                                        ></div>
                                    </div>
                                @else
                                    <form class="d-none" action="{{ route('admin.packages.versions.approve', [$package, $version]) }}" method="post">
                                        @csrf
                                    </form>
                                    <div class="btn-group btn-group-sm">
                                        @if($version->status === 'pending')
                                        <a class="btn btn-success submitsApprovalForm">
                                            {{ __('packages.approve_version') }}
                                        </a>
                                        @endif
                                        @if($version->archive_path_exists)
                                        <a class="btn btn-secondary" href="{{ route('admin.packages.versions.download', [$package, $version]) }}">
                                            {{ __('packages.download_version') }}
                                        </a>
                                        @endif
                                        <a class="btn btn-info" href="{{ route('admin.packages.versions.show', [$package, $version]) }}">
                                            {{ __('packages.view_version') }}
                                        </a>
                                    </div>
                                @endif
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

    <script>

    </script>
@endsection