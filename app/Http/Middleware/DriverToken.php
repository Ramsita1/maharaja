<?php
namespace App\Http\Middleware;

use Closure;
use DB;
use Session;
use Redirect;
use App\User;
use Illuminate\Support\Facades\Route;

class DriverToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if(!$user = getApiCurrentUser()) {
                return Response()->json(['status'=> false, 'message' => 'Your Session has been expired, Please login again.', 'response' => [] ],401);
            } else { 
                if ($user->role != 'DeliveryBoy') {
                    return Response()->json(['status'=> false, 'message' => 'You are not authorized to access this url, Please login again.', 'response' => [] ],401);
                }               
                return $next($request); 
            }
        } catch(Exception $e){
            return Response()->json(['status'=> false, 'message' => 'Your Session has been expired, Please login again.', 'response' => [] ],401);
        }
        return $next($request);
    }
}
