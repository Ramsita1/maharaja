<div class="page-body">
   <h3><?php echo $postTitle['title']; ?></h3>
   <?php echo Form::open(['route' => array('post.store', 'postType='.$postType), 'method' => 'post', 'class' => 'md-float-material']) ?>
      <div class="row">
         <div class="col-md-9 padding-right">

            <div class="card">            
               <div class="card-block accordion-block color-accordion-block">
                  <div class="color-accordion" id="color-accordion">
                     <?php
                     foreach (getSupportLNG() as $language_key => $language_value) {
                        $post = old($language_key);
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
                                    <input type="text" name="langauge[<?php echo $language_key ?>][post_title]" <?php echo $attr ?> id="post_title_<?php echo $language_key ?>" class="form-control form-control-lg" placeholder="Title" value="<?php echo (isset($post['post_title'])?$post['post_title']:'') ?>">
                                    <span class="md-line"></span>
                                 </div>
                                 <?php 
                                 if (isset($postTitle['support']) && is_array($postTitle['support']) && in_array('content', $postTitle['support'])) {
                                 ?>
                                    <div class="input-group row">
                                       <label class="col-form-label" for="post_content_<?php echo $language_key ?>">Content</label><br>
                                       <textarea name="langauge[<?php echo $language_key ?>][post_content]" id="post_content_<?php echo $language_key ?>" class="form-control form-control-lg ckeditor" placeholder="Content"><?php echo (isset($post['post_content'])?$post['post_content']:'') ?></textarea>
                                       <span class="md-line"></span>
                                    </div>
                                 <?php } ?>
                                 <?php 
                                 if (isset($postTitle['support']) && is_array($postTitle['support']) && in_array('excerpt', $postTitle['support'])) {
                                 ?>
                                    <div class="input-group row">
                                       <label class="col-form-label" for="post_excerpt_<?php echo $language_key ?>">Excerpt</label><br>
                                       <textarea rows="7" name="langauge[<?php echo $language_key ?>][post_excerpt]" id="post_excerpt_<?php echo $language_key ?>" class="form-control form-control-lg" placeholder="Excerpt"><?php echo (isset($post['post_excerpt'])?$post['post_excerpt']:'') ?></textarea>
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
                                       <input type="text" name="langauge[<?php echo $language_key ?>][meta_Keywords]" id="meta_Keywords_<?php echo $language_key ?>" class="form-control form-control-lg" placeholder="Meta Keywords" value="<?php echo (isset($post['meta_Keywords'])?$post['meta_Keywords']:'') ?>">
                                       <span class="md-line"></span>
                                    </div>
                                    <div class="input-group row">
                                       <label class="col-form-label" for="meta_title_<?php echo $language_key ?>">Meta Title</label><br>
                                       <input type="text" name="langauge[<?php echo $language_key ?>][meta_title]" id="meta_title_<?php echo $language_key ?>" class="form-control form-control-lg" placeholder="Meta Title" value="<?php echo (isset($post['meta_title'])?$post['meta_title']:'') ?>">
                                       <span class="md-line"></span>
                                    </div>
                                    <div class="input-group row">
                                       <label class="col-form-label" for="meta_description_<?php echo $language_key ?>">Meta Description</label><br>
                                       <textarea rows="4" name="langauge[<?php echo $language_key ?>][meta_description]" id="meta_description_<?php echo $language_key ?>" class="form-control form-control-lg" placeholder="Meta Description"><?php echo (isset($post['meta_description'])?$post['meta_description']:'') ?></textarea>
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
                  <?php addPostMetaBox($postType,  0); ?>
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
                           <option value="publish">Publish</option>
                           <option value="draft">Draft</option>
                           <option value="trash">Trash</option>
                        </select>
                     </div>
                  </div>
                  <div class="row m-t-30">
                     <div class="col-md-12">
                        <label class="col-form-label" for="comment_status">Comments</label><br>
                        <select class="form-control form-control-lg" name="comment_status" id="comment_status">
                           <option value="close">Close</option>
                           <option value="open">Open</option>
                        </select>
                     </div>
                  </div>
                     <div class="row m-t-30">
                        <div class="col-md-12">
                           <label class="col-form-label" for="createSiteMap">Create This Page In SiteMap</label><br>
                           <select class="form-control form-control-lg" name="createSiteMap" id="createSiteMap">
                              <option value="no">No</option>
                              <option value="yes">Yes</option>
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
                                 <option value="<?php echo $templateKey; ?>" <?php echo ($templateKey == old('post_template')?'selected':''); ?>><?php echo $templateValue ?></option>
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
                           <img src="" id="guid-img" style="width: 100%;height: 200px;">
                           <button type="button" data-eid="guid" class="btn btn-success setFeaturedImage">Select image</button>
                           <button type="button" data-eid="guid" class="btn btn-warning removeFeaturedImage">Remove image</button>
                           <input type="hidden" name="guid" id="guid" value="">
                        </div>
                     </div>
                  <?php } ?>
                  <?php 
                  if (!empty($postTitle['taxonomy'])) {
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
                                       echo '<label for="term_'.$term->term_id.'">'.$term->name.'<input type="checkbox" name="terms[]" id="term_'.$term->term_id.'" value="'.$term->term_id.'" '.(is_array(old('terms')) && in_array($term->term_id, old('terms'))?'checked':'').'></label>';
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
                        <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Save</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>      
      </div>   
   </form>
</div>
<div id="styleSelector">
</div><?php /**PATH C:\xampp\htdocs\MaharajaPrestonApi\resources\views/Admin/Post/Create.blade.php ENDPATH**/ ?>