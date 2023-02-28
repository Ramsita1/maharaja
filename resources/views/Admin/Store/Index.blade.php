<div class="page-body">
   <div class="row">

      <div class="col-md-12 col-xl-12">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-6">
                        <h5 class="m-b-10">Stores</h5>
                     </div>
                     <div class="col-md-6">
                        <a href="<?php echo route('stores.create') ?>?tab=StoreInfo" class="btn btn-success" style="float: right;"><i class="ti-plus"></i>Add </a>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              <th>SNO#</th>
                              <th>Store Name</th>
                              <th>Store User</th>
                              <th>Store Suburb</th>
                              <th>Store Phone</th>
                              <th>Store Email</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                           $index = 1;
                           foreach ($stores as $store) {
                              ?>
                              <tr>
                                 <td><?php echo $index; ?></td>
                                 <td><?php echo $store->store_title; ?></td>
                                 <td><?php echo $store->userName; ?></td>
                                 <td><?php echo $store->store_suburb; ?></td>
                                 <td><?php echo $store->store_location_phone; ?></td>
                                 <td><?php echo $store->store_location_email; ?></td>
                                 <td>
                                    <a class="edit-button" href="<?php echo route('stores.edit', $store->store_id) ?>?tab=StoreInfo"><button type="button" class="btn btn-success "><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> | 
                                    <?php echo Form::open(['route' => array('stores.destroy', $store->store_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
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
                  <?php echo $stores->appends(request()->except('page'))->links(); ?>
               </div>
            </div>
         </div>
      </div>
      
   </div>
</div>
<div id="styleSelector">
</div>