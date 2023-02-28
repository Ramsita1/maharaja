<div class="page-body">
   <div class="row">

      <div class="col-md-12 col-xl-12">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-3">
                        <h5 class="m-b-10">Store SurgeCharges</h5>
                     </div>
                     <div class="col-md-6">
                        <h5 class="m-b-10 col-md-8" style="float: left">Enable Store SurgeCharges</h5>
                        <div class="checkbox col-md-4" style="float: right">
                          <label>
                            <input type="checkbox" id="store_enable_sur_charge" name="store_enable_sur_charge" value="yes" <?php echo ($store->store_enable_sur_charge == 'yes'?'checked':'') ?> data-toggle="toggle">
                          </label>
                          <input type="hidden" name="store_sur_charges" value="0">
                        </div>
                     </div>
                     <div class="col-md-3">
                        <a href="<?php echo route('storesSurgeCharges.create') ?>" class="btn btn-success" style="float: right;"><i class="ti-plus"></i>Add </a>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table">
                        <thead>
                           <tr>
                              <th>Date</th>
                              <th>Reason</th>
                              <th>Percentage</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                           foreach ($surgeCharges as $surgeCharge) {
                              ?>
                              <tr>
                                 <td><?php echo $surgeCharge->date ?></td>
                                 <td><?php echo $surgeCharge->reason ?></td>
                                 <td><?php echo $surgeCharge->percentage ?></td>
                                 <td>
                                    <a href="<?php echo route('storesSurgeCharges.edit', $surgeCharge->store_surge_id) ?>"><button type="button" class="btn btn-success"><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> 
                                    | 
                                    <?php echo Form::open(['route' => array('storesSurgeCharges.destroy', $surgeCharge->store_surge_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
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
                  <?php echo $surgeCharges->links(); ?>
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
      $('#store_enable_sur_charge').change(function(event) {
         var enableStoreSurCharge = 'no';
         if ($(this).is(':checked')) {
            var enableStoreSurCharge = 'yes';
         }
         $.ajax({
            url: '<?php echo route('storesSurgeCharges.updateSurcharge') ?>',
            type: 'GET',
            data: {enableStoreSurCharge: enableStoreSurCharge},
         });         
      });
   });
</script><?php /**PATH C:\xampp\htdocs\MaharajaPrestonApi\resources\views/Admin/StoresSurgeCharges/Index.blade.php ENDPATH**/ ?>