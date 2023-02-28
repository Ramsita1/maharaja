<?php

namespace App\Http\Controllers\Admin\Feedback;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\FeedBack;
use Session,Redirect;
class FeedbackController extends Controller
{
    public function index(){
        $feedbacks = FeedBack::paginate(pagination());
        $view = 'Admin.Feedback.Index';
       return view('Admin',compact('view','feedbacks'));
    }
    public function destroy($id){
        $feedback = FeedBack::find($id);
        $feedback->delete();
        Session::flash ( 'success',"Feedback Deleted." );
        return Redirect::back();
    }
}
