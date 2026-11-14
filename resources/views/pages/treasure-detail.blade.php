@extends('layouts.app')
@section('title', $item['title'])
@section('content')
<div class="detail-breadcrumb wrap"><a href="{{ route('home') }}">Trang chủ</a><span>/</span><a href="{{ route('treasures') }}">Bộ sưu tập</a><span>/</span><b>{{ $item['title'] }}</b></div>
<section class="detail-hero wrap">
  <div class="detail-visual">
    @if(!empty($item['image']))
      <img src="{{ \Illuminate\Support\Str::startsWith($item['image'], ['http://', 'https://']) ? $item['image'] : asset($item['image']) }}" alt="{{ $item['title'] }}">
    @else
      <div class="detail-placeholder"><span>✳</span><b>HỒ SƠ HIỆN VẬT</b><small>Chưa có ảnh được cấp phép công bố</small></div>
    @endif
    <p>{{ $item['image_caption'] ?? 'Ảnh hiện vật chưa được bổ sung.' }}</p>
  </div>
  <div class="detail-heading">
    <span class="eyebrow">HỒ SƠ BẢO VẬT · {{ $item['identifier'] }}</span>
    <h1>{{ $item['title'] }}</h1>
    <p class="detail-date">{{ $item['date'] }}</p>
    <p class="detail-lead">{{ $item['description'] }}</p>
    <div class="detail-chips"><span>{{ $item['type'] ?? 'Hiện vật bảo tàng' }}</span><span>{{ $item['language'] ?? 'Tiếng Việt' }}</span></div>
    <a class="btn btn-dark" href="#thong-tin">Khám phá hồ sơ ↓</a>
  </div>
</section>
<section class="wrap detail-layout" id="thong-tin">
  <article class="detail-main">
    <div class="detail-section"><span class="eyebrow">01 · NHẬN DIỆN</span><h2>Thông tin hiện vật</h2>
      <dl class="metadata-grid">
        <div><dt>Nhan đề</dt><dd>{{ $item['title'] }}</dd></div>
        <div><dt>Mã định danh</dt><dd>{{ $item['identifier'] }}</dd></div>
        <div><dt>Người / cơ quan tạo ra</dt><dd>{{ $item['creator'] ?? 'Chưa xác định' }}</dd></div>
        <div><dt>Niên đại</dt><dd>{{ $item['date'] }}</dd></div>
        <div><dt>Loại hình</dt><dd>{{ $item['type'] ?? 'Chưa xác định' }}</dd></div>
        <div><dt>Chất liệu / kích thước</dt><dd>{{ $item['format'] ?? 'Chưa xác định' }}</dd></div>
        <div><dt>Số đăng ký</dt><dd>{{ $item['registration_number'] ?? 'Chưa xác định' }}</dd></div>
        <div><dt>Nơi lưu giữ hiện nay</dt><dd>{{ $item['holding_current'] ?? 'Chưa xác định' }}</dd></div>
      </dl>
    </div>
    <div class="detail-section"><span class="eyebrow">02 · MÔ TẢ</span><h2>Câu chuyện của bảo vật</h2><p class="prose">{{ $item['description'] }}</p><p class="prose muted">Phạm vi địa lý: {{ $item['coverage'] ?? 'Chưa có thông tin' }}</p></div>
    <div class="detail-section"><span class="eyebrow">03 · HỒ SƠ SỐ</span><h2>Tình trạng tư liệu và quyền công bố</h2>
      <dl class="metadata-grid">
        <div><dt>Tình trạng bản gốc</dt><dd>{{ $item['original_condition'] ?? 'Chưa xác định' }}</dd></div>
        <div><dt>Loại bản đang hiển thị</dt><dd>{{ $item['display_copy_type'] ?? 'Bản ghi mô tả số' }}</dd></div>
        <div><dt>Người cho phép công bố</dt><dd>{{ $item['permission_grantor'] ?? 'Chưa xác định' }}</dd></div>
        <div><dt>Nội dung không công bố</dt><dd>{{ $item['withheld_content'] ?? 'Không có thông tin' }}</dd></div>
      </dl>
      <div class="verification-note"><b>Mức độ kiểm chứng</b><p>{{ $item['verification_level'] ?? 'Thông tin cần được đối chiếu với đơn vị lưu giữ.' }}</p></div>
      <p class="rights-note">{{ $item['rights'] ?? '' }}</p>
    </div>
    <div class="detail-section"><span class="eyebrow">04 · NGUỒN THAM KHẢO</span><h2>Đọc thêm và đối chiếu</h2>
      <div class="source-links">
        @foreach(array_unique($item['source_urls'] ?? [$item['source']]) as $index => $url)
          <a href="{{ $url }}" target="_blank" rel="noopener"><span>Nguồn {{ $index + 1 }}</span><b>{{ parse_url($url, PHP_URL_HOST) }}</b><i>↗</i></a>
        @endforeach
      </div>
    </div>
  </article>
  <aside class="detail-aside">
    <div class="aside-card"><span class="eyebrow">HỎI ĐÁP HỌC TẬP</span><h3>Điều bạn muốn biết?</h3><p>Đặt câu hỏi về bảo vật. Trợ lý chỉ hỗ trợ giải thích từ dữ liệu hiện có và sẽ nêu rõ khi nguồn chưa đủ thông tin.</p>
      <div class="ai-box detail-ai" data-item-id="{{ $item['id'] }}"><label for="detailQuestion">Câu hỏi của bạn</label><textarea id="detailQuestion" data-ai-question maxlength="500" placeholder="Ví dụ: Hoa văn trên hiện vật có ý nghĩa gì?"></textarea><button type="button" class="btn btn-dark" data-ai-explain>Gửi câu hỏi</button><p data-ai-answer class="ai-answer" aria-live="polite"></p></div>
    </div>
    <div class="aside-card aside-quiz"><span class="eyebrow">HỌC QUA TRÒ CHƠI</span><h3>Thử thách kiến thức</h3><p>Ôn tập các thông tin về bảo vật qua câu hỏi trắc nghiệm có giải thích.</p><a class="text-link" href="{{ route('quiz') }}">Bắt đầu thử thách →</a></div>
    <a class="back-collection" href="{{ route('treasures') }}">← Quay lại bộ sưu tập</a>
  </aside>
</section>
@if($related->isNotEmpty())
<section class="wrap related-section"><div class="section-head"><div><span class="eyebrow">TIẾP TỤC KHÁM PHÁ</span><h2>Hồ sơ liên quan</h2></div><a class="text-link" href="{{ route('treasures') }}">Xem tất cả →</a></div>
<div class="related-grid">@foreach($related as $row)<a class="related-card" href="{{ route('treasure.show', $row['id']) }}"><small>{{ $row['identifier'] }}</small><h3>{{ $row['title'] }}</h3><p>{{ $row['date'] }}</p><b>Đọc hồ sơ →</b></a>@endforeach</div></section>
@endif
@endsection
