@extends('layouts.app')
@section('title','Đăng nhập')
@section('content')
<section class="page-hero"><span class="eyebrow">TÀI KHOẢN HỌC TẬP</span><h1>Chào mừng <em>trở lại</em></h1><p>Đăng nhập để lưu lịch sử học tập và theo dõi tiến độ của bạn.</p></section>
<section class="auth-wrap"><form class="auth-card" method="POST" action="{{ route('login.store') }}">@csrf
<h2>Đăng nhập</h2>
@if($errors->any())<div class="form-errors">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
<label>Email<input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"></label>
<label>Mật khẩu<input type="password" name="password" required autocomplete="current-password"></label>
<label class="check-line"><input type="checkbox" name="remember" value="1"> Ghi nhớ đăng nhập</label>
<button class="btn btn-dark" type="submit">Đăng nhập</button>
<p class="auth-switch">Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký</a></p>
</form></section>
@endsection
