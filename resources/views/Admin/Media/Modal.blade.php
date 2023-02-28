<style>
   .gallery .pics {
      -webkit-transition: all 350ms ease;
      transition: all 350ms ease; 
   }
   .gallery .animation {
      -webkit-transform: scale(1);
      -ms-transform: scale(1);
      transform: scale(1); 
      border: 1px solid #ccc;
      height: 100%;
      max-height: 170px;
      padding: 15px;
      text-align: center;
      margin-top: 15px;
   }
   .animation img.img-fluid {
       height: 100%;
   }
   .animation img.img-fluid {
       height: 100%;
       width: 100%;
   }
   .mediaUploaderBody .mediaUploader {
       max-height: 480px;
       overflow-x: auto;
   }
   .mediaUploader .animation{cursor: pointer;}
   .mediaUploader .animation.active{
      border: 5px solid blue;
   }
   a.removeThumbanil, a.editThumbanil {
       position: absolute;
       top: 0;
       color: #fff !important;
       padding: 5px;
       font-weight: bolder !important;
       display: none;
   }
   a.removeThumbanil {
       right: 0;
       background: #ed1c24;    
   }
   a.editThumbanil {
       left: 0;
       background: #2f6e09;    
   }
   .mediaUploader .animation:hover a.removeThumbanil, .mediaUploader .animation:hover a.editThumbanil {
       display: block;
   }
   .editAltTag {
      display: none;
      position: absolute;
      background: #fff;
      box-shadow: 0px 0px 3px 0px #000;
      width: 105%;
      left: -5px;
      top: -5px;
      height: 106%;
      padding-top: 30px;
  }
  .page-body{
    width: 100%;
  }
</style>
<div class="page-body">
   <ul class="nav nav-tabs" id="myTab" role="tablist">
     <li class="nav-item">
       <a class="nav-link nav-link-uploader active" id="home-tab" href="#homeMedia">Media</a>
     </li>
     <li class="nav-item">
       <a class="nav-link nav-link-uploader" id="profile-tab" href="#uploadMedia">Upload</a>
     </li>
   </ul>
   <input type="text" name="searchKey" id="searchKey" value="" class="form-control" placeholder="Search Media">
   <div class="tab-content" id="myTabContent">
     <div class="tab-pane tab-pane-uploader fade show active" id="homeMedia" role="tabpanel" aria-labelledby="home-tab">
        <div class="gallery" id="gallery">
            <div class="row">
               <div class="col-md-12">
                  <div class="mediaUploader">
                     <?php echo $gallery; ?> 
                  </div> 
               </div>
            </div> 
            <?php 
            if ($showControl) {
               ?>
               <div class="row">
                  <div class="col-md-12">
                     <button type="button" class="btn btn-info" id="selectThumb" style="float: right;">Select</button>
                  </div>
               </div>
               <?php
            } ?>            
        </div>
     </div>
     <div class="tab-pane tab-pane-uploader fade" id="uploadMedia" role="tabpanel" aria-labelledby="profile-tab">
         <form action="{{route('media.store')}}" class='dropzone' >
         </form> 
     </div>
   </div>
   
</div>
<link rel="stylesheet" type="text/css" href="<?php echo adminPublicPath() ?>/dropzone/dist/min/dropzone.min.css">
<script src="<?php echo adminPublicPath() ?>/dropzone/dist/min/dropzone.min.js" type="text/javascript"></script>
<script>
   var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

   Dropzone.autoDiscover = false;         
   function getMediaLibrary(page = 0) {
      $.ajax({
         url: '<?php echo route('media.get') ?>',
         data: {
            page: page,
            searchKey: $('#searchKey').val()
         }
      })
      .done(function(response) {
         $('.mediaUploader').html(response);
      });            
   }
   var myDropzone = new Dropzone(".dropzone",{ 
      maxFilesize: 3,  // 3 mb
      acceptedFiles: ".jpeg,.jpg,.png,.pdf",
   });
   myDropzone.on("sending", function(file, xhr, formData) {
      formData.append("_token", CSRF_TOKEN);
   });
   myDropzone.on("queuecomplete", function(file, res) {
      if (myDropzone.files[0].status != Dropzone.SUCCESS ) {
          alert('yea baby');
      } else {
         getMediaLibrary();
      }
   });
   $(document).on('change', '#searchKey', function(event) {
     event.preventDefault();
     getMediaLibrary();
   });
</script>