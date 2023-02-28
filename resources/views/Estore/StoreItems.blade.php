<div class="container">
  <section class="regular slider">
    <?php 
    foreach ($banners as $banner) {
      ?>
      <div>
        <img src="<?php echo publicPath().'/'.$banner->banner_image; ?>">
      </div>
      <?php
    }
    ?>
    
    <div>
      <img src="<?php echo publicPath() ?>/front/images/menu-img2.jpg">
    </div>
    <div>
      <img src="<?php echo publicPath() ?>/front/images/menu-img1.jpg">
    </div>
    <div>
      <img src="<?php echo publicPath() ?>/front/images/menu-img2.jpg">
    </div>
    
  </section>
</div>
<input type="hidden" id="checkBackButton" value="no">
<div class="colorlib-menu">
   <div class="container">
      <div class="row">
         <div class="col-md-12 animate-box">
            <div class="row sticky" >
               <div class="col-md-12 text-center">
                
                  <ul class="nav nav-tabs text-center catFilter" role="tablist">
                    <section class="menu-slider">
                    <?php 
                    $menuItemID = array_column($menuItems, 'menu_item_id');
                    $itemCategoryIDS = array_column($menuItems, 'item_category');
                    $categories = \App\MenuItemsCategory::whereIn('item_cat_id', $itemCategoryIDS)->where('cat_status', "Active")->orderBy('menu_order', 'ASC')->get()->toArray();
                    $categoryArrays = [];
                    $active = '';
                    $catTitles = [];
                    if ($categories) {
                      foreach ($categories as $category) {
                        $menuItemData = \App\MenuItems::where('item_category', $category['item_cat_id'])->whereIn('menu_item_id', $menuItemID)->orderBy('menu_order', 'ASC')->get();
                        $categoryArrays[$category['cat_slug']] = $menuItemData;
                        $catTitles[$category['cat_slug']] = $category['cat_name'];
                        echo '<div><li role="presentation" class="'.$active.'"><a class="filterItems" data-href="'.$category['cat_slug'].'">'.$category['cat_name'].'</a></div></li>';            
                        $active = '';  
                      }          
                    }
                     ?>
                       </section>
                  </ul>
                 
               </div>
            </div>
            <div class="tab-content">
              <?php 
              if ($categoryArrays) {
                $activeTab = 'active';
                  ?>
                  <div role="tabpanel" class="tab-pane <?php echo $activeTab; ?>" id="allItem">
                     <div class="row">
                      <?php 
                        foreach ($categoryArrays as $catName => $menuData) {
                          echo '<div class="col-md-12 closestDIV" id="'.$catName.'"><h4 class="itemCatHeading">'.$catTitles[$catName].'</h4></div>';
                          if ($menuData) {
                            foreach ($menuData as $menuItem) {
                              echo view('Estore.Item'.$menuItem->item_is, compact('menuItem','catName'));
                            }
                          }
                        }
                      ?>
                     </div>
                  </div>
                  <?php
                  $activeTab = '';
              }
              ?>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<div class="cartGroup">
  <?php echo getCartHtml(false); ?>
</div>
<script type="text/javascript">
  jQuery(document).ready(function($) {
    if ($('#checkBackButton').val() == 'no') {
      $('#checkBackButton').val('yes');
    } else {
      window.location.reload();
    }
  });

  $('.menu-slider').slick({
  dots: false,
  infinite: false,
  speed: 300,
  slidesToShow: 4,
  slidesToScroll: 4,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true,
        dots: false
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows:false
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});
</script>