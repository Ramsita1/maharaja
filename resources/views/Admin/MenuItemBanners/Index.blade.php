<div class="page-body">
   <div class="row">

      <div class="col-md-12 col-xl-12">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-6">
                        <h5 class="m-b-10">Menu Banners</h5>
                     </div>
                     <div class="col-md-6">
                        <a href="<?php echo route('menuItemBanner.create') ?>" class="btn btn-success" style="float: right;"><i class="ti-plus"></i>Add </a>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table">
                        <thead>
                           <tr>
                              <th>Banner Name</th>
                              <th>Banner Image</th>
                              <th>Last Update Date</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                           foreach ($menuItemBanners as $menuItemBanner) {
                              ?>
                              <tr>
                                 <td><?php echo $menuItemBanner->banner_name ?></td>
                                 <td><img src="<?php echo publicPath().'/'.$menuItemBanner->banner_image; ?>" style="width:70px; height:70px;"></td>
                                 <td><?php echo dateFormat($menuItemBanner->updated_at); ?></td>
                                 <td>
                                    <a href="<?php echo route('menuItemBanner.edit', $menuItemBanner->banner_id) ?>"><button type="button" class="btn btn-success"><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> 
                                    |                                    
                                    <?php echo Form::open(['route' => array('menuItemBanner.destroy', $menuItemBanner->banner_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
                                       <button type="submit" class="btn btn-danger"><span class="pcoded-micon"><i class="ti-trash"></i></span></button>
                                    </form>                                
                                 </td>
                              </tr>
                              <?php
                           }
                           ?>                           
                        </tbody>
                     </table>
                  </div>
                  <?php echo $menuItemBanners->links(); ?>
               </div>
            </div>
         </div>
      </div>
      
   </div>
</div>
<div id="styleSelector">
</div>