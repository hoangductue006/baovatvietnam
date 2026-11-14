@extends('layouts.app')
@section('title','Thẻ ghi nhớ')
@section('content')
<section class="page-hero"><span class="eyebrow">02 / GHI NHỚ</span><h1>Học qua <em>thẻ ghi nhớ</em></h1><p>Chạm vào thẻ để lật và xem thông tin về bảo vật.</p></section>
<section class="wrap flash-wrap" id="flashApp" data-items='@json($items)'><div class="flash-count"><span id="flashCounter">01 / {{ count($items) }}</span><span>Dùng mũi tên để chuyển thẻ</span></div><button class="flash-card" id="flashCard"><span class="flash-front"><small>NHẤN ĐỂ LẬT THẺ</small><b id="flashTitle"></b><span id="flashPeriod"></span></span><span class="flash-back"><small>THÔNG TIN GHI NHỚ</small><b id="flashBackTitle"></b><span id="flashDescription"></span><em id="flashLocation"></em></span></button><div class="flash-controls"><button class="btn btn-outline-dark" id="flashPrev">← Trước</button><button class="btn btn-dark" id="flashFlip">Lật thẻ</button><button class="btn btn-outline-dark" id="flashNext">Tiếp →</button></div></section>
@endsection
