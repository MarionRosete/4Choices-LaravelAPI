<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Exams;
use App\Models\QuestionandAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ExamController extends Controller
{
  
    /**
     * 
     */
    public function createExam(Request $request){
        $fields = $request -> validate([
            'name'=> 'required|string',
            'subject'=>'required|string',
            'description'=>'required|string',
           
        ]);
        $exam = Exams::create([
          'name'=>$fields['name'],
          'subject'=>$fields['subject'],
          'description'=>$fields['description'],
          'instructor'=>auth()->user()->fullname,
          "code"=> Str::random(12)
        ]);
        $response = [
            "success"=>true,
            'message'=>'successful',
            'exam details' => $exam,
        ];
        return response($response);
      }

      public function qa(Request $request, $code){
        $examcode = Exams::where(["code"=>$code])->first();
          if($examcode){
          $fields = $request -> validate([
            'question'=> 'required|string',
            'answer'=>'required|string'
          ]);
          $createexam = QuestionandAnswer::create([
            'question'=>$fields['question'],
            'answer'=>$fields['answer'],
            'code'=>$examcode->code
          ]);
          dd($examcode);
          }
        dd("whoops not working");  
      }
    /**
     * 
     */
    public function myexam(){
     
        $instructor = auth()->user()->fullname;
        $exam = Exams::first();
        if($exam ===null){
          return response(["auth"=>true,"user"=>$instructor,"success"=>false]);
        }
        $all = Exams::where(["instructor"=>$instructor])->get();
        return response(["auth"=>true,"user"=>$instructor, "success"=>true, "exam"=>$all]);
    } 
    /**
     * 
     */
    public function activate(Request $request){
       $id = $request->id;
       $check = Exams::where(["id"=>$id])->first();
       
        if($check){
        $code=Exams::where(["code"=>$check->id])->get();
       
        
        return response($code);
        }
        
      
    }
}
