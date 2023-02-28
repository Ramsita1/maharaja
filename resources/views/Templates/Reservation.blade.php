<?php echo headerCommon($post); ?>

<?php echo view('Templates.HeaderSliderIntro') ?>

<div class="colorlib-reservation reservation-page">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3 text-center animate-box intro-heading">
				<h2>Make A Reservation</h2>
				<p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life</p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="row">
					<div class="col-md-12">
						<form method="post" class="colorlib-form">
		              	<div class="row">
								<div class="col-md-6 animate-box">
									<div class="form-group">
										<label for="name">Fullname</label>
										<div class="form-field">
											<input type="text" class="form-control" placeholder="name">
										</div>
									</div>
								</div>
								<div class="col-md-6 animate-box">
									<div class="form-group">
										<label for="email">Email</label>
										<div class="form-field">
											<input type="text" class="form-control" placeholder="email">
										</div>
									</div>
								</div>
								<div class="col-md-6 animate-box">
									<div class="form-group">
										<label for="phone">Phone</label>
										<div class="form-field">
											<input type="text" class="form-control" placeholder="phone">
										</div>
									</div>
								</div>
								<div class="col-md-6 animate-box">
									<div class="form-group">
										<label for="date">Date:</label>
										<div class="form-field">
											<i class="icon icon-calendar2"></i>
											<input type="text" id="date" class="form-control date" placeholder="Date">
										</div>
									</div>
								</div>
								<div class="col-md-6 animate-box">
									<div class="form-group">
										<label for="time">Time</label>
										<div class="form-field">
											<i class="icon icon-arrow-down3"></i>
											<select name="time" id="time" class="form-control">
												<option value="#">6:30am</option>
												<option value="#">7:00am</option>
												<option value="#">7:30am</option>
												<option value="#">8:00am</option>
												<option value="#">8:30am</option>
												<option value="#">9:00am</option>
												<option value="#">9:30am</option>
												<option value="#">10:00am</option>
												<option value="#">10:30am</option>
												<option value="#">11:00am</option>
												<option value="#">11:30am</option>
												<option value="#">12:00am</option>
												<option value="#">12:30am</option>
												<option value="#">1:00pm</option>
												<option value="#">1:30pm</option>
												<option value="#">2:00pm</option>
												<option value="#">2:30pm</option>
												<option value="#">3:00pm</option>
												<option value="#">3:30pm</option>
												<option value="#">4:00pm</option>
												<option value="#">4:30pm</option>
												<option value="#">5:00pm</option>
												<option value="#">5:30pm</option>
												<option value="#">6:00pm</option>
												<option value="#">6:30pm</option>
												<option value="#">7:00pm</option>
												<option value="#">7:30pm</option>
												<option value="#">8:00pm</option>
												<option value="#">8:30pm</option>
												<option value="#">9:00pm</option>
												<option value="#">9:30pm</option>
												<option value="#">10:00pm</option>
												<option value="#">10:30pm</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-6 animate-box">
									<div class="form-group">
										<label for="person">Person</label>
										<div class="form-field">
											<i class="icon icon-arrow-down3"></i>
											<select name="people" id="people" class="form-control">
												<option value="#">1</option>
												<option value="#">2</option>
												<option value="#">3</option>
												<option value="#">4</option>
												<option value="#">5+</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-12 animate-box">
									<div class="row">
										<div class="col-md-4 col-md-offset-4">
											<input type="submit" name="submit" id="submit" value="Book a table" class="btn btn-primary btn-block">
										</div>
									</div>
								</div>
							</div>
		            </form>
	           	</div>
				</div>
			</div>
		</div>
	</div>
</div>