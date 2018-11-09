@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('packages.editing') }} {{ $package->name }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.packages.update', [$package]) }}" method="post">
                            @csrf
                            @method('put')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection