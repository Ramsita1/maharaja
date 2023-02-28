<?php
namespace App\Http\Middleware;

use Closure;
use DB;
use Session;
use Redirect;
use App\User;
use Auth;
use Illuminate\Support\Facades\Route;

class AdminToken
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
            if(!$user = Auth::user())
            {
                Session::flash ( 'warning', "Your Session has been expired, Please login again." );
                return redirect()->route('login.index');
            }else{ 

                if ($user->role != 'Admin') {
                    Session::flash ( 'warning', "You are not authorized to access this url, Please login again." );
                    return redirect()->route('login.index');
                }               
                return $next($request); 
            }
        } catch(Exception $e){
            Session::flash ( 'warning', "Your Session has been expired, Please login again." );
            return redirect()->route('login.index');
        }
        return $next($request);
    }
}
