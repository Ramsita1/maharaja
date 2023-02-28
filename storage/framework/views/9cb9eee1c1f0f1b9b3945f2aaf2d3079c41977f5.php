<div class="page-body">
   <h3>Create Store</h3>
   <style type="text/css">
      #exTab1 .tab-content {
        padding : 5px 15px;
      }
      #exTab1 .nav-pills > li > a {
        border-radius: 0;
      }
   </style>
   <?php 
   $store_id = $store->store_id;
   $tab = Request()->get('tab');
   
   if (!$store_id) {
      $activeTabShow = 'StoreInfo';
   }else{      
      $activeTabShow = 'All';
   }   
   ?>
      <div class="row">
         <div class="col-md-12">

            <div class="card">            
               <div class="card-block">

                  <div id="exTab1" class="container">
                     <ul  class="nav nav-tabs">
                        <?php 
                        
                        $activeTab = '';
                        foreach (storeCreateUpdateViewTabs($activeTabShow) as $tabKey => $tabValue) { 
                           if ($tabKey == $tab) {
                              $activeTab = 'active';
                           }
                        ?>
                           <li class=" nav-item"><a class="nav-link <?php echo $activeTab; ?>" href="<?php echo $pageUrl ?>?tab=<?php echo $tabKey ?>"><?php echo $tabValue ?></a></li>
                        <?php 
                        $activeTab = '';
                        } ?>
                     </ul>
                     <div class="tab-content clearfix">
                        <?php 
                        foreach (storeCreateUpdateViewTabs($activeTabShow) as $tabKey => $tabValue) { 
                           if ($tabKey == $tab) {
                              ?>
                                 <div class="tab-pane active" id="<?php echo $tabKey; ?>">
                                    <?php echo view('Admin.Store.'.$tabKey, compact('store','pageUrl')); ?>
                                 </div>
                              <?php 
                           }
                        } ?>
                     </div>
                  </div>
               </div>
            </div>

         </div>     
      </div>   
</div>
<div id="styleSelector">
</div><?php /**PATH /home/k9c9adh99pg7/public_html/pza/resources/views/Admin/Store/CreateEdit.blade.php ENDPATH**/ ?>