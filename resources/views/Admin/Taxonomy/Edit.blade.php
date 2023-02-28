<div class="page-body">
   <h3><?php echo $taxonomyTitle; ?></h3>
   <?php echo Form::open(['route' => array('taxonomy.update', $term_id), 'method' => 'put', 'class' => 'md-float-material']) ?>
      <div class="row">
         <div class="col-md-9 padding-right">
            <div class="card">            
               <div class="card-block accordion-block color-accordion-block">
                  <div class="input-group row">
                     <label class="col-form-label" for="name">Name</label><br>
                     <input type="text" name="name" id="name" required="" class="form-control form-control-lg" placeholder="Name" value="<?php echo $term->name ?>">
                     <span class="md-line"></span>
                  </div>
                  <div class="input-group row">
                     <label class="col-form-label" for="parent">Parent</label><br>
                     <select name="parent" id="parent" class="form-control form-control-lg">
                        <option value="0">Select</option>
                        <?php 
                        foreach ($parentTerms as $parentTerm) {
                           ?>
                           <option value="<?php echo $parentTerm->term_id ?>" <?php echo ($parentTerm->term_id == $term->parent?'selected':'') ?>><?php echo $parentTerm->name ?></option>
                           <?php
                        }
                        ?>
                     </select>
                     <span class="md-line"></span>
                  </div>
                  <?php
                  if($posts){
                     ?>
                        <div class="input-group row">
                           <label class="col-form-label" for="link_post">Link Post</label><br>
                           <select name="link_post" id="link_post" class="form-control form-control-lg">
                              <option value="0">Select</option>
                              <?php 
                              foreach ($posts as $post) {
                                 ?>
                                 <option value="<?php echo $post['post_id'] ?>" <?php echo ($post['post_id'] == getTermMeta($term_id, 'link_post')?'selected':'') ?>><?php echo $post['post_title'] ?></option>
                                 <?php
                              }
                              ?>
                           </select>
                           <span class="md-line"></span>
                        </div> 
                     <?php  
                  }
                  ?>
                  <div class="input-group row">
                     <label class="col-form-label" for="description">Description</label><br>
                     <textarea name="description" id="description" class="form-control form-control-lg" placeholder="Description"><?php echo $term->description ?></textarea>
                     <span class="md-line"></span>
                  </div>    
                  <div class="input-group row">
                     <?php $createSiteMap = getTermMeta($term->term_id, 'createSiteMap') ?>
                     <label class="col-form-label" for="createSiteMap">Create This Page In SiteMap</label><br>
                     <select class="form-control form-control-lg" name="createSiteMap" id="createSiteMap">
                        <option value="no" <?php echo ($createSiteMap == 'no'?'selected':'') ?>>No</option>
                        <option value="yes" <?php echo ($createSiteMap == 'yes'?'selected':'') ?>>Yes</option>
                     </select>
                  </div>
                  <?php addTermMetaBox($term->term_group,  $term->term_id); ?>            
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