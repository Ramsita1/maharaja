<div class="row">
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="card">
		   	<div class="card-block table-border-style" style="padding-right: 0px;">
		   		<?php 
		   		$store_pickup_location_id = Request()->get('store_pickup_location_id');
		   		$storePrice = \App\StorePickupLocations::find($store_pickup_location_id);
		   		if (isset($storePrice->store_pickup_location_id) && !empty($storePrice->store_pickup_location_id)) {
		   			echo Form::open(['route' => array('storePickupLocations.update', $storePrice->store_pickup_location_id), 'method' => 'put', 'class' => 'md-float-material']);
		   		}else{
		   			$storePrice = new \App\StorePickupLocations();
		   			echo Form::open(['route' => array('storePickupLocations.store'), 'method' => 'post', 'class' => 'md-float-material']);
		   		}
		   		
		   		?>
		   		<input type="hidden" name="type" value="StorePickupLocations">
		   		<input type="hidden" name="store_id" value="<?php echo $store->store_id ?>">
			   		<div class="input-group row">
			   		   <label class="col-form-label" for="suburb">Suburb</label><br>
			   		   <input type="text" name="suburb" required="" id="suburb" class="form-control form-control-lg" placeholder="Suburb" value="<?php echo $storePrice->suburb ?>">
			   		   <span class="md-line"></span>
			   		</div>
			   		<div class="input-group row">
			   		   <label class="col-form-label" for="city">City</label><br>
			   		   <input type="text" name="city" required="" id="city" class="form-control form-control-lg" placeholder="City" value="<?php echo $storePrice->city ?>">
			   		   <span class="md-line"></span>
			   		</div>
			   		<div class="input-group row">
			   		   <label class="col-form-label" for="postal_code">Postal Code</label><br>
			   		   <input type="text" name="postal_code" required="" id="postal_code" class="form-control form-control-lg " placeholder="Postal Code" value="<?php echo $storePrice->postal_code ?>">
			   		   <span class="md-line"></span>
			   		</div>
			   		<div class="row m-t-30 col-md-12">
					  <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Save</button>
					</div>
				</form>
		    </div>
		</div>
	</div>
	<div class="col-md-9 col-sm-6 col-xs-12">
		<div class="card">
	      	<div class="card-block table-border-style">
	      		<div class="table-responsive">
	      		   <table class="table table-bordered table-striped">
	      		      <thead>
	      		         <tr>
	      		            <th>SNO#</th>
	      		            <th>City</th>
	      		            <th>Suburb</th>
	      		            <th>Postal Code</th>
	      		            <th>Action</th>
	      		         </tr>
	      		      </thead>
	      		      <tbody>
	      		         <?php 
	      		         $index = 1;
	      		         $storePickupLocations = \App\StorePickupLocations::where('store_id', $store->store_id)->paginate(pagination());
	      		         foreach ($storePickupLocations as $storePickupLocation) {
	      		            ?>
	      		            <tr>
	      		               <td><?php echo $index; ?></td>
	      		               <td><?php echo $storePickupLocation->city; ?></td>
	      		               <td><?php echo $storePickupLocation->suburb; ?></td>
	      		               <td><?php echo $storePickupLocation->postal_code; ?></td>
	      		               <td><?php $route = $pageUrl.'?tab=StorePickupLocations&store_pickup_location_id='.$storePickupLocation->store_pickup_location_id; ?>
	      		                  <a class="edit-button" href="<?php echo $route ?>"><button type="button" class="btn btn-success "><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> | 
	      		                  <?php echo Form::open(['route' => array('storePickupLocations.destroy', $storePickupLocation->store_pickup_location_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
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
	      		<?php echo $storePickupLocations->appends(request()->except('page'))->links(); ?>
	      	</div>
		</div>
	</div>
</div>