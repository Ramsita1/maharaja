<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect;
use App\Posts;
use App\Terms;
use App\Links;
use App\PostMetas;
use App\TermRelations;
use App\Vouchers;
use App\User;
use App\ProductOrder;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$orders = ProductOrder::select(DB::raw("(COUNT(*)) as total"), 'order_status')->groupBy('order_status')->get()->toArray();
        $totalOrder = ProductOrder::get()->count();
        $totalUsers = User::get()->count();
        $posts = Posts::select(DB::raw("(COUNT(*)) as total"), 'post_type')
                    ->whereIn('post_type', ['post','page','clients','projects','service','teams','help_today','choose_us','product'])
                    ->groupBy('post_type')
                    ->orderBy('post_type', 'ASC')
                    ->get()
                    ->toArray();*/
        
        $view = 'Admin.Dashboard.Index';
        return view('Admin', compact('view'));
    }   
    public function createVoucher()
    {
        $currentUser = getCurrentUser();
        $users = ProductOrder::where(function($query) use($currentUser){
                if ($currentUser->role != 'Admin') {
                    $query->where('store_id', $currentUser->store_id);
                }
            })
            ->where('accpet_term_condition', 1)
            ->select('name', 'email', 'phone')
            ->groupBy('name','email','phone')
            ->get();
        $vouchers = Vouchers::get();
        $view = 'Admin.Vouchers.SendPromotionalEmail';
        return view('Admin', compact('view','users','vouchers'));
    } 
    public function dashboardPostVoucher(Request $request)
    {  
        $voucher_id = $request->input('vouchers');
        $voucherCode = Vouchers::where('voucher_id', $voucher_id)->get()->pluck('code')->first();
        $users = $request->input('users');
        $message = $request->input('message');
        $message = $message.' Your voucher code is: '.$voucherCode;
        if (is_array($users) && !empty($users)) {
            foreach ($users as $user) {
                $userData = explode('|', $user);
                $name = (isset($userData[0])?$userData[0]:'');
                $email = (isset($userData[1])?$userData[1]:'');
                $phone = (isset($userData[2])?$userData[2]:'');
                if ($email) {
                    $emailSubject = 'Promotional Email';
                    $emailBody = $message;
                    SendEmail($email, $emailSubject, $emailBody);
                } 
                if ($phone) {
                    SendSMS($phone, $message);       
                }
            }
        }        
        Session::flash('success', 'Message Sent');
        return Redirect()->back();
    }
}
