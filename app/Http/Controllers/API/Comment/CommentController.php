<?php

namespace App\Http\Controllers\API\Comment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect;
use App\Comment;
use App\CommentMeta;
use App\User;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public static function storeRules($request, $id = null)
    {
        $rules = [
            'comment_content' => 'required|max:255',
            'comment_rating' => 'required|in:1,1.5,2,2.5,3,3.5,4,4.5,5',      
        ]; 
        if ($id) {
            $rules['post_id'] = 'required';
        }else{
            $rules['comment_author'] = 'required|max:255';
            $rules['comment_author_email'] = 'required|email';
            $rules['post_id'] = 'required';
        }
        return $rules;
    }



    public function store(Request $request)
    {
        $comment_author = $comment_author_email = '';
        $user_id = 0;
        if($user = User::where('user_id',$request->input('user_id'))->get()->first()){
            
            $validator = Validator::make($request->all(), self::storeRules($request,$user->user_id));
            
            if($validator->fails()){
                return Response()->json(['status'=>false,'message'=>$validator->getMessageBag()->first(),'response' => []],200);
            }
            
            $comment_author = $user->name;
            $comment_author_email = $user->email;
            $user_id = $user->user_id;
        
        } else{
            $validator = Validator::make($request->all(), self::storeRules($request));
            
            if($validator->fails()){
                return Response()->json(['status'=>false,'message'=>$validator->getMessageBag()->first(),'response' => []],200);
            }
            
            if(!filter_var($request->input('comment_author_email'), FILTER_VALIDATE_EMAIL))
            {
                return Response()->json(['status'=>false,'message'=>'Email Must be a Valid Email'],200);
            }

            $comment_author = $request->input('comment_author');
            $comment_author_email = $request->input('comment_author_email');
        }

        $comment = new Comment;
        $comment->post_id = $request->input('post_id');
        $comment->comment_author_email = $comment_author_email;
        $comment->comment_author = $comment_author;
        $comment->comment_date = new DateTime;
        $comment->comment_content = $request->input('comment_content');
        $comment->user_id = $user_id;
        $comment->save();  
        $commentMeta = updateCommentMeta($comment->comment_ID,'comment_rating',$request->input('comment_rating'));
        $comment->rating = getCommentMeta($comment->comment_ID,'comment_rating');
        return Response()->json(['status' => true ,'message' => 'Commenting Successfull', 'response'=>compact('comment')],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
