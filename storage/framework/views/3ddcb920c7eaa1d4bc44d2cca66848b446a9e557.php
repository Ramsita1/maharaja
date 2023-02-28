<div class="page-body">
   <div class="row">

      <div class="col-md-12 col-xl-12">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-6">
                        <h5 class="m-b-10">Deals</h5>
                     </div>
                     <div class="col-md-6">
                        <a href="<?php echo route('vouchers.create') ?>" class="btn btn-success" style="float: right;"><i class="ti-plus"></i>Add </a>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table">
                        <thead>
                           <tr>
                              <th>Code</th>
                              <th>Discount Type</th>
                              <th>Discount</th>
                              <th>Min Order</th>
                              <th>Expiry Date</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                           foreach ($vouchers as $voucher) {
                              ?>
                              <tr>
                                 <td><?php echo $voucher->code ?></td>
                                 <td><?php echo $voucher->discount_type ?></td>
                                 <td><?php echo $voucher->discount ?></td>
                                 <td><?php echo priceFormat($voucher->min_order); ?></td>
                                 <td><?php echo dateFormat($voucher->expiry_date); ?></td>
                                 <td>
                                    <a href="<?php echo route('vouchers.edit', $voucher->voucher_id) ?>"><button type="button" class="btn btn-success"><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> 
                                    | 
                                    <?php echo Form::open(['route' => array('vouchers.destroy', $voucher->voucher_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
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
                  <?php echo $vouchers->links(); ?>
               </div>
            </div>
         </div>
      </div>
      
   </div>
</div>
<div id="styleSelector">
</div><?php /**PATH C:\xampp\htdocs\MaharajaPrestonApi\resources\views/Admin/Vouchers/Index.blade.php ENDPATH**/ ?>