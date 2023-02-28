<div class="page-body">
   <div class="row">

      <div class="col-md-12 col-xl-12">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-2">
                        <h5 class="m-b-10">Comment</h5>
                     </div>
                     <div class="col-md-6">
                        <div class="row">
                           <div class="col-md-6">
                              <select class="form-control form-control-lg" id="actions">
                                 <option value="delete">Delete</option>
                              </select>
                           </div>
                           <div class="col-md-6">
                              <button class="btn btn-info" id="applyActions">Apply</button>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-3">
                        
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table">
                        <thead>
                           <tr>
                              <th><input type="checkbox" class="deleteAllPostMain"></th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Content</th>
                              <th>Created</th>
                              <th>Last Updated</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody id="sortable">
                           <?php 
                           foreach ($comments as $comment) {
                              ?>
                              <tr class="tr_rows" data-comment_ID="<?php echo $comment->comment_ID; ?>">
                                 <th scope="row"><input type="checkbox" class="deleteAllPost comment-<?php echo $comment->comment_ID; ?>" value="<?php echo $comment->comment_ID; ?>" name=""></th>
                                 <td style="white-space: normal;"><?php echo $comment->comment_author ?></td>
                                 <td style="white-space: normal;"><?php echo $comment->comment_author_email ?></td>
                                 <td><?php echo $comment->comment_content ?></td>
                                 <td><?php echo dateFormat($comment->created_at); ?></td>
                                 <td><?php echo dateFormat($comment->updated_at); ?></td>
                                 <td>
                                    <?php echo Form::open(['route' => array('comment.destroy', $comment->comment_ID), 'method' => 'delete','style'=>'display: inline-block;']) ?>
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
                  <?php echo $comments->appends(request()->except('page'))->links(); ?>
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
         var postIds = [];
         $.each(checkedLength, function(index, val) {
            var comment_ID = $(this).val();
            postIds.push(comment_ID);
         });
         postIds = postIds.join(',');
         $.ajax({
            url: '<?php echo route('comment.deleteAll') ?>',
            type: 'GET',
            data: {postIds: postIds,action:action},
         })
         .done(function() {
            window.location.reload();
         });
         return false;
                  
      });
      
   });
</script>