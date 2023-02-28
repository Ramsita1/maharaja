<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect;
use App\Posts;
use App\PostMetas;
use App\User;

class FrontController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    
        $view = 'Templates.Home';
        $title = 'Home | Maharaja Hotel';
        $sliders = Posts::where('posts.post_type', 'slider')
                    ->leftJoin('posts as getImage','getImage.post_id','posts.guid')
                    ->select('posts.post_title','posts.post_content','getImage.media as post_image','getImage.post_title as post_image_alt')
                    ->where('posts.post_status', 'publish')
                    ->where('posts.post_lng', defaultLanguage())
                    ->get();
        return view('Front', compact('view','title','sliders'));
    } 
    public function single($slug = null)
    {
        $post = Posts::where('posts.post_name', $slug)
                    ->leftJoin('posts as getImage','getImage.post_id','posts.guid')
                    ->leftJoin('users as user','user.user_id','posts.user_id')
                    ->select('posts.*','getImage.media as post_image', 'user.name as user_name','getImage.post_title as post_image_alt')
                    ->where('posts.post_status', 'publish')
                    ->where('posts.post_lng', defaultLanguage())
                    ->get()->first();
        if (!$post) {
           return Redirect('/');
        }
        $post->extraFields = getPostMeta($post->post_id);
        $post->posted_date = date('M d, Y', strtotime($post->created_at));
        $post->posted_time = date('h:i A', strtotime($post->created_at));
        $view = 'Templates.'.$post->post_template;
        $title = $post->post_title.' | Maharaja Hotel';
        if ($post->post_template == 'Checkout') {
            $itemPage = true;
            $requestPerameters = Session::get('delivery_pickup_address');
            if (!isset($requestPerameters['store']) || !$requestPerameters['store']) {
                return Redirect('/');
            }
            $storeSlug = explode('-', $requestPerameters['store']);
            $store_id = end($storeSlug);
            $store = \App\Stores::where('store_id', $store_id)->get()->first();
            return view('OrderFront', compact('view','post','title','itemPage','requestPerameters','store'));
        }
        if (in_array($post->post_template, ['MyAccount','ThankYou','Cancel'])) {
            return view('OrderFront', compact('view','post','title'));
        }
        return view('Front', compact('view','post','title'));
    }   
    public function paymentCheckout($slug = 'Checkout')
    {
        $post = Posts::where('posts.post_name', $slug)
                    ->leftJoin('posts as getImage','getImage.post_id','posts.guid')
                    ->leftJoin('users as user','user.user_id','posts.user_id')
                    ->select('posts.*','getImage.media as post_image', 'user.name as user_name','getImage.post_title as post_image_alt')
                    ->where('posts.post_status', 'publish')
                    ->where('posts.post_lng', defaultLanguage())
                    ->get()->first();
        if ($post) {
           $post->extraFields = getPostMeta($post->post_id);
           $post->posted_date = date('M d, Y', strtotime($post->created_at));
           $post->posted_time = date('h:i A', strtotime($post->created_at));
        }
        $view = 'Templates.'.$post->post_template;
        $title = $post->post_title.' | Maharaja Hotel';
        if ($post->post_template == 'Checkout') {
            $itemPage = true;
            $requestPerameters = Session::get('delivery_pickup_address');
            $storeSlug = explode('-', $requestPerameters['store']);
            $store_id = end($storeSlug);
            $store = \App\Stores::where('store_id', $store_id)->get()->first();
            return view('OrderFront', compact('view','post','title','itemPage','requestPerameters','store'));
        }
        return view('Front', compact('view','post','title'));
    } 
}
