<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect;
use App\User;
use App\DeviceToken;
use App\PhoneOtpVerification;

class LoginController extends Controller
{
    public static function storeRules()
    { 
        return [
            'phone' => 'required',
            'password'=> 'required',
            "device_token" => 'required'
        ];
    }

    public function login(Request $request){

        $validator = Validator::make($request->all(), self::storeRules());
        
        if($validator->fails()){
            return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],401);
        }

        $credentials = $request->only('phone', 'password');

        if(Auth::attempt($credentials,true)){

            $user = Auth::user();
            $role = $user->role;   
            $token = encryptID($user->user_id);

            $device_token_data = DeviceToken::where('token',$request->device_token)->where('user_id',$user->user_id)->first();
            if(empty($device_token_data)){
                $device_token = new DeviceToken;
                $device_token->user_id = $user->user_id;
                $device_token->token = $request->device_token;
                $device_token->save();
            }else{ 
                DeviceToken::where('token',$request->device_token)->update(['user_id'=>$user->user_id]);
            }


            return Response()->json(['status'=>'success', 'message' => 'Login Successfullysss', 'response' => compact('token', 'role') ],200);
        }else{
            return Response()->json(['status'=>'error', 'message' => 'Invalid Credentials', 'response' => [] ],401);
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
            return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],401);
        }
        $phone = $request->input('phone');
        if (!User::where('phone', $phone)->get()->first()) {
            return Response()->json(['status'=>'warn', 'message' => 'Phone number not exists in our system', 'response' => [] ],401);
        }
        $otp = phoneOtpSendVarification($phone);
        return Response()->json(['status'=>'success', 'message' => 'OTP Sent.'. $otp, 'response' => [] ],200);
    }
    public static function forgotPasswordOtpRules()
    {
        return [
            'phone' => 'required|numeric',
            'otp_code' => 'required|numeric',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ];
    }
    public function resetPasswordOtpUser(Request $request)
    {
        $validator = Validator::make($request->all(), self::forgotPasswordOtpRules());
        
        if($validator->fails()){
            return Response()->json(['status'=>'warn', 'message' => $validator->getMessageBag()->first(), 'response' => [] ],401);
        }
        $phone = $request->input('phone');
        $otp_code = $request->input('otp_code');
        if (!$user = User::where('phone', $phone)->get()->first()) {
            return Response()->json(['status'=>'warn', 'message' => 'Phone number not exists in our system', 'response' => [] ],401);
        }
        $otpCode = PhoneOtpVerification::where('phone', $request->input('phone'))->where('otp_status', 0)->where('otp_code', $otp_code)->where('otp_for', 'phone_number')->get()->first();
        if (empty($otpCode)) {
            return Response()->json(['status'=>'warn', 'message' => 'Otp is invalid.', 'response' => [] ],401);
        }
        PhoneOtpVerification::where('otp_id', $otpCode->otp_id)->update(['otp_status'=>1]);
        $user->password = bcrypt($request->input('password'));
        $user->save();
        return Response()->json(['status'=>'success', 'message' => 'Password Changed. Please login to continue.', 'response' => [] ],200);
    }
}
 