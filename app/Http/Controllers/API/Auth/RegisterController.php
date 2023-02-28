<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect;
use App\User;
use App\PhoneOtpVerification;

class RegisterController extends Controller
{
    public static function storeRules()
    {
       return [
           'name' => 'required',
           'email' => 'required|email|unique:users',
           'phone' => 'required|numeric|unique:users',
           'password' => 'required|min:6|max:15',
       ];
    }
    public static function storeStepTwoRules()
    {
       return [
           'name' => 'required',
           'email' => 'required|email|unique:users',
           'phone' => 'required|numeric|unique:users',
           'password' => 'required|min:6|max:15',
           'dob' => 'required|date_format:Y-m-d',
           'gender' => 'required|in:M,F'
       ];
    }
    public static function storeStepThreeRules()
    {
       return [
           'name' => 'required',
           'email' => 'required|email|unique:users',
           'phone' => 'required|numeric|unique:users',
           'password' => 'required|min:6|max:15',
           'dob' => 'required|date_format:Y-m-d',
           'gender' => 'required|in:M,F',
           'otp_code' => 'required|numeric'
       ];
    }
    public function storeStepOne(Request $request){

       $validator = Validator::make($request->all(), self::storeRules());
       
       if($validator->fails()){
        return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],200);
       }
       if(!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
        return Response()->json(['status'=>'warn', 'message' => 'The email must be a valid email address.', 'response' => [] ],200);
       }
       $phone = $request->input('phone');
       $otp = phoneOtpSendVarification($phone);
       return Response()->json(['status'=>'success', 'message' => 'OTP Sent.'. $otp, 'response' => [] ],200);
    }
    public function storeStepTwo(Request $request){

       $validator = Validator::make($request->all(), self::storeStepTwoRules());
       
       if($validator->fails()){
        return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],200);
       }
       if(!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
        return Response()->json(['status'=>'warn', 'message' => 'The email must be a valid email address.', 'response' => [] ],200);
       }
       return Response()->json(['status'=>'success', 'message' => '', 'response' => [] ],200);
    }
    public function store(Request $request){

       $validator = Validator::make($request->all(), self::storeStepThreeRules());
       
       if($validator->fails()){
        return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],200);
       }
       if(!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
          return Response()->json(['status'=>'warn', 'message' => 'The email must be a valid email address.', 'response' => [] ],200);
       }
       $otp_code = $request->input('otp_code');
       if (empty($otp_code)) {
        return Response()->json(['status'=>'warn', 'message' => 'Otp is required.', 'response' => [] ],200);
       }
       $otpCode = PhoneOtpVerification::where('phone', $request->input('phone'))->where('otp_status', 0)->where('otp_code', $otp_code)->where('otp_for', 'phone_number')->get()->first();
       if (empty($otpCode)) {
        return Response()->json(['status'=>'warn', 'message' => 'Otp is invalid.', 'response' => [] ],200);
       }
       PhoneOtpVerification::where('otp_id', $otpCode->otp_id)->update(['otp_status'=>1]);

       $activation_key = sha1(mt_rand(10000,99999).time().$request->input('email'));
       $password = $request->input('password');
       $email = $request->input('email');
       $user = new User();
       $user->user_nicename = $request->input('name');
       $user->role = 'Customer';
       $user->name = $request->input('name');
       $user->user_login = $request->input('email');
       $user->email = $request->input('email');
       $user->phone = $request->input('phone');
       $user->password = bcrypt($password);
       $user->user_registered = new DateTime;
       $user->created_at = new DateTime;
       $user->updated_at = new DateTime;
       $user->user_activation_key = $activation_key;
       $user->user_status = 1;
       $user->save();
       
       updateUserMeta($user->user_id, 'dob', $request->input('dob'));
       updateUserMeta($user->user_id, 'gender', $request->input('gender'));
       updateUserMeta($user->user_id, 'phone_verified', 'yes');
       updateUserMeta($user->user_id, 'email_verified_at', new DateTime);
       updateUserMeta($user->user_id, 'email_verified', 'no');
       
       $link = url('varify/email/link/'.$activation_key);
       $name = $request->input('name');
       $emailTo = $request->input('email');
       $emailSubject = 'Activation At Maharaja';
       $emailBody = view('Email.RegisterVerifyEmailLink', compact('name', 'link', 'password', 'email'));
       SendEmail($emailTo, $emailSubject, $emailBody, [], '', '', '', '');
       return Response()->json(['status'=>'success', 'message' => 'Registration completed successfully.', 'response' => [] ],200);
    }
    public function verifyEmailLink($activation_key='')
    {
       $user = User::where('user_activation_key', $activation_key)->get()->first();
       if (!$user) {
           Session::flash ( 'warning', 'Invalid token please try after sometime.' );
           return Redirect()->back();
       }
       $user->email_verified_at = new DateTime;
       updateUserMeta($user->user_id, 'email_verified', 'yes');

       $name = $user->name;
       $emailTo = $user->email;
       $emailSubject = 'Account Verified At Maharaja';
       $emailBody = view('Email.AccountVerifyEmail', compact('name'));

       SendEmail($emailTo, $emailSubject, $emailBody, [], '', '', '', '');
        Session::flash ( 'success', 'Account Verified' );
        return Redirect()->back();
    }
    public function forgotPasswordUpdateEmail(Request $request){
       if(!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
          Session::flash ( 'warning', 'The email must be a valid email address.' );
          return Redirect()->back();
       }
       $user = User::where('email', $request->input('email'))->get()->first();
       if(!$user){
          Session::flash ( 'warning', 'Account does not exist in our system.' );
          return Redirect()->back();
       }
       $password = rand(000000000, 999999999);
       $user->password = bcrypt($password);
       $user->save();

       $name = $user->name;
       $email = $user->email;
       $emailSubject = 'Reset Password Email At Infiway';
       $emailBody = view('Email.ForgotPasswordEmail', compact('name', 'password', 'email'));
       
       SendEmail($email, $emailSubject, $emailBody, [], '', '', '', '');
       Session::flash ( 'success', 'Reset password has been sent to your email.' );
       return Redirect()->back();
    }

    public static function storeRulesupdate($user_id)
    {
       return [
           'name' => 'required',
           'phone' => 'required|unique:users,phone,'.$user_id.',user_id',
       ];
    }
    public function updateProfile(Request $request)
    {
       $user_id = $request->input('user_id');
       $validator = Validator::make($request->all(), self::storeRulesupdate($user_id));
       
       if($validator->fails()){
          Session::flash ( 'warning', $validator->getMessageBag()->first() );
          return Redirect()->back();
       }

       $user = User::find($user_id);
       if (empty($user)) {
          Session::flash ( 'warning', 'Account not found in our system' );
          return Redirect()->back();
       }
       $password = $request->input('password');
       $newPassword = $request->input('newPassword');
       $newRePassword = $request->input('newRePassword');
       if (empty($password) && !empty($newPassword)) {
          Session::flash ( 'warning', 'Please enter old password to update new password.' );
          return Redirect()->back();
       }
       if (!empty($password) && !User::where('email', $request->input('email'))->where('password', $password)->get()->first()) {
          Session::flash ( 'warning', 'Old Password is wrong.' );
          return Redirect()->back();
       }
       if (!empty($newPassword) && $newPassword != $newRePassword) {
          Session::flash ( 'warning', 'New password and re enter new password is not same.' );
          return Redirect()->back();
       }
       
       $user->name = $request->input('name');
       $user->phone = $request->input('phone');
       if ($newPassword) {
           $user->password = bcrypt($password);
       }
       $user->save();
       updateUserMeta($user_id, 'billing_address', $request->input('billing_address'));
       updateUserMeta($user_id, 'shipping_address', $request->input('shipping_address'));

       $user->extraFields = getUserMeta($user->user_id);

       Session::flash ( 'success', 'Profile updated.' );
       return Redirect()->back();    
    }
}
