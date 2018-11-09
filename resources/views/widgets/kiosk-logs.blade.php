<table class="table table-hover m-0">
    <tbody>
    @foreach($kiosk->logs()->orderBy('timestamp', 'desc')->limit(20)->get() as $log)
        <tr @if($log->level === 'error') class="bg-danger" @endif>
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