
<style>
.theme_sidebar_content {
    padding: 20px 40px;
}
</style>
<div class="card">  
    <div class="card-body">  
        <div class="col-md-12">  
            <div class="page-body">
                <form class="ThemeOptionForm" novalidate>
                    <div class="theme-content">
                        <nav class="theme_nav">
                            <div class="right">
                                <ul class="ml-auto">
                                    <li><button type="submit" class="btn btn-success active" href="#home">Save</button></li>
                                    <li><button type="reset" class="btn btn-primary" href="#news">Reset</button></li>
                                </ul>
                            </div>
                        </nav>
                        <?php echo $option; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="styleSelector">
</div>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">


<script>
 jQuery(document).ready(function(){
     $('.ThemeOptionForm').on('submit',function(event){
         event.preventDefault();
        console.log($(this).serialize())
        $.ajax({
            url:'<?php echo route('themes.store') ?>',
            method:'POST',
            data:$(this).serialize(),
            success:function(response){
                if(response.status==true){
                    Swal.fire('Success','Data Saved Successfully','success');                    
                }
            },
            error:function(response){

            }
        })
    })   
})
</script>
<?php /**PATH C:\xampp\htdocs\MaharajaPrestonApi\resources\views/Admin/Themes/Index.blade.php ENDPATH**/ ?>