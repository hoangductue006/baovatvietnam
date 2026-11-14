@extends('layouts.app')
@section('title','Bảng điểm')
@section('content')
<section class="page-hero"><span class="eyebrow">TIẾN ĐỘ HỌC TẬP</span><h1>Bảng <em>thành tích</em></h1><p>Lịch sử các lượt thử thách được lưu trong cơ sở dữ liệu của ứng dụng.</p></section>
<section class="wrap section">
@php($best=$attempts->max('score') ?? 0)
@php($average=$attempts->count() ? round($attempts->avg('score')) : 0)
<div class="score-summary"><div><small>ĐIỂM CAO NHẤT</small><strong id="bestScore">{{ $best }}</strong></div><div><small>SỐ LẦN LUYỆN TẬP</small><strong id="attemptCount">{{ $attempts->count() }}</strong></div><div><small>ĐIỂM TRUNG BÌNH</small><strong id="averageScore">{{ $average }}</strong></div></div>
@if(!auth()->check())<div class="collection-note">Lịch sử khách được lưu trên trình duyệt này. <a href="{{ route('login') }}">Đăng nhập</a> để lưu kết quả trong tài khoản và xem trên thiết bị khác.</div><script>
document.addEventListener('DOMContentLoaded',()=>{try{const a=JSON.parse(localStorage.getItem('i01_scores')||'[]');document.getElementById('bestScore').textContent=a.length?Math.max(...a.map(x=>x.score)):0;document.getElementById('attemptCount').textContent=a.length;document.getElementById('averageScore').textContent=a.length?Math.round(a.reduce((s,x)=>s+x.score,0)/a.length):0;const body=document.getElementById('scoreRows');body.innerHTML=a.length?a.map((x,i)=>`<tr><td>${i+1}</td><td>${x.at}</td><td>${x.correct}/10</td><td><b>${x.score}</b></td></tr>`).join(''):'<tr><td colspan="4">Chưa có lượt chơi nào. Hãy bắt đầu thử thách!</td></tr>';}catch(e){}});
</script>@endif
<div class="table-wrap"><table class="score-table"><thead><tr><th>STT</th><th>Thời gian</th><th>Số câu đúng</th><th>Điểm</th></tr></thead><tbody id="scoreRows">
@forelse($attempts as $i=>$attempt)<tr><td>{{ $i+1 }}</td><td>{{ $attempt->created_at->format('d/m/Y H:i') }}</td><td>{{ $attempt->correct_answers }}/{{ $attempt->total_questions }}</td><td><b>{{ $attempt->score }}</b></td></tr>
@empty<tr><td colspan="4">Chưa có lượt chơi nào. Hãy bắt đầu thử thách!</td></tr>@endforelse
</tbody></table></div>
<a class="btn btn-dark" href="{{ route('quiz') }}">Làm thử thách →</a>
</section>
@endsection
