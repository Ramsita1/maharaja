<div class="row">
    <div class="col-sm-12">
        <!-- Bootstrap tab card start -->
        <div class="card">
            <div class="card-header">
                <h3>Menus</h3>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option" style="width: 180px;">
                        <li><i class="fa fa-chevron-left fa-chevron-right"></i></li>
                        <li><i class="fa fa-window-maximize full-card"></i></li>
                        <li><i class="fa minimize-card fa-minus"></i></li>
                        <li><i class="fa fa-refresh reload-card"></i></li>
                        <li><i class="fa fa-times close-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block" style="">
                <!-- Row start -->
                <div class="row">
                    <div class="col-lg-12 col-xl-12">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs  tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#home1" role="tab" aria-expanded="true"><h6>Edit Menus</h6></a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content tabs card-block">
                            <div class="tab-pane active" id="home1" role="tabpanel" aria-expanded="true">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h5>Add menu items</h5>
                                        <br>
                                        <div class="card">
                                            <div class="card-header">
                                            </div>
                                            <div class="card-block accordion-block">
                                                <div id="accordion" role="tablist" aria-multiselectable="true">
                                                <?php 
                                                foreach($menu_data as $menuKey => $menuValue) {
                                                    $postTitle = getPostType($menuKey);
                                                    ?>
                                                    <div class="accordion-panel">
                                                            <div class="accordion-heading" role="tab" id="<?php echo $menuKey ?>Accordian">
                                                                <h3 class="card-title accordion-title">
                                                                    <a class="accordion-msg" data-toggle="collapse"
                                                                        data-parent="#accordion" href="#<?php echo $menuKey ?>collapse"
                                                                        aria-expanded="true" aria-controls="<?php echo $menuKey ?>collapse">
                                                                        <h6><b><?php echo $postTitle['title'] ?></b></h6>
                                                                    </a>
                                                                </h3>
                                                            </div>
                                                            <div id="<?php echo $menuKey ?>collapse" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="<?php echo $menuKey ?>Accordian">
                                                                <form class="formAddMenu">
                                                                    <div class="accordion-content accordion-desc">
                                                                        <ul>
                                                                            <?php foreach ( $menuValue as $menu){ ?>
                                                                                <li>
                                                                                    <input class="menuCheckBox" type="checkbox"
                                                                                        value="<?php echo $menu['post_id'] ?>" 
                                                                                        data-name="<?php echo $menu['post_title'] ?>" 
                                                                                        data-url="<?php echo $menu['post_name'] ?>"                                                                                       
                                                                                        data-target="post"
                                                                                        data-targetType="<?php echo $menu['post_type'] ?>"  
                                                                                        id="<?php echo $menu['post_id'] ?>"
                                                                                    > 
                                                                                    <?php echo $menu['post_title'] ?>
                                                                                </li>   
                                                                            <?php } ?>
                                                                        </ul>
        
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-lg-6 pull-right">
                                                                            <button type="submit" class="btn btn-mat btn-primary btn-sm">Add to Menu</button>
                                                                        </div>   
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    <?php
                                                }     
                                                ?>                                                   

                                                </div>
                                                <div id="accordion" role="tablist" aria-multiselectable="true">
                                                <?php 
                                                foreach($category_data as $categoryKey => $categoryValue) {
                                                    ?>
                                                    <div class="accordion-panel">
                                                            <div class="accordion-heading" role="tab" id="<?php echo $categoryKey ?>Accordian">
                                                                <h3 class="card-title accordion-title">
                                                                    <a class="accordion-msg" data-toggle="collapse"
                                                                        data-parent="#accordion" href="#<?php echo $categoryKey ?>collapse"
                                                                        aria-expanded="true" aria-controls="<?php echo $categoryKey ?>collapse">
                                                                        <h6><b><?php echo $categoryValue['title'] ?></b></h6>
                                                                    </a>
                                                                </h3>
                                                            </div>
                                                            <div id="<?php echo $categoryKey ?>collapse" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="<?php echo $categoryKey ?>Accordian">
                                                                <form class="formAddMenu">
                                                                    <div class="accordion-content accordion-desc">
                                                                        <ul>
                                                                            <?php foreach ( $categoryValue['menus'] as $termMenu){ ?>
                                                                                <li>
                                                                                    <input class="menuCheckBox" type="checkbox"
                                                                                        value="<?php echo $termMenu['term_id'] ?>" 
                                                                                        data-name="<?php echo $termMenu['name'] ?>" 
                                                                                        data-target="term"
                                                                                        data-targetType="<?php echo $termMenu['term_group'] ?>" 
                                                                                        id="<?php echo $termMenu['term_id'] ?>"
                                                                                    > 
                                                                                    <?php echo $termMenu['name'] ?>
                                                                                </li>   
                                                                            <?php } ?>
                                                                        </ul>
        
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-lg-6 pull-right">
                                                                            <button type="submit" class="btn btn-mat btn-primary btn-sm">Add to Menu</button>
                                                                        </div>   
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    <?php
                                                }     
                                                ?>                                                   

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <form class="submitForm">
                                        <!-- Nav tabs -->
                                        <?php
                                        $menuContent = '';
                                        $menuTab = ''; 
                                        $tabContentActive = $tabActive = 'active';
                                        
                                        foreach(registerNavBarMenu() as $menuKey => $menuValue)
                                        {
                                            $menuTab .= '<li class="nav-item">
                                                    <a class="nav-link '.$tabActive.'" data-toggle="tab" href="#'.$menuKey.'" role="tab" aria-expanded="true">'.$menuValue.'</a>
                                                </li>';
                                            $menuID = $menuKey.'List';
                                            $nestedID = $menuKey.'Nested';
                                            ob_start();
                                                ?>
                                                    <div class="tab-pane getActive  <?php echo $tabContentActive ?>" id="<?php echo $menuKey ?>" role="tabpanel" aria-expanded="true">
                                                        <div class="row">
                                                            <div class="dd" id="<?php echo $nestedID ?>">
                                                                <ol class="dd-list" id="<?php echo $menuID; ?>">
                                                                    <?php echo (isset($menusHtml[$menuKey])?$menusHtml[$menuKey]:'') ?>
                                                                </ol>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        jQuery(document).ready(function($){
                                                            var updateOutput = function (e) {
                                                                var list = e.length ? e : $(e.target), output = list.data('output');      
                                                            };
                                                            $('#<?php echo $nestedID ?>').nestable({
                                                                group: 1,
                                                                maxDepth: 3,
                                                            });  
                                                        });
                                                    </script>
                                                <?php
                                            $menuContent .= ob_get_clean();
                                            $tabContentActive = $tabActive = '';
                                        }    
                                        ?>                                            
                                        <ul class="nav nav-tabs  tabs" role="tablist">                                            
                                            <?php echo $menuTab; ?>
                                        </ul>
                                        <!-- Tab panes -->
                                        <div class="tab-content tabs card-block">                                            
                                                <?php echo $menuContent; ?>
                                        </div>

                                        <div class="card">
                                            <div class="card-footer text-muted">
                                                <div class="row pull-right">
                                                    <button type="submit" class="btn btn-primary">Save Menu</button> 
                                                </div>    
                                            </div>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row end -->
            </div>
        </div>
        <!-- Bootstrap tab card end -->
    </div>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    jQuery(document).ready(function(){
        $('.close-assoc-file').on('mousedown', function(event) {
            event.preventDefault();
            var parentli = $(this).closest('li.dd-item');
            var dataAttr = parentli.data();
            var id =$('.getActive.active').attr('id');
            $.ajax({
                url: '<?php echo route('delete.menu') ?>',
                type: 'GET',
                data: {
                    targettype: dataAttr.targettype,
                    target: dataAttr.target,
                    post_id: dataAttr.post_id,
                    link_id: dataAttr.link_id,
                    relation: id,
                },
            });        
            if (parentli.find('ol.dd-list').length > 0) {
                var innerHtml = parentli.find('ol.dd-list').html();
                parentli.after(innerHtml);
            }
            parentli.remove();
            return false;
        });
        $('.formAddMenu').on('submit',function(event){
            event.preventDefault()
            var id =$('.getActive.active').attr('id');
            let selectedArray = [];
            var form = $(this).closest('.formAddMenu').find('.menuCheckBox:checked');
            $(form).each(function () {
                var target = $(this).attr('data-target')
                var targetType = $(this).attr('data-targetType');
                let Items = '<li class="dd-item" data-link_id="0" data-target="'+target+'" data-targetType="'+targetType+'" data-post_id="'+$(this).val()+'" data-id="'+$(this).val()+'">'+
                                '<div class="dd-handle">'+$(this).data('name')+'</div>'+
                            '</li>'
                $('#'+id+'List').append(Items)
            });
        });  
   
        $('.submitForm').on('submit',function(event){
            event.preventDefault();
            var id =$('.getActive.active').attr('id');
            var menuOrder = $('.getActive.active .dd').nestable('serialize');
            
            $.ajax({
                url:'<?php echo route('add.menu') ?>',
                method:'POST',
                data:{
                    'relation' : id,
                    'menuOrder' : menuOrder 
                },
                success:function(response){
                    window.location.reload();
                }
            });
        })
    });

</script>