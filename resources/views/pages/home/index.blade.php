<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Excellent Atelier — Barbershop Senopati, Jakarta</title>
  <meta name="description"
    content="Barbershop premium di Senopati, Jakarta Selatan. Cek jadwal dan booking potong rambut secara online.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@1,500&display=swap"
    rel="stylesheet">
  <style>
    :root {
      --bg: #f6f4ef;
      --surface: #ffffff;
      --ink: #0f1b33;
      --muted: #5d6577;
      --gold: #c39a45;
      --gold-soft: #f3e9d2;
      --line: #e4dfd3;
      --navy: #0f1b33;
      --radius: 14px;
      --maxw: 1120px;
      --font: 'Plus Jakarta Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
      --serif: 'Playfair Display', Georgia, serif;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    html {
      scroll-behavior: smooth
    }

    body {
      font-family: var(--font);
      background: var(--bg);
      color: var(--ink);
      line-height: 1.6;
      -webkit-font-smoothing: antialiased
    }

    img,
    svg {
      display: block;
      max-width: 100%
    }

    a {
      color: inherit;
      text-decoration: none
    }

    :focus-visible {
      outline: 2px solid var(--gold);
      outline-offset: 3px
    }

    .wrap {
      width: min(100% - 2.5rem, var(--maxw));
      margin-inline: auto
    }

    section {
      padding: 3.5rem 0
    }

    .tag {
      display: inline-block;
      font-size: .68rem;
      font-weight: 600;
      letter-spacing: .08em;
      color: #8a6a24;
      background: var(--gold-soft);
      border: 1px solid #e3d2a6;
      border-radius: 6px;
      padding: .2rem .55rem
    }

    /* nav */
    .nav {
      position: sticky;
      top: 0;
      z-index: 20;
      background: rgba(246, 244, 239, .9);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid var(--line)
    }

    .nav .wrap {
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 60px
    }

    .brand {
      font-weight: 800;
      letter-spacing: .14em;
      font-size: .82rem;
      display: flex;
      align-items: center;
      gap: .4rem
    }

    .brand i {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: var(--gold)
    }

    .nav-links {
      display: none;
      gap: 2rem;
      font-size: .88rem;
      font-weight: 500;
      color: var(--muted)
    }

    .nav-links a:hover {
      color: var(--ink)
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: .5rem;
      font: 600 .9rem var(--font);
      border: 0;
      border-radius: 10px;
      padding: .8rem 1.3rem;
      cursor: pointer;
      transition: transform .15s, background .2s
    }

    .btn:active {
      transform: scale(.98)
    }

    .btn-navy {
      background: var(--navy);
      color: #fff
    }

    .btn-navy:hover {
      background: #1c2c52
    }

    .btn-gold {
      background: var(--gold);
      color: #fff
    }

    .btn-gold:hover {
      background: #b48a37
    }

    .btn-sm {
      padding: .55rem 1rem;
      font-size: .8rem
    }

    .btn-block {
      width: 100%
    }

    /* hero */
    .hero {
      padding: 1.5rem 0 3rem
    }

    .hero .wrap {
      display: grid;
      gap: 1.75rem
    }

    .pill {
      display: flex;
      align-items: center;
      gap: .7rem;
      background: var(--surface);
      border: 1px solid var(--gold);
      border-radius: 12px;
      padding: .6rem .9rem;
      font-size: .78rem
    }

    .pill .dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--gold);
      flex: none;
      box-shadow: 0 0 0 4px var(--gold-soft)
    }

    .pill b {
      display: block;
      font-size: .78rem
    }

    .pill span {
      color: var(--muted);
      font-size: .72rem
    }

    h1 {
      font-size: clamp(2.5rem, 9vw, 4.4rem);
      line-height: 1;
      font-weight: 800;
      letter-spacing: -.03em;
      text-transform: uppercase
    }

    h1 em {
      display: block;
      font: italic 500 clamp(2.3rem, 8.5vw, 4rem)/1.05 var(--serif);
      color: var(--gold);
      text-transform: uppercase;
      letter-spacing: 0
    }

    .lead {
      color: var(--muted);
      max-width: 46ch;
      margin-top: 1rem
    }

    .search {
      background: var(--surface);
      border: 1px solid var(--line);
      border-radius: var(--radius);
      padding: 1rem;
      display: grid;
      gap: .7rem;
      margin-top: 1.5rem;
      box-shadow: 0 10px 30px -18px rgba(15, 27, 51, .25)
    }

    .search h2 {
      font-size: .68rem;
      letter-spacing: .1em;
      color: #8a6a24;
      font-weight: 700
    }

    .field {
      display: flex;
      align-items: center;
      gap: .6rem;
      border: 1px solid var(--line);
      border-radius: 10px;
      padding: .65rem .8rem;
      background: #fff
    }

    .field svg {
      width: 16px;
      height: 16px;
      color: var(--gold);
      flex: none
    }

    .field input,
    .field select {
      border: 0;
      outline: 0;
      width: 100%;
      font: 500 .85rem var(--font);
      color: var(--ink);
      background: transparent
    }

    .field:focus-within {
      border-color: var(--gold)
    }

    .row2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: .7rem
    }

    .photo {
      border-radius: 20px;
      overflow: hidden;
      position: relative;
      min-height: 340px;
      background: linear-gradient(160deg, #d9d2c3 0%, #a99d86 55%, #4a4338 100%)
    }

    .photo.has-img {
      background-size: cover;
      background-position: center
    }

    .arch {
      position: absolute;
      left: 50%;
      bottom: 0;
      transform: translateX(-50%);
      width: 62%;
      height: 78%;
      border-radius: 999px 999px 0 0;
      background: linear-gradient(#fff6e0, #efe6d2);
      opacity: .85
    }

    .photo::after {
      content: "Ganti dengan foto interior barbershop";
      position: absolute;
      inset: auto 0 .8rem;
      text-align: center;
      font-size: .7rem;
      color: rgba(255, 255, 255, .75)
    }

    .cta-wide {
      margin-top: 1rem
    }

    /* sec 01 */
    .sec01 .wrap {
      display: grid;
      gap: 2rem
    }

    blockquote {
      border-left: 4px solid var(--gold);
      padding-left: 1rem;
      font-size: clamp(1.4rem, 4.6vw, 2.2rem);
      font-weight: 800;
      line-height: 1.2;
      letter-spacing: -.02em;
      text-transform: uppercase;
      margin: 1rem 0
    }

    .sec01 p {
      color: var(--muted);
      max-width: 52ch
    }

    .figure {
      border-radius: 16px;
      overflow: hidden;
      background: var(--surface);
      border: 1px solid var(--line)
    }

    .figure .photo {
      border-radius: 0;
      min-height: 230px;
      background: linear-gradient(140deg, #5a4a38, #2a2118 60%, #8c6d44)
    }

    .figure .photo::after {
      content: "Ganti dengan foto alat cukur"
    }

    .figure figcaption {
      display: flex;
      justify-content: space-between;
      padding: .8rem 1rem;
      font-size: .7rem;
      letter-spacing: .06em;
      font-weight: 600
    }

    .figure figcaption span:first-child {
      color: #8a6a24
    }

    .figure figcaption span:last-child {
      color: var(--muted)
    }

    /* lokasi */
    .sec-head {
      display: grid;
      gap: .6rem;
      margin-bottom: 1.5rem
    }

    h2.title {
      font-size: clamp(1.8rem, 6vw, 2.7rem);
      font-weight: 800;
      line-height: 1.05;
      letter-spacing: -.025em;
      text-transform: uppercase
    }

    .sec-head p {
      color: var(--muted);
      max-width: 52ch;
      font-size: .92rem
    }

    .loc {
      display: grid;
      gap: 1.25rem
    }

    .card {
      background: var(--surface);
      border: 1px solid var(--line);
      border-radius: var(--radius);
      padding: 1.2rem
    }

    .studio {
      display: flex;
      align-items: center;
      gap: .5rem;
      font-size: .72rem;
      font-weight: 700;
      letter-spacing: .08em;
      color: #8a6a24;
      margin-bottom: .8rem
    }

    .studio svg {
      width: 16px;
      height: 16px
    }

    .addr strong {
      font-size: 1.05rem;
      display: block
    }

    .addr span {
      color: var(--muted);
      font-size: .82rem
    }

    .hours {
      margin-top: 1.1rem
    }

    .hours h3,
    .kontak h3 {
      font-size: .68rem;
      letter-spacing: .1em;
      color: #8a6a24;
      font-weight: 700;
      margin-bottom: .5rem
    }

    .hours li {
      list-style: none;
      display: flex;
      justify-content: space-between;
      gap: 1rem;
      font-size: .85rem;
      padding: .55rem .8rem;
      border-radius: 8px
    }

    .hours li+li {
      margin-top: .35rem
    }

    .hours li:first-of-type {
      background: #f4f1ea
    }

    .hours li.break {
      background: var(--gold-soft)
    }

    .hours li b {
      white-space: nowrap
    }

    .kontak {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 1rem;
      padding-top: 1rem;
      border-top: 1px solid var(--line);
      font-size: .85rem
    }

    .kontak h3 {
      margin: 0
    }

    .kontak b {
      font-weight: 700
    }

    .map {
      position: relative;
      border-radius: var(--radius);
      overflow: hidden;
      border: 1px solid var(--line);
      min-height: 240px;
      background:
        repeating-linear-gradient(35deg, transparent 0 38px, rgba(15, 27, 51, .07) 38px 40px),
        repeating-linear-gradient(-55deg, transparent 0 52px, rgba(15, 27, 51, .06) 52px 54px), #e4e8ea
    }

    .map .badge {
      position: absolute;
      left: .8rem;
      bottom: .8rem;
      background: #fff;
      border: 1px solid var(--line);
      border-radius: 8px;
      padding: .4rem .7rem;
      font-size: .68rem;
      font-weight: 700;
      letter-spacing: .05em;
      display: flex;
      gap: .4rem;
      align-items: center
    }

    .map .badge i {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--gold)
    }

    .map iframe {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      border: 0
    }

    .dir {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .5rem;
      margin-top: .8rem;
      border: 1px solid var(--line);
      background: #fff;
      border-radius: 10px;
      padding: .75rem;
      font-size: .78rem;
      font-weight: 700;
      letter-spacing: .05em;
      color: #8a6a24;
      transition: background .2s
    }

    .dir:hover {
      background: var(--gold-soft)
    }

    /* ulasan */
    .rate {
      display: flex;
      align-items: center;
      gap: .5rem;
      font-size: .8rem;
      font-weight: 700
    }

    .stars {
      color: var(--gold);
      letter-spacing: .1em
    }

    .reviews {
      display: grid;
      gap: 1rem
    }

    .rev .top {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: .7rem;
      color: var(--muted)
    }

    .rev p {
      font-style: italic;
      font-size: .9rem;
      margin: .7rem 0 1rem
    }

    .who {
      display: flex;
      align-items: center;
      gap: .7rem
    }

    .av {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: var(--navy);
      color: #fff;
      font-size: .75rem;
      font-weight: 700;
      display: grid;
      place-items: center;
      flex: none
    }

    .who b {
      display: block;
      font-size: .82rem;
      line-height: 1.2
    }

    .who small {
      color: var(--muted);
      font-size: .7rem
    }

    .chip {
      margin-left: auto;
      font-size: .62rem;
      font-weight: 700;
      letter-spacing: .06em;
      color: #8a6a24;
      background: var(--gold-soft);
      border-radius: 6px;
      padding: .25rem .5rem;
      white-space: nowrap
    }

    /* footer */
    footer {
      background: var(--navy);
      color: #cfd5e3;
      padding: 3rem 0 2rem;
      margin-top: 2rem
    }

    .foot {
      display: grid;
      gap: 2rem
    }

    footer .brand {
      color: #fff
    }

    footer p {
      font-size: .82rem;
      max-width: 42ch;
      margin-top: .8rem;
      color: #9aa3b8
    }

    footer h4 {
      font-size: .68rem;
      letter-spacing: .1em;
      color: var(--gold);
      margin-bottom: .8rem
    }

    footer ul {
      list-style: none;
      display: grid;
      gap: .5rem;
      font-size: .85rem
    }

    footer ul a:hover {
      color: #fff
    }

    .cols {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem
    }

    .copy {
      border-top: 1px solid rgba(255, 255, 255, .12);
      margin-top: 2rem;
      padding-top: 1.2rem;
      font-size: .7rem;
      color: #7d879e
    }

    /* tablet & desktop */
    @media(min-width:760px) {
      section {
        padding: 5rem 0
      }

      .nav-links {
        display: flex
      }

      .hero {
        padding: 3rem 0 4rem
      }

      .hero .wrap {
        grid-template-columns: 1.05fr .95fr;
        align-items: center;
        gap: 3.5rem
      }

      .hero .photo {
        min-height: 560px
      }

      .hero-copy .btn-block {
        display: none
      }

      .sec01 .wrap {
        grid-template-columns: 1fr 1fr;
        align-items: center;
        gap: 4rem
      }

      .figure .photo {
        min-height: 340px
      }

      .loc {
        grid-template-columns: 1fr 1fr;
        align-items: stretch
      }

      .map-col {
        display: flex;
        flex-direction: column
      }

      .map {
        flex: 1
      }

      .reviews {
        grid-template-columns: repeat(3, 1fr)
      }

      .sec-head.split {
        grid-template-columns: 1fr auto;
        align-items: end
      }

      .foot {
        grid-template-columns: 1.4fr 1fr
      }
    }

    @media(max-width:759px) {
      .hero-copy .cta-top {
        display: none
      }
    }

    /* ===== Transisi ===== */
    :root {
      --ease: cubic-bezier(.22, .7, .2, 1)
    }

    /* 1. Hero: muncul berurutan saat halaman dibuka (sekali saja) */
    @keyframes rise {
      from {
        opacity: 0;
        transform: translateY(22px)
      }

      to {
        opacity: 1;
        transform: none
      }
    }

    @keyframes arch-up {
      from {
        transform: translate(-50%, 40px);
        opacity: 0
      }

      to {
        transform: translate(-50%, 0);
        opacity: .85
      }
    }

    .hero-copy>*,
    .hero-copy h1+.lead {
      animation: rise .9s var(--ease) backwards
    }

    .hero-copy .pill {
      animation-delay: .05s
    }

    .hero-copy h1 {
      animation-delay: .15s
    }

    .hero-copy .lead {
      animation-delay: .28s
    }

    .hero-copy .search {
      animation-delay: .4s
    }

    .hero .photo {
      animation: rise 1.1s var(--ease) .25s backwards
    }

    .arch {
      animation: arch-up 1.4s var(--ease) .5s backwards
    }

    /* 2. Scroll reveal (aktif hanya jika JS jalan) */
    .js [data-reveal] {
      opacity: 0;
      transform: translateY(26px);
      transition: opacity .9s var(--ease), transform .9s var(--ease)
    }

    .js [data-reveal].is-in {
      opacity: 1;
      transform: none
    }

    .js .card[data-reveal].is-in:hover {
      transform: translateY(-4px)
    }

    /* 3. Hover & fokus yang halus */
    .btn {
      transition: transform .25s var(--ease), background-color .25s, box-shadow .25s
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 22px -10px rgba(15, 27, 51, .45)
    }

    .btn:active {
      transform: translateY(0) scale(.98)
    }

    .btn span {
      display: inline-block;
      transition: transform .25s var(--ease)
    }

    .btn:hover span {
      transform: translateX(5px)
    }

    .card {
      transition: transform .35s var(--ease), box-shadow .35s var(--ease), border-color .35s
    }

    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 18px 34px -22px rgba(15, 27, 51, .4);
      border-color: #d9c79a
    }

    .figure .photo {
      transition: transform 1.2s var(--ease)
    }

    .figure:hover .photo {
      transform: scale(1.05)
    }

    .dir {
      transition: background-color .25s, transform .25s var(--ease)
    }

    .dir:hover {
      transform: translateY(-2px)
    }

    .nav-links a,
    footer ul a {
      position: relative;
      transition: color .25s
    }

    .nav-links a::after {
      content: "";
      position: absolute;
      left: 0;
      right: 0;
      bottom: -4px;
      height: 2px;
      background: var(--gold);
      transform: scaleX(0);
      transform-origin: left;
      transition: transform .35s var(--ease)
    }

    .nav-links a:hover::after {
      transform: scaleX(1)
    }

    .field {
      transition: border-color .25s, box-shadow .25s
    }

    .field:focus-within {
      box-shadow: 0 0 0 4px var(--gold-soft)
    }

    .nav {
      transition: box-shadow .3s
    }

    .nav.scrolled {
      box-shadow: 0 8px 24px -16px rgba(15, 27, 51, .35)
    }

    @media(prefers-reduced-motion:reduce) {

      *,
      *::before,
      *::after {
        animation: none !important;
        transition: none !important;
        scroll-behavior: auto !important
      }

      .js [data-reveal] {
        opacity: 1;
        transform: none
      }
    }
  </style>
</head>

<body>

  <header class="nav">
    <div class="wrap">
      <a href="#" class="brand"><i></i>EXCELLENT</a>
      <nav class="nav-links" aria-label="Navigasi utama">
        <a href="#tentang">Tentang</a><a href="#lokasi">Lokasi</a><a href="#ulasan">Ulasan</a>
      </nav>
      <a href="#kontak" class="btn btn-navy btn-sm">Kontak</a>
    </div>
  </header>

  <main>
    <!-- HERO -->
    <div class="hero">
      <div class="wrap">
        <div class="hero-copy">
          <div class="pill">
            <span class="dot"></span>
            <div><b>Buka hari ini: 15:00 – 21:30</b><span>Tersedia slot kosong sore ini</span></div>
          </div>
          <h1 style="margin-top:1.5rem">Potongan rapi.<em>Tanpa antre.</em></h1>
          <p class="lead">Pilih jadwal, datang tepat waktu, dan serahkan rambutmu pada barber berpengalaman
            kami di Senopati.</p>

          <form class="search"
            onsubmit="event.preventDefault();document.getElementById('booking-msg').hidden=false">
            <h2>CARI JADWAL</h2>
            <label class="field">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="7" />
                <path d="m20 20-3.5-3.5" />
              </svg>
              <input type="text" placeholder="Layanan atau nama barber"
                aria-label="Layanan atau nama barber">
            </label>
            <div class="row2">
              <label class="field">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="5" width="18" height="16" rx="2" />
                  <path d="M3 10h18M8 3v4M16 3v4" />
                </svg>
                <input type="date" aria-label="Tanggal">
              </label>
              <label class="field">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="9" />
                  <path d="M12 7v5l3 2" />
                </svg>
                <input type="time" value="15:00" aria-label="Waktu">
              </label>
            </div>
            <button class="btn btn-gold btn-block" type="submit">Cari booking</button>
            <p id="booking-msg" hidden style="font-size:.78rem;color:var(--muted)">Sambungkan formulir ini
              ke sistem booking atau WhatsApp klien.</p>
          </form>
        </div>

        <div>
          <div class="photo" role="img" aria-label="Interior barbershop">
            <div class="arch"></div>
          </div>
          <a href="{{ route('booking.index') }}" class="btn btn-navy btn-block cta-wide"
            style="justify-content:space-between">Booking sekarang <span>→</span></a>
        </div>
      </div>
    </div>

    <!-- SEC 01 -->
    <section class="sec01" id="tentang">
      <div class="wrap">
        <div>
          <span class="tag">TENTANG KAMI</span>
          <blockquote>Rambut rapi dimulai dari tangan yang teliti.</blockquote>
          <p>Setiap kunjungan dimulai dengan konsultasi singkat, dilanjutkan potongan presisi dan finishing
            handuk hangat. Kami tidak buru-buru, karena hasil yang baik butuh waktu yang cukup.</p>
        </div>
        <figure class="figure">
          <div class="photo" role="img" aria-label="Peralatan cukur"></div>
          <figcaption><span>PERALATAN PILIHAN</span><span>SEJAK 2019</span></figcaption>
        </figure>
      </div>
    </section>

    <!-- LOKASI -->
    <section id="lokasi" style="padding-top:1rem">
      <div class="wrap">
        <div class="sec-head">
          <span class="tag" style="justify-self:start">SEC 02</span>
          <h2 class="title">Lokasi &amp; kontak kami</h2>
          <p>Datang langsung ke studio atau hubungi meja layanan untuk atur jadwal.</p>
        </div>
        <div class="loc">
          <div class="card" id="kontak">
            <div class="studio">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z" />
                <circle cx="12" cy="10" r="2.5" />
              </svg>
              STUDIO UTAMA
            </div>
            <div class="addr">
              <strong>Jl. Senopati Raya No. 42B, Kebayoran Baru</strong>
              <span>Jakarta Selatan 12190</span>
            </div>
            <div class="hours">
              <h3>JAM OPERASIONAL</h3>
              <ul>
                <li>Layanan setiap hari <b>15:00 – 21:30 WIB</b></li>
                <li class="break">Jeda istirahat karyawan <b>18:00 – 19:30 WIB</b></li>
              </ul>
            </div>
            <div class="kontak">
              <h3>KONTAK DESK</h3>
              <b>+62 21 5550 1928</b>
            </div>
          </div>

          <div class="map-col">
            <div class="map" role="img" aria-label="Peta lokasi studio">
              <!-- Ganti dengan <iframe src="URL embed Google Maps"> -->
              <span class="badge"><i></i>EXCELLENT ATELIER • SENOPATI</span>
            </div>
            <a class="dir"
              href="https://www.google.com/maps/search/?api=1&query=Jl.+Senopati+Raya+42B+Jakarta"
              target="_blank" rel="noopener">⌖ TUNJUK ARAH (GOOGLE MAPS)</a>
          </div>
        </div>
      </div>
    </section>

    <!-- ULASAN -->
    <section id="ulasan">
      <div class="wrap">
        <div class="sec-head">
          <span class="tag" style="justify-self:start">ULASAN TAMU</span>
          <h2 class="title">Kata mereka tentang kami</h2>
          <div class="rate"><span class="stars">★★★★★</span> 5.0 / 5.0</div>
        </div>
        <div class="reviews">
          <article class="card rev">
            <div class="top"><span class="stars">★★★★★</span><span>2 hari lalu</span></div>
            <p>“Potongannya rapi dan sesuai bentuk wajah. Barbernya sabar menjelaskan sebelum mulai.”</p>
            <div class="who">
              <div class="av">LS</div>
              <div><b>Lorem Smith</b><small>Pelanggan tervertifikasi</small></div><span
                class="chip">HAIRCUT</span>
            </div>
          </article>
          <article class="card rev">
            <div class="top"><span class="stars">★★★★★</span><span>1 minggu lalu</span></div>
            <p>“Suasananya nyaman, antrean tertib, dan hasil cukur janggutnya bersih. Pasti balik lagi.”</p>
            <div class="who">
              <div class="av">DA</div>
              <div><b>Dolor Amet</b><small>Pelanggan tervertifikasi</small></div><span class="chip">HAIR +
                BEARD</span>
            </div>
          </article>
          <article class="card rev">
            <div class="top"><span class="stars">★★★★★</span><span>2 minggu lalu</span></div>
            <p>“Booking lewat web gampang, datang langsung dilayani. Finishing styling-nya tahan seharian.”
            </p>
            <div class="who">
              <div class="av">AE</div>
              <div><b>Adipiscing Elit</b><small>Pelanggan tervertifikasi</small></div><span
                class="chip">HAIRCUT &amp; STYLING</span>
            </div>
          </article>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <div class="wrap">
      <div class="foot">
        <div>
          <div class="brand"><i></i>EXCELLENT</div>
          <p>Barbershop premium di Senopati. Potongan presisi, suasana tenang, dan layanan tanpa terburu-buru.
          </p>
        </div>
        <div class="cols">
          <div>
            <h4>DIREKTORI</h4>
            <ul>
              <li><a href="#lokasi">Daftar layanan</a></li>
              <li><a href="#kontak">Reservasi klien</a></li>
              <li><a href="#tentang">Etika &amp; kebijakan studio</a></li>
            </ul>
          </div>
          <div>
            <h4>PUBLIKASI</h4>
            <ul>
              <li><a href="#">Instagram</a></li>
              <li><a href="#">Substack One</a></li>
              <li><a href="#">Spotify Studio Playlist</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="copy">© 2021–2026 Excellent Atelier. Seluruh hak cipta dilindungi.</div>
    </div>
  </footer>

  <script>
    (function() {
      var root = document.documentElement;
      root.classList.add('js');
      var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

      // Elemen yang muncul saat di-scroll (stagger untuk grup)
      var groups = [
        '.sec01 .wrap > *',
        '#lokasi .sec-head > *', '#lokasi .loc > *',
        '#ulasan .sec-head > *', '#ulasan .rev',
        'footer .foot > *'
      ];
      var els = [];
      groups.forEach(function(sel) {
        document.querySelectorAll(sel).forEach(function(el, i) {
          el.setAttribute('data-reveal', '');
          el.style.transitionDelay = (i * 90) + 'ms';
          els.push(el);
        });
      });

      // true  = animasi diulang setiap elemen masuk/keluar layar (naik & turun)
      // false = animasi hanya sekali, saat pertama kali terlihat
      var REPLAY = true;

      function show(el) {
        el.classList.add('is-in');
        if (!REPLAY) {
          // lepas atribut setelah selesai agar efek hover kartu tidak terganggu
          setTimeout(function() {
            el.removeAttribute('data-reveal');
            el.style.transitionDelay = '';
          }, 1400);
        }
      }

      if (reduce || !('IntersectionObserver' in window)) {
        els.forEach(show);
      } else {
        var io = new IntersectionObserver(function(entries) {
          entries.forEach(function(e) {
            if (e.isIntersecting) {
              show(e.target);
              if (!REPLAY) io.unobserve(e.target);
            } else if (REPLAY) {
              e.target.classList.remove('is-in');
            }
          });
        }, {
          threshold: .15,
          rootMargin: '0px 0px -40px 0px'
        });
        els.forEach(function(el) {
          io.observe(el);
        });
      }

      // Bayangan navbar saat halaman di-scroll
      var nav = document.querySelector('.nav');

      function onScroll() {
        nav.classList.toggle('scrolled', window.scrollY > 8);
      }
      window.addEventListener('scroll', onScroll, {
        passive: true
      });
      onScroll();
    })();
  </script>
</body>

</html>
