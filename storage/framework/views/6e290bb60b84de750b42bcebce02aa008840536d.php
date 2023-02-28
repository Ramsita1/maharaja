<div class="page-body">
   <div class="row">

      <div class="col-md-12 col-xl-12">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-6">
                        <h5 class="m-b-10">Menu Item Type</h5>
                     </div>
                     <div class="col-md-6">
                        <a href="<?php echo route('menuAttributeSize.create') ?>" class="btn btn-success" style="float: right;"><i class="ti-plus"></i>Add </a>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table">
                        <thead>
                           <tr>
                              <th>Itme Type</th>
                              <th>Created Date</th>
                              <th>Last Update Date</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                           foreach ($menuAttributeSizes as $menuAttributeSize) {
                              ?>
                              <tr>
                                 <td><?php echo $menuAttributeSize->size_name ?></td>
                                 <td><?php echo dateFormat($menuAttributeSize->created_at); ?></td>
                                 <td><?php echo dateFormat($menuAttributeSize->updated_at); ?></td>
                                 <td>
                                    <a href="<?php echo route('menuAttributeSize.edit', $menuAttributeSize->attribute_size_id) ?>"><button type="button" class="btn btn-success"><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> 
                                    | 
                                    <?php echo Form::open(['route' => array('menuAttributeSize.destroy', $menuAttributeSize->attribute_size_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
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
                  <?php echo $menuAttributeSizes->links(); ?>
               </div>
            </div>
         </div>
      </div>
      
   </div>
</div>
<div id="styleSelector">
</div><?php /**PATH /home/k9c9adh99pg7/public_html/pza/resources/views/Admin/MenuAttributeSize/Index.blade.php ENDPATH**/ ?>