<div class="page-body">
   <div class="row">
      <!-- order-card start -->
      <?php 
      $bg = ['pink','blue','green','yellow','red'];
      ?>
      <style type="text/css">
      	.card.bg-c-red {
      	    background: #ed1c24;
      	}
      </style>
      <!-- <div class="col-md-6 col-xl-3">
         <div class="card bg-c-blue order-card">
            <div class="card-block">
               <h6 class="m-b-20">Orders Received</h6>
               <h2 class="text-right"><i class="ti-reload f-left"></i><span><?php //echo $totalOrder ?></span></h2>
            </div>
         </div>
      </div>
      <div class="col-md-6 col-xl-3">
         <div class="card bg-c-green order-card">
            <div class="card-block">
               <h6 class="m-b-20">Total Users</h6>
               <h2 class="text-right"><i class="ti-reload f-left"></i><span><?php //echo $totalUsers ?></span></h2>
            </div>
         </div>
      </div> -->
      <?php 
      	/*if ($orders) {
      		$i = 0;
      		foreach ($orders as $order) {
      			if ($i == 5) {
      				$i = 0;
      			}
	      		$bgClass = $bg[$i];
	      		?>
	      		<div class="col-md-6 col-xl-3">
	      		   <div class="card bg-c-<?php echo $bgClass; ?> order-card">
	      		      <div class="card-block">
	      		         <h6 class="m-b-20"><?php echo ucfirst($order['order_status']); ?></h6>
	      		         <h2 class="text-right"><i class="ti-reload f-left"></i><span><?php echo $order['total']; ?></span></h2>
	      		      </div>
	      		   </div>
	      		</div>
	      		<?php
	      		$i++;
      		}
      	}*/
      ?>
      <?php 
      	/*if ($posts) {
      		$i = 0;
      		foreach ($posts as $post) {
	      		$postTitle = getPostType($post['post_type']);
      			if ($i == 5) {
      				$i = 0;
      			}
	      		$bgClass = $bg[$i];
	      		?>
	      		<div class="col-md-6 col-xl-3">
	      		   <div class="card bg-c-<?php echo $bgClass; ?> order-card">
	      		      <div class="card-block">
	      		         <h6 class="m-b-20"><?php echo ucfirst($postTitle['title']); ?></h6>
	      		         <h2 class="text-right"><i class="ti-reload f-left"></i><span><?php echo $post['total']; ?></span></h2>
	      		      </div>
	      		   </div>
	      		</div>
	      		<?php
	      		$i++;
      		}
      	}*/
      ?>
      
      <!-- order-card end -->
      <!-- statustic and process start -->
      <div class="col-lg-8 col-md-12">
         <div class="card">
            <div class="card-header">
               <h5>Statistics</h5>
               <div class="card-header-right">
                  <ul class="list-unstyled card-option">
                     <li><i class="fa fa-chevron-left"></i></li>
                     <li><i class="fa fa-window-maximize full-card"></i></li>
                     <li><i class="fa fa-minus minimize-card"></i></li>
                     <li><i class="fa fa-refresh reload-card"></i></li>
                     <li><i class="fa fa-times close-card"></i></li>
                  </ul>
               </div>
            </div>
            <div class="card-block">
               <canvas id="Statistics-chart" height="200"></canvas>
            </div>
         </div>
      </div>
      <div class="col-lg-4 col-md-12">
         <div class="card">
            <div class="card-header">
               <h5>Customer Feedback</h5>
            </div>
            <div class="card-block">
               <span class="d-block text-c-blue f-24 f-w-600 text-center">365247</span>
               <canvas id="feedback-chart" height="100"></canvas>
               <div class="row justify-content-center m-t-15">
                  <div class="col-auto b-r-default m-t-5 m-b-5">
                     <h4>83%</h4>
                     <p class="text-success m-b-0"><i class="ti-hand-point-up m-r-5"></i>Positive</p>
                  </div>
                  <div class="col-auto m-t-5 m-b-5">
                     <h4>17%</h4>
                     <p class="text-danger m-b-0"><i class="ti-hand-point-down m-r-5"></i>Negative</p>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- statustic and process end -->
      <!-- tabs card start -->
      <div class="col-sm-12">
         <div class="card tabs-card">
            <div class="card-block p-0">
               <!-- Nav tabs -->
               <ul class="nav nav-tabs md-tabs" role="tablist">
                  <li class="nav-item">
                     <a class="nav-link active" data-toggle="tab" href="#home3" role="tab"><i class="fa fa-home"></i>Home</a>
                     <div class="slide"></div>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" data-toggle="tab" href="#profile3" role="tab"><i class="fa fa-key"></i>Security</a>
                     <div class="slide"></div>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" data-toggle="tab" href="#messages3" role="tab"><i class="fa fa-play-circle"></i>Entertainment</a>
                     <div class="slide"></div>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" data-toggle="tab" href="#settings3" role="tab"><i class="fa fa-database"></i>Big Data</a>
                     <div class="slide"></div>
                  </li>
               </ul>
               <!-- Tab panes -->
               <div class="tab-content card-block">
                  <div class="tab-pane active" id="home3" role="tabpanel">
                     <div class="table-responsive">
                        <table class="table">
                           <tr>
                              <th>Image</th>
                              <th>Product Code</th>
                              <th>Customer</th>
                              <th>Purchased On</th>
                              <th>Status</th>
                              <th>Transaction ID</th>
                           </tr>
                           <tr>
                              <td><img src="<?php echo adminPublicPath() ?>/images/product/prod2.jpg" alt="prod img" class="img-fluid"></td>
                              <td>PNG002344</td>
                              <td>John Deo</td>
                              <td>05-01-2017</td>
                              <td><span class="label label-danger">Faild</span></td>
                              <td>#7234486</td>
                           </tr>
                           <tr>
                              <td><img src="<?php echo adminPublicPath() ?>/images/product/prod3.jpg" alt="prod img" class="img-fluid"></td>
                              <td>PNG002653</td>
                              <td>Eugine Turner</td>
                              <td>04-01-2017</td>
                              <td><span class="label label-success">Delivered</span></td>
                              <td>#7234417</td>
                           </tr>
                           <tr>
                              <td><img src="<?php echo adminPublicPath() ?>/images/product/prod4.jpg" alt="prod img" class="img-fluid"></td>
                              <td>PNG002156</td>
                              <td>Jacqueline Howell</td>
                              <td>03-01-2017</td>
                              <td><span class="label label-warning">Pending</span></td>
                              <td>#7234454</td>
                           </tr>
                        </table>
                     </div>
                     <div class="text-center">
                        <button class="btn btn-outline-primary btn-round btn-sm">Load More</button>
                     </div>
                  </div>
                  <div class="tab-pane" id="profile3" role="tabpanel">
                     <div class="table-responsive">
                        <table class="table">
                           <tr>
                              <th>Image</th>
                              <th>Product Code</th>
                              <th>Customer</th>
                              <th>Purchased On</th>
                              <th>Status</th>
                              <th>Transaction ID</th>
                           </tr>
                           <tr>
                              <td><img src="<?php echo adminPublicPath() ?>/images/product/prod3.jpg" alt="prod img" class="img-fluid"></td>
                              <td>PNG002653</td>
                              <td>Eugine Turner</td>
                              <td>04-01-2017</td>
                              <td><span class="label label-success">Delivered</span></td>
                              <td>#7234417</td>
                           </tr>
                           <tr>
                              <td><img src="<?php echo adminPublicPath() ?>/images/product/prod4.jpg" alt="prod img" class="img-fluid"></td>
                              <td>PNG002156</td>
                              <td>Jacqueline Howell</td>
                              <td>03-01-2017</td>
                              <td><span class="label label-warning">Pending</span></td>
                              <td>#7234454</td>
                           </tr>
                        </table>
                     </div>
                     <div class="text-center">
                        <button class="btn btn-outline-primary btn-round btn-sm">Load More</button>
                     </div>
                  </div>
                  <div class="tab-pane" id="messages3" role="tabpanel">
                     <div class="table-responsive">
                        <table class="table">
                           <tr>
                              <th>Image</th>
                              <th>Product Code</th>
                              <th>Customer</th>
                              <th>Purchased On</th>
                              <th>Status</th>
                              <th>Transaction ID</th>
                           </tr>
                           <tr>
                              <td><img src="<?php echo adminPublicPath() ?>/images/product/prod1.jpg" alt="prod img" class="img-fluid"></td>
                              <td>PNG002413</td>
                              <td>Jane Elliott</td>
                              <td>06-01-2017</td>
                              <td><span class="label label-primary">Shipping</span></td>
                              <td>#7234421</td>
                           </tr>
                           <tr>
                              <td><img src="<?php echo adminPublicPath() ?>/images/product/prod4.jpg" alt="prod img" class="img-fluid"></td>
                              <td>PNG002156</td>
                              <td>Jacqueline Howell</td>
                              <td>03-01-2017</td>
                              <td><span class="label label-warning">Pending</span></td>
                              <td>#7234454</td>
                           </tr>
                        </table>
                     </div>
                     <div class="text-center">
                        <button class="btn btn-outline-primary btn-round btn-sm">Load More</button>
                     </div>
                  </div>
                  <div class="tab-pane" id="settings3" role="tabpanel">
                     <div class="table-responsive">
                        <table class="table">
                           <tr>
                              <th>Image</th>
                              <th>Product Code</th>
                              <th>Customer</th>
                              <th>Purchased On</th>
                              <th>Status</th>
                              <th>Transaction ID</th>
                           </tr>
                           <tr>
                              <td><img src="<?php echo adminPublicPath() ?>/images/product/prod1.jpg" alt="prod img" class="img-fluid"></td>
                              <td>PNG002413</td>
                              <td>Jane Elliott</td>
                              <td>06-01-2017</td>
                              <td><span class="label label-primary">Shipping</span></td>
                              <td>#7234421</td>
                           </tr>
                           <tr>
                              <td><img src="<?php echo adminPublicPath() ?>/images/product/prod2.jpg" alt="prod img" class="img-fluid"></td>
                              <td>PNG002344</td>
                              <td>John Deo</td>
                              <td>05-01-2017</td>
                              <td><span class="label label-danger">Faild</span></td>
                              <td>#7234486</td>
                           </tr>
                        </table>
                     </div>
                     <div class="text-center">
                        <button class="btn btn-outline-primary btn-round btn-sm">Load More</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
   </div>
</div>
<div id="styleSelector">
</div>