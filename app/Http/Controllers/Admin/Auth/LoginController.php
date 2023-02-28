<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Session, Redirect, DB, Validator;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Admin.Auth.Login');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_login' => 'required|string|max:255',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            Session::flash ( 'warning', $validator->getMessageBag()->first() );
            return Redirect::back ();
        }
        $credentials = $request->only('user_login', 'password');
        
        if (Auth::attempt ( $credentials, true )) {
            session ( [ 
                    'user' => Auth::user() 
            ] );
            Session::flash ( 'success', "Login Successfully" );
            return redirect()->route('dashboard.index');
        } else {
            Session::flash ( 'warning', "Invalid Credentials , Please try again." );
            return Redirect::back ();
        }
    }
    public function logout() {
        Session::flush ();
        Auth::logout ();
        Session::flash ( 'warning', "Logout Successfully" );
        return redirect()->back();
    }
}
