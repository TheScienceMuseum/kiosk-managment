@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        User: {{ $user->name }}

                        <form action="{{ route('admin.users.on-board', [$user]) }}" method="post" class="d-none reSendOnBoarding">
                            @csrf
                        </form>

                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-info submitsForm" type="button" data-target="reSendOnBoarding">
                                Reset User
                            </button>
                        </div>
                    </div>

                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
