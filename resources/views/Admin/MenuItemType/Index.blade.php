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
                        <a href="<?php echo route('menuItemType.create') ?>" class="btn btn-success" style="float: right;"><i class="ti-plus"></i>Add </a>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table">
                        <thead>
                           <tr>
                              <th>Itme Type</th>
                              <th>Type Description</th>
                              <th>Created Date</th>
                              <th>Last Update Date</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                           foreach ($menuItemTypes as $menuItemType) {
                              ?>
                              <tr>
                                 <td><?php echo $menuItemType->type_name ?></td>
                                 <td><?php echo $menuItemType->type_description ?></td>
                                 <td><?php echo dateFormat($menuItemType->created_at); ?></td>
                                 <td><?php echo dateFormat($menuItemType->updated_at); ?></td>
                                 <td>
                                    <a href="<?php echo route('menuItemType.edit', $menuItemType->item_type_id) ?>"><button type="button" class="btn btn-success"><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> 
                                    | 
                                    <?php echo Form::open(['route' => array('menuItemType.destroy', $menuItemType->item_type_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
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
                  <?php echo $menuItemTypes->links(); ?>
               </div>
            </div>
         </div>
      </div>
      
   </div>
</div>
<div id="styleSelector">
</div>