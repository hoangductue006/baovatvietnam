@extends('layouts.app')
@section('title','Bộ sưu tập bảo vật')
@section('content')
<section class="page-hero collection-hero">
  <div class="wrap"><span class="eyebrow">KHÁM PHÁ · TRA CỨU · HỌC TẬP</span>
    <h1>Bộ sưu tập <em>bảo vật</em></h1>
    <p>Mỗi hiện vật là một dấu mốc lịch sử. Tìm hiểu thông tin, bối cảnh và nguồn tư liệu của từng bảo vật.</p>
    <div class="hero-meta"><span>{{ $total }} hồ sơ trong bộ dữ liệu</span><span>Thông tin có dẫn nguồn</span><span>Miễn phí · Phi lợi nhuận</span></div>
  </div>
</section>
<section class="wrap collection-section">
  <form class="collection-tools" method="GET" action="{{ route('treasures') }}">
    <div class="search-field"><span>⌕</span><input name="q" value="{{ $keyword }}" placeholder="Tìm tên bảo vật, niên đại, mô tả hoặc nơi lưu giữ..." aria-label="Tìm kiếm bảo vật"></div>
    <input name="period" value="{{ $period }}" placeholder="Thời kỳ (ví dụ Đông Sơn)" aria-label="Lọc theo thời kỳ">
    <input name="material" value="{{ $material }}" placeholder="Chất liệu (ví dụ đồng)" aria-label="Lọc theo chất liệu">
    <button class="btn btn-dark" type="submit">Lọc kết quả</button>
    @if($keyword || $period || $material)<a class="clear-filter" href="{{ route('treasures') }}">Xóa bộ lọc</a>@endif
  </form>
  <div class="collection-toolbar"><div><strong>{{ count($items) }}</strong> kết quả <span class="muted">· Sắp xếp theo danh mục</span></div><div class="toolbar-links"><a href="{{ route('export.csv') }}">Tải CSV ↓</a><a href="{{ route('export.json') }}">Tải JSON ↓</a></div></div>
  <div class="collection-grid">
    @forelse($items as $item)
      <article class="object-card">
        <a class="object-image-link" href="{{ route('treasure.show', $item['id']) }}" aria-label="Xem {{ $item['title'] }}">
          <div class="object-image">
            @if(!empty($item['image']))
              <img src="{{ \Illuminate\Support\Str::startsWith($item['image'], ['http://', 'https://']) ? $item['image'] : asset($item['image']) }}" alt="{{ $item['title'] }}" loading="lazy">
            @else
              <span class="artifact-placeholder-mark">✳</span><b>HỒ SƠ HIỆN VẬT</b><small>Ảnh thật sẽ hiển thị khi được cấp phép</small>
            @endif
          </div>
        </a>
        <div class="object-body">
          <div class="card-kicker"><span>{{ $item['identifier'] }}</span><span class="record-status">Hồ sơ số</span></div>
          <h2><a href="{{ route('treasure.show', $item['id']) }}">{{ $item['title'] }}</a></h2>
          <p class="object-period">{{ $item['date'] }}</p>
          <p class="object-summary">{{ \Illuminate\Support\Str::limit($item['description'], 145) }}</p>
          <div class="object-facts"><div><small>CHẤT LIỆU</small><b>{{ \Illuminate\Support\Str::limit($item['format'] ?? 'Chưa xác định', 48) }}</b></div><div><small>NƠI LƯU GIỮ</small><b>{{ $item['holding_current'] ?? 'Chưa xác định' }}</b></div></div>
          <a class="card-cta" href="{{ route('treasure.show', $item['id']) }}">Xem hồ sơ bảo vật <span>→</span></a>
        </div>
      </article>
    @empty
      <div class="empty-state"><span>⌕</span><h2>Chưa tìm thấy bảo vật phù hợp</h2><p>Thử thay đổi từ khóa hoặc xóa bớt bộ lọc.</p><a class="btn btn-dark" href="{{ route('treasures') }}">Xem toàn bộ bộ sưu tập</a></div>
    @endforelse
  </div>
  <div class="pagination-wrap">{{ $items->links() }}</div>\n  <div class="collection-note"><b>Về độ tin cậy của dữ liệu</b><p>Một số trường chưa có căn cứ độc lập được ghi rõ là “chưa xác định” hoặc “cần xác minh”. Ảnh hiện vật chỉ được bổ sung khi có quyền sử dụng hợp lệ.</p></div>
</section>
@endsection
