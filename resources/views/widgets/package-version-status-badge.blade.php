@switch($status)
    @case('approved')
    <span class="badge badge-success">
    @break

    @case('pending')
        <span class="badge badge-warning">
    @break

    @case('draft')
        <span class="badge badge-secondary">
    @break

    @case('failed')
        <span class="badge badge-danger">
    @break

@endswitch
    {{ $status }}
</span>
