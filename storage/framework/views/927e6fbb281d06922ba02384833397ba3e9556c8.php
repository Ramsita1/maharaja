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
                        <a href="<?php echo route('deals.create') ?>" class="btn btn-success" style="float: right;"><i class="ti-plus"></i>Add </a>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table">
                        <thead>
                           <tr>
                              <th>Deal Name</th>
                              <th>Deal Type</th>
                              <th>Discount</th>
                              <th>Min Order</th>
                              <th>Start Date</th>
                              <th>End Date</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                           foreach ($deals as $deal) {
                              ?>
                              <tr>
                                 <td><?php echo $deal->deal_title ?></td>
                                 <td><?php echo $deal->deal_type ?></td>
                                 <td><?php echo $deal->discount ?></td>
                                 <td><?php echo priceFormat($deal->min_order); ?></td>
                                 <td><?php echo dateFormat($deal->start_date); ?></td>
                                 <td><?php echo dateFormat($deal->end_date); ?></td>
                                 <td>
                                    <a href="<?php echo route('deals.edit', $deal->deal_id) ?>"><button type="button" class="btn btn-success"><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> 
                                    | 
                                    <?php echo Form::open(['route' => array('deals.destroy', $deal->deal_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
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
                  <?php echo $deals->links(); ?>
               </div>
            </div>
         </div>
      </div>
      
   </div>
</div>
<div id="styleSelector">
</div><?php /**PATH /home/k9c9adh99pg7/public_html/pza/resources/views/Admin/Deals/Index.blade.php ENDPATH**/ ?>