<?php

namespace App\Http\Controllers\Admin\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB, DateTime, Session, Redirect, Auth;
use App\Posts;
use App\Terms;
use App\Links;

class MenuController extends Controller
{
    public function index(){
        $menu_data = [];   
        foreach(postTypes() as $key=>$value){
            if ($value['showMenu'] == true) {
                $menu_data[$key] = Posts::where('post_type',$key)
                                    ->where('post_lng',defaultLanguage())
                                    ->where('post_status','publish')
                                    ->select(['post_id','post_title','post_name','post_type'])
                                    ->get()->toArray();
            }                        
        }
        $category_data = [];
        foreach(postTypes() as $key=>$value)
        {
            if (!empty($value['taxonomy'])) {
                foreach ($value['taxonomy'] as $taxonomyKey => $taxonomyValue) {
                    if ($taxonomyValue['showMenu'] == true) {
                        $category_data[$taxonomyKey]['title'] = $taxonomyValue['title'];
                        $category_data[$taxonomyKey]['menus'] = Terms::where('term_group', $taxonomyKey)
                                    ->select('term_id','name','term_group','post_type')
                                    ->get()->toArray();
                    }
                }
            }
        }
        $view = 'Admin/Menu/Index';
        $menusHtml = [];
        foreach(registerNavBarMenu() as $menuKey => $menuValue)
        {
            $menusHtml[$menuKey] = self::getMenusByKey($menuKey);
        }
        return view('Admin',compact('view','menu_data','menusHtml','category_data'));
    }

    public function getMenusByKey($menuKey) {
        $menus = Links::where('link_visible','Y')->where('link_rel', $menuKey)->where('link_parent', 0)->orderBy('link_order', 'ASC')->get()->toArray();
        ob_start();
        if ($menus) {
            $firstCount = 0;
            foreach ($menus as $menu) {          
                ?>
                <li class="dd-item" data-link_id="<?php echo $menu['link_id'] ?>" data-post_id="<?php echo $menu['post_id'] ?>" data-id="<?php echo $firstCount; ?>" data-target="<?php echo $menu['link_target'] ?>" data-targetType="<?php echo $menu['target_type'] ?>">
                    <div class="dd-handle">
                        <span><?php echo $menu['link_name'] ?></span>   
                        <a href="javascript:void(0)" class="close close-assoc-file" data-dismiss="alert" aria-label="close">×</a>
                    </div>
                    <?php 
                    $childMenus = Links::where('link_visible','Y')->where('link_rel', $menuKey)->where('link_parent', $menu['link_id'])->orderBy('link_order', 'ASC')->get()->toArray();
                    if ($childMenus) {
                        ?>
                        <ol class="dd-list">
                        <?php
                        $secondCount = 0;
                        foreach ($childMenus as $childMenu) {          
                            ?>
                            <li class="dd-item" data-link_id="<?php echo $childMenu['link_id'] ?>" data-post_id="<?php echo $childMenu['post_id'] ?>" data-id="<?php echo $secondCount ?>" data-target="<?php echo $childMenu['link_target'] ?>" data-targetType="<?php echo $childMenu['target_type'] ?>">
                                <div class="dd-handle">                                    
                                    <span><?php echo $childMenu['link_name'] ?></span>   
                                    <a href="javascript:void(0)" class="close close-assoc-file" data-dismiss="alert" aria-label="close">×</a>
                                </div>
                                <?php 
                                $innerChildMenus = Links::where('link_visible','Y')->where('link_rel', $menuKey)->where('link_parent', $childMenu['link_id'])->orderBy('link_order', 'ASC')->get()->toArray();
                                if ($innerChildMenus) {
                                    ?>
                                    <ol class="dd-list">
                                    <?php
                                    $thirdCount = 0;
                                    foreach ($innerChildMenus as $innerChildMenu) {          
                                        ?>
                                        <li class="dd-item" data-link_id="<?php echo $innerChildMenu['link_id'] ?>" data-post_id="<?php echo $innerChildMenu['post_id'] ?>" data-id="<?php echo $thirdCount ?>" data-target="<?php echo $innerChildMenu['link_target'] ?>" data-targetType="<?php echo $innerChildMenu['target_type'] ?>">
                                            <div class="dd-handle">                 
                                                <span> <?php echo $innerChildMenu['link_name'] ?></span>   
                                                <a href="javascript:void(0)" class="close close-assoc-file" data-dismiss="alert" aria-label="close">×</a>
                                            </div>
                                        </li>
                                        <?php
                                        $thirdCount++;
                                    }
                                    ?>
                                    </ol>
                                    <?php
                                }
                                ?>
                            </li>
                            <?php
                            $secondCount++;
                        }
                        ?>
                        </ol>
                        <?php
                    }
                    ?>
                </li>
                <?php
                $firstCount++;
            }
        }
        return ob_get_clean();
    }
    public function addMenuItems(Request $request){
        $menuRelation = $request->input('relation');
        $menuOrders = $request->input('menuOrder');
        foreach($menuOrders as $key => $menuOrder) {
            $parentFirst = self::insertUpdateMenu($menuOrder['link_id'], $key, $menuOrder['post_id'], 0, $menuRelation, $menuOrder['target'], $menuOrder['targettype']);
            if(isset($menuOrder['children']) && !empty($menuOrder['children'])){
                foreach($menuOrder['children'] as $innerKey => $children) {
                    $parentSecond = self::insertUpdateMenu($children['link_id'], $innerKey, $children['post_id'], $parentFirst, $menuRelation, $children['target'], $children['targettype']);
                    if(isset($children['children']) && !empty($children['children'])){
                        foreach($children['children'] as $innerChildKey => $innerChild) {
                            self::insertUpdateMenu($innerChild['link_id'], $innerChildKey, $innerChild['post_id'], $parentSecond, $menuRelation, $innerChild['target'], $innerChild['targettype']);
                        }
                    }
                }
            }            
        }
    }
    public static function insertUpdateMenu($link_id, $link_order, $post_id, $link_parent, $link_rel, $link_target, $link_target_type){
        if (!$menu = Links::where('link_id', $link_id)->where('link_target', $link_target)->where('target_type', $link_target_type)->get()->first()) {
            $menu = new Links();
            $menu->updated_at = new DateTime;            
        }
        if ($link_target == 'post') {
            $post = Posts::find($post_id);
            $menu->link_url = $post->post_name;
            $menu->link_name = $post->post_title;
        } else {
            $term = Terms::find($post_id);
            $menu->link_url = $term->slug;
            $menu->link_name = $term->name;
        }
        $menu->link_rel = $link_rel;
        $menu->post_id = $post_id;
        $menu->link_target = $link_target;
        $menu->target_type = $link_target_type;
        $menu->link_order = $link_order;
        $menu->link_parent = $link_parent;
        $menu->link_visible = 'y';
        $menu->created_at = new DateTime;
        $menu->save();
        return $menu->link_id;
    }
    public function deleteMenuItems(Request $request)
    {
        $targettype = $request->input('targettype');
        $target = $request->input('target');
        $post_id = $request->input('post_id');
        $link_id = $request->input('link_id');
        $relation = $request->input('relation');
        $menu = Links::where('link_id', $link_id)->where('link_target', $target)->where('target_type', $targettype)->where('post_id', $post_id)->where('link_rel', $relation)->get()->first();
        $menu->delete();
        Links::where('link_parent', $menu->link_id)->update(['link_parent' => 0]);
        die('delete');
    }
}
