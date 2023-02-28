<div class="page-body">
   <h3>Menu Attribute</h3>
   <?php 
      if ($holiday->store_holiday_id) {
         echo Form::open(['route' => array('storeHolidays.update', $holiday->store_holiday_id), 'method' => 'put', 'class' => 'md-float-material']);
      }else{
         echo Form::open(['route' => array('storeHolidays.store'), 'method' => 'post', 'class' => 'md-float-material']);
      }       
   ?>
      <div class="row">
         <div class="col-md-6">
            <div class="card">            
               <div class="card-block">         
                  <div class="">                          
                     <div class="input-group row">
                        <label class="col-form-label" for="date">Date</label><br>
                        <input type="text" name="date" required="" id="date" class="form-control form-control-lg datePicker" placeholder="Date" value="<?php echo $holiday->date ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row showHide">
                        <label class="col-form-label" for="close_start_time">Start Time</label><br>
                        <input type="text" name="close_start_time" required="" id="close_start_time" class="form-control form-control-lg timepicker" placeholder="Start Time" value="<?php echo $holiday->close_start_time ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row showHide">
                        <label class="col-form-label" for="close_end_time">End Time</label><br>
                        <input type="text" name="close_end_time" required="" id="close_end_time" class="form-control form-control-lg timepicker" placeholder="End Time" value="<?php echo $holiday->close_end_time ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="full_day_off">Full Day OFF</label><br>
                        <div class="checkbox col-md-12">
                          <label>
                            <input type="checkbox" id="full_day_off" name="full_day_off" value="1" <?php echo ($holiday->full_day_off == 1?'checked':'') ?> data-toggle="toggle">
                          </label>
                        </div>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                       <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Save</button>
                     </div>

                  </div>
               </div>
            </div>

         </div>     
      </div>   
   </form>
</div>
<div id="styleSelector">
</div>
<script type="text/javascript">
   jQuery(document).ready(function($) {
      $('#full_day_off').change(function(event) {
         if ($(this).is(':checked')) {
            $('.showHide').fadeOut();
            $('#close_start_time').val('12:00:00');
            $('#close_end_time').val('11:59:00');
         } else {
            $('.showHide').fadeIn();
            $('#close_start_time').val('');
            $('#close_end_time').val('');
         }
      });
      $('#full_day_off').trigger('change');
   });
</script>