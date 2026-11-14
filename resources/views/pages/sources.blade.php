@extends('layouts.app')
@section('title','Nguồn tư liệu')
@section('content')
<section class="page-hero"><span class="eyebrow">MINH BẠCH HỌC THUẬT</span><h1>Nguồn <em>tư liệu</em></h1><p>Thông tin trong ứng dụng cần được đối chiếu với nguồn công bố. Mở liên kết gốc để kiểm tra.</p></section>
<section class="wrap section"><div class="source-list">@foreach($sources as $index => $source)<article class="source-item"><span class="source-index">{{ str_pad($index+1,2,'0',STR_PAD_LEFT) }}</span><div><small>{{ $source['type'] }} · {{ $source['publisher'] }}</small><h3>{{ $source['title'] }}</h3><p>{{ $source['note'] }}</p><a class="text-link" href="{{ $source['url'] }}" target="_blank" rel="noopener">Mở tài liệu gốc ↗</a></div></article>@endforeach</div></section>
@endsection
