<div class="page-body">
   <h3>Site Map Xml</h3>
   <?php echo Form::open(['route' => array('siteMap.store'), 'method' => 'post', 'class' => 'md-float-material']) ?>
      <div class="row">
         <div class="col-md-9 padding-right">

            <div class="card">            
               <div class="card-block accordion-block color-accordion-block">
                           
                  <div class="card-block">
                     <div class="">  
                           <div class="input-group row">
                              <label class="col-form-label" for="post_content">SIte Map Xml File</label><br>
                              <textarea name="post_content" rows="40" id="post_content" class="form-control form-control-lg" placeholder="Content"><?php echo (isset($xmlContent)?$xmlContent:''); ?></textarea>
                              <span class="md-line"></span>
                           </div>
                     </div>
                  </div>

               </div>               
            </div>
         </div>
         <div class="col-md-3">
            <div class="card ">
               <div class="card-block">  
                  <div class="row m-t-30">
                     <div class="col-md-12">
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