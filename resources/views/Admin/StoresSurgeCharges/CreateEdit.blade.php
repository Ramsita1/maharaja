<div class="page-body">
   <h3>Stores SurgeCharges</h3>
   <?php 
      if ($surgeCharges->store_surge_id) {
         echo Form::open(['route' => array('storesSurgeCharges.update', $surgeCharges->store_surge_id), 'method' => 'put', 'class' => 'md-float-material']);
      }else{
         echo Form::open(['route' => array('storesSurgeCharges.store'), 'method' => 'post', 'class' => 'md-float-material']);
      }       
   ?>
      <div class="row">
         <div class="col-md-6">
            <div class="card">            
               <div class="card-block">         
                  <div class="">                          
                     <div class="input-group row">
                        <label class="col-form-label" for="date">Date</label><br>
                        <input type="text" name="date" required="" id="date" class="form-control form-control-lg datePicker" placeholder="Date" value="<?php echo $surgeCharges->date ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row showHide">
                        <label class="col-form-label" for="reason">Reason</label><br>
                        <input type="text" name="reason" required="" id="reason" class="form-control form-control-lg" placeholder="Reason" value="<?php echo $surgeCharges->reason ?>">
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row showHide">
                        <label class="col-form-label" for="percentage">Percentage %</label><br>
                        <input type="text" name="percentage" required="" id="percentage" class="form-control form-control-lg InputNumber" placeholder="Percentage" value="<?php echo $surgeCharges->percentage ?>">
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