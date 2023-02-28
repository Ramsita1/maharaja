<aside id="colorlib-hero">
   <div class="flexslider">
      <ul class="slides">
      	<?php 
      	foreach ($sliders as $slider) {
      		?>
      		<li style="background-image: url(<?php echo publicPath().'/'.$slider->post_image ?>);">
      		   <div class="overlay"></div>
      		   <div class="container-fluid">
      		      <div class="row">
      		         <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-3 slider-text">
      		            <div class="slider-text-inner text-center">
      		               <div class="desc">
      		                  <span class="icon"><i class="flaticon-cutlery"></i></span>
      		                  <h1><?php echo $slider->post_title ?></h1>
      		                  <?php echo $slider->post_content ?>
      		                  <p>
      		                  	<a href="" class="btn btn-primary btn-lg btn-learn">Book a table</a>
      		                  	<a href="<?php echo route('order.online') ?>" class="btn btn-primary btn-lg btn-learn">Order Online</a>
      		                  </p>
      		                  <div class="desc2"></div>
      		               </div>
      		            </div>
      		         </div>
      		      </div>
      		   </div>
      		</li>
      		<?php
      	}
      	?>
      </ul>
      <div class="mouse">
         <a href="#" class="mouse-icon">
            <div class="mouse-wheel"></div>
         </a>
      </div>
   </div>
</aside>
<?php echo view('Templates.HeaderSliderIntro') ?>
<div class="goto-here"></div>
<div class="colorlib-about" class="colorlib-light-grey">
   <div class="container">
      <div class="row">
         <div class="col-md-5">
            <div class="row">
               <div class="about-desc">
                  <div class="col-md-12 col-md-offset-0 animate-box intro-heading">
                     <span>Welcome to luto</span>
                     <h2>Taste a delicious food here in Italy &amp; We are inspired since 1895</h2>
                  </div>
                  <div class="col-md-12 animate-box">
                     <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics.</p>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-7">
            <div class="row">
               <div class="col-md-6 animate-box">
                  <div class="about-img" style="background-image: url(<?php echo publicPath() ?>/front/images/about.jpg);">
                  </div>
               </div>
               <div class="col-md-6 animate-box">
                  <div class="about-img about-img-2" style="background-image: url(<?php echo publicPath() ?>/front/images/about-2.jpg);">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="colorlib-introduction" style="background-image: url(<?php echo publicPath() ?>/front/images/cover_bg_1.jpg);" data-stellar-background-ratio="0.5">
   <div class="overlay"></div>
   <div class="container">
      <div class="row">
         <div class="col-md-6 col-md-offset-3 col-md-push-3">
            <div class="intro-box animate-box">
               <h2><a href="#"></a>Foods you love to taste</h2>
               <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
               <p><a href="https://vimeo.com/channels/staffpicks/93951774" class="btn btn-primary btn-lg btn-outline popup-vimeo"><i class="icon-play3"></i> Watch Video</a></p>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="colorlib-menu">
   <div class="container">
      <div class="row">
         <div class="col-md-6 col-md-offset-3 text-center animate-box intro-heading">
            <span class="icon"><i class="flaticon-cutlery"></i></span>
            <h2>Our Delicious Specialties</h2>
            <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life</p>
         </div>
      </div>
      <div class="row">
         <?php 
         $ourDeliciousSpecialties = getPostsByPostType('our_delicious_specialties');
         foreach ($ourDeliciousSpecialties as $ourDeliciousSpecialtie) {
            ?>
            <div class="col-md-4 animate-box">
               <div class="dish-wrap">
                  <div class="wrap">
                     <div class="dish-img" style="background-image: url(<?php echo publicPath().'/'.$ourDeliciousSpecialtie->post_image ?>);"></div>
                     <div class="desc">
                        <h2><a href="#"><?php echo $ourDeliciousSpecialtie->post_title ?></a></h2>
                     </div>
                  </div>
               </div>
            </div>
         <?php } ?>
      </div>
   </div>
</div>

<div class="colorlib-testimony" style="background-image: url(<?php echo publicPath() ?>/front/images/cover_bg_2.jpg);" data-stellar-background-ratio="0.5">
   <div class="overlay"></div>
   <div class="container">
      <div class="row">
         <div class="col-md-6 col-md-offset-3 text-center animate-box intro-heading">
            <h2>Our Customer Says</h2>
         </div>
      </div>
      <div class="row animate-box">
         <div class="owl-carousel">
            <?php 
            $testimonials = getPostsByPostType('testimonials');
            foreach ($testimonials as $testimonial) {
               ?>
               <div class="item">
                  <div class="col-md-8 col-md-offset-2 text-center">
                     <div class="testimony">
                        <blockquote>
                           <?php echo $testimonial->post_content ?>
                           <span>" &mdash; <?php echo $testimonial->post_title ?></span>
                        </blockquote>
                     </div>
                  </div>
               </div>
               <?php
            }
            ?>
         </div>
      </div>
   </div>
</div>
<div class="colorlib-menu">
   <div class="container">
      <div class="row">
         <div class="col-md-6 col-md-offset-3 text-center animate-box intro-heading">
            <span class="icon"><i class="flaticon-cutlery"></i></span>
            <h2>Our Delicious Specialties</h2>
            <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life</p>
         </div>
      </div>
      <div class="row">
         <?php 
         foreach ($ourDeliciousSpecialties as $ourDeliciousSpecialtie) {
            ?>
            <div class="col-md-4 animate-box">
               <div class="dish-wrap">
                  <div class="wrap">
                     <div class="dish-img" style="background-image: url(<?php echo publicPath().'/'.$ourDeliciousSpecialtie->post_image ?>);"></div>
                     <div class="desc">
                        <h2><a href="#"><?php echo $ourDeliciousSpecialtie->post_title ?></a></h2>
                     </div>
                  </div>
               </div>
            </div>
         <?php } ?>
      </div>
   </div>
</div>
<div class="colorlib-reservation reservation-img" style="background-image: url(<?php echo publicPath() ?>/front/images/cover_bg_2.jpg);" data-stellar-background-ratio="0.5">
   <div class="overlay"></div>
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
</div><?php /**PATH /home/k9c9adh99pg7/public_html/pza/resources/views/Templates/Home.blade.php ENDPATH**/ ?>