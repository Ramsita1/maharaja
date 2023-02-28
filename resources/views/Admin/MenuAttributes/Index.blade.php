<div class="page-body">
   <div class="row">

      <div class="col-md-12 col-xl-12">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-6">
                        <h5 class="m-b-10">Menu Attribute</h5>
                     </div>
                     <div class="col-md-6">
                        <a href="<?php echo route('menuAttribute.create') ?>" class="btn btn-success" style="float: right;"><i class="ti-plus"></i>Add </a>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table">
                        <thead>
                           <tr>
                              <th>Attribute Name</th>
                              <th>Attribute Selection</th>
                              <th>Attribute Type</th>
                              <th>Attribute Status</th>
                              <th>Created Date</th>
                              <th>Last Update Date</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                           foreach ($menuAttributes as $menuAttribute) {
                              ?>
                              <tr>
                                 <td><?php echo $menuAttribute->attr_name ?></td>
                                 <td><?php echo $menuAttribute->attr_selection ?></td>
                                 <td><?php echo $menuAttribute->attr_type ?></td>
                                 <td><?php echo $menuAttribute->attr_status ?></td>
                                 <td><?php echo dateFormat($menuAttribute->created_at); ?></td>
                                 <td><?php echo dateFormat($menuAttribute->updated_at); ?></td>
                                 <td>
                                    <a href="<?php echo route('menuAttribute.edit', $menuAttribute->menu_attr_id) ?>"><button type="button" class="btn btn-success"><span class="pcoded-micon"><i class="ti-pencil-alt"></i></span></button></a> 
                                    | 
                                    <?php echo Form::open(['route' => array('menuAttribute.destroy', $menuAttribute->menu_attr_id), 'method' => 'delete','style'=>'display: inline-block;']) ?>
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
                  <?php echo $menuAttributes->links(); ?>
               </div>
            </div>
         </div>
      </div>
      
   </div>
</div>
<div id="styleSelector">
</div>