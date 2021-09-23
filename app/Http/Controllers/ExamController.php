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
            'code'=>'required|string'
        ]);
        $exam = Exams::create([
          'name'=>$fields['name'],
          'subject'=>$fields['subject'],
          'description'=>$fields['description'],
          'instructor'=>auth()->user()->fullname,
          "code"=> $fields['code'],
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
            'answer4'=>'required|string'
          ]);
          $createexam = QuestionandAnswer::create([
            'question'=>$fields['question'],
            'answer1'=>$fields['answer1'],
            'answer2'=>$fields['answer2'],
            'answer3'=>$fields['answer3'],
            'answer4'=>$fields['answer4'],
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
        $all = Exams::where(["instructor"=>$instructor])->get();
         
        return response(["exam"=>$all]);
    }
    /**
     * 
     */
    public function myqa($code){
        $validcode = QuestionandAnswer::where(["code"=>$code])->first();
        if($validcode){
          $all = QuestionandAnswer::where(["code"=>$code])->get();
          return response(["success"=>true,"qa"=>$all]);
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
}
