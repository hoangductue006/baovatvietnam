@extends('layouts.app')
@section('title','Quản trị')
@section('content')
<section class="page-hero"><span class="eyebrow">KHU VỰC QUẢN TRỊ</span><h1>Quản lý <em>nội dung</em></h1><p>Chỉ tài khoản được cấp quyền quản trị mới truy cập được khu vực này.</p></section>
<section class="wrap section admin-dashboard">
<a class="admin-tile" href="{{ route('admin.treasures') }}"><small>THƯ VIỆN HIỆN VẬT</small><strong>{{ $count }}</strong><h2>Quản lý bảo vật</h2><p>Thêm mới, chỉnh sửa và xóa hồ sơ bảo vật.</p><b>Mở quản lý →</b></a>
<div class="admin-tile"><small>NGUỒN TƯ LIỆU</small><strong>{{ $sources }}</strong><h2>Danh mục nguồn</h2><p>Danh mục nguồn hiện được lưu trong tệp dữ liệu dự án.</p><b>Kiểm tra nguồn tại trang Nguồn tư liệu</b></div>
</section>
@endsection
