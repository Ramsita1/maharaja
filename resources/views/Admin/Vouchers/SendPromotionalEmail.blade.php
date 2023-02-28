<div class="page-body">
   <h3>Send Promotional Vouchers</h3>
   <?php 
      echo Form::open(['route' => array('dashboardPostVoucher'), 'method' => 'post', 'class' => 'md-float-material']);
   ?>
      <div class="row">
         <div class="col-md-6">
            <div class="card">            
               <div class="card-block">         
                  <div class="">                          
                     <div class="input-group row">
                        <label class="col-form-label" for="vouchers">Select User</label>
                        <select class="form-control form-control-lg select2" required="" id="vouchers" name="vouchers">
                           <option value="">Select</option>
                           <?php 
                           foreach ($vouchers as $voucher) {
                              echo '<option value="'.$voucher->voucher_id.'">'.$voucher->code.'</option>';
                           }
                           ?>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="users">Select User</label>
                        <select class="form-control form-control-lg select2" required="" id="users" name="users[]" multiple>
                           <option value="">Select</option>
                           <?php 
                           foreach ($users as $user) {
                              echo '<option value="'.$user->name.'|'.$user->email.'|'.$user->phone.'">'.$user->name.' | '.$user->email.' | '.$user->phone.'</option>';
                           }
                           ?>
                        </select>
                        <span class="md-line"></span>
                     </div>
                     <div class="input-group row">
                        <label class="col-form-label" for="message">Message</label><br>
                        <textarea name="message" id="message" required="" class="form-control form-control-lg" placeholder="Description"></textarea>
                        <span class="md-line"></span>
                     </div>
                     
                     <div class="input-group row">
                       <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Send</button>
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
