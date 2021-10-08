<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Classes;
class ClassController extends Controller
{
    public function createclass(Request $request){
       $input = $request -> validate([
        'section'=>'required|string',
        'subject'=>'required|string',
        'schedule'=>'required|string',
       ]);
       $class = Classes::Create([
        'section'=>$input['section'],
        'subject'=>$input['subject'],
        'schedule'=>$input['schedule'],
        'user_id'=>auth()->user()->id,
       ]);
       return response(['class'=>$class]);
    }
    public function myclass(){
        $instructor = auth()->user()->id;
        $all = Classes::where(["user_id"=>$instructor])->get();
        return response(["exam"=>$all]);
    }

    public function delete($id){
        $class = Classes::where(["id"=>$id])->first();
        $class->delete();
        return response($class);
      }
}
