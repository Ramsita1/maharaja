<div class="row">
	<div class="col-md-4 col-sm-6 col-xs-12">
		<div class="card">
		   	<div class="card-block table-border-style">
		   		<?php 
		   		$formType = 'employee';
		   		echo view('Admin.Users.Create', compact('formType','store')); 
		   		?>
		    </div>
		</div>
	</div>
	<div class="col-md-8 col-sm-6 col-xs-12">
		<div class="card">
	      	<div class="card-block table-border-style">
	      		<div class="table-responsive">
	      		   <table class="table table-bordered table-striped">
	      		      <thead>
	      		         <tr>
	      		            <th>SNO#</th>
	      		            <th>Name</th>
                          	<th>Email</th>
                          	<th>Date</th>
                          	<th>Role</th>
                          	<th>Action</th>
	      		         </tr>
	      		      </thead>
	      		      <tbody>
	      		         <?php 
        				$users = \App\User::whereIn('role', ['StoreAdmin','storeEmployee'])->where('store_id',$store->store_id)->paginate(pagination());
	      		        $index = 1;
	      		        foreach ($users as $user) {
	      		            ?>
	      		            <tr>
	      		               <td><?php echo $index; ?></td>
	      		               <td><?php echo $user->name ?></td>
	      		               <td><?php echo $user->email ?></td>
	      		               <td><?php echo dateFormat($user->updated_at); ?></td>
	      		               <td><?php echo $user->role ?></td>
	      		               <td>
	      		                  <a class="edit-button" href="<?php echo route('users.edit', $user->user_id) ?>?tab=StoreEmployee"><button type="button" class="btn btn-success "><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> | 
	      		                  <?php echo Form::open(['route' => array('users.destroy', $user->user_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
	      		                     <button type="submit" class="btn btn-danger"><span class="pcoded-micon"><i class="ti-trash"></i></span></button>
	      		                  </form>
	      		               </td>
	      		            </tr>
	      		            <?php
	      		            $index++;
	      		        }
	      		         ?>      
	      		      </tbody>
	      		   </table>
	      		</div>
	      		<?php echo $users->appends(request()->except('page'))->links(); ?>
	      	</div>
		</div>
	</div>
</div>