@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600']) }}>
        <p style="color: green;">{{ $status }}</p>
    </div>

@endif
