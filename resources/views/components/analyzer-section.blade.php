@props(['id' => 'analyzer-panel', 'maxWidth' => '5xl'])
@php
    $mw = ['5xl' => 'max-w-5xl', '6xl' => 'max-w-6xl', '7xl' => 'max-w-7xl'][$maxWidth] ?? 'max-w-5xl';
@endphp
<section id="{{ $id }}" {{ $attributes->merge(['class' => 'py-10 px-5 sm:px-8']) }}>
    <div class="{{ $mw }} mx-auto">{{ $slot }}</div>
</section>
