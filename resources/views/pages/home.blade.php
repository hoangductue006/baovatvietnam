@extends('layouts.app')
@section('title', 'Trang chủ')
@section('content')
    <section class="hero">
        <div class="hero-copy"><span class="eyebrow">DI SẢN · LỊCH SỬ · KHÁM PHÁ</span>
            <h1>Mỗi bảo vật<br><em>một câu chuyện.</em></h1>
            <p>Khám phá những bảo vật quốc gia Việt Nam qua trò chơi, câu hỏi và thẻ ghi nhớ. Học lịch sử theo cách chủ động
                và thú vị hơn.</p>
            <div class="hero-actions"><a class="btn btn-gold" href="{{ route('quiz') }}">Bắt đầu thử thách <span>→</span></a><a
                    class="btn btn-outline" href="{{ route('treasures') }}">Khám phá bộ sưu tập</a></div>
            <div class="hero-note"><span class="dot"></span> Nội dung học tập có dẫn nguồn tham khảo</div>
        </div>
        <div class="hero-art">
            <div class="seal">DI SẢN<br><b>VIỆT NAM</b><small>EST. 2026</small></div>
            <div class="artifact-symbol">✺</div>
            <div class="art-caption">KHO TÀNG LỊCH SỬ · VĂN HÓA</div>
        </div>
    </section>
    <section class="stats wrap">
        <div><strong>{{ $count }}</strong><span>Bản ghi trong bộ dữ liệu</span></div>
        <div><strong>06</strong><span>Hoạt động học tập và trò chơi</span></div>
        <div><strong>100%</strong><span>Tiếng Việt</span></div>
    </section>
    <section class="section wrap">
        <div class="section-head">
            <div><span class="eyebrow">BẮT ĐẦU HÀNH TRÌNH</span>
                <h2>Khám phá theo cách của bạn</h2>
            </div><a class="text-link" href="{{ route('treasures') }}">Xem toàn bộ bộ sưu tập ↗</a>
        </div>
        <div class="feature-grid">
            <a class="feature-card feature-dark" href="{{ route('quiz') }}"><span class="feature-icon">✦</span><small>01 /
                    THỬ THÁCH</small>
                <h3>Kiểm tra<br>kiến thức</h3>
                <p>Trả lời câu hỏi và xem giải thích có dẫn nguồn.</p><b>Chơi ngay →</b>
            </a>
            <a class="feature-card feature-cream" href="{{ route('flashcards') }}"><span
                    class="feature-icon">▤</span><small>02 / GHI NHỚ</small>
                <h3>Thẻ ghi nhớ<br>thông minh</h3>
                <p>Ôn tập tên gọi, niên đại và thông tin nổi bật.</p><b>Học ngay →</b>
            </a>
            <a class="feature-card feature-green" href="{{ route('scores') }}"><span class="feature-icon">↗</span><small>03
                    / TIẾN BỘ</small>
                <h3>Theo dõi<br>kết quả</h3>
                <p>Lưu điểm trên thiết bị và xem lịch sử luyện tập.</p><b>Xem điểm →</b>
            </a>
            <a class="feature-card feature-cream" href="{{ route('learning') }}"><span
                    class="feature-icon">⌂</span><small>04 / GIÁO DỤC</small>
                <h3>Trung tâm<br>học tập</h3>
                <p>Lộ trình khám phá, bài học từ hồ sơ và hoạt động ôn tập.</p><b>Vào học tập →</b>
            </a>
            <a class="feature-card feature-dark" href="{{ route('minigames') }}"><span
                    class="feature-icon">♟</span><small>05 / MINI GAME</small>
                <h3>Chơi để<br>ghi nhớ</h3>
                <p>Ghép cặp, giải mã manh mối và thử thách tốc độ.</p><b>Chơi ngay →</b>
            </a>
        </div>
    </section>
    <section class="section wrap">
        <div class="section-head">
            <div><span class="eyebrow">GỢI Ý KHÁM PHÁ</span>
                <h2>Một vài bảo vật tiêu biểu</h2>
            </div>
        </div>
        <div class="mini-grid">
            @foreach ($featured as $item)
                <a class="mini-card" href="{{ route('treasure.show', $item['id']) }}"><span
                        class="mini-number">{{ $item['identifier'] }}</span>
                    <h3>{{ $item['title'] }}</h3>
                    <p>{{ $item['date'] }}</p><span class="text-link">Đọc hồ sơ ↗</span>
                </a>
            @endforeach
        </div>
    </section>
@endsection
