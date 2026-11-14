@extends('layouts.app')
@section('title','Thế giới mini game')
@section('content')
<section class="page-hero"><span class="eyebrow">HỌC QUA TRÒ CHƠI · MINI GAME ARENA</span><h1>Chơi để <em>ghi nhớ.</em></h1><p>Ba trò chơi ngắn sử dụng dữ liệu bảo vật trong thư viện. Mỗi lượt chơi giúp bạn củng cố kiến thức và tích lũy XP.</p></section>
<section class="wrap game-hub">
<div class="game-stats"><div><small>TỔNG XP</small><strong id="gameXp">0</strong></div><div><small>TRÒ ĐÃ CHƠI</small><strong id="gamePlayed">0</strong></div><div><small>KỶ LỤC</small><strong id="gameBest">0</strong></div></div>
<div class="game-select-grid">
<button class="game-select active" data-game="memory"><span class="game-emoji">🧩</span><small>GAME 01 · GHI NHỚ</small><h2>Ghép cặp bảo vật</h2><p>Lật thẻ và ghép tên hiện vật với mô tả phù hợp. Ghi nhớ càng tốt, điểm càng cao.</p><b>Chơi ghép cặp →</b></button>
<button class="game-select" data-game="detective"><span class="game-emoji">🔎</span><small>GAME 02 · SUY LUẬN</small><h2>Thám tử di sản</h2><p>Đọc manh mối từ hồ sơ và đoán đúng bảo vật. Mỗi câu trả lời mở ra một kiến thức mới.</p><b>Giải mã ngay →</b></button>
<button class="game-select" data-game="rush"><span class="game-emoji">⏱️</span><small>GAME 03 · TỐC ĐỘ</small><h2>60 giây thử tài</h2><p>Trả lời liên tiếp các câu hỏi trong 60 giây. Cố gắng đạt chuỗi trả lời đúng cao nhất.</p><b>Bắt đầu tính giờ →</b></button>
</div>
<div class="game-stage" id="gameStage" data-items='@json($items)'>
<div class="game-stage-empty"><span>🎮</span><h2>Chọn một trò chơi để bắt đầu</h2><p>Mỗi trò chơi được thiết kế để giúp bạn học từ dữ liệu bảo vật, không chỉ bấm chọn đáp án.</p></div>
</div>
<div class="game-help"><b>Mẹo học tập:</b> Sau khi chơi, hãy mở hồ sơ bảo vật và nguồn tham khảo để đọc sâu hơn. Điểm XP và thành tích mini game được lưu trên trình duyệt hiện tại.</div>
</section>
@endsection
@push('scripts')
<script src="{{ asset('js/minigames.js') }}" defer></script>
@endpush
