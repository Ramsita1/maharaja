<div class="row">
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="card">
		   	<div class="card-block table-border-style" style="padding-right: 0px;">
		   		<?php 
		   		$store_delivery_location_id = Request()->get('store_delivery_location_id');
		   		$storePrice = \App\StoreDeliveryLocationPrice::find($store_delivery_location_id);
		   		if (isset($storePrice->store_delivery_location_id) && !empty($storePrice->store_delivery_location_id)) {
		   			echo Form::open(['route' => array('storesDeliveryLocationPrice.update', $storePrice->store_delivery_location_id), 'method' => 'put', 'class' => 'md-float-material']);
		   		}else{
		   			$storePrice = new \App\StoreDeliveryLocationPrice();
		   			echo Form::open(['route' => array('storesDeliveryLocationPrice.store'), 'method' => 'post', 'class' => 'md-float-material']);
		   		}
		   		
		   		?>
		   		<input type="hidden" name="type" value="StoreDeliveryLocationPrice">
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
			   		<div class="input-group row">
			   		   <label class="col-form-label" for="minimum_delivery_charge">Delivery Fees</label><br>
			   		   <input type="text" name="minimum_delivery_charge" required="" id="minimum_delivery_charge" class="form-control form-control-lg InputNumber" placeholder="Minimum Delivery Charge" value="<?php echo $storePrice->minimum_delivery_charge ?>">
			   		   <span class="md-line"></span>
			   		</div>
			   		<div class="input-group row">
			   		   <label class="col-form-label" for="minimum_delivery_order">Minimum Delivery Order</label><br>
			   		   <input type="text" name="minimum_delivery_order" required="" id="minimum_delivery_order" class="form-control form-control-lg InputNumber" placeholder="Minimum Delivery Order" value="<?php echo $storePrice->minimum_delivery_order ?>">
			   		   <span class="md-line"></span>
			   		</div>
			   		<div class="input-group row">
			   		   <label class="col-form-label" for="store_delivery_partner_commission">Delivery Partner Commission %</label><br>
			   		   <input type="text" name="store_delivery_partner_commission" id="store_delivery_partner_commission" class="form-control form-control-lg InputNumber" placeholder="Delivery Partner Commission" value="<?php echo $storePrice->store_delivery_partner_commission ?>">
			   		  <span class="md-line"></span>
			   		</div>
			   		<div class="input-group row">
			   		   <label class="col-form-label" for="store_delivery_partner_compensation">Delivery Partner Compensation $</label><br>
			   		   <input type="text" name="store_delivery_partner_compensation" id="store_delivery_partner_compensation" class="form-control form-control-lg InputNumber" placeholder="Delivery Partner Compensation" value="<?php echo $storePrice->store_delivery_partner_compensation ?>">
			   		  <span class="md-line"></span>
			   		</div>
					<div class="input-group row">
			   		   <label class="col-form-label" for="charges">Charges</label><br>
			   		   <input type="text" name="charges" id="charges" class="form-control form-control-lg InputNumber" placeholder="Store charges" value="<?php echo $storePrice->charges ?>">
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
	      		            <th>Suburb</th>
	      		            <th>Postal Code</th>
	      		            <th>Delivery Fees</th>
	      		            <th>Minimum<br> Delivery Order</th>
	      		            <th>Action</th>
	      		         </tr>
	      		      </thead>
	      		      <tbody>
	      		         <?php 
	      		         $index = 1;
	      		         $storePrices = \App\StoreDeliveryLocationPrice::where('store_id', $store->store_id)->paginate(pagination());
	      		         foreach ($storePrices as $storePrice) {
	      		            ?>
	      		            <tr>
	      		               <td><?php echo $index; ?></td>
	      		               <td><?php echo $storePrice->suburb; ?></td>
	      		               <td><?php echo $storePrice->postal_code; ?></td>
	      		               <td><?php echo priceFormat($storePrice->minimum_delivery_charge); ?></td>
	      		               <td><?php echo priceFormat($storePrice->minimum_delivery_order); ?></td>
	      		               <td><?php $route = $pageUrl.'?tab=StoreDeliveryLocationPrice&store_delivery_location_id='.$storePrice->store_delivery_location_id; ?>
	      		                  <a class="edit-button" href="<?php echo $route ?>"><button type="button" class="btn btn-success "><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> | 
	      		                  <?php echo Form::open(['route' => array('storesDeliveryLocationPrice.destroy', $storePrice->store_delivery_location_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
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
	      		<?php echo $storePrices->appends(request()->except('page'))->links(); ?>
	      	</div>
		</div>
	</div>
</div><?php /**PATH C:\xampp\htdocs\MaharajaPrestonApi\resources\views/Admin/Store/StoreDeliveryLocationPrice.blade.php ENDPATH**/ ?>