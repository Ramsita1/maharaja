<div class="row">
	<style type="text/css">
		.tableTimings td, .tableTimings th{
			padding: 8px;
		}
	</style>
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="card">
		   	<div class="card-block table-border-style">
		   		<?php 
		   		$storeOrder = \App\StoreOnlineOrderTimings::where('store_id', $store->store_id)->where('type' ,'StoreOnlineOrderTimingsPickup')->get()->first();
		   		if (isset($storeOrder->store_online_order_timing_id) && !empty($storeOrder->store_online_order_timing_id)) {
		   			echo Form::open(['route' => array('storesOnlineOrderTimings.update', $storeOrder->store_online_order_timing_id), 'method' => 'put', 'class' => 'md-float-material']);
		   		}else{
		   			$storeOrder = new \App\StoreOnlineOrderTimings();
		   			echo Form::open(['route' => array('storesOnlineOrderTimings.store'), 'method' => 'post', 'class' => 'md-float-material']);
		   		}
		   		$weekdays = maybe_decode($storeOrder->weekdays);
		   		?>
			   		<input type="hidden" name="type" value="StoreOnlineOrderTimingsPickup">
			   		<input type="hidden" name="store_id" value="<?php echo $store->store_id ?>">
		      		<div class="table-responsive">
		      		   <table class="table table-bordered table-striped tableTimings">
		      		   		<thead>
		      		   			<tr>
		      		   				<th></th>
		      		   				<?php 
		      		   				foreach (itemFor() as $itemFor) {
		      		   					?>
		      		   					<th colspan="2">Time For <?php echo $itemFor; ?></th>
		      		   					<?php
		      		   				}
		      		   				?>
		      		   				<th></th>
		      		   				<th></th>
		      		   			</tr>
		      		   			<tr>
		      		   				<th>Day</th>
		      		   				<?php 
		      		   				foreach (itemFor() as $itemFor) {
		      		   					?>
			      		   				<th>Open Time</th>
			      		   				<th>Close Time</th>
		      		   					<?php
		      		   				}
		      		   				?>
		      		   				<th>Interval</th>
		      		   				<th>Status</th>
		      		   			</tr>
		      		   		</thead>
		      		   		<tbody>
		      		   			<?php 
		      		   			$days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
		      		   			foreach ($days as $day) {
		      		   				$interval = (isset($weekdays[$day]['interval'])?$weekdays[$day]['interval']:'15');
		      		   				$status = (isset($weekdays[$day]['status'])?$weekdays[$day]['status']:'');
		      		   				?>
		      		   				<tr>
		      		   					<td><?php echo $day; ?></td>
			      		   				<?php 
			      		   				foreach (itemFor() as $itemFor) {
			      		   					?>
				      		   				<td><input type="text" name="weekdays[<?php echo $day ?>][<?php echo $itemFor ?>][open_time]" class="form-control timepicker" placeholder="Open Time" style="width: 100px;" value="<?php echo (isset($weekdays[$day][$itemFor]['open_time'])?$weekdays[$day][$itemFor]['open_time']:'') ?>"></td>
			      		   					<td><input type="text" name="weekdays[<?php echo $day ?>][<?php echo $itemFor ?>][close_time]" class="form-control timepicker" placeholder="Close Time" style="width: 100px;" value="<?php echo (isset($weekdays[$day][$itemFor]['close_time'])?$weekdays[$day][$itemFor]['close_time']:'') ?>"></td>
			      		   					<?php
			      		   				}
			      		   				?>
		      		   					<td>
		      		   						<select name="weekdays[<?php echo $day ?>][interval]" class="form-control">
		      		   							<option value="15" <?php echo ($interval == '15'?'selected':'') ?>>15 Minute</option>
		      		   							<option value="30" <?php echo ($interval == '30'?'selected':'') ?>>30 Minute</option>
		      		   							<option value="45" <?php echo ($interval == '45'?'selected':'') ?>>45 Minute</option>
		      		   							<option value="60" <?php echo ($interval == '60'?'selected':'') ?>>60 Minute</option>
		      		   							<option value="90" <?php echo ($interval == '90'?'selected':'') ?>>90 Minute</option>
		      		   							<option value="120" <?php echo ($interval == '120'?'selected':'') ?>>120 Minute</option>
		      		   						</select>
		      		   					</td>
		      		   					<td><input type="checkbox" <?php echo ($status == '1'?'checked':'') ?> name="weekdays[<?php echo $day ?>][status]" class="form-control" value="1"></td>
		      		   				</tr>
		      		   				<?php
		      		   			}
		      		   			?>	      		   			
		      		   		</tbody>
		      		   </table>
		      		   <br>
		      		   <div class="form-group col-md-3">
		      		   	<label for="cut_of_time">Cut Of Time</label>
		      		   	<?php $cut_of_time = (isset($weekdays['cut_of_time'])?$weekdays['cut_of_time']:'15'); ?>
		      		   	<select id="cut_of_time" name="weekdays[cut_of_time]" class="form-control">
		      		   		<option value="15" <?php echo ($cut_of_time == '15'?'selected':'') ?>>15 Minute</option>
   							<option value="30" <?php echo ($cut_of_time == '30'?'selected':'') ?>>30 Minute</option>
   							<option value="45" <?php echo ($cut_of_time == '45'?'selected':'') ?>>45 Minute</option>
   							<option value="60" <?php echo ($cut_of_time == '60'?'selected':'') ?>>60 Minute</option>
   							<option value="90" <?php echo ($cut_of_time == '90'?'selected':'') ?>>90 Minute</option>
   							<option value="120" <?php echo ($cut_of_time == '120'?'selected':'') ?>>120 Minute</option>
		      		   	</select>
		      		   </div>
		      		   <button type="submit" class="btn btn-success">Save</button>
		      		</div>
				</form>
		    </div>
		</div>
	</div>
</div>