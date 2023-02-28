<?php
namespace App\Http\Middleware;

use Closure;
use DB;
use Session;
use Redirect;
use App\User;
use Illuminate\Support\Facades\Route;

class EmployeeToken
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

        $user = getApiCurrentUser();
        // echo json_encode($user->role);die;
        try {
            if(!$user = getApiCurrentUser()) {
                return Response()->json(['status'=> false, 'message' => 'Your Session has been expired, Please login again.', 'response' => [] ],401);
            } else { 
                if ($user->role != 'StoreEmployee' && $user->role != 'StoreAdmin' && $user->role != 'DeliveryBoy' ) {
                    // echo "loddd";die;
                    return Response()->json(['status'=> false, 'message' => 'You are not authorized to access this url, Please login again.', 'response' => [] ],401);
                }
        
            }
            
            // die("dfhfh");
            return $next($request); 
        } catch(Exception $e){
            return Response()->json(['status'=> false, 'message' => 'Your Session has been expired, Please login again.', 'response' => [] ],401);
        }
        return $next($request);
    }
}
