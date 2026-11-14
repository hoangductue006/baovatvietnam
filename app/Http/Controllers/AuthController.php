<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller {
 public function loginForm(){return view('auth.login');}
 public function registerForm(){return view('auth.register');}
 public function register(Request $request){
  $data=$request->validate(['name'=>['required','string','max:120'],'email'=>['required','email','max:190','unique:users,email'],'password'=>['required','string','min:8','confirmed']]);
  $user=User::create(['name'=>$data['name'],'email'=>$data['email'],'password'=>Hash::make($data['password']),'is_admin'=>false]);
  Auth::login($user);$request->session()->regenerate();return redirect()->route('home')->with('success','Tạo tài khoản thành công.');
 }
 public function login(Request $request){
  $credentials=$request->validate(['email'=>['required','email'],'password'=>['required','string']]);
  if(!Auth::attempt($credentials,$request->boolean('remember')))throw ValidationException::withMessages(['email'=>'Email hoặc mật khẩu chưa chính xác.']);
  $request->session()->regenerate();return redirect()->intended(route('home'));
 }
 public function logout(Request $request){Auth::logout();$request->session()->invalidate();$request->session()->regenerateToken();return redirect()->route('home');}
}
