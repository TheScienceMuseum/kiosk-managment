@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        Create A User
                    </div>

                    <div class="card-body">
                        <form method="post" action="{{ route('admin.users.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="create-user-name">User Name</label>
                                <input type="text" name="name" class="form-control" id="create-user-name" value="{{ old('name') }}">
                            </div>

                            <div class="form-group">
                                <label for="create-user-email">User Email</label>
                                <input type="email" name="email" class="form-control" id="create-user-email" value="{{ old('email') }}">
                            </div>

                            <div class="form-group">
                                <label for="create-user-roles">User Roles</label>
                                <select class="custom-select" name="roles[]" multiple id="create-user-roles">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ ucwords($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-success" type="submit">
                                    Create
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
