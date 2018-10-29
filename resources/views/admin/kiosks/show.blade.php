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
                    <div class="card-body @if(empty(request('filter'))) collapse @endif" id="collapsible-filters">
                        <form>
                            <div class="form-group row">
                                <div class="col">
                                    <input type="text" class="form-control form-control-lg" name="filter[name]" placeholder="Kiosk Name" value="{{ request('filter.name') }}">
                                </div>

                                <div class="col">
                                    <select class="custom-select custom-select-lg" name="filter[location]">
                                        <option @if(!request('filter.location')) selected @endif value="">Kiosk Location</option>
                                        @foreach($filters->location as $location)
                                            <option value="{{ $location }}" @if(request('filter.location') === $location) selected @endif>{{ $location }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="filter-registered-true" name="filter[registered]" class="custom-control-input" value="true" @if(request('filter.registered') === 'true') checked @endif>
                                        <label class="custom-control-label" for="filter-registered-true">Registered Kiosks</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="filter-registered-false" name="filter[registered]" class="custom-control-input" value="false" @if(request('filter.registered') === 'false') checked @endif>
                                        <label class="custom-control-label" for="filter-registered-false">Unregistered Kiosks</label>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-dark btn-block" type="submit">
                                Apply Filters
                            </button>
                        </form>
                    </div>
                    <table class="table table-hover mb-0">
                        <tbody>
                        @forelse($kiosks as $kiosk)
                        <tr>
                            <td>
                                @if($kiosk->name)
                                    <strong>{{ $kiosk->name }}</strong>
                                @else
                                    <strong class="text-muted">Unregistered</strong>
                                @endif
                                <br>
                                <small>Identity: {{ $kiosk->identifier }}</small>
                            </td>
                            <td>
                                <strong>Current Package:</strong> {{ $kiosk->current_package ? $kiosk->current_package : 'No reported package' }}<br>
                                <strong>Assigned Package:</strong> {{ $kiosk->package ? $kiosk->package->name . ' with version ' . $kiosk->package->current_version->version : 'None' }}
                            </td>
                            <td class="text-right align-middle">
                                <a class="btn btn-sm btn-primary" href="{{ route('admin.kiosk.show', $kiosk) }}">
                                    View
                                </a>
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
