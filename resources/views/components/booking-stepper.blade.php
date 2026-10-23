@props(['current' => 1])
<ol class="booking-stepper" aria-label="Tahapan booking">
    @foreach (['Pilih layanan', 'Pilih barber', 'Pilih jadwal'] as $index => $label)
        @php($step = $index + 1)
        <li class="{{ $step === (int) $current ? 'is-current' : ($step < (int) $current ? 'is-complete' : '') }}" aria-current="{{ $step === (int) $current ? 'step' : 'false' }}">
            <span>{{ $step }}</span>{{ $label }}
        </li>
    @endforeach
</ol>
