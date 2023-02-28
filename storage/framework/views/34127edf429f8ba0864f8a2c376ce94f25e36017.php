<div class="page-body">
   <div class="row">

      <div class="col-md-12 col-xl-12">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-2">
                        <h5 class="m-b-10"><?php echo ($postTitle['title']) ?></h5>
                     </div>
                     <div class="col-md-6">
                        <div class="row">
                           <div class="col-md-6">
                              <select class="form-control form-control-lg" id="actions">
                                 <option value="edit">Edit</option>
                                 <option value="trash">Move to Trash</option>
                              </select>
                           </div>
                           <div class="col-md-6">
                              <button class="btn btn-info" id="applyActions">Apply</button>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <a href="<?php echo route('post.create', ['postType'=>$postType]) ?>" class="btn btn-success" style="float: right;"><i class="ti-plus"></i>Add </a>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table">
                        <thead>
                           <tr>
                              <th><input type="checkbox" class="deleteAllPostMain"></th>
                              <th>Title</th>
                              <th>Url</th>
                              <th>Author</th>
                              <?php 
                              if (!empty($postTitle['taxonomy']) && is_array($postTitle['taxonomy'])) {
                                 foreach ($postTitle['taxonomy'] as $taxonomyKey => $taxonomyValue) {
                                    echo '<th>'.$taxonomyValue['title'].'</th>';
                                 }
                              }
                              ?>
                              <th>Created</th>
                              <th>Last Updated</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody id="sortable">
                           <?php 
                           foreach ($posts as $post) {
                              ?>
                              <tr class="tr_rows" data-post_id="<?php echo $post->post_id; ?>">
                                 <th scope="row"><input type="checkbox" class="deleteAllPost post-<?php echo $post->post_id; ?>" value="<?php echo $post->post_id; ?>" name=""></th>
                                 <td style="white-space: normal;"><?php echo $post->post_title ?></td>
                                 <td style="white-space: normal;" onContextMenu="return false;" contenteditable data-post_id="<?php echo $post->post_id; ?>"><?php echo $post->post_name ?></td>
                                 <td><?php echo $post->post_author ?></td>
                                 <?php 
                                 if (!empty($postTitle['taxonomy']) && is_array($postTitle['taxonomy'])) {
                                    foreach ($postTitle['taxonomy'] as $taxonomyKey => $taxonomyValue) {
                                       echo '<td>'.(isset($post->category[$taxonomyKey])?$post->category[$taxonomyKey]:'').'</td>';
                                    }
                                 }
                                 ?>
                                 <td><?php echo dateFormat($post->created_at); ?></td>
                                 <td><?php echo dateFormat($post->updated_at); ?></td>
                                 <td>
                                    <a class="edit-button" href="<?php echo route('post.edit', $post->post_id, $postType) ?>"><button type="button" class="btn btn-success "><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> | 
                                    <a href="<?php echo route('post.clone', $post->post_id) ?>"><button type="button" class="btn btn-info"><span class="pcoded-micon"><i class="ti-layers"></i></span></button></a> | 
                                    <?php echo Form::open(['route' => array('post.destroy', $post->post_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
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
                  <?php echo $posts->appends(request()->except('page'))->links(); ?>
               </div>
            </div>
         </div>
      </div>
      
   </div>
</div>
<div id="styleSelector">
</div>
<script type="text/javascript">
   jQuery(document).ready(function($) {
      $('.deleteAllPostMain').change(function(event) {
         if($(this).is(':checked')){
            $('.deleteAllPost').prop('checked', true);
         } else {
            $('.deleteAllPost').prop('checked', false);
         }
      });
      $('.deleteAllPost').change(function(event) {
         var checkedLength = $('.deleteAllPost:checked').length;
         var unCheckedLength = $('.deleteAllPost').length;
         if (checkedLength == unCheckedLength) {
            $('.deleteAllPostMain').prop('checked', true);
         } else {
            $('.deleteAllPostMain').prop('checked', false);
         }
      });
      $('#applyActions').click(function(event) {
         var action = $('#actions').val();
         var checkedLength = $('.deleteAllPost:checked');
         if (checkedLength.length == 0) {
            window.alert('Please select atleast one post');
            return false;
         }
         if (action == 'edit') {
            var firstChildValue = checkedLength[0].value;
            var trRow = $('.post-'+firstChildValue).closest('.tr_rows').find('.edit-button').attr('href');
            window.location.href=trRow;
            return false;
         } else if(action == 'trash'){
            var postIds = [];
            $.each(checkedLength, function(index, val) {
               var post_id = $(this).val();
               postIds.push(post_id);
            });
            postIds = postIds.join(',');
            $.ajax({
               url: '<?php echo route('post.deleteAll') ?>',
               type: 'GET',
               data: {postIds: postIds},
            })
            .done(function() {
               window.location.reload();
            });
            return false;
         }
                  
      });
      $( "#sortable" ).sortable({
         update: function( ) {
            var sortIndex = [];
            $.each($('.tr_rows'), function(index, val) {
               sortIndex.push($(this).attr('data-post_id'));
            });
            $.ajax({
               url: '<?php echo route('post.updateOrder') ?>',
               type: 'GET',
               data: {order: sortIndex},
            });            
         }
      });
      $(document).on('blur', 'td[contenteditable]', function() {
         const $this = $(this);
         var post_name = $this[0].innerText;
         var post_id = $this.attr('data-post_id');
         $.ajax({
            url: '<?php echo route('post.updatePostName') ?>',
            type: 'GET',
            data: {post_name: post_name, post_id, post_id},
         })
         .done(function(result){
            $this[0].innerText = result;
         }); 
      });
   });
</script><?php /**PATH C:\xampp\htdocs\MaharajaPrestonApi\resources\views/Admin/Post/Index.blade.php ENDPATH**/ ?>