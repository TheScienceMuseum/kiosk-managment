@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        {{ __('kiosk.kiosk') }} {{ $kiosk->name ? $kiosk->name : strtoupper($kiosk->identifier) . ' (' . __('kiosk.unregistered') . ')' }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <form method="post" action="{{ route('admin.kiosks.update', $kiosk) }}">
                                    @csrf
                                    @method('put')

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Name</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="name" class="form-control" placeholder="Name" value="{{ $kiosk->name }}">
                                        </div>
                                    </div>

                                    {{--<div class="form-group row">--}}
                                        {{--<label class="col-sm-4 col-form-label">Asset Tag</label>--}}
                                        {{--<div class="col-sm-8">--}}
                                            {{--<input type="text" name="asset_tag" class="form-control" placeholder="Asset Tag" value="{{ $kiosk->asset_tag }}">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Location</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="location" class="form-control" placeholder="Location" value="{{ $kiosk->location }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Allocated Package</label>
                                        <div class="col-sm-8">
                                            <select class="custom-select" name="assigned_package_version">
                                                <option value="" selected>No Package Version Assigned</option>
                                                @forelse($approvedPackageVersions as $package => $packageVersions)
                                                    <optgroup label="{{ $package }}">
                                                        @foreach($packageVersions as $version)
                                                            <option value="{{ $version->id }}"
                                                                    @if($kiosk->assigned_package_version && $kiosk->assigned_package_version->id === $version->id) selected @endif
                                                            >
                                                                {{ $package . ' version ' . $version->version }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @empty
                                                    <option value="" selected disabled>No Approved Versions Available</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Displayed Package</label>
                                        <div class="col-sm-8">
                                            <input type="text"
                                                   class="form-control"
                                                   value="{{ $kiosk->currently_running_package ? $kiosk->currently_running_package : __('none') }} {{ $kiosk->manually_set_at ? '(manually set)' : '' }}"
                                                   disabled
                                            >
                                        </div>
                                    </div>

                                    <div class="form-group text-right">
                                        @if($kiosk->manually_set_at)
                                            <button type="submit" class="btn btn-outline-danger text-dark" name="manually_set" value="">
                                                Override
                                            </button>
                                        @endif
                                        <button type="submit" class="btn btn-outline-success text-dark">
                                            Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="col-6">
                                <div class="card">
                                    @include('widgets.kiosk-logs')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
