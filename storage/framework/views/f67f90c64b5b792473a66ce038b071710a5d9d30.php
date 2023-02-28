<?php echo headerCommon($post); ?>

<?php echo view('Templates.HeaderSliderIntro') ?>

<div class="colorlib-blog">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3 text-center animate-box intro-heading">
				<h2>Read our blog</h2>
				<p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life</p>
			</div>
		</div>
		<div class="row">
			<?php 
			$response = getPosts('post'); 
			foreach ($response['posts'] as $post) {
				?>
				<div class="col-md-4">
					<article class="article-entry">
						<a href="<?php echo url('/'.$post->post_name) ?>" class="blog-img" style="background-image: url(<?php echo publicPath() ?>/<?php echo $post->post_image ?>);"></a>
						<div class="desc">
							<p class="meta"><?php echo $post->posted_date ?></p>
							<p class="admin"><span>Posted by:</span> <span><?php echo $post->user_name ?></span></p>
							<h2><a href="<?php echo url('/'.$post->post_name) ?>"><?php echo $post->post_title ?></a></h2>
							<p><?php echo $post->post_excerpt ?></p>
						</div>
					</article>
				</div>
				<?php
			}
			?>
		</div>
		<div class="row">
			<div class="col-md-12"><?php echo $response['posts']->appends(request()->except('page'))->links() ?></div>
		</div>
	</div>
</div><?php /**PATH /home/k9c9adh99pg7/public_html/pza/resources/views/Templates/Blog.blade.php ENDPATH**/ ?>