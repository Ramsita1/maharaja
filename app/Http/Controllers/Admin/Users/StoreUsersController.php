<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect;
use App\User;
use App\UserMetas;

class StoreUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function index(Request $request)
    {
        $store_id = getCurrentUserByKey('store_id');
        $view = 'Admin.StoreUsers.Index';
        $users = User::where('store_id', $store_id)->paginate(pagination());
        return view('Admin', compact('view','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $view = 'Admin.StoreUsers.Create';
        $formType = 'users';
        return view('Admin', compact('view','formType'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public static function storeRules($request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'role' => 'required|in:'.implode(',', userRoles()),
            'user_status' => 'required|in:0,1,-1',      
        ];
        if ($id) {
            $rules['email'] = 'required|string|email|max:255|unique:users,email,'.$id.',user_id';
        }else{
            $rules['email'] = 'required|string|email|max:255|unique:users';
            $rules['password'] = 'required';
        }
        return $rules;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), self::storeRules($request));
        if ($validator->fails()) {
            Session::flash ( 'warning', $validator->getMessageBag()->first() );
            return Redirect::back()->withInput($request->all());
        }
        if(!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL))
        {
            Session::flash ( 'success', "The email must be a valid email address." );
            return Redirect::back()->withInput($request->all());
        }
        $formType = $request->input('formType');
        $activation_key = sha1(mt_rand(10000,99999).time().$request->input('email'));
        $password = $request->input('password');
        $email = $request->input('email');
        $user = new User();
        $user->name = $request->input('name');
        $user->user_nicename = $request->input('name');
        $user->email = $request->input('email');
        $user->email_verified_at = new DateTime; 
        $user->password = bcrypt($password);
        $user->user_login = $request->input('email');
        $user->role = $request->input('role');
        $user->user_status = $request->input('user_status');
        $user->store_id = getCurrentUserByKey('store_id');
        $user->created_at = new DateTime;
        $user->updated_at = new DateTime;
        $user->user_activation_key = $activation_key;
        $user->save();

        $link = url('varify/email/link/'.$activation_key);
        $name = $request->input('fname');
        $emailTo = $request->input('email');
        $emailSubject = 'Activation At Infiway';
        $emailBody = view('Email.RegisterVerifyEmailLink', compact('name', 'link', 'password', 'email'));
        SendEmail($emailTo, $emailSubject, $emailBody, [], '', '', '', '');

        return Redirect::route('storeUsers.index'); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $view = 'Admin.StoreUsers.Edit';
        return view('Admin', compact('view','id','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), self::storeRules($request, $id));
        if ($validator->fails()) {
            Session::flash ( 'warning', $validator->getMessageBag()->first() );
            return Redirect::back()->withInput($request->all());
        }
        if(!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL))
        {
            Session::flash ( 'success', "The email must be a valid email address." );
            return Redirect::back()->withInput($request->all());
        }

        $user = User::find($id);
        $user->name = $request->input('name');
        $user->user_nicename = $request->input('name');
        $user->email = $request->input('email');
        if ($request->input('password')) {
            $user->password = bcrypt($request->input('password'));
        }        
        $user->user_login = $request->input('email');
        $user->role = $request->input('role');
        $user->user_status = $request->input('user_status');
        $user->updated_at = new DateTime;
        $user->save();

        Session::flash ( 'success', "User Updated." );
        return Redirect::route('storeUsers.index');  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        Session::flash ( 'success', "User Deleted." );
        return Redirect::route('storeUsers.index');   
    }
}
