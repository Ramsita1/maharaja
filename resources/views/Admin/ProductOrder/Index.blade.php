<div class="page-body">
   <div class="row">

      <div class="col-md-12 col-xl-12">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-6">
                        <h5 class="m-b-10">Orders</h5>
                     </div>
                     <div class="col-md-6">
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table">
                        <thead>
                           <tr>
                              <th>Order ID</th>
                              <th>User Name</th>
                              <th>Price</th>
                              <th>Order Status</th>
                              <th>Payment Status</th>
                              <th>Created</th>
                              <th>Last Updated</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                           foreach ($orders as $order) {
                              $userName = \App\User::where('user_id', $order->user_id)->pluck('name')->first();
                              ?>
                              <tr>
                                 <td><?php echo $order->order_id; ?></td>
                                 <td><?php echo $userName; ?></td>
                                 <td><?php echo $order->grand_total; ?></td>
                                 <td><?php echo $order->order_status; ?></td>
                                 <td><?php echo $order->payment_status; ?></td>
                                 <td><?php echo dateFormat($order->created_at); ?></td>
                                 <td><?php echo dateFormat($order->updated_at); ?></td>
                                 <td>
                                    <a href="<?php echo route('orders.show', $order->order_id) ?>"><button type="button" class="btn btn-success"><span class="pcoded-micon"><i class="ti-layers"></i></span></button></a> <!-- | 
                                    <?php echo Form::open(['route' => array('orders.destroy', $order->order_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
                                       <button type="submit" class="btn btn-danger"><span class="pcoded-micon"><i class="ti-trash"></i></span></button>
                                    </form> -->
                                 </td>
                              </tr>
                              <?php
                           }
                           ?>                           
                        </tbody>
                     </table>
                  </div>
                  <?php echo $orders->links(); ?>
               </div>
            </div>
         </div>
      </div>
      
   </div>
</div>
<div id="styleSelector">
</div>