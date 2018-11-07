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
                                <form method="post" action="{{ route('admin.kiosk.update', $kiosk) }}">
                                    @csrf
                                    @method('put')

                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="Name" value="{{ $kiosk->name }}">
                                    </div>

                                    <div class="form-group">
                                        <input type="text" name="asset_tag" class="form-control" placeholder="Asset Tag" value="{{ $kiosk->asset_tag }}">
                                    </div>

                                    <div class="form-group">
                                        <input type="text" name="location" class="form-control" placeholder="Location" value="{{ $kiosk->location }}">
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success float-right">
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
