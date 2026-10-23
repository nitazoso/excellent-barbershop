@props(['number', 'title', 'description', 'price', 'duration'])
<article class="service">
    <span class="service-number">{{ $number }}</span>
    <div>
        <h3>{{ $title }}</h3>
        <p>{{ $description }}</p>
        <div class="service-price">{{ $price }} <span>/ {{ $duration }}</span></div>
    </div>
</article>
