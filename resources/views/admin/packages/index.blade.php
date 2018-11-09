@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        Packages
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#collapsible-filters">
                                Filters
                            </button>
                            <a class="btn btn-success" href="{{ route('admin.packages.create') }}">
                                Create
                            </a>
                        </div>
                    </div>
                    <div class="card-body collapse @if(!empty(request('filter'))) show @endif" id="collapsible-filters">
                        <form>
                            <div class="form-group row">
                                <div class="col-lg mb-3">
                                    <input type="text" class="form-control" name="filter[name]" placeholder="Kiosk Name" value="{{ request('filter.name') }}">
                                </div>

                                <div class="col-lg mb-3">

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
                        @forelse($packages as $package)
                            <tr>
                                <td>
                                    <strong>{{ $package->name }}</strong>
                                    <br>
                                    <small>Running on {{ count($package->kiosks) }} kiosks</small>
                                </td>
                                <td>

                                </td>
                                <td class="text-right align-middle">
                                    <a class="btn btn-sm btn-primary" href="{{ route('admin.packages.show', $package) }}">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>There are no packages found based on your filters</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="card-footer">
                        {{ $packages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
