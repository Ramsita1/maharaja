<div class="page-body">
   <h3><?php echo $postTitle['title']; ?></h3>
   <?php echo Form::open(['route' => array('post.update', $post_id), 'method' => 'put', 'class' => 'md-float-material']) ?>
      <div class="row">
         <div class="col-md-9 padding-right">
            <div class="card">            
               <div class="card-block accordion-block color-accordion-block">
                  <div class="color-accordion" id="color-accordion">
                        <?php
                        foreach (getSupportLNG() as $language_key => $language_value) {
                           $post = getLNGPost($posts, $language_key);
                           $attr = '';
                           if ($language_value['type'] == 'default') {
                              $attr = 'required';
                           }
                        ?>
                           <a class="accordion-msg b-none">
                              <?php echo $language_value['title'] ?> 
                              <img src="<?php echo $language_value['icon'] ?>" style="width: 20px;float: right;">
                           </a>
                           <div class="accordion-desc">
                              
                              <div class="card-block">
                                 <div class="">                          
                                    <div class="input-group row">
                                       <label class="col-form-label" for="post_title_<?php echo $language_key ?>">Title</label><br>
                                       <input type="text" name="langauge[<?php echo $language_key ?>][post_title]" <?php echo $attr; ?> id="post_title_<?php echo $language_key ?>" class="form-control form-control-lg" placeholder="Title" value="<?php echo $post->post_title ?>">
                                       <span class="md-line"></span>
                                    </div>
                                    <?php 
                                    if (isset($postTitle['support']) && is_array($postTitle['support']) && in_array('content', $postTitle['support'])) {
                                    ?>
                                       <div class="input-group row">
                                          <label class="col-form-label" for="post_content_<?php echo $language_key ?>">Content</label><br>
                                          <textarea name="langauge[<?php echo $language_key ?>][post_content]" id="post_content_<?php echo $language_key ?>" class="form-control form-control-lg ckeditor" placeholder="Content"><?php echo $post->post_content ?></textarea>
                                          <span class="md-line"></span>
                                       </div>
                                    <?php } ?>
                                    <?php 
                                    if (isset($postTitle['support']) && is_array($postTitle['support']) && in_array('excerpt', $postTitle['support'])) {
                                    ?>
                                       <div class="input-group row">
                                          <label class="col-form-label" for="post_excerpt_<?php echo $language_key ?>">Excerpt</label><br>
                                          <textarea rows="7" name="langauge[<?php echo $language_key ?>][post_excerpt]" id="post_excerpt_<?php echo $language_key ?>" class="form-control form-control-lg" placeholder="Excerpt"><?php echo $post->post_excerpt ?></textarea>
                                          <span class="md-line"></span>
                                       </div>
                                    <?php } ?>
                                 </div>
                                 <div class="">    
                                    <?php 
                                    if (isset($postTitle['support']) && is_array($postTitle['support']) && in_array('seo', $postTitle['support'])) {
                                    ?>
                                       <div class="input-group row">
                                          <label class="col-form-label" for="meta_Keywords_<?php echo $language_key ?>">Meta Keywords</label><br>
                                          <input type="text" name="langauge[<?php echo $language_key ?>][meta_Keywords]" id="meta_Keywords_<?php echo $language_key ?>" class="form-control form-control-lg" placeholder="Meta Keywords" value="<?php echo getPostMeta($post->post_id, 'meta_Keywords') ?>">
                                          <span class="md-line"></span>
                                       </div>
                                       <div class="input-group row">
                                          <label class="col-form-label" for="meta_title_<?php echo $language_key ?>">Meta Title</label><br>
                                          <input type="text" name="langauge[<?php echo $language_key ?>][meta_title]" id="meta_title_<?php echo $language_key ?>" class="form-control form-control-lg" placeholder="Meta Title" value="<?php echo getPostMeta($post->post_id, 'meta_title') ?>">
                                          <span class="md-line"></span>
                                       </div>
                                       <div class="input-group row">
                                          <label class="col-form-label" for="meta_description_<?php echo $language_key ?>">Meta Description</label><br>
                                          <textarea rows="4" name="langauge[<?php echo $language_key ?>][meta_description]" id="meta_description_<?php echo $language_key ?>" class="form-control form-control-lg" placeholder="Meta Description"><?php echo getPostMeta($post->post_id, 'meta_description') ?></textarea>
                                          <span class="md-line"></span>
                                       </div>
                                    <?php } ?>
                                 </div>
                              </div>
                           </div>
                        <?php 
                        if ($postTitle['multilng'] == false) {
                           break;
                        }
                     } ?>
                  </div>
               </div>
            </div>
            <div class="card">  
               <div class="card-block accordion-block color-accordion-block">
                  <?php addPostMetaBox($postType,  $post_id); ?>
               </div>
            </div>
         </div>
         <div class="col-md-3">
            <div class="card ">
               <div class="card-block">                  
                  <div class="row m-t-30">
                     <div class="col-md-12">
                        <label class="col-form-label" for="post_status">Status</label><br>
                        <select class="form-control form-control-lg" name="post_status" id="post_status">
                           <option value="publish" <?php echo ($post->post_excerpt == 'publish'?'selected':'') ?>>Publish</option>
                           <option value="draft" <?php echo ($post->post_excerpt == 'draft'?'selected':'') ?>>Draft</option>
                           <option value="trash" <?php echo ($post->post_excerpt == 'trash'?'selected':'') ?>>Trash</option>
                        </select>
                     </div>
                  </div>
                  <div class="row m-t-30">
                     <div class="col-md-12">
                        <label class="col-form-label" for="comment_status">Comments</label><br>
                        <select class="form-control form-control-lg" name="comment_status" id="comment_status">
                           <option value="close" <?php echo ($post->comment_status == 'close'?'selected':'') ?>>Close</option>
                           <option value="open" <?php echo ($post->comment_status == 'open'?'selected':'') ?>>Open</option>
                        </select>
                     </div>
                  </div>
                  <div class="row m-t-30">
                     <div class="col-md-12">
                        <?php $createSiteMap = getPostMeta($post_id, 'createSiteMap') ?>
                        <label class="col-form-label" for="createSiteMap">Create This Page In SiteMap</label><br>
                        <select class="form-control form-control-lg" name="createSiteMap" id="createSiteMap">
                           <option value="no" <?php echo ($createSiteMap == 'no'?'selected':'') ?>>No</option>
                           <option value="yes" <?php echo ($createSiteMap == 'yes'?'selected':'') ?>>Yes</option>
                        </select>
                     </div>
                  </div>
                  <?php 
                  if (isset($postTitle['templateOption']) && is_array($postTitle['templateOption']))
                  {
                     ?>
                     <div class="row m-t-30">
                        <div class="col-md-12">
                           <label class="col-form-label" for="post_template">Template</label><br>
                           <select class="form-control form-control-lg" name="post_template" id="  post_template">
                              <?php 
                              foreach ($postTitle['templateOption'] as $templateKey => $templateValue) {
                                 ?>
                                 <option value="<?php echo $templateKey; ?>" <?php echo ($templateKey == $post->post_template?'selected':''); ?>><?php echo $templateValue ?></option>
                                 <?php
                              }
                              ?>
                           </select>
                        </div>
                     </div>
                     <?php
                  }
                  ?> 
                  <?php 
                  if (isset($postTitle['support']) && is_array($postTitle['support']) && in_array('featured', $postTitle['support'])) {
                  ?>
                     <div class="row m-t-30">
                        <div class="col-md-12 imageUploadGroup">
                           <?php 
                           if ($thumbnail) {
                              ?>
                              <img src="<?php echo publicPath().'/'.$thumbnail ?>" id="guid-img" style="display:block;width: 100%;height: 200px;">
                              <button type="button" data-eid="guid" style="display:none;" class="btn btn-success setFeaturedImage">Select image</button>
                              <button type="button" data-eid="guid" style="display:block;" class="btn btn-warning removeFeaturedImage">Remove image</button>
                              <?php
                           }else{
                              ?>
                              <img src="" id="guid-img" style="width: 100%;height: 200px;">
                              <button type="button" data-eid="guid" class="btn btn-success setFeaturedImage">Select image</button>
                              <button type="button" data-eid="guid" class="btn btn-warning removeFeaturedImage">Remove  image</button>
                              <?php
                           }
                           ?>                        
                           <input type="hidden" name="guid" id="guid" value="<?php echo $post->guid; ?>">
                        </div>
                     </div>
                  <?php } ?>
                  <?php 
                  if (!empty($postTitle['taxonomy'])) {
                     $selectedTerms = [];
                     $oldTerms = \App\TermRelations::where('object_id', $post_id)->get();
                     foreach ($oldTerms as $oldTerm) {
                        $selectedTerms[] = $oldTerm->term_id;
                     }
                     foreach ($postTitle['taxonomy'] as $taxonomyKey => $taxonomyValue) {
                        if ($taxonomyValue['hasVariations'] == false) {
                           ?>
                           <div class="row m-t-30">
                              <div class="col-md-12">
                                 <label class="col-form-label" for="terms_<?php echo $taxonomyKey ?>"><?php echo $taxonomyValue['title'] ?></label><br>   
                                 <div class="checkbox-group">                               
                                    <?php
                                    $terms = \App\Terms::where('term_group', $taxonomyKey)->get();
                                    foreach ($terms as $term) {
                                       echo '<label for="term_'.$term->term_id.'">'.$term->name.'<input type="checkbox" name="terms[]" id="term_'.$term->term_id.'" value="'.$term->term_id.'" '.(is_array($selectedTerms) && in_array($term->term_id, $selectedTerms)?'checked':'').'></label>';
                                    }
                                    ?>
                                 </div>
                              </div>
                           </div>
                           <?php
                        }
                     }
                  }
                  ?>
                  <div class="row m-t-30">
                     <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Update</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>      
      </div>   
   </form>
</div>
<div id="styleSelector">
</div>