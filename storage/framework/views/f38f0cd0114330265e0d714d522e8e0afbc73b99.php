<style>
.authModal {
	display: none;
	position: fixed;
	padding-top: 100px;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	overflow: auto;
	background-color: rgb(0, 0, 0);
	background-color: rgba(0, 0, 0, 0.4);
}

.authModal .modal-content {
	background-color: #fefefe;
	margin: auto;
	padding: 20px;
	border: 1px solid #888;
	width: 100%;
	max-width: 500px;
}

.close {
	color: #aaaaaa;
	float: right;
	font-size: 28px;
	font-weight: bold;
}

.close:hover,
.close:focus {
	color: #000;
	text-decoration: none;
	cursor: pointer;
}

.login-box {
	color: #000;
}

.login-box .form-control {
	border: 1px solid #333;
	border-radius: 5px;
	color: #000;
	height: 50px;
	color: #000;
}

.login-box h2 {
	color: #FF6107;
	font-size: 26px;
}

.login-box .form-group {
	position: relative;
}

#phone {
	padding-left: 50px;
}

.forgot-pass {
	display: block;
	text-align: right;
}

.phone-data span {
	position: absolute;
	top: 24px;
	color: #000;
	left: 6px;
	border-right: 1px solid #000;
	padding-right: 8px;
}

.login-box .btn-default {
	width: 100%;
	height: 50px;
	background: #FF6107;
	color: #fff;
	border-color: #FF6107;
	font-size: 16px!important;
	font-weight: 500;
}

.footer-modal {
	text-align: center;
	padding: 10px 0px;
}
</style>
<div id="loginModal" class="modal authModal">
   <div class="modal-content">
      <span class="close" onclick="$('#loginModal').fadeOut();">&times;</span>
      <div class="login-box">
        <h2>Login</h2>
        <?php echo Form::open(['route' => array('front.login'), 'method' => 'post', 'id' => 'loginUser']) ?>
	        <div class="form-group phone-data">
	           <input type="text" class="form-control InputNumber" id="phone" name="phone" placeholder="Phone">
	           <span>+61</span>
	        </div>
	        <div class="form-group">
	           <input type="password" class="form-control" id="password" name="password" placeholder="Password">
	           <a href="javascript:void(0);" class="forgot-pass forgotPassBTN"><span>Forgot Password?</span></a>
	        </div>
	        <div class="form-group">
	        	<input type="checkbox" name="remember_password" value="1">&nbsp;&nbsp;Remember Password
	        </div>
	        <button type="submit" class="btn btn-default">Login</button>
	        <div class="footer-modal">New User? <a href="javascript:void(0);" class="signupUserBTN">SIGNUP</a></div>
	    </form>
      </div>
   </div>
</div>
<div id="forgotModal" class="modal authModal">
   <div class="modal-content">
      <span class="close" onclick="$('#forgotModal').fadeOut();">&times;</span>
      <div class="login-box">
         <h2>Forgot Password</h2>
         <p>Please enter your Phone Number</p>
         <?php echo Form::open(['route' => array('front.forgotPasswordUser'), 'method' => 'post', 'id' => 'forgotPasswordUser']) ?>
            <div class="form-group">
               <input type="text" class="form-control InputNumber" id="otpphone" name="phone" placeholder="Phone Number">
               <div class="text-center" style="padding: 20px 0px 10px;"> <img src="<?php echo publicPath() ?>/front/images/otp-img.png" style="width:55px;"/></div>
               <p class="text-center">We will send you a One Time Password <br>
                  on your Phone Number
               </p>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
         </form>
      </div>
   </div>
</div>
<div id="forgotModal2" class="modal authModal">
   <div class="modal-content">
      <span class="close" onclick="$('#forgotModal2').fadeOut();">&times;</span>
      <div class="login-box">
         <h2>Forgot Password</h2>
         <p class="text-center">Please enter One Time Password <br>
            sent on +61XXXXXXXX 
         </p>
         <div class="text-center" style="padding: 20px 0px 10px;"> <img src="<?php echo publicPath() ?>/front/images/sms-img.png" style="width:55px;"/></div>
         <?php echo Form::open(['route' => array('front.forgotPasswordOtpUser'), 'method' => 'post', 'id' => 'forgotPasswordOtpUser']) ?>
            <div class="form-group">
               <input type="text" class="form-control" id="otp_code" name="otp_code" placeholder="Otp Code">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
         </form>
      </div>
   </div>
</div>
<div id="resetpass" class="modal authModal">
   <div class="modal-content">
      <span class="close" onclick="$('#resetpass').fadeOut();">&times;</span>
      <div class="login-box">
         <h2>Reset Password</h2>
         <?php echo Form::open(['route' => array('front.resetPasswordUser'), 'method' => 'post', 'id' => 'resetPasswordUser']) ?>
	         <div class="form-group">
	            <input type="password" class="form-control" id="password" name="password" placeholder="Create New Password">
	         </div>
	         <div class="form-group">
	            <input type="password" class="form-control" id="confirm-password" name="password_confirmation" placeholder="New Confirm` Password">
	         </div>
	         <div class="text-center" style="padding: 0px 0px 20px;"> <img src="<?php echo publicPath() ?>/front/images/key-img.png" style="width:55px;"/></div>
            <button type="submit" class="btn btn-default">Submit</button>
         </form>
      </div>
   </div>
</div>
<div id="signupPage" class="modal authModal">
   <div class="modal-content">
   	  <span class="close" onclick="$('#signupPage').fadeOut();">&times;</span>
      <div class="login-box step1">
         <h2>Signup<span style="float: right;
            color: #ccc;
            font-size: 12px;
            display: inline-block;
            padding-top: 10px;">(step 1 of 2)</span></h2>
         <?php echo Form::open(['route' => array('front.registerStepOne'), 'method' => 'post', 'id' => 'registerUser']) ?>
            <div class="form-group">
               <input type="text" class="form-control" id="name" name="name" placeholder="Name">
            </div>
            <div class="form-group">
               <input type="email" class="form-control" id="email" name="email" placeholder="Email">
            </div>
            <div class="form-group phone-data">
               <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
               <span style="top:12px;">+61</span>
            </div>
            <div class="form-group">
               <input type="password" class="form-control" id="password" name="password" placeholder="Create Password">
            </div>
            <div class="form-group">
	        	<input type="checkbox" name="remember_password" value="1">&nbsp;&nbsp;Remember Password
	        </div>
            <button type="submit" class="btn btn-default">Submit</button>
            <div class="footer-modal">Existing User? <a class="loginUserBTN">LOGIN</a></div>
         </form>
      </div>
      <div class="login-box step2" style="display: none;">
         <h2>Signup<span style="float: right;
            color: #ccc;
            font-size: 12px;
            display: inline-block;
            padding-top: 10px;">(step 2 of 2)</span></h2>
          <?php echo Form::open(['route' => array('front.registerStepTwo'), 'method' => 'post', 'id' => 'registerUserStep2']) ?>
            <div class="form-group">
               <input type="text" class="form-control datePicker" id="dob" name="dob" readonly="" placeholder="Date of Birth">
            </div>
            <div class="form-group">
               <select class="form-control" id="gender" name="gender">
                  <option>Gender</option>
                  <option value="M">Male</option>
                  <option value="F">Female</option>
               </select>
            </div>
            <div style="text-align: center;padding-bottom: 20px;line-height: 18px;">We will send you a One Time Password<br> 
               on your Phone Number
            </div>
            <div style="font-size:12px;padding-bottom:5px;">By clicking Sign up, you agree to our Terms & Conditions 
               and Privacy Statement.
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
          </form>
      </div>
      <div class="login-box step3" style="display: none;">
         <h2>Verification</h2>
         <p style="text-align: center;">Please enter One Time Password<br> 
            sent on +61XXXXXXXX 
         </p>
         <div class="text-center" style="padding: 20px 0px 10px;"> <img src="<?php echo publicPath() ?>/front/images/sms-img.png" style="width:55px;"/></div>
         <?php echo Form::open(['route' => array('front.register'), 'method' => 'post', 'id' => 'registerUserStep3']) ?>
            <div class="form-group">
               <input type="text" class="form-control InputNumber" id="Regotp_code" name="otp_code" placeholder="One Time Passward">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
         </form>
      </div>
   </div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".datePicker").datepicker({
           dateFormat: 'yy-mm-dd',
           changeMonth: true,
           changeYear: true
        });
		$(document).on('click', '.loginUserBTN', function(event) {
			event.preventDefault();
			$('body').removeClass('menu-show');
			$('.modal').fadeOut();
			$('#loginModal').fadeIn();
		});
		$(document).on('click', '.signupUserBTN', function(event) {
			event.preventDefault();
			$('.modal').fadeOut();
			$('#signupPage').fadeIn();
		});
		$(document).on('click', '.forgotPassBTN', function(event) {
			event.preventDefault();
			$('.modal').fadeOut();
			$('#forgotModal').fadeIn();
		});
		$(document).on('submit', '#loginUser', function(event) {
			event.preventDefault();
			var url = $(this).attr('action');
			var dataString = $(this).serialize();
			$.ajax({
				url: url,
				type: 'POST',
				data: dataString,
			})
			.done(function(response) {
				windowALert(response.status, response.message);
				if (response.status == 'success') {
					$('.modal').fadeOut();
					window.location.reload();
				}
			})
			.fail(function() {
				windowALert('error', 'Something Went wrong, Please try after sometime');
			});			
		});
		$(document).on('submit', '#registerUser', function(event) {
			event.preventDefault();
			var url = $(this).attr('action');
			var dataString = $(this).serialize();
			$.ajax({
				url: url,
				type: 'POST',
				data: dataString,
			})
			.done(function(response) {
				windowALert(response.status, response.message);
				if (response.status == 'success') {
					$('.step1, .step3').fadeOut();
					$('.step2').fadeIn();
				}
			})
			.fail(function() {
				windowALert('error', 'Something Went wrong, Please try after sometime');
			});			
		});
		$(document).on('submit', '#registerUserStep2', function(event) {
			event.preventDefault();
			var url = $(this).attr('action');
			var dataString = $('#registerUser').serialize();
			var dob = $('#dob').val();
			var gender = $('#gender').val();
			dataString += '&dob='+dob+'&gender='+gender;
			$.ajax({
				url: url,
				type: 'POST',
				data: dataString,
			})
			.done(function(response) {
				windowALert(response.status, response.message);
				if (response.status == 'success') {
					$('.step1, .step2').fadeOut();
					$('.step3').fadeIn();
				}
			})
			.fail(function() {
				windowALert('error', 'Something Went wrong, Please try after sometime');
			});			
		});
		$(document).on('submit', '#registerUserStep3', function(event) {
			event.preventDefault();
			var url = $(this).attr('action');
			var dataString = $('#registerUser').serialize();
			var dob = $('#dob').val();
			var gender = $('#gender').val();
			var otp_code = $('#Regotp_code').val();
			dataString += '&dob='+dob+'&gender='+gender+'&otp_code='+otp_code;

			$.ajax({
				url: url,
				type: 'POST',
				data: dataString,
			})
			.done(function(response) {
				windowALert(response.status, response.message);
				if (response.status == 'success') {
					$('#registerUser')[0].reset();
					$('#registerUserStep2')[0].reset();
					$('#registerUserStep3')[0].reset();
					$('.modal').fadeOut();
					$('.loginUserBTN').trigger('click');
				}
			})
			.fail(function() {
				windowALert('error', 'Something Went wrong, Please try after sometime');
			});			
		});
		$(document).on('submit', '#forgotPasswordUser', function(event) {
			event.preventDefault();
			var url = $(this).attr('action');
			var dataString = $(this).serialize();
			$.ajax({
				url: url,
				type: 'POST',
				data: dataString,
			})
			.done(function(response) {
				windowALert(response.status, response.message);
				if (response.status == 'success') {
					$('#forgotModal').fadeOut();
					$('#forgotModal2').fadeIn();
				}
			})
			.fail(function() {
				windowALert('error', 'Something Went wrong, Please try after sometime');
			});			
		});
		$(document).on('submit', '#forgotPasswordOtpUser', function(event) {
			event.preventDefault();
			var url = $(this).attr('action');
			var dataString = $(this).serialize();
			var phone = $('#otpphone').val();
			dataString += '&phone='+phone;
			$.ajax({
				url: url,
				type: 'POST',
				data: dataString,
			})
			.done(function(response) {
				windowALert(response.status, response.message);
				if (response.status == 'success') {
					$('#forgotModal2').fadeOut();
					$('#resetpass').fadeIn();
				}
			})
			.fail(function() {
				windowALert('error', 'Something Went wrong, Please try after sometime');
			});			
		});
		$(document).on('submit', '#resetPasswordUser', function(event) {
			event.preventDefault();
			var url = $(this).attr('action');
			var dataString = $(this).serialize();
			var phone = $('#otpphone').val();
			dataString += '&phone='+phone;
			$.ajax({
				url: url,
				type: 'POST',
				data: dataString,
			})
			.done(function(response) {
				windowALert(response.status, response.message);
				if (response.status == 'success') {
					$('#resetPasswordUser')[0].reset();
					$('#forgotPasswordOtpUser')[0].reset();
					$('#forgotPasswordUser')[0].reset();
					$('.modal').fadeOut();
					$('.loginUserBTN').trigger('click');
				}
			})
			.fail(function() {
				windowALert('error', 'Something Went wrong, Please try after sometime');
			});			
		});
	});
</script><?php /**PATH C:\xampp\htdocs\pza\resources\views/Templates/LoginRegister.blade.php ENDPATH**/ ?>