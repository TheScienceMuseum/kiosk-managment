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

@endswitch
    {{ $status }}
</span>
