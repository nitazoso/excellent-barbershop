@props(['name', 'role', 'image', 'alt' => ''])
<article class="barber-card">
    <img src="{{ asset($image) }}" alt="{{ $alt ?: $name }}" loading="lazy">
    <div><h3>{{ $name }}</h3><p>{{ $role }}</p></div>
</article>
