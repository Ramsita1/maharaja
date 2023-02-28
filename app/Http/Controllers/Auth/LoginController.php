<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect;
use App\User;
use App\PhoneOtpVerification;

class LoginController extends Controller
{
    public static function storeRules()
    {
        return [
            'phone' => 'required',
            'password'=> 'required'
        ];
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), self::storeRules());
        
        if($validator->fails()){
            return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],200);
        }

        $credentials = $request->only('phone', 'password');

        if(Auth::attempt($credentials,true)){
            $user = Auth::user();
            /*if ($user->user_status != 1) {

                $link = url('varify/email/link/'.$user->user_activation_key);
                $name = $user->name;
                $emailTo = $user->email;
                $emailSubject = 'Activation At Infiway';
                $emailBody = view('Email.RegisterVerifyEmailLink', compact('name', 'link'));

                SendEmail($emailTo, $emailSubject, $emailBody, [], '', '', '', '');

                return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],200);
                Session::flash ( 'warning', 'Your account is not verified, Please verify your email id. Email sent you.' );
                return Redirect()->back();
            }*/
            $user->extraFields = getUserMeta($user->user_id);
            return Response()->json(['status'=>'success', 'message' => 'Login Successfully', 'response' => [] ],200);
        }else{
            return Response()->json(['status'=>'error', 'message' => 'Invalid Credentials', 'response' => [] ],200);
        }
    }
    public static function forgotPasswordRules()
    {
        return [
            'phone' => 'required|numeric'
        ];
    }
    public function forgotPasswordUser(Request $request)
    {
        $validator = Validator::make($request->all(), self::forgotPasswordRules());
        
        if($validator->fails()){
            return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],200);
        }
        $phone = $request->input('phone');
        if (!User::where('phone', $phone)->get()->first()) {
            return Response()->json(['status'=>'warn', 'message' => 'Phone number not exists in our system', 'response' => [] ],200);
        }
        $otp = phoneOtpSendVarification($phone);
        return Response()->json(['status'=>'success', 'message' => 'OTP Sent.'. $otp, 'response' => [] ],200);
    }
    public static function forgotPasswordOtpRules()
    {
        return [
            'phone' => 'required|numeric',
            'otp_code' => 'required|numeric'
        ];
    }
    public function forgotPasswordOtpUser(Request $request)
    {
        $validator = Validator::make($request->all(), self::forgotPasswordOtpRules());
        
        if($validator->fails()){
            return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],200);
        }
        $phone = $request->input('phone');
        if (!User::where('phone', $phone)->get()->first()) {
            return Response()->json(['status'=>'warn', 'message' => 'Phone number not exists in our system', 'response' => [] ],200);
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
        return Response()->json(['status'=>'success', 'message' => 'OTP Verified', 'response' => [] ],200);
    }
    public static function resetPasswordRules()
    {
        return [
            'phone' => 'required|numeric',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ];
    }
    public function resetPasswordUser(Request $request)
    {
        $validator = Validator::make($request->all(), self::resetPasswordRules());
        
        if($validator->fails()){
            return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],200);
        }
        $phone = $request->input('phone');
        if (!$user = User::where('phone', $phone)->get()->first()) {
            return Response()->json(['status'=>'warn', 'message' => 'Phone number not exists in our system', 'response' => [] ],200);
        }
        $user->password = bcrypt($request->input('password'));
        $user->save();
        return Response()->json(['status'=>'success', 'message' => 'Password Changed. Please login to continue.', 'response' => [] ],200);
    }
}
 