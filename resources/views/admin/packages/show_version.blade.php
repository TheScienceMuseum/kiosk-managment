@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('packages.editing_version') }} <a href="{{ route('admin.packages.show', [$version->package]) }}">{{ $version->package->name }}</a> version {{ $version->version }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.packages.versions.update', [$version->package, $version]) }}" method="post">
                            @csrf
                            @method('put')

                            <div class="form-group">
                                <textarea class="form-control" name="data" rows="20">{{ $version->data }}</textarea>
                            </div>

                            <div class="form-group text-right">
                                @if ($version->status === 'draft')
                                <button class="btn btn-secondary" type="submit" name="status" value="pending">
                                    Submit For Approval
                                </button>
                                @endif

                                @if ($version->status !== 'approved')
                                <button class="btn btn-success" type="submit" name="status" value="draft">
                                    Save @if ($version->status !== 'draft') (will reset version to draft) @endif
                                </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
