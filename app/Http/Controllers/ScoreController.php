<?php
namespace App\Http\Controllers;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
class ScoreController extends Controller {
 public function store(Request $request){
  if (!$request->user()) return response()->json(['message'=>'Đăng nhập để lưu điểm vào tài khoản.'],401);
  $data=$request->validate(['correct_answers'=>['required','integer','min:0','max:100'],'total_questions'=>['required','integer','min:1','max:100'],'score'=>['required','integer','min:0','max:100'],'details'=>['nullable','array']]);
  if($data['correct_answers']>$data['total_questions'])return response()->json(['message'=>'Số câu đúng không hợp lệ.'],422);
  $attempt=QuizAttempt::create($data+['user_id'=>$request->user()?->id]);
  return response()->json(['message'=>'Đã lưu kết quả.','id'=>$attempt->id],201);
 }
 public function index(Request $request){
  $query=QuizAttempt::query()->latest();
  if ($request->user()) $query->where('user_id',$request->user()->id); else $query->whereRaw('1 = 0');
  $attempts=$query->limit(50)->get();return view('pages.scores',compact('attempts'));
 }
}
