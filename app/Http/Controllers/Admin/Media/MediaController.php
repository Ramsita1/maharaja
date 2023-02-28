<?php

namespace App\Http\Controllers\Admin\Media;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB, DateTime, Session, Redirect, Auth;
use App\Posts;
use App\PostMetas;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function index()
    {
        $view = 'Admin.Media.Index';
        $gallery = self::gallery(false);
        return view('Admin', compact('view','gallery'));
    }

    public function modal()
    {
        echo self::galleryThumbs();
    }

    public function gallery($showControl = true)
    {
        $gallery = self::galleryThumbs();
        return view('Admin.Media.Modal', compact('showControl','gallery'));
    }
    public function galleryThumbs()
    {
        $posts = Posts::where('post_type', 'media')
                  ->where(function ($query){
                    if (Request()->get('searchKey')) {
                      $query->where('post_title', 'LIKE', '%'.Request()->get('searchKey').'%');
                    }
                  })->orderBy('post_id', 'desc')->paginate(pagination());
        ob_start();
            ?>
            <div class="row">
                <?php
               foreach ($posts as $post) {
                  ?>
                     <div class="mb-3 pics  col-md-3">
                        <div class="animation" data-media_id="<?php echo $post->post_id ?>" data-media_url="<?php echo publicPath().'/'.$post->media ?>" data-media_show_url="<?php echo $post->media ?>">
                           <img class="img-fluid" src="<?php echo publicPath().'/'.$post->media ?>" alt="<?php echo $post->post_title ?>">
                           <a class="removeThumbanil" data-media_id="<?php echo $post->post_id ?>"><i class="ti-trash"></i></a>
                           <a class="editThumbanil" data-media_id="<?php echo $post->post_id ?>"><i class="ti-pencil-alt"></i></a>
                           <div class="editAltTag" id="post_title_<?php echo $post->post_id ?>_popup">
                            <label for="post_title_<?php echo $post->post_id ?>">Alt Text</label>
                            <input type="text" name="post_title" class="form-control" id="post_title_<?php echo $post->post_id ?>" value="<?php echo $post->post_title ?>">
                            <button class="btn btn-success saveMediaTitle" type="button" data-media_id="<?php echo $post->post_id ?>">Save</button>
                           </div>
                        </div>
                     </div>
                  <?php
                }
                ?>  
            </div>
        <div class="ajax-pagination">
            <?php
            echo $posts->links();
            ?>
        </div>
        <?php
        return ob_get_clean();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file = $request->file('file');
        $post_title = $file->getClientOriginalName();
        $postCount = Posts::where('post_title', $post_title)->get()->count();
        if ($postCount > 0) {
            $post_name = $post_title.' '.$postCount;
        }else{
            $post_name = $post_title;
        }

        $post_name = str_slug($post_name, '-');
        $post = new Posts();
        $post->post_title = $post_title;
        $post->post_name = $post_name;
        $post->user_id = Auth::user()->user_id;
        $post->post_content = '';
        $post->post_excerpt = '';
        $post->post_status = 'publish';
        $post->post_parent = 0;
        $post->comment_status = 'close';
        $post->menu_order = 0;
        $post->post_type = 'media';
        $post->media = fileuploadExtra($request, 'file');
        $post->comment_count = 0;
        $post->created_at = new DateTime;
        $post->updated_at = new DateTime;
        $post->save();

        echo json_encode(['media_id' => $post->post_id, 'thumbnail'=> publicPath().'/'.$post->media]);
        die;
    }
    public function update(Request $request)
    {
      $id = $request->input('post_id');
      $post_title = $request->input('post_title');
      $post = Posts::find($id);
      $post->post_title = $post_title;
      $post->updated_at = new DateTime;
      $post->save();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('post_id');
        $post = Posts::find($id);
        $post->delete();

        echo self::galleryThumbs();
    }
}
