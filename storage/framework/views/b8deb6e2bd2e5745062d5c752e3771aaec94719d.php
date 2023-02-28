<div class="page-body">
   <div class="row">

      <div class="col-md-12 col-xl-12">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-6">
                        <h5 class="m-b-10">Store Holidays</h5>
                     </div>
                     <div class="col-md-6">
                        <a href="<?php echo route('storeHolidays.create') ?>" class="btn btn-success" style="float: right;"><i class="ti-plus"></i>Add </a>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table">
                        <thead>
                           <tr>
                              <th>Date</th>
                              <th>Start Time</th>
                              <th>End Time</th>
                              <th>Full Day Off</th>
                              <th>Created Date</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                           foreach ($holidays as $holiday) {
                              ?>
                              <tr>
                                 <td><?php echo $holiday->date ?></td>
                                 <td><?php echo ($holiday->full_day_off == 1?'':$holiday->close_start_time) ?></td>
                                 <td><?php echo ($holiday->full_day_off == 1?'':$holiday->close_end_time) ?></td>
                                 <td><?php echo ($holiday->full_day_off == 1?'Yes':'No') ?></td>
                                 <td><?php echo dateFormat($holiday->created_at); ?></td>
                                 <td>
                                    <a href="<?php echo route('storeHolidays.edit', $holiday->store_holiday_id) ?>"><button type="button" class="btn btn-success"><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> 
                                    | 
                                    <?php echo Form::open(['route' => array('storeHolidays.destroy', $holiday->store_holiday_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
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
                  <?php echo $holidays->links(); ?>
               </div>
            </div>
         </div>
      </div>
      
   </div>
</div>
<div id="styleSelector">
</div><?php /**PATH C:\xampp\htdocs\MaharajaPrestonApi\resources\views/Admin/StoresHolidays/Index.blade.php ENDPATH**/ ?>