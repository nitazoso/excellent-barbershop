@extends('layouts.app')

@section('content')
<div class="status"><div class="status-left"><span class="open-label">CHAIR 01–04 OPEN // SENOPATI, JAKARTA</span><span>TEMPERATUR 21°C — AKUSTIK ANALOG</span></div><span class="status-right">DISIPLIN &amp; PRESISI SEJAK 2018</span></div>
            <section class="hero" aria-labelledby="hero-title">
                    <div class="hero-copy"><span class="edition">EDISI BARBERSHOP SENOPATI</span><h1 id="hero-title">YOUR STYLE.<br>YOUR RULES.</h1><p class="hero-description">Arsitektur pangkas presisi dan ritual botani tanpa kompromi. Kami meninggalkan hiruk-pikuk salon konvensional untuk menghadirkan ketenangan tempa manual di atas marmer travertin dan kayu ek pekat.</p>
                    <div class="hero-buttons"><a class="primary" href="#booking">BOOKING SEKARANG <span aria-hidden="true">→</span></a><a class="text-link" href="#layanan">LIHAT LAYANAN LENGKAP <span aria-hidden="true">↓</span></a></div>
                    <div class="metrics"><div class="metric"><strong>3.000 K</strong><span>SUHU PENCAHAYAAN</span></div><div class="metric"><strong>45–60M</strong><span>ALOKASI PER SESI</span></div><div class="metric"><strong>0% SINTETIK</strong><span>FORMULASI BOTANI</span></div></div>
                </div>
                <figure class="photo-card"><img class="room-photo" src="https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&amp;fit=crop&amp;w=1100&amp;q=85" alt="Interior barbershop hangat dengan kursi barber klasik" fetchpriority="high"><figcaption class="photo-caption"><strong>ARSIP RUANG 08.01</strong><span>Senopati Barbershop — Ruang Temu 02</span></figcaption></figure>
            </section>
            <section class="archive" id="arsip"><div class="section-heading"><div><span class="section-kicker">01 / CATATAN RUANG</span><h2 id="estetika">Bentuk, tekstur, dan waktu.</h2></div><p>Fragmen kecil dari barbershop kami di Senopati.</p></div><div class="archive-grid"><article class="archive-tile"><img src="https://images.unsplash.com/photo-1599351431202-1e0f0137899a?auto=format&amp;fit=crop&amp;w=1100&amp;q=80" alt="Barber menyiapkan peralatan grooming" loading="lazy"><div class="archive-caption"><span>ARSIP 08.02 · GESTUR</span><h3>Sebuah kebiasaan yang baik.</h3></div></article><article class="archive-tile"><img src="https://images.unsplash.com/photo-1621605815971-fbc98d665033?auto=format&amp;fit=crop&amp;w=900&amp;q=80" alt="Detail alat dan kursi di studio barber" loading="lazy"><div class="archive-caption"><span>ARSIP 08.03 · OBJEK</span><h3>Alat yang menemani waktu.</h3></div></article></div></section>
            <section class="location" id="lokasi">
                <div class="location-heading"><span class="section-kicker">[ BAB 02 : KUNJUNGI BARBERSHOP ]</span><h2>STUDIO &amp; RESERVASI</h2><p>Terletak strategis di kawasan asli Senopati, dirancang sebagai suaka dari hiruk-pikuk ritme kota Jakarta.</p></div>
                <div class="location-grid">
                    <div class="location-card">
                        <div class="location-detail"><span class="detail-label"><svg class="detail-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg> ALAMAT STUDIO</span><h3 class="location-address">Jl. Senopati No. 42B,<br>Kebayoran Baru</h3><p class="location-note">Jakarta Selatan 12190 (Lantai Dasar, Sebelah Roastery)</p></div>
                        <div class="location-detail"><span class="detail-label">WAKTU OPERASIONAL</span><div class="hours-row"><span>Senin – Jumat</span><strong>10:00 – 21:00 WIB</strong></div><div class="hours-row"><span>Sabtu – Minggu</span><strong class="weekend">09:00 – 20:00 WIB</strong></div></div>
                        <div class="location-detail"><span class="detail-label">KONTAK DESK</span><div class="contact-row"><span></span><a href="tel:+622155501928">+62 21 5550 1928</a></div></div>
                        <a class="primary" id="booking" href="https://wa.me/6285723915324?text=Halo%20Excellent%20Barbershop%2C%20saya%20ingin%20booking." target="_blank" rel="noopener">BOOKING VIA WHATSAPP <span aria-hidden="true">↗</span></a>
                    </div>
                    <div class="map-card"><iframe class="map-frame" title="Peta lokasi Excellent Barbershop di Senopati" src="https://maps.google.com/maps?q=Jl.%20Senopati%20No.%2042B%2C%20Kebayoran%20Baru%2C%20Jakarta%20Selatan&amp;z=15&amp;output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe><a class="map-directions" href="https://www.google.com/maps/search/?api=1&amp;query=Jl.%20Senopati%20No.%2042B%2C%20Kebayoran%20Baru%2C%20Jakarta%20Selatan" target="_blank" rel="noopener"><span aria-hidden="true">➤</span> PETUNJUK ARAH (GOOGLE MAPS)</a></div>
                </div>
            </section>
@endsection
