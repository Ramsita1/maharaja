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

class TaxonomyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function index(Request $request)
    {
        $view = 'Admin.Taxonomy.Index';
        $postType = $request->get('postType');
        $taxonomy = $request->get('taxonomy');
        $postTitle = getPostType($postType);
        if (empty($postTitle)) {
            Session::flash ( 'warning', "Something went wrong, Please try after sometime." );
            return redirect()->route('dashboard.index');  
        }
        $taxonomyTitle = getTaxonomyType($postType, $taxonomy);
        if (empty($taxonomyTitle)) {
            Session::flash ( 'warning', "Something went wrong, Please try after sometime." );
            return redirect()->route('dashboard.index');  
        }
        $terms = Terms::select('*', DB::raw("(SELECT COUNT(*) as total FROM posts WHERE posts.post_type = '".$postType."' AND posts.post_lng = '".defaultLanguage()."' AND posts.post_id in (SELECT object_id from term_relationships as tm WHERE tm.term_id = terms.term_id)) as count"))
                    ->where('term_group', $taxonomy)->paginate(pagination());
        $parentTerms = Terms::where('term_group', $taxonomy)->where('post_type', $postType)->get();
        $posts = [];
        if(isset($postTitle['taxonomy'][$taxonomy]['showPost']) && !empty($postTitle['taxonomy'][$taxonomy]['showPost']) && is_array($postTitle['taxonomy'][$taxonomy]['showPost'])){
            $posts = Posts::whereIn('post_type', $postTitle['taxonomy'][$taxonomy]['showPost'])
                            ->where('post_status','publish')
                            ->where('post_lng',defaultLanguage())
                            ->select('post_id','post_title')->get()->toArray();
        }
        return view('Admin', compact('view','postTitle','postType','terms', 'taxonomy', 'parentTerms','taxonomyTitle','posts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $postType = $request->get('postType');
        $taxonomy = $request->get('taxonomy');
        $postTitle = getPostType($postType);
        if (empty($postTitle)) {
            Session::flash ( 'warning', "Something went wrong, Please try after sometime." );
            return redirect()->route('dashboard.index');  
        }
        $taxonomyTitle = $taxonomy;
        $title = ($request->input('name')?$request->input('name'):'draft');
        $postCount = Terms::where('name', $title)->get()->count();
        if ($postCount > 0) {
            $slug = $title.' '.$postCount;
        }else{
            $slug = $title;
        }

        $slug = str_slug($slug, '-');
        $term = new Terms();
        $term->name = $title;
        $term->slug = $slug;
        $term->parent = $request->input('parent');
        $term->description = $request->input('description');
        $term->term_group = $taxonomy;
        $term->post_type = $postType;
        $term->created_at = new DateTime;
        $term->updated_at = new DateTime;
        $term->save();
        updateTermMeta($term->term_id, 'createSiteMap', $request->input('createSiteMap'));
        if(Request()->input('createSiteMap') == 'yes'){                    
            $postUrl = siteUrl().'/'.$term->post_type.'/'.$term->term_group.'/'.$term->slug;
            createUpdateSiteMapXML($postUrl);
        }      
        if(Request()->input('createSiteMap') == 'no'){
            $postUrl = siteUrl().'/'.$term->post_type.'/'.$term->term_group.'/'.$term->slug;
            deleteSiteMapXML($postUrl);
        }

        insertUpdateTermMetaBox($taxonomy, $request, $term->term_id);

        Session::flash ( 'success', "Term saved." );

        return Redirect::back();  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($term_id)
    {
        $view = 'Admin.Taxonomy.Edit';
        $term = Terms::where('term_id', $term_id)->first();
        
        $taxonomyTitle = getTaxonomyType($term->post_type, $term->term_group);
        $parentTerms = Terms::where('term_group', $term->term_group)->where('term_id', '!=', $term_id)->get();
        $postTitle = getPostType($term->post_type);
        $posts = [];
        $taxonomy = $term->term_group;
        if(isset($postTitle['taxonomy'][$taxonomy]['showPost']) && !empty($postTitle['taxonomy'][$taxonomy]['showPost']) && is_array($postTitle['taxonomy'][$taxonomy]['showPost'])){
            $posts = Posts::whereIn('post_type', $postTitle['taxonomy'][$taxonomy]['showPost'])
                            ->where('post_status','publish')
                            ->where('post_lng',defaultLanguage())
                            ->select('post_id','post_title')->get()->toArray();
        }
        return view('Admin', compact('view','term', 'parentTerms','taxonomyTitle','term_id','posts'));
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
        $term = Terms::find($id);
        $term->name = $request->input('name');
        $term->parent = $request->input('parent');
        $term->description = $request->input('description');
        $term->updated_at = new DateTime;
        $term->save();
        updateTermMeta($term->term_id, 'createSiteMap', $request->input('createSiteMap'));
        if(Request()->input('createSiteMap') == 'yes'){                    
            $postUrl = siteUrl().'/'.$term->post_type.'/'.$term->term_group.'/'.$term->slug;
            createUpdateSiteMapXML($postUrl);
        }      
        if(Request()->input('createSiteMap') == 'no'){
            $postUrl = siteUrl().'/'.$term->post_type.'/'.$term->term_group.'/'.$term->slug;
            deleteSiteMapXML($postUrl);
        }

        insertUpdateTermMetaBox($term->term_group, $request, $term->term_id);
        
        $taxonomyTitle = getTaxonomyType($term->post_type, $term->term_group);

        Session::flash ( 'success', "Term updated." );
        if (empty($taxonomyTitle)) {
            return Redirect::route('taxonomy.configureTerms', ['taxonomy'=>$term->term_group,'postType'=>$term->post_type]);
        }
        return Redirect::route('taxonomy.index', ['postType'=>$term->post_type,'taxonomy'=>$term->term_group]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $term = Terms::find($id);
        $term->delete();

        Session::flash ( 'success', "Term deleted." );
        return Redirect::back(); 
    }
    public function configureTerms($postType, $taxonomy)
    {
        $view = 'Admin.Taxonomy.Index';
        $postTitle = getPostType($postType);
        if (empty($postTitle)) {
            Session::flash ( 'warning', "Something went wrong, Please try after sometime." );
            return redirect()->route('dashboard.index');  
        }
        $taxonomyTitle = $taxonomy;
        $terms = Terms::select('*', DB::raw("(SELECT COUNT(*) as total FROM posts WHERE posts.post_type = '".$postType."' AND posts.post_lng = '".defaultLanguage()."' AND posts.post_id in (SELECT object_id from term_relationships as tm WHERE tm.term_id = terms.term_id)) as count"))
                    ->where('term_group', $taxonomy)->paginate(pagination());
        $parentTerms = Terms::where('term_group', $taxonomy)->where('post_type', $postType)->get();
        $posts = [];
        return view('Admin', compact('view','postTitle','postType','terms', 'taxonomy', 'parentTerms','taxonomyTitle','posts'));
    }
}
