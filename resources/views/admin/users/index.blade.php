@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        Users
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
                                    <input type="text" class="form-control" name="filter[name]" placeholder="Name" value="{{ request('filter.name') }}">
                                </div>

                                <div class="col-lg mb-3">
                                    <input type="text" class="form-control" name="filter[email]" placeholder="Email" value="{{ request('filter.email') }}">
                                </div>

                                <div class="col-lg mb-3 align-middle">
                                    <select class="custom-select" name="filter[role]">
                                        <option value="">Filter by Role</option>
                                        @foreach($filters->roles as $role)
                                            <option value="{{ $role }}" @if(request('filter.role') === $role) selected @endif>{{ ucwords($role) }}</option>
                                        @endforeach
                                    </select>
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
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                    <br>
                                    <small>{{ $user->email }}</small>
                                </td>
                                <td class="text-uppercase align-middle">
                                    @foreach($user->roles as $role)
                                        <h5><span class="badge badge-info">{{ $role->name }}</span></h5>
                                    @endforeach
                                </td>
                                <td class="text-right align-middle">
                                    <a class="btn btn-sm btn-primary" href="{{ route('admin.users.show', $user) }}">
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
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection