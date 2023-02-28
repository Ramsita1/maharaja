<div class="page-body">
   <h3>Store User</h3>
   <?php echo Form::open(['route' => array('storeUsers.update', $user->user_id), 'method' => 'put', 'class' => 'md-float-material']) ?>
      <div class="row">
         <div class="col-md-6">

            <div class="card">            
               <div class="card-block">
                  <div class="">         
                     <input type="hidden" name="tab" value="<?php echo Request()->get('tab') ?>">                 
                     <div class="input-group row">
                        <label class="col-form-label" for="name">Name</label><br>
                        <input type="text" name="name" required="" id="name" class="form-control form-control-lg" placeholder="Name" value="<?php echo $user->name ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="email">Email</label><br>
                        <input type="email" name="email" required="" id="email" class="form-control form-control-lg" placeholder="Email" value="<?php echo $user->email ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="password">Password</label><br>
                        <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Password">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="role">Role</label><br>
                        <select required="" id="role" class="form-control form-control-lg" name="role">
                           <option value="">Select</option>
                           <?php 
                           $userRoles = userRoles();
                           unset($userRoles[0]);
                           unset($userRoles[4]);
                           foreach ($userRoles as $role) {
                              echo '<option value="'.$role.'" '.($user->role == $role ?'selected':'').'>'.$role.'</option>';
                           }
                           ?>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="user_status">Status</label><br>
                        <select id="user_status" class="form-control form-control-lg" name="user_status">
                           <option value="">Select</option>
                           <?php 
                           foreach (userStatus() as $statusKey => $statusValue) {
                              echo '<option value="'.$statusKey.'" '.($user->user_status == $statusKey ?'selected':'').'>'.$statusValue.'</option>';
                           }
                           ?>
                        </select>
                     </div>
                     <div class="input-group row">
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
</div><?php /**PATH /home/k9c9adh99pg7/public_html/pza/resources/views/Admin/StoreUsers/Edit.blade.php ENDPATH**/ ?>