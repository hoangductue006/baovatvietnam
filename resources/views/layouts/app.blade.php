<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Bảo Vật Việt Nam') · Hành trình khám phá di sản</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @stack('head')
</head>
<body>
<header class="topbar">
  <a class="brand" href="{{ route('home') }}"><span class="brand-mark">BV</span><span>BẢO VẬT <b>VIỆT NAM</b><small>HÀNH TRÌNH KHÁM PHÁ DI SẢN</small></span></a>
  <button class="menu-toggle" aria-label="Mở menu" onclick="document.querySelector('.nav').classList.toggle('open')">☰</button>
  <nav class="nav">
    <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Trang chủ</a><a class="{{ request()->routeIs('treasures','treasure.show') ? 'active' : '' }}" href="{{ route('treasures') }}">Bảo vật</a>
    <a class="{{ request()->routeIs('learning') ? 'active' : '' }}" href="{{ route('learning') }}">Học tập</a><a class="{{ request()->routeIs('minigames') ? 'active' : '' }}" href="{{ route('minigames') }}">Mini game</a><a class="{{ request()->routeIs('quiz') ? 'active' : '' }}" href="{{ route('quiz') }}">Thử thách</a><a class="{{ request()->routeIs('flashcards') ? 'active' : '' }}" href="{{ route('flashcards') }}">Thẻ ghi nhớ</a>
    <a class="{{ request()->routeIs('scores') ? 'active' : '' }}" href="{{ route('scores') }}">Bảng điểm</a><a class="{{ request()->routeIs('sources') ? 'active' : '' }}" href="{{ route('sources') }}">Nguồn tư liệu</a><a class="{{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Giới thiệu</a>
    @auth
      @if(auth()->user()->is_admin)<a href="{{ route('admin.dashboard') }}">Quản trị</a>@endif
      <form class="nav-logout" method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Đăng xuất</button></form>
    @else
      <a href="{{ route('login') }}">Đăng nhập</a>
    @endauth
  </nav>
</header>
<main>@yield('content')</main>
<footer class="footer"><div><b>BẢO VẬT VIỆT NAM</b><p>Dự án giáo dục phi lợi nhuận · Đề tài I01</p></div><div class="footer-links"><a href="{{ route('treasures') }}">Bộ sưu tập</a><a href="{{ route('sources') }}">Nguồn tư liệu</a><a href="{{ route('about') }}">Giới thiệu dự án</a><p>Thông tin hiện vật luôn đi kèm nguồn tham khảo.</p></div></footer>
<script>window.I01_ROUTES={explain:@json(route('ai.explain')),saveScore:@json(route('scores.store'))};</script>
<script src="{{ asset('js/app.js') }}" defer></script>
@stack('scripts')
</body></html>
