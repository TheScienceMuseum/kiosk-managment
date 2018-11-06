@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        Kiosk {{ $kiosk->name ? $kiosk->name : strtoupper($kiosk->identifier) . ' (' . __('unregistered') . ')' }}
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('admin.kiosk.update', $kiosk) }}">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Name">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Asset Tag">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Location">

                                        {{--@if(!empty(\App\Kiosk::allLocations()))--}}
                                        {{--<select class="custom-select">--}}
                                            {{--@foreach(\App\Kiosk::allLocations() as $location)--}}
                                                {{--<option value="{{ $location }}">{{ $location }}</option>--}}
                                            {{--@endforeach--}}
                                        {{--</select>--}}
                                        {{--@endif--}}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <table class="table table-hover m-0">
                                        <tbody>
                                        @foreach($kiosk->logs()->orderBy('timestamp', 'desc')->get() as $log)
                                            <tr>
                                                <td width="30%">
                                                    {{ (new \Carbon\Carbon($log->timestamp))->diffForHumans() }}
                                                    <br>
                                                    <small>{{ (new \Carbon\Carbon($log->timestamp))->toDateTimeString() }}</small>
                                                    <br>
                                                    <small>level: {{ $log->level }}</small>
                                                </td>
                                                <td>{{ $log->message }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
