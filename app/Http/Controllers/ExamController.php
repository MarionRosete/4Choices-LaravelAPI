<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Exams;
use App\Models\Classes;
use App\Models\QuestionandAnswer;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ExamController extends Controller
{
  
    /**
     * 
     */
    public function createExam(Request $request, $id){
      $checkid= Classes::where(['id'=>$id])->first();
        $fields = $request -> validate([
            'name'=> 'required|string',
        ]);
        $exam = Exams::create([
          'name'=>$fields['name'],
          "code"=> Str::random(12),
          "class_id"=>$checkid->id
        ]);
        $response = [
            "success"=>true,
            'message'=>'successful',
            'exam' => $exam,
        ];
        return response($response);
      }

      public function createqa(Request $request, $code){
        $examcode = Exams::where(["code"=>$code])->first();
        
          if($examcode){
          $fields = $request -> validate([
            'question'=> 'required|string',
            'answer1'=>'required|string',
            'answer2'=>'required|string',
            'answer3'=>'required|string',
            'answer4'=>'required|string',
            'answer'=>'required'
          ]);
          $createexam = QuestionandAnswer::create([
            'question'=>$fields['question'],
            'answer1'=>$fields['answer1'],
            'answer2'=>$fields['answer2'],
            'answer3'=>$fields['answer3'],
            'answer4'=>$fields['answer4'],
            'answer'=>$fields['answer'],
            'exam_id'=>$examcode->id
          ]);
          return response(["success"=>true,]);
          }
        dd("Not Working");
         
      }
    /**
     * 
     */
    public function myexam($id){
     
      $checkid= Classes::where(['id'=>$id])->first();
        if($checkid){
        $all = Exams::where(["class_id"=>$checkid->id])->get(); 
        return response(["exam"=>$all]);
        };
    }
    /**
     * 
     */
    public function myqa($code){
        
        $examname = Exams::where(["code"=>$code])->first();
        if($examname){
          $all = QuestionandAnswer::where(["exam_id"=>$examname->id])->get();
          return response(["success"=>true,"exam"=>$examname->name,"qa"=>$all]);
        }
      return response (["success"=>false]);
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
    public function delete($code){
      $examname = Exams::where(["code"=>$code])->first();
      $examname->delete();
      return response([$examname]);
    }
    public function attendees($code){
        
      $examname = Exams::where(["code"=>$code])->first();
      if($examname){
        $all = QuestionandAnswer::where(["exam_id"=>$examname->id])->get();
        $subject = Classes::where(["id"=>$examname->class_id])->first();
        return response(["success"=>true,"exam"=>$subject->subject, "code"=>$code,"qa"=>$all]); 
      }
    return response (["success"=>false]);
  }
  
}
