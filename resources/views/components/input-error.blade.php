@props(['messages'])

@if ($messages)
    <div {{ $attributes }}>
        @foreach ((array) $messages as $message)
            <span class="text-sm text-red-600 !text-red-600 block" style="color: red">{{ $message }}</span>
        @endforeach
    </div>
@endif
