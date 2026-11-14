@extends('layouts.app')
@section('title','Quản lý bảo vật')
@section('content')
<section class="page-hero"><span class="eyebrow">QUẢN TRỊ · THƯ VIỆN</span><h1>Danh sách <em>bảo vật</em></h1><p>Thay đổi nội dung sẽ được lưu vào tệp dữ liệu của ứng dụng.</p></section>
<section class="wrap section">
<div class="admin-toolbar"><a class="btn btn-dark" href="{{ route('admin.treasures.create') }}">＋ Thêm bảo vật</a><a class="text-link" href="{{ route('admin.dashboard') }}">← Tổng quan quản trị</a></div>
@if(session('success'))<div class="success-message">{{ session('success') }}</div>@endif
<div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Mã</th><th>Tên bảo vật</th><th>Niên đại</th><th>Thao tác</th></tr></thead><tbody>
@foreach($items as $item)<tr><td>{{ $item['identifier'] }}</td><td><b>{{ $item['title'] }}</b></td><td>{{ $item['date'] }}</td><td class="admin-actions"><a href="{{ route('admin.treasures.edit',$item['id']) }}">Sửa</a><form method="POST" action="{{ route('admin.treasures.destroy',$item['id']) }}" onsubmit="return confirm('Bạn chắc chắn muốn xóa hồ sơ này?')">@csrf @method('DELETE')<button type="submit">Xóa</button></form><a href="{{ route('treasure.show',$item['id']) }}" target="_blank">Xem</a></td></tr>@endforeach
</tbody></table></div>
</section>
@endsection
