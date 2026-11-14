<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller {
 private function path(string $name): string { return storage_path('app/data/'.$name.'.json'); }
 private function read(string $name): array { $p=$this->path($name); if(!is_file($p))return []; $v=json_decode(file_get_contents($p),true);return is_array($v)?$v:[]; }
 private function write(string $name,array $data): void { $p=$this->path($name); if(!is_dir(dirname($p)))mkdir(dirname($p),0755,true); file_put_contents($p,json_encode(array_values($data),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),LOCK_EX); }
 public function dashboard(){return view('admin.dashboard',['count'=>count($this->read('treasures')),'sources'=>count($this->read('sources'))]);}
 public function index(){return view('admin.treasures',['items'=>$this->read('treasures')]);}
 public function create(){return view('admin.treasure-form',['item'=>null]);}
 public function store(Request $r){
  $d=$this->validateItem($r);if($r->hasFile('image_file'))$d['image']='storage/'.$r->file('image_file')->store('artifacts','public');$items=$this->read('treasures');$n=1;foreach($items as $x){$n=max($n,(int)substr($x['id']??'I01-0000',4)+1);}
  $d['id']='I01-'.str_pad((string)$n,4,'0',STR_PAD_LEFT);$d['identifier']=$d['id'];if(!isset($d['image']))$d['image']=null;$items[]=$d;$this->write('treasures',$items);
  return redirect()->route('admin.treasures')->with('success','Đã thêm hồ sơ bảo vật.');
 }
 public function edit(string $id){$item=collect($this->read('treasures'))->firstWhere('id',$id);abort_if(!$item,404);return view('admin.treasure-form',compact('item'));}
 public function update(Request $r,string $id){
  $d=$this->validateItem($r);if($r->hasFile('image_file'))$d['image']='storage/'.$r->file('image_file')->store('artifacts','public');$items=$this->read('treasures');$found=false;
  foreach($items as &$item){if(($item['id']??'')===$id){$d['id']=$id;$d['identifier']=$item['identifier']??$id;if(!isset($d['image']))$d['image']=$item['image']??null;$item=array_merge($item,$d);$found=true;break;}}
  abort_unless($found,404);$this->write('treasures',$items);return redirect()->route('admin.treasures')->with('success','Đã cập nhật hồ sơ.');
 }
 public function destroy(string $id){
  $items=array_values(array_filter($this->read('treasures'),fn($x)=>($x['id']??'')!==$id));$this->write('treasures',$items);return redirect()->route('admin.treasures')->with('success','Đã xóa hồ sơ.');
 }
 private function validateItem(Request $r): array {
  $validated=$r->validate([
   'title'=>['required','string','max:180'],'date'=>['nullable','string','max:180'],'type'=>['nullable','string','max:180'],
   'format'=>['nullable','string','max:500'],'description'=>['required','string','max:10000'],'holding_current'=>['nullable','string','max:255'],
   'source'=>['required','url','max:1000'],'question'=>['nullable','string','max:1000'],
   'option_a'=>['required_with:question','nullable','string','max:500'],'option_b'=>['required_with:question','nullable','string','max:500'],'option_c'=>['required_with:question','nullable','string','max:500'],'option_d'=>['required_with:question','nullable','string','max:500'],
   'answer'=>['nullable','integer','between:0,3'],'explanation'=>['nullable','string','max:3000'],
   'image_file'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
  ]);
  unset($validated['image_file'],$validated['option_a'],$validated['option_b'],$validated['option_c'],$validated['option_d']);
  $validated['options']=array_values(array_filter([$r->input('option_a'),$r->input('option_b'),$r->input('option_c'),$r->input('option_d')],fn($v)=>filled($v)));
  return $validated;
 }
}
