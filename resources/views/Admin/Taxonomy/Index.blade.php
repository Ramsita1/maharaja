<div class="page-body">
   <div class="row">

      <div class="col-md-12 col-xl-12">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-12">
                        <h5 class="m-b-10"><?php echo ($taxonomyTitle) ?></h5>
                     </div>                     
                  </div>
                  <div class="row">
                     <div class="col-md-5">
                        <?php echo Form::open(['route' => array('taxonomy.store', 'postType='.$postType.'&taxonomy='.$taxonomy), 'method' => 'post', 'class' => 'md-float-material']) ?>
                           <div class="input-group row">
                              <label class="col-form-label" for="name">Name</label><br>
                              <input type="text" name="name" id="name" required="" class="form-control form-control-lg" placeholder="Name" value="<?php echo old('name') ?>">
                              <span class="md-line"></span>
                           </div>
                           <div class="input-group row">
                              <label class="col-form-label" for="parent">Parent</label><br>
                              <select name="parent" id="parent" class="form-control form-control-lg">
                                 <option value="0">Select</option>
                                 <?php 
                                 foreach ($parentTerms as $parentTerm) {
                                    ?>
                                    <option value="<?php echo $parentTerm->term_id ?>" <?php echo ($parentTerm->term_id == old('parent')?'selected':'') ?>><?php echo $parentTerm->name ?></option>
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
                                          <option value="<?php echo $post['post_id'] ?>" <?php echo ($post['post_id'] == old('link_post')?'selected':'') ?>><?php echo $post['post_title'] ?></option>
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
                              <textarea name="description" id="description" class="form-control form-control-lg" placeholder="Description"><?php echo old('description') ?></textarea>
                              <span class="md-line"></span>
                           </div>
                           <div class="input-group row">
                              <label class="col-form-label" for="createSiteMap">Create This Taxonomy In SiteMap</label><br>
                              <select class="form-control form-control-lg" name="createSiteMap" id="createSiteMap">
                                 <option value="no">No</option>
                                 <option value="yes">Yes</option>
                              </select>
                           </div>
                           <?php addTermMetaBox($taxonomy,  0); ?>
                           <div class="row m-t-30">
                              <div class="col-md-3">
                                 <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Save</button>
                              </div>
                           </div>
                        </form>
                     </div>
                     <div class="col-md-7">
                        <?php 
                        $hasVariations = (isset($postTitle['taxonomy'][$taxonomy]['hasVariations'])?$postTitle['taxonomy'][$taxonomy]['hasVariations']:false);
                        ?>
                     <div class="table-responsive">
                        <table class="table">
                           <thead>
                              <tr>
                                 <th>Title</th>
                                 <th>Date</th>
                                 <th>Count</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php 
                              foreach ($terms as $term) {
                                 ?>
                                 <tr>
                                    <td><?php echo $term->name ?></td>
                                    <td><?php echo dateFormat($term->updated_at); ?></td>
                                    <td><?php echo $term->count ?></td>
                                    <td> 
                                       <?php 
                                       if ($hasVariations) {
                                          ?>
                                          <a href="<?php echo route('taxonomy.configureTerms', ['taxonomy'=>$term->slug,'postType'=>$term->post_type]) ?>">Configure Terms</a> |
                                          <?php
                                       }
                                       ?>                                       
                                       <a href="<?php echo route('taxonomy.edit', $term->term_id) ?>"><button type="button" class="btn btn-success"><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> | 
                                       <?php echo Form::open(['route' => array('taxonomy.destroy', $term->term_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
                                          <button type="submit" class="btn btn-danger"><span class="pcoded-micon"><i class="ti-trash"></i></span></button>
                                       </form>                                  
                                    </td>
                                 </tr>
                                 <?php
                              }
                              ?>                           
                           </tbody>
                        </table>
                     </div>
                     <?php echo $terms->appends(request()->except('page'))->links(); ?>                     
                  </div>

               </div>
            </div>
         </div>
      </div>
      
   </div>
</div>
<div id="styleSelector">
</div>