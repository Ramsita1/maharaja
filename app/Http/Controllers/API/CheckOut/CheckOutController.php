<?php

namespace App\Http\Controllers\API\CheckOut;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ProductOrder;
use App\User;
use App\Posts;
use App\TermRelations;
use App\Terms;
use App\Comment;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect;

class CheckOutController extends Controller
{
    public static function getSinglePost($post_id)
    {
        $post = Posts::where('posts.post_id', $post_id)
                    ->leftJoin('posts as getImage','getImage.post_id','posts.guid')
                    ->select('posts.*','getImage.media as post_image')
                    ->where('posts.post_status', 'publish')
                    ->where('posts.post_lng', defaultLanguage())
                    ->first();
        
        if($post)
        {
           $termRelations = TermRelations::where('object_id', $post->post_id)->select('term_id');
           if(!empty($postTypes['taxonomy']))
           {
              $termsSelected = [];
              foreach ($postTypes['taxonomy'] as $key => $value) {
                 $termsSelected[$key] = Terms::whereIn('term_id', $termRelations)->where('term_group', $key)->get();
              }
              $post->category = $termsSelected;
           }
           $post->extraFields = getPostMeta($post->post_id);
           $post->postedComments = Comment::where('post_id',$post->post_id)->get();
        }
        return $post;
    }
    public static function storeRules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'street_address' => 'required|string',
            'country' => 'required|string',
            //'state' => 'required|string',
            'city' => 'required|string',
            'zipcode' => 'required|string',
            'phone' => 'required|numeric',
            'email' => 'required|email',
        ];
    }

    public function checkoutStore(Request $request){

        $validator = Validator::make($request->all(), self::storeRules());
        
        if($validator->fails()){
            return Response()->json(['status'=>false,'message'=>$validator->getMessageBag()->first(),'response' => []],200);
        }

        if(!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            return Response()->json(['status'=>false,'message'=>'The email must be a valid email address.','response' =>[] ],200);
        }
        $user = [];
        $token = '';
        $user_id = $request->input('user_id');
        if (!empty($request->input('user_id')) && User::where('user_id', $request->input('user_id'))->get()->first()) {
            $user_id = $request->input('user_id');
        } else if($user = User::where('email', $request->input('email'))->get()->first()){
            $user_id = $user->user_id;
            
            $user->extraFields = getUserMeta($user->user_id);
            $token = $user->remember_token;

        } else {
            $user = new User();
            $user->name = $request->input('first_name').' '.$request->input('last_name');
            $user->user_nicename = $request->input('first_name');
            $user->email = $request->input('email');
            $user->email_verified_at = new DateTime; 
            $user->password = bcrypt($request->input('password'));
            $user->user_login = $request->input('email');
            $user->role = 'Subscriber';
            $user->user_status = 1;
            $user->created_at = new DateTime;
            $user->updated_at = new DateTime;
            $user->save();

            $credentials = $request->only('email', 'password');

            if(Auth::attempt($credentials,true)){
                $user = Auth::user();
                /*if ($user->user_status != 1) {
                    return Response()->json(['status'=>'error','message'=>'Your account is not verified, Please verify your email id.','response'=>[]],200);
                }*/
                $user->extraFields = getUserMeta($user->user_id);
                $token = Auth::user()->remember_token;
            }            
            $user_id = $user->user_id;
        }
    	$price=0;
    	$attributes=[];
    	$productDetails=[];
    	$billingAddress=[
    		'name'=>$request->first_name,
    		'lname' => $request->last_name,
    		'phone' => $request->phone,
            'email' => $request->email,
    		'city' => $request->city,
    		'state' => $request->state,
            'country' => $request->country,
            'zipcode' => $request->zipcode,
    		'street_address' => $request->street_address,
    		'orderNote'=>$request->orderNote,
    		'hearaboutus'=>$request->hearaboutus
    	];

    	$productDetail = $request->product_detail;

    	foreach ($productDetail as $key => $value) {
            $post = self::getSinglePost($value['post_id']);
    		$attributes[$value['post_id']]['product_attr'] = $post->post_title.($value['product_attr']?'-('.$value['product_attr'].($value['product_date']?'-'.$value['product_date']:'').')':'');
            $attributes[$value['post_id']]['product_comment'] = $value['product_comment'];
    		$attributes[$value['post_id']]['product_date'] = $value['product_date'];
    		$attributes[$value['post_id']]['product_price'] = $value['product_price'];
    		$attributes[$value['post_id']]['product_time'] = $value['product_time'];
            $attributes[$value['post_id']]['quantity'] = $value['quantity'];
            $attributes[$value['post_id']]['product_total_price'] = $value['quantity'] * $value['product_price'];
            $productDetails[$value['post_id']] = $post;
    		$price += $value['product_price']*$value['quantity'];
    	}

    	$random = rand(000000, 999999);
    	$order = new ProductOrder;
    	$order->user_id = $user_id;
    	$order->post_id = 0;
    	$order->attributes = maybe_encode($attributes);
    	$order->product_detail = maybe_encode($productDetails);
    	$order->sub_total = $price;
    	$order->grand_total = $price;
    	$order->billing_address = maybe_encode($billingAddress);
    	$order->shipping_address = maybe_encode($billingAddress);
    	$order->payment_id = 0;
    	$order->transaction_id = 0;
    	$order->save();
    	$order->transaction_id = $random.$order->order_id;
    	$order->save();

        $emailTo = $request->input('email');
        $emailSubject = 'Order Placed';

        $order->attributes = maybe_decode($order->attributes);
        $order->product_detail = maybe_decode($order->product_detail);
        $order->billing_address = maybe_decode($order->billing_address);
        $order->shipping_address = maybe_decode($order->shipping_address);

        $emailBody = view('Email.OrderPlaced', compact('order'));

        SendEmail($emailTo, $emailSubject, $emailBody, [], '', '', '', '');

    	return Response()->json(['status'=>true,'message'=>'Order placed successfully','response'=>compact('order','user','token')],200);
    }
}
