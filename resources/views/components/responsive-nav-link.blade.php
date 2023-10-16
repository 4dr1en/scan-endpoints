@props(['active'])

@php
    $classes = $active ?? false ? 'active' : 'unactive';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
