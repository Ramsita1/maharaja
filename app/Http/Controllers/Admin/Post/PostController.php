<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth;
use App\Posts;
use App\Terms;
use App\Links;
use App\PostMetas;
use App\TermRelations;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function index(Request $request)
    {
        $view = 'Admin.Post.Index';
        $postType = $request->get('postType');
        $postTitle = getPostType($postType);
        
        if (empty($postTitle)) {
            Session::flash ( 'warning', "Something went wrong, Please try after sometime." );
            return redirect()->route('dashboard.index');  
        }
        $post_lng = defaultLanguage();
        $posts = Posts::where('post_type', $postType)
                    ->select('*', DB::raw("(SELECT name FROM users where users.user_id = posts.user_id LIMIT 0, 1) as post_author"))
                    ->where('post_status', '!=', 'trash')
                    ->where('post_lng', $post_lng)
                    ->orderBy('menu_order', 'ASC')
                    ->paginate(pagination());
        if (!empty($postTitle['taxonomy']) && is_array($postTitle['taxonomy'])) {
            foreach ($posts as $post) {
                $termCollections = [];
                foreach ($postTitle['taxonomy'] as $taxonomyKey => $taxonomyValue) {
                    $termRelations = TermRelations::where('object_id', $post->post_id)->select('term_id');
                    $terms = Terms::where('term_group', $taxonomyKey)->whereIn('term_id', $termRelations)->select('name')->get();
                    $termCollection = [];
                    foreach ($terms as $term) {
                        $termCollection[] = $term->name;
                    }
                    $termCollections[$taxonomyKey] = implode(',<br>', $termCollection);
                }   
                $post->category = $termCollections;
            }
        }    
        return view('Admin', compact('view','postTitle','postType','posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $view = 'Admin.Post.Create';
        $projectCategory=[];
        $postType = $request->get('postType');
        $postTitle = getPostType($postType);
        if (empty($postTitle)) {
            Session::flash ( 'warning', "Something went wrong, Please try after sometime." );
            return redirect()->route('dashboard.index');  
        }
        return view('Admin', compact('view','postTitle','postType','projectCategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $langauge = $request->input('langauge');
        $getSupportLNG = getSupportLNG();
        $post_lng = '';
        $post_parent = '';
        foreach ($getSupportLNG as $key => $value) {
            if (isset($getSupportLNG[$key]['type']) && $getSupportLNG[$key]['type'] == 'default') {
                $post_lng = $key;
                $postType = $request->get('postType');
                $postTitle = getPostType($postType);
                $title = ($langauge[$key]['post_title']?$langauge[$key]['post_title']:'draft');
                if (empty($postTitle)) {
                    Session::flash ( 'warning', "Something went wrong, Please try after sometime." );
                    return redirect()->route('dashboard.index');  
                }
                $postCount = Posts::where('post_title', $title)->get()->count();
                if ($postCount > 0) {
                    $post_name = $title.' '.$postCount;
                }else{
                    $post_name = $title;
                }
                if($request->input('createSiteMap') == 'yes'){
                    $postUrl = '';
                    if (in_array($postType, ['page'])) {
                        $postUrl = siteUrl().'/'.$post_name;
                    } else {
                        $postUrl = siteUrl().'/'.$postType.'/'.$post_name;
                    }
                    createUpdateSiteMapXML($postUrl);
                }      
                if($request->input('createSiteMap') == 'no'){
                    $postUrl = '';
                    if (in_array($postType, ['page'])) {
                        $postUrl = siteUrl().'/'.$post_name;
                    } else {
                        $postUrl = siteUrl().'/'.$postType.'/'.$post_name;
                    }
                    deleteSiteMapXML($postUrl);
                }          
                $post_name = str_slug($post_name, '-');
                $post = new Posts();
                $post->post_title = $title;
                $post->post_name = $post_name;
                $post->user_id = Auth::user()->user_id;
                $post->post_content = (isset($langauge[$key]['post_content'])?$langauge[$key]['post_content']:'');
                $post->post_excerpt = (isset($langauge[$key]['post_excerpt'])?$langauge[$key]['post_excerpt']:'');
                $post->post_status = $request->input('post_status');
                $post->post_parent = 0;
                $post->comment_status = $request->input('comment_status');
                $post->guid = $request->input('guid');
                $post->post_template = $request->input('post_template');
                $post->menu_order = 0;
                $post->post_type = $postType;
                $post->post_lng = $key;
                $post->comment_count = 0;
                $post->created_at = new DateTime;
                $post->updated_at = new DateTime;
                $post->save();
                updatePostMeta($post->post_id, 'meta_Keywords', (isset($langauge[$key]['meta_Keywords'])?$langauge[$key]['meta_Keywords']:''));
                updatePostMeta($post->post_id, 'createSiteMap', $request->input('createSiteMap'));
                updatePostMeta($post->post_id, 'meta_title', (isset($langauge[$key]['meta_title'])?$langauge[$key]['meta_title']:''));
                updatePostMeta($post->post_id, 'meta_description', (isset($langauge[$key]['meta_description'])?$langauge[$key]['meta_description']:''));
                
                self::insertUpdateTerms($post->post_id);
                insertUpdatePostMetaBox($postType, $request, $post->post_id);
                $post_parent = $post->post_id;
                break;
            }
        }
        $postTitle = getPostType($postType);
        if ($postTitle['multilng'] == 'true') {
            foreach ($getSupportLNG as $key => $value) {
                if ($post_lng != $key) {
                    $title = ($langauge[$key]['post_title']?$langauge[$key]['post_title']:'draft');

                    $postType = $request->get('postType');
                    $postTitle = getPostType($postType);
                    if (empty($postTitle)) {
                        Session::flash ( 'warning', "Something went wrong, Please try after sometime." );
                        return redirect()->route('dashboard.index');  
                    }
                    $postCount = Posts::where('post_title', $title)->get()->count();
                    if ($postCount > 0) {
                        $post_name = $title.' '.$postCount;
                    }else{
                        $post_name = $title;
                    }
                    $post_name = str_slug($post_name, '-');
                    $post = new Posts();
                    $post->post_title = $title;
                    $post->post_name = $post_name;
                    $post->user_id = Auth::user()->user_id;
                    $post->post_content = (isset($langauge[$key]['post_content'])?$langauge[$key]['post_content']:'');
                    $post->post_excerpt = (isset($langauge[$key]['post_excerpt'])?$langauge[$key]['post_excerpt']:'');
                    $post->post_status = $request->input('post_status');
                    $post->post_parent = $post_parent;
                    $post->comment_status = $request->input('comment_status');
                    $post->guid = $request->input('guid');
                    $post->post_template = $request->input('post_template');
                    $post->menu_order = 0;
                    $post->post_type = $postType;
                    $post->post_lng = $key;
                    $post->comment_count = 0;
                    $post->created_at = new DateTime;
                    $post->updated_at = new DateTime;
                    $post->save();

                    self::insertUpdateTerms($post->post_id);
                    updatePostMeta($post->post_id, 'meta_Keywords', (isset($langauge[$key]['meta_Keywords'])?$langauge[$key]['meta_Keywords']:''));
                    updatePostMeta($post->post_id, 'meta_title', (isset($langauge[$key]['meta_title'])?$langauge[$key]['meta_title']:''));
                    updatePostMeta($post->post_id, 'meta_description', (isset($langauge[$key]['meta_description'])?$langauge[$key]['meta_description']:''));

                    insertUpdatePostMetaBox($postType, $request, $post->post_id);
                }
            }
        }        

        Session::flash ( 'success', $postTitle['title']." saved." );
        return Redirect::route('post.index', ['postType'=>$postType]);  
    }
    public static function insertUpdateTerms($post_id){
        $terms = Request()->input('terms');
        $notTermIn = [];
        if (!empty($terms) && is_array($terms)) {
            foreach ($terms as $term) {
                $termSelected = Terms::where('term_id', $term)->select('slug','post_type','term_group')->first();
                
                if ($relation = TermRelations::where('term_id', $term)->where('object_id', $post_id)->get()->first()) {
                    $relation->created_at = new DateTime;
                    $relation->term_id = $term;
                    $relation->object_id = $post_id;
                    $relation->updated_at = new DateTime;
                    $relation->save();
                    $notTermIn[] = $relation->term_id;
                }else{
                    $relation = new TermRelations();
                    $relation->created_at = new DateTime;
                    $relation->term_id = $term;
                    $relation->object_id = $post_id;
                    $relation->updated_at = new DateTime;
                    $relation->save();
                    $notTermIn[] = $relation->term_id;
                }
            }
            TermRelations::wherenotIn('term_id', $notTermIn)->where('object_id', $post_id)->delete();
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($post_id)
    {
        $view = 'Admin.Post.Edit';
        $post = Posts::find($post_id);
        $subPosts = Posts::where('post_parent', $post_id)->get();
        $posts = $subPosts->add($post);
        $postTitle = getPostType($post->post_type);
        $postType = $post->post_type;
        $thumbnail = Posts::where('post_id', $post->guid)->select('media')->get()->pluck('media')->first();
        return view('Admin', compact('view','post_id','posts','thumbnail','postTitle', 'postType'));
    }
    public function clone($post_id = null)
    {
        $clonePost = Posts::find($post_id);
        $title = $clonePost->post_title;
        $post = new Posts();

        $postCount = Posts::where('post_name', $clonePost->post_name)->get()->count();
        if ($postCount > 0) {
            $post_name = $title.' '.$postCount;
        }else{
            $post_name = $title;
        }

        $post_name = str_slug($post_name, '-');
        if (empty($post->post_name)) {
            $post->post_name = $post_name;
        }

        $post->post_title = $title;
        $post->user_id = $clonePost->user_id;
        $post->post_content = $clonePost->post_content;
        $post->post_excerpt = $clonePost->post_excerpt;
        $post->post_status = $clonePost->post_status;
        $post->post_parent = $clonePost->post_parent;
        $post->comment_status = $clonePost->comment_status;
        $post->guid = $clonePost->guid;
        $post->menu_order = 0;
        $post->post_type = $clonePost->post_type;
        $post->post_lng = $clonePost->post_lng;
        $post->post_template = $clonePost->post_template;
        $post->comment_count = 0;
        $post->created_at = new DateTime;
        $post->updated_at = new DateTime;
        $post->save();
        updatePostMeta($post->post_id, 'meta_Keywords', getPostMeta($clonePost->post_id, 'meta_Keywords'));
        updatePostMeta($post->post_id, 'meta_title', getPostMeta($clonePost->post_id, 'meta_title'));
        updatePostMeta($post->post_id, 'meta_description', getPostMeta($clonePost->post_id, 'meta_description'));
        $postTitle = getPostType($post->post_type);
        Session::flash ( 'success', $postTitle['title']." Cloned." );
        return Redirect::route('post.index', ['postType'=>$post->post_type]);  
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
        $langauge = $request->input('langauge');
        $getSupportLNG = getSupportLNG();
        $post_lng = '';
        $post_parent = '';
        $parent_post = [];
        foreach ($getSupportLNG as $key => $value) {
            if (isset($getSupportLNG[$key]['type']) && $getSupportLNG[$key]['type'] == 'default') {
                $post_lng = $key;
                $title = ($langauge[$key]['post_title']?$langauge[$key]['post_title']:'draft');
                if (!$post = Posts::where('post_id', $id)->where('post_lng', $key)->get()->first()) {
                    $post = new Posts();
                    $postCount = Posts::where('post_title', $title)->get()->count();
                    if ($postCount > 0) {
                        $post_name = $title.' '.$postCount;
                    }else{
                        $post_name = $title;
                    }
                    $post->post_name = str_slug($post_name, '-');
                }
                if (empty($post->post_name)) {
                    $post->post_name = str_slug($title, '-');
                }
          

                if($request->input('createSiteMap') == 'yes'){
                    $postUrl = '';
                    if (in_array($post->post_type, ['page'])) {
                        $postUrl = siteUrl().'/'.$post->post_name;
                    } else {
                        $postUrl = siteUrl().'/'.$post->post_type.'/'.$post->post_name;
                    }
                    createUpdateSiteMapXML($postUrl);
                }      
                if($request->input('createSiteMap') == 'no'){
                    $postUrl = '';
                    if (in_array($post->post_type, ['page'])) {
                        $postUrl = siteUrl().'/'.$post->post_name;
                    } else {
                        $postUrl = siteUrl().'/'.$post->post_type.'/'.$post->post_name;
                    }
                    deleteSiteMapXML($postUrl);
                }

                $parent_post = $post;
                $post->post_title = $title;
                $post->user_id = Auth::user()->user_id;
                $post->post_content = (isset($langauge[$key]['post_content'])?$langauge[$key]['post_content']:'');
                $post->post_excerpt = (isset($langauge[$key]['post_excerpt'])?$langauge[$key]['post_excerpt']:'');
                $post->post_status = $request->input('post_status');
                $post->comment_status = $request->input('comment_status');
                $post->guid = $request->input('guid');
                $post->post_template = $request->input('post_template');
                $post->updated_at = new DateTime;
                $post->save();
                self::insertUpdateTerms($post->post_id);
                $post_parent = $post->post_id;
                updatePostMeta($post->post_id, 'meta_Keywords', (isset($langauge[$key]['meta_Keywords'])?$langauge[$key]['meta_Keywords']:''));
                updatePostMeta($post->post_id, 'createSiteMap', $request->input('createSiteMap'));
                updatePostMeta($post->post_id, 'meta_title', (isset($langauge[$key]['meta_title'])?$langauge[$key]['meta_title']:''));
                updatePostMeta($post->post_id, 'meta_description', (isset($langauge[$key]['meta_description'])?$langauge[$key]['meta_description']:''));
                insertUpdatePostMetaBox($post->post_type, $request, $post->post_id);
                break;
            }
        }
        $postTitle = getPostType($parent_post->post_type);
        if ($postTitle['multilng'] == 'true') {
            foreach ($getSupportLNG as $key => $value) {
                if ($post_lng != $key) {

                    if (!$post = Posts::where('post_parent', $id)->where('post_lng', $key)->get()->first()) {
                        $post = new Posts();
                    }
                    $title = ($langauge[$key]['post_title']?$langauge[$key]['post_title']:'draft');
                    $post->post_title = $title;
                    if (empty($post->post_name)) {
                        $post->post_name = str_slug($title, '-');
                    }
                    $post->user_id = Auth::user()->user_id;
                    $post->post_content = (isset($langauge[$key]['post_content'])?$langauge[$key]['post_content']:'');
                    $post->post_excerpt = (isset($langauge[$key]['post_excerpt'])?$langauge[$key]['post_excerpt']:'');
                    $post->post_status = $request->input('post_status');
                    $post->post_parent = $post_parent;
                    $post->comment_status = $request->input('comment_status');
                    $post->guid = $request->input('guid');
                    $post->post_template = $request->input('post_template');
                    $post->menu_order = 0;
                    $post->post_type = $parent_post->post_type;
                    $post->post_lng = $key;
                    $post->comment_count = 0;
                    $post->updated_at = new DateTime;
                    $post->save();
                    self::insertUpdateTerms($post->post_id);
                    updatePostMeta($post->post_id, 'meta_Keywords', (isset($langauge[$key]['meta_Keywords'])?$langauge[$key]['meta_Keywords']:''));
                    updatePostMeta($post->post_id, 'meta_title', (isset($langauge[$key]['meta_title'])?$langauge[$key]['meta_title']:''));
                    updatePostMeta($post->post_id, 'meta_description', (isset($langauge[$key]['meta_description'])?$langauge[$key]['meta_description']:''));
                    insertUpdatePostMetaBox($post->post_type, $request, $post->post_id);
                }
            }     
        }   

        Session::flash ( 'success', $postTitle['title']." updated." );
        return Redirect::route('post.index', ['postType'=>$post->post_type]);  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Posts::find($id);
        $post->post_status = 'trash';
        $post->save();
        $link = Links::where('post_id',$id)->update(['link_visible'=>'N']);
        $postTitle = getPostType($post->post_type);
        Session::flash ( 'success', $postTitle['title']." Trashed." );
        return Redirect::route('post.index', ['postType'=>$post->post_type]); 
    }
    public function deleteAll(Request $request)
    {
        $postIds = explode(',', $request->input('postIds'));
        Posts::whereIn('post_id', $postIds)->update(['post_status'=>'trash']);
    }
    public function updateOrder(Request $request){
        $orders = $request->input('order');
        if (!empty($orders) && is_array($orders)) {
            $index = 1;
            foreach ($orders as $order) {
                $post = Posts::find($order);
                $post->menu_order = $index;
                $post->save();
                $index++;
            }
        }
    }
    public function updatePostName(Request $request){
        $post_name = $request->input('post_name');
        $post_id = $request->input('post_id');

        $post = Posts::find($post_id);

        $postCount = Posts::where('post_name', $post_name)->where('post_id','!=',$post_id)->get()->count();
        if ($postCount > 0) {
            $post_name = $post_name.' '.$postCount;
        }
        $post->post_name = str_slug($post_name, '-');
        $post->save();
        return $post->post_name;
    }
    
}
