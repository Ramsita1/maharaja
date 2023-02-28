<?php

namespace App\Http\Controllers\Admin\Comment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth;
use App\Comment;
use App\CommentMeta;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function index(Request $request)
    {
        
        $comments = Comment::paginate(pagination());
        $view = 'Admin.Comment.Index';
        return view('Admin', compact('view','comments'));
    }
    public function destroy($id)
    {
        $comment = Comment::find($id);
        CommentMeta::where('comment_id', $id)->delete();
        $comment->delete();
        Session::flash ( 'success', "Comment Deleted." );
        return Redirect::route('comment.index'); 
    }
    public function deleteAll(Request $request)
    {
        $postIds = explode(',', $request->input('postIds'));
        $action = $request->input('action');
        if ($action == 'delete') {
            CommentMeta::whereIn('comment_id', $postIds)->delete();
            Comment::whereIn('comment_ID', $postIds)->delete();
        }         
    }
    
}
