<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Exams;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ExamController extends Controller
{
  
    //create exam
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
          
         
        ]);
  
       
  
        $response = [
            "success"=>true,
            'message'=>'successful',
            'exam details' => $exam,
        ];
        return response($response);
      }


    public function myexam(){
     
        $instructor = auth()->user()->fullname;
        $exam = Exams::first();
        if($exam ===null){
          return response(["auth"=>true,"user"=>$instructor,"success"=>false]);
        }
        $all = Exams::where(["instructor"=>$instructor])->get();
        return response(["auth"=>true,"user"=>$instructor, "success"=>true, "exam"=>$all]);
    } 

    public function activate(Request $request){
       $name = $request->name;
       $subject = $request->subject;
       $instructor = $request->instructor;
       $check = Exams::where(["name"=>$name, "subject"=>$subject,"instructor"=>$instructor])->first();
       
        if($check){
        $code=Exams::where([
          "name"=>$check->name, 
          "subject"=>$check->subject,
          "instructor"=>$check->instructor,])->update(["code"=> Str::random(12)]);
          $updated = Exams::where(["name"=>$name, "subject"=>$subject,"instructor"=>$instructor])->first();
        
        return response($updated);
        }
        
      
    }
}
