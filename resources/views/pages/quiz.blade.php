@extends('layouts.app')
@section('title','Thử thách kiến thức')
@section('content')
<section class="page-hero"><span class="eyebrow">01 / THỬ THÁCH</span><h1>Thử tài <em>khám phá</em></h1><p>Trả lời câu hỏi về các bảo vật. Mỗi câu đều có giải thích và nguồn để bạn đọc thêm.</p></section>
<section class="quiz-shell wrap" id="quizApp" data-items='@json($items)'>
  <div class="quiz-top"><span id="questionCounter">CÂU 01 / 10</span><span id="progressLabel">0% hoàn thành</span></div><div class="progress"><i id="progressBar"></i></div>
  <div class="quiz-card"><div class="quiz-image"><span>ẢNH BẢO VẬT</span><small>Ảnh thật sẽ được bổ sung khi có quyền sử dụng</small></div><div class="quiz-content"><span class="eyebrow">CÂU HỎI KIẾN THỨC</span><h2 id="questionText">Đang tải câu hỏi...</h2><div class="answer-list" id="answerList"></div><div class="answer-feedback" id="feedback" hidden></div><div class="quiz-controls"><button class="btn btn-outline-dark" id="restartQuiz">Làm lại</button><button class="btn btn-dark" id="nextQuestion" disabled>Câu tiếp theo →</button></div></div></div>
  <div class="quiz-result" id="quizResult" hidden><span class="eyebrow">HOÀN THÀNH THỬ THÁCH</span><h2 id="finalScore"></h2><p id="finalMessage"></p><button class="btn btn-gold" id="playAgain">Chơi lại</button></div>
</section>
@endsection
