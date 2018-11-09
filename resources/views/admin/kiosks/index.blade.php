@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        Kiosks
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#collapsible-filters">
                                Filters
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse @if(!empty(request('filter'))) show @endif" id="collapsible-filters">
                        <form>
                            <div class="form-group row">
                                <div class="col-lg mb-3">
                                    <input type="text" class="form-control" name="filter[name]" placeholder="Kiosk Name" value="{{ request('filter.name') }}">
                                </div>

                                <div class="col-lg mb-3">
                                    <select class="custom-select" name="filter[location]">
                                        <option @if(!request('filter.location')) selected @endif value="">Kiosk Location</option>
                                        @foreach($filters->location as $location)
                                            <option value="{{ $location }}" @if(request('filter.location') === $location) selected @endif>{{ $location }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg mb-3 align-middle">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="filter-registered-true" name="filter[registered]" class="custom-control-input" value="true" @if(request('filter.registered') === 'true') checked @endif>
                                        <label class="custom-control-label" for="filter-registered-true">Registered</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="filter-registered-false" name="filter[registered]" class="custom-control-input" value="false" @if(request('filter.registered') === 'false') checked @endif>
                                        <label class="custom-control-label" for="filter-registered-false">Unregistered</label>
                                    </div>
                                </div>
                            </div>

                            <div class="btn-group btn-group-sm float-right">
                                <button class="btn btn-warning text-dark" type="reset">
                                    Reset Filters
                                </button>
                                <button class="btn btn-dark" type="submit">
                                    Apply Filters
                                </button>
                            </div>
                        </form>
                    </div>
                    <table class="table table-hover mb-0">
                        <tbody>
                        @forelse($kiosks as $kiosk)
                        <tr>
                            <td class="align-middle">
                                @if($kiosk->name)
                                    <strong>{{ $kiosk->name }}</strong>
                                @else
                                    <strong class="text-muted">{{ $kiosk->identifier }} <small>(unregistered)</small></strong>
                                @endif
                                <br>
                                <small>Identity: {{ $kiosk->identifier }}</small>
                                <br>
                                <small>Last Seen: {{ $kiosk->last_seen_at ? $kiosk->last_seen_at->diffForHumans() : __('never') }}</small>
                            </td>
                            <td class="align-middle">
                                <strong>Running:</strong> {{ $kiosk->current_package ? $kiosk->current_package : __('none') }}<br>
                                <strong>Assigned:</strong> {{ $kiosk->assigned_package_version ? $kiosk->assigned_package_version->package->name . '@' . $kiosk->assigned_package_version->version : __('none') }}
                            </td>
                            <td class="text-right align-middle">
                                <div class="btn-group-vertical">
                                    <a class="btn btn-sm btn-primary" href="{{ route('admin.kiosks.show', [$kiosk]) }}">
                                        Edit Kiosk
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td>There are no kiosks found based on your filters</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="card-footer">
                        {{ $kiosks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
