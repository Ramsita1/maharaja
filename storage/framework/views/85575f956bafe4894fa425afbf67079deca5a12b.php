<aside id="colorlib-hero">
   <div class="flexslider">
      <ul class="slides">
      		<li style="background-image: url(<?php echo publicPath().'/front/images/bg-img.jpg' ?>);">
      		   
      		   <div class="container-fluid">
      		      <div class="row">
                     <h2 class="page-title">Select Order Type</h2>
      		         <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-3 slider-text">
      		            <div class="slider-text-inner text-center">
      		               <div class="desc">
      		                  <span class="icon"><i class="flaticon-cutlery"></i></span>
      		                  <p>
      		                  	<a href="<?php echo route('order.type',['type' => 'delivery']) ?>" class="btn btn-primary btn-lg btn-learn">Delivery</a>
      		                  	<a href="<?php echo route('order.type',['type' => 'pickup']) ?>" class="btn btn-primary btn-lg btn-learn">Pickup</a>
      		                  </p>
      		                  <div class="desc2"></div>
      		               </div>
      		            </div>
      		         </div>
      		      </div>
      		   </div>
      		</li>
      </ul>
      
   </div>
</aside>
<?php echo view('Templates.HeaderSliderIntro') ?>
<?php /**PATH C:\xampp\htdocs\pza\resources\views/Estore/OrderOnline.blade.php ENDPATH**/ ?>