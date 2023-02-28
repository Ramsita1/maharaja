<?php

namespace App\Http\Controllers\API\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect;
use App\User;

class StoreUsersController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function getUserDetails(Request $request)
    {
        $currentUser = getApiCurrentUser();
        return Response()->json(['status'=>'success', 'message' => 'user details', 'response' => compact('currentUser') ],200);
    }

    
}
