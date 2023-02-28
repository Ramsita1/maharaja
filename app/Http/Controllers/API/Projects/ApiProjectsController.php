<?php

namespace App\Http\Controllers\API\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Posts;
use App\Terms;
use App\TermRelations;

class ApiProjectsController extends Controller
{
    public function index(){
        $terms = Terms::where('post_type','projects')->where('term_group','project_category')->select('slug','name')->get();
        $projects = Posts::where('posts.post_type','projects')
                    ->leftJoin('posts as getImage','getImage.post_id','posts.guid')
                    ->select('posts.*','getImage.media as post_image','getImage.post_title as post_image_alt')
                    ->where('posts.post_status', 'publish')
                    ->where('posts.post_lng', defaultLanguage())
                    ->orderby('posts.menu_order', 'ASC')
                    ->get();
        foreach($projects as &$project)
        {
            $termRelations = TermRelations::where('object_id', $project->post_id)->select('term_id');
            $projectTerms = Terms::whereIn('term_id', $termRelations)->select('slug')->get();
            $projectTermsData = [];
            foreach ($projectTerms as $projectTerm) {
                $projectTermsData[] = $projectTerm->slug;
            }
            $project->category = implode(' ', $projectTermsData);
            $project->extraFields = getPostMeta($project->post_id);
        }

        return Response()->json(['status'=>true,'message'=>'Projects Section','response'=>compact('terms','projects')],200);
    }
}
