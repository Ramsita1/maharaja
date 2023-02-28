<div class="page-body">
   <div class="row">

      <div class="col-md-12 col-xl-12">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-6">
                        <h5 class="m-b-10">Users</h5>
                     </div>
                     <div class="col-md-6">
                        <a href="<?php echo route('storeUsers.create') ?>" class="btn btn-success" style="float: right;"><i class="ti-plus"></i>Add </a>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table">
                        <thead>
                           <tr>
                              <th><input type="checkbox" value="" name=""></th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Date</th>
                              <th>Role</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                           foreach ($users as $user) {
                              ?>
                              <tr>
                                 <th scope="row"><input type="checkbox" value="<?php echo $user->user_id; ?>" name=""></th>
                                 <td><?php echo $user->name ?></td>
                                 <td><?php echo $user->email ?></td>
                                 <td><?php echo dateFormat($user->updated_at); ?></td>
                                 <td><?php echo $user->role ?></td>
                                 <td>
                                    <a href="<?php echo route('storeUsers.edit', $user->user_id) ?>"><button type="button" class="btn btn-success"><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> 
                                    <?php if ($user->role != 'Admin') {
                                       ?>
                                       | 
                                       <?php echo Form::open(['route' => array('storeUsers.destroy', $user->user_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
                                          <button type="submit" class="btn btn-danger"><span class="pcoded-micon"><i class="ti-trash"></i></span></button>
                                       </form>
                                       <?php
                                    } ?>                                    
                                 </td>
                              </tr>
                              <?php
                           }
                           ?>                           
                        </tbody>
                     </table>
                  </div>
                  <?php echo $users->links(); ?>
               </div>
            </div>
         </div>
      </div>
      
   </div>
</div>
<div id="styleSelector">
</div><?php /**PATH C:\xampp2\htdocs\maharaja-hotel\resources\views/Admin/StoreUsers/Index.blade.php ENDPATH**/ ?>