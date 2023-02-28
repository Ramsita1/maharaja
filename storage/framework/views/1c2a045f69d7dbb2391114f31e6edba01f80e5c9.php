<style>
	/*my account css*/
	.myaccount-wrap{padding:20px 0px;}
 .myaccount-header{border: 1px solid #666;
    border-radius: 4px;
    padding: 20px 20px;
    margin: 10px 0px;
    float: left;
    width: 100%;}   
 .myaccount-header .myaccount-left{font-size: 16px;
    color: #000;
    float: left;}
.myaccount-header .myaccount-left span    {font-size: 16px;
    margin-right: 20px;}
.myaccount-header .myaccount-left span img{width:40px;}
.myaccount-header .myaccount-right{float:right;text-align: right;}
.myaccount-header .myaccount-right a{display:block;}
.myaccount-blk{border: 1px solid #666;
    border-radius: 4px;
    width: 100%;
    float: left;
    margin: 10px 0px;}
.myaccount-blk   a{    display: block;
    width: 100%;
    height: 100%;
    float: left; 
    padding: 20px;}
.myaccount-blk     a:hover{   background: #efefef;}
.blk-left{    float: left;}

.blk-left img{width: 40px;}
.blk-right{float: right;
    color: #000;padding-top:5px;}
.myaccount-foot .myaccount-left{padding-top:10px;}
.myaccount-foot .myaccount-left span img{width:120px;}
.myaccount-foot .myaccount-right b{color:#000;font-weight:500;}
.myaccount-foot .myaccount-right a{font-size:13px;}

</style>
<?php 
$currentUser = getCurrentUser();
if (!$currentUser->user_id) {
	?>
	<div id="">
	   <div class="myaccount-wrap">
	      <div class="container">
	         <div class="row">
	            <div class="col-md-12">
	               <div class="myaccount-header">
	               		<div class="myaccount-left">
	                     	<span><img src="<?php echo publicPath() ?>/front/images/king.png"></span>
                  		</div>
	                  <div class="myaccount-right">
	                  	<a>You are not login yet!!!</a>
	                     <a href="javascript:void(0);" class="loginUserBTN">Login</a>
	                  </div>
	               </div>
	            </div>
	         </div>
	      </div>
	   </div>
    </div>
	<?php
} else {
	?>
	<div id="">
	   <div class="myaccount-wrap">
	      <div class="container">
	         <div class="row">
	            <div class="col-md-12">
	               <div class="myaccount-header">
	                  <div class="myaccount-left">
	                     <span><img src="<?php echo publicPath() ?>/front/images/king.png"></span>Jasgun Singh
	                  </div>
	                  <div class="myaccount-right">
	                     <a href="#">Edit Profile</a>
	                     <a href="<?php echo url('/admin/logout') ?>">Log Out</a>
	                  </div>
	               </div>
	            </div>
	            <div class="col-md-6">
	               <div class="myaccount-blk">
	                  <a href="<?php echo url('/my-order') ?>">
	                     <div class="blk-left">
	                        <img src="<?php echo publicPath() ?>/front/images/scroll.png">
	                     </div>
	                     <div class="blk-right">
	                        <span>Order History</span>
	                     </div>
	                  </a>
	               </div>
	            </div>
	            <div class="col-md-6">
	               <div class="myaccount-blk">
	                  <a href="#">
	                     <div class="blk-left">
	                        <img src="<?php echo publicPath() ?>/front/images/star.png">
	                     </div>
	                     <div class="blk-right">
	                        <span>Favourite Orders</span>
	                     </div>
	                  </a>
	               </div>
	            </div>
	         </div>
	         <div class="row">
	            <div class="col-md-6">
	               <div class="myaccount-blk">
	                  <a href="#">
	                     <div class="blk-left">
	                        <img src="<?php echo publicPath() ?>/front/images/saved-address.png">
	                     </div>
	                     <div class="blk-right">
	                        <span>Saved Addresses</span>
	                     </div>
	                  </a>
	               </div>
	            </div>
	            <div class="col-md-6">
	               <div class="myaccount-blk">
	                  <a href="#">
	                     <div class="blk-left">
	                        <img src="<?php echo publicPath() ?>/front/images/loyality.png">
	                     </div>
	                     <div class="blk-right">
	                        <span>Loyalty Points</span>
	                     </div>
	                  </a>
	               </div>
	            </div>
	         </div>
	         <div class="row">
	            <div class="col-md-6">
	               <div class="myaccount-blk">
	                  <a href="#">
	                     <div class="blk-left">
	                        <img src="<?php echo publicPath() ?>/front/images/booking.png">
	                     </div>
	                     <div class="blk-right">
	                        <span>Table Bookings</span>
	                     </div>
	                  </a>
	               </div>
	            </div>
	            <div class="col-md-6">
	               <div class="myaccount-blk">
	                  <a href="#">
	                     <div class="blk-left">
	                        <img src="<?php echo publicPath() ?>/front/images/help.png">
	                     </div>
	                     <div class="blk-right">
	                        <span>Help</span>
	                     </div>
	                  </a>
	               </div>
	            </div>
	         </div>
	         <div class="row">
	            <div class="col-md-12">
	               <div class="myaccount-header myaccount-foot">
	                  <div class="myaccount-left">
	                     <span><img src="<?php echo publicPath() ?>/front/images/ios-logo.png"></span>
	                     <span><img src="<?php echo publicPath() ?>/front/images/gplay-icon.png"></span>
	                  </div>
	                  <div class="myaccount-right">
	                     <b>Download Our App</b>
	                     <a href="#">Get Live Tracking & Realtime Order Updates</a>
	                  </div>
	               </div>
	            </div>
	         </div>
	      </div>
	   </div>
	</div>
<?php 
}
?><?php /**PATH C:\xampp2\htdocs\maharaja-hotel\resources\views/Templates/MyAccount.blade.php ENDPATH**/ ?>