@extends('layouts.app')
@section('title', $item ? 'Sửa bảo vật' : 'Thêm bảo vật')
@section('content')
<section class="page-hero"><span class="eyebrow">QUẢN TRỊ · HỒ SƠ</span><h1>{{ $item ? 'Chỉnh sửa' : 'Thêm mới' }} <em>bảo vật</em></h1><p>Điền thông tin có căn cứ. Không tự suy đoán dữ liệu chưa được xác minh.</p></section>
<section class="auth-wrap admin-form-wrap"><form class="auth-card admin-form" method="POST" enctype="multipart/form-data" action="{{ $item ? route('admin.treasures.update',$item['id']) : route('admin.treasures.store') }}">@csrf @if($item) @method('PUT') @endif
@if($errors->any())<div class="form-errors">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
<div class="form-grid">
<label>Tên bảo vật *<input name="title" required value="{{ old('title',$item['title']??'') }}"></label>
<label>Niên đại<input name="date" value="{{ old('date',$item['date']??'') }}"></label>
<label>Loại hình<input name="type" value="{{ old('type',$item['type']??'') }}"></label>
<label>Chất liệu / kích thước<input name="format" value="{{ old('format',$item['format']??'') }}"></label>
<label class="full">Nơi lưu giữ<input name="holding_current" value="{{ old('holding_current',$item['holding_current']??'') }}"></label>
<label class="full">Mô tả *</label><textarea class="full" name="description" required rows="5">{{ old('description',$item['description']??'') }}</textarea>
<label class="full">URL nguồn tham khảo *<input type="url" name="source" required value="{{ old('source',$item['source']??'') }}"></label>
<label class="full">Ảnh hiện vật (JPG, PNG, WebP; tối đa 5MB)<input type="file" name="image_file" accept="image/jpeg,image/png,image/webp"></label>
<div class="full form-subhead">Câu hỏi trắc nghiệm liên quan (không bắt buộc)</div>
<label class="full">Câu hỏi<input name="question" value="{{ old('question',$item['question']??'') }}"></label>
@php($opts=old('options',$item['options']??[]))
@foreach(['A','B','C','D'] as $i=>$letter)<label>Phương án {{ $letter }}<input name="option_{{ strtolower($letter) }}" value="{{ old('option_'.strtolower($letter),$opts[$i]??'') }}"></label>@endforeach
<label>Đáp án đúng<select name="answer">@foreach(['A','B','C','D'] as $i=>$letter)<option value="{{ $i }}" @selected((string)old('answer',$item['answer']??0)===(string)$i)>{{ $letter }}</option>@endforeach</select></label>
<label class="full">Giải thích đáp án<textarea name="explanation" rows="3">{{ old('explanation',$item['explanation']??'') }}</textarea></label>
</div>
<div class="admin-form-actions"><button class="btn btn-dark" type="submit">Lưu hồ sơ</button><a class="btn btn-outline-dark" href="{{ route('admin.treasures') }}">Hủy</a></div>
<p class="form-help">Ảnh hiện vật cần được bổ sung riêng sau khi có quyền sử dụng hợp lệ.</p>
</form></section>
@endsection
