<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Student;
class PaymentController extends Controller
{
    public function add(Request $request){
        $request->validate([
            'date'=>"required",
            'amount'=>'required|integer',
            "student_id"=>"required|integer"
        ]);

        $pay=new Payment();
        $pay->amount=$request->amount;
        $pay->date=$request->date;
        $pay->billno=$request->billno;
        $pay->student_id=$request->student_id;
        $pay->save();

        $std=Student::find($pay->student_id);
        $std->balance=$std->balance-$pay->amount;
        $std->save();
        return redirect()->route('students.show',['std'=>$pay->student_id])->with('message',"Payment Added sucessfully");
    }

    public function edit(Request $request,Payment $pay){
        $request->validate([
            'date'=>"required",
            'amount'=>'required|number',
            "student_id"=>"required|integer"
        ]);

        
        $std=Student::find($pay->student_id);
        $std->balance=$std->balance+$pay->amount-$request->amount;
        $std->save();
        $pay->amount=$request->amount;
        $pay->billno=$request->billno;
        $pay->date=$request->date;
        $pay->save();
        return redirect()->route('students.show',['std'=>$pay->student_id])->with('message',"Payment Updated sucessfully");
    }

    public function delete(Payment $pay){
        $std=Student::find($pay->student_id);
        $std->balance=$std->balance+$pay->amount;
        $std->save();

        $pay->delete();
        return redirect()->route('students.show',['std'=>$pay->student_id])->with('message',"Payment Deleted sucessfully");

    }
}