                              
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- Required Jquery -->
         <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/jquery-slimscroll/jquery.slimscroll.js"></script>
         <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/modernizr/modernizr.js"></script>
         <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/sweetalert2.min.js"></script>
         <script src="<?php echo adminPublicPath() ?>/pages/widget/amchart/amcharts.min.js"></script>
         <script src="<?php echo adminPublicPath() ?>/pages/widget/amchart/serial.min.js"></script>
         <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/chart.js/Chart.js"></script>
         <script type="text/javascript " src="<?php echo adminPublicPath() ?>/pages/todo/todo.js "></script>
         <script type="text/javascript" src="<?php echo adminPublicPath() ?>/pages/dashboard/custom-dashboard.js"></script>
         <script type="text/javascript" src="<?php echo adminPublicPath() ?>/pages/accordion/accordion.js"></script>
         <script type="text/javascript" src="<?php echo adminPublicPath() ?>/js/script.js"></script>
         <script type="text/javascript " src="<?php echo adminPublicPath() ?>/js/SmoothScroll.js"></script>
         <script src="<?php echo adminPublicPath() ?>/js/pcoded.min.js"></script>
         <script src="<?php echo adminPublicPath() ?>/js/vartical-demo.js"></script>
         <script src="<?php echo adminPublicPath() ?>/js/jquery.mCustomScrollbar.concat.min.js"></script>
         <script src="<?php echo adminPublicPath() ?>/js/jquery.nestable.js"></script>
         <script src="<?php echo adminPublicPath() ?>/js/jquery.timepicker.min.js"></script>
         <script src="//cdn.ckeditor.com/4.10.1/full-all/ckeditor.js"></script>
         <?php echo $__env->yieldContent('script'); ?>
         <script type="text/javascript"> 
            $(function () { 
               if($('.ckeditor').length > 0)
               {
                  var editor = CKEDITOR.replace('.ckeditor'); 
                  editor.config.allowedContent = 'p{text-align}(*); strong(*); em(*); b(*); i(*); u(*); sup(*); sub(*); ul(*); ol(*); li(*); a[!href](*); br(*); hr(*); img{*}[*](*); iframe(*); svg(*); path(*); h1(*); h2(*); h3(*); h4(*); h5(*); h6(*);'; 
                  editor.config.disallowedContent = '*[on*]'; 
                  editor.config.format_tags = 'p;h4;h5;h6;pre;address;div'; 
               }                                                            
            });
            </script>
         <script>
         $.ajaxSetup({
    			headers: {
        					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    					 }
			});
         jQuery(document).ready(function($) {
            $(document).on('click', '.ajax-pagination a', function(event) {
               event.preventDefault();
               var url = $(this).attr('href');
               var parameters = url.split('=');
               getMediaLibrary(parameters[1]);
            });
            $(document).on('click', '.nav-link-uploader', function(event) {
               event.preventDefault();
               $('.nav-link-uploader').removeClass('active');
               $('.tab-pane-uploader').removeClass('show active');
               var url = $(this).attr('href');
               $(url).addClass('show active');
               $(this).removeClass('active');
            });
            $(document).on('click', '.setFeaturedImage', function(event) {
               let attribute = '' 
               if($(this).data('eid')){
                  attribute = $(this).data('eid');
               }
               $.ajax({
                  url: '<?php echo route('media.gallery') ?>',
               })
               .done(function(response) {
                  $('.mediaUploaderBody').html(response);
                  $('#selectThumb').attr('data-eid',attribute)
                  $('#mediaModal').modal('show');
               });               
            });
            $(document).on('click', '.mediaUploader .animation', function(event) {
               event.preventDefault();
               $('.mediaUploader .animation').removeClass('active');
               $(this).addClass('active');
            });

            $(document).on('click', '#selectThumb', function(event) {
               event.preventDefault();
               let id=$(this).data('eid')
               $('#'+id).closest('.imageUploadGroup').find('.setFeaturedImage').fadeOut();
               $('#'+id).closest('.imageUploadGroup').find('.removeFeaturedImage').fadeIn();
               var mediaID = $('.mediaUploader .animation.active').attr('data-media_id');
               var mediaURL = $('.mediaUploader .animation.active').attr('data-media_url');
               var media_show_url = $('.mediaUploader .animation.active').attr('data-media_show_url');
               if ($('#'+id).closest('.imageUploadGroup').find('#guid').length > 0) {
                  $('#guid').val(mediaID);
               }               
               if(id){
                  if ($('#'+id).closest('.imageUploadGroup').find('#guid').length == 0) {
                     $('#'+id).val(media_show_url);
                  }
                  $('#'+id+'-img').fadeIn().attr('src',mediaURL);
               }
               $('#mediaModal').modal('hide');
            });
            $(document).on('click', '.removeFeaturedImage', function(event) {
               event.preventDefault();
               let id=$(this).data('eid')
               $('#'+id).closest('.imageUploadGroup').find('.setFeaturedImage').fadeIn();
               $(this).fadeOut();
               if ($('#'+id).closest('.imageUploadGroup').find('#guid').length > 0) {
                  $('#guid').removeAttr('value').attr('val','');
               } 
               
               if(id){
                  $('#'+id).removeAttr('value').attr('val','');
                  $('#'+id+'-img').fadeOut();
               }
               $('#mediaURL').fadeOut();
            });
            $(document).on('click', '.removeThumbanil', function(event) {
               event.preventDefault();
               var mediaID = $(this).attr('data-media_id');
               $.ajax({
                  url: '<?php echo route('media.delete') ?>',
                  method: 'GET',
                  data: {
                     post_id: mediaID
                  }
               })
               .done(function(response) {
                  $('.mediaUploader').html(response);
               });
            });
            $(document).on('click', '.editThumbanil', function(event) {
               event.preventDefault();
               var mediaID = $(this).attr('data-media_id');
               $('#post_title_'+mediaID+'_popup').toggle();
            });
            $(document).on('click', '.saveMediaTitle', function(event) {
               event.preventDefault();
               var mediaID = $(this).attr('data-media_id');
               var post_title = $('#post_title_'+mediaID).val();
               $.ajax({
                  url: '<?php echo route('media.updateAlt') ?>',
                  type: 'GET',
                  data: {post_id: mediaID, post_title: post_title},
               })
               .done(function() {
                  $('#post_title_'+mediaID+'_popup').toggle();
               });               
            });
            
            $(".InputNumber").keydown(function (e) {
                 if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                     (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
                     (e.keyCode >= 35 && e.keyCode <= 40)) {
                         return;
                 }
                 if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                     e.preventDefault();
                 }
            });
            var currentUrl = window.location.href;
            $.each($('.sidebaarmenuCustom a'), function(index, val) {
               if ($(this).attr('href') == currentUrl) {
                  jQuery(this).parents('li').addClass('active');
                  jQuery(this).closest('.pcoded-hasmenu').addClass('pcoded-trigger complete')
               }
            });
            $(document).on('change', '#store_country', function(event) {
               event.preventDefault();
               var country_name = $(this).val();
               $.ajax({
                  url: '<?php echo url('getstates') ?>',
                  type: 'GET',
                  data: {country_name: country_name},
               })
               .done(function(response) {
                  var optionHtml = '<option value="">Select</option>';
                  if (response.length > 0) {
                     for (var i = 0; i < response.length; i++) {                        
                        optionHtml += '<option value="'+response[i].name+'">'+response[i].name+'</option>';
                     }
                  }
                  $('#store_suburb').html(optionHtml);
               });               
            });
            $(document).on('change', '#store_suburb', function(event) {
               event.preventDefault();
               var state_name = $(this).val();
               $.ajax({
                  url: '<?php echo url('getcitis') ?>',
                  type: 'GET',
                  data: {state_name: state_name},
               })
               .done(function(response) {
                  var optionHtml = '<option value="">Select</option>';
                  if (response.length > 0) {
                     for (var i = 0; i < response.length; i++) {                        
                        optionHtml += '<option value="'+response[i].name+'">'+response[i].name+'</option>';
                     }
                  }
                  $('#store_city').html(optionHtml);
               });               
            }); 
            $(".datePicker").datepicker({
               dateFormat: 'yy-mm-dd'
            });
            $(".dateRangePicker").daterangepicker({
               presetRanges: [{
                  text: 'Today',
                  dateStart: function() { return moment() },
                  dateEnd: function() { return moment() }
               }, {
                  text: 'Tomorrow',
                  dateStart: function() { return moment().add('days', 1) },
                  dateEnd: function() { return moment().add('days', 1) }
               }, {
                  text: 'Next 7 Days',
                  dateStart: function() { return moment() },
                  dateEnd: function() { return moment().add('days', 6) }
               }, {
                  text: 'Next Week',
                  dateStart: function() { return moment().add('weeks', 1).startOf('week') },
                  dateEnd: function() { return moment().add('weeks', 1).endOf('week') }
               }],
               applyOnMenuSelect: true,
               datepickerOptions: {
                  minDate: 0,
                  maxDate: null
               },
               change: function(fdate)
               {
                  var date = $(this).val();
                  date = $.parseJSON(date);
                  $(this).val(date.start+' - '+date.end);
               }
             }).css('display','block'); 
             $('.timepicker').timepicker({});  
             $('.select2').select2();                    
         });
         </script>
   </body>
</html>
<div class="modal fade" id="mediaModal" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body mediaUploaderBody">

         </div>
      </div>
   </div>
</div>
<?php /**PATH /home/k9c9adh99pg7/public_html/pza/resources/views/Include/Footer.blade.php ENDPATH**/ ?>