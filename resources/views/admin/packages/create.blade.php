@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        {{ __('packages.create') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.packages.store') }}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Package Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" class="form-control" placeholder="ValidKioskPackageName">
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-outline-success text-dark">
                                    Create Package
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection