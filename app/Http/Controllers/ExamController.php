<?php

namespace App\Http\Controllers;

use App\Models\Exams;
use Illuminate\Http\Request;

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
          'code'=> sha1(time()),
         
        ]);
  
       
  
        $response = [
            'message'=>'successful',
            'exam details' => $exam,
        ];
        return response($response);
      }
}
