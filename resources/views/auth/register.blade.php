@extends('layouts.app')
@section('title','Đăng ký')
@section('content')
<section class="page-hero"><span class="eyebrow">THAM GIA HÀNH TRÌNH</span><h1>Tạo tài khoản <em>học tập</em></h1><p>Lưu kết quả và tiếp tục hành trình khám phá di sản.</p></section>
<section class="auth-wrap"><form class="auth-card" method="POST" action="{{ route('register.store') }}">@csrf
<h2>Đăng ký tài khoản</h2>
@if($errors->any())<div class="form-errors">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
<label>Họ và tên<input name="name" value="{{ old('name') }}" required maxlength="120" autocomplete="name"></label>
<label>Email<input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"></label>
<label>Mật khẩu<input type="password" name="password" required minlength="8" autocomplete="new-password"><small>Tối thiểu 8 ký tự.</small></label>
<label>Nhập lại mật khẩu<input type="password" name="password_confirmation" required autocomplete="new-password"></label>
<button class="btn btn-dark" type="submit">Tạo tài khoản</button>
<p class="auth-switch">Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></p>
</form></section>
@endsection
