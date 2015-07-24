<section class="highlight slider container">
	<div class="row-margin">
		<div class="section-top">
			<div class="img-repo">
				<?php foreach($highlight_images as $i => $img_path): ?>
					<img class="<?php if($i == 0) echo 'active'; ?>" src="<?php echo $img_path; ?>">
				<?php endforeach; ?>
			</div>
			<img class="top-layer" src="<?php $asset->path('highlight-top-layer.png'); ?>">
		</div>
		<div class="section-bottom">
			<div class="text-repo">
				<?php foreach($home->highlight_slides_text as $i => $text): ?>
					<div class="<?php if($i == 0) echo 'active'; ?>">
						<div class="pull-left" style="<?php if (isset($text['widths'])) echo 'width:'.$text['widths'][0]; ?>">
							<h4 class="text-extra-light"><?php echo $text['light']; ?></h4>
							<h2 class="text-bold uppercase"><?php echo $text['bold']; ?></h2>
						</div>
						<p class="pull-right text-helvetica" style="<?php if (isset($text['widths'])) echo 'width:'.$text['widths'][1]; ?>">
							<?php echo $text['text']; ?>
						</p>
						<div class="clearfix"></div>
					</div>
				<?php endforeach; ?>
			</div>
			<ol class="slider-indicators">
				<?php for ($i = 0; $i<count($highlight_images); $i++): ?>
					<li data-go-to="<?php echo $i; ?>" class="<?php if($i == 0) echo 'active'; ?>"></li>
				<?php endfor; ?>
			</ol>
		</div>
	</div>
</section>
<div class="container animation-branding">
	<div class="row">
		<section class="animation slider col-sm-6 reset-bs-padding" id="Animation">
			<h3 class="section-title">Animation</h3>
			<div class="section-top">
				<div class="img-repo">
					<?php foreach ($animation_images as $i => $img_path): ?>
						<img class="<?php if($i == 0) echo 'active'; ?>" src="<?php echo $img_path; ?>" data-video-id="<?php echo $home->animation_video_ids[$i]; ?>">
					<?php endforeach; ?>
				</div>
			</div>
			<ol class="slider-indicators">
				<?php for ($i = 0; $i<count($animation_images); $i++): ?>
					<li data-go-to="<?php echo $i; ?>" class="<?php if($i == 0) echo 'active'; ?>"></li>
				<?php endfor; ?>
			</ol>
		</section>
		<section class="branding slider col-sm-6 reset-bs-padding" id="Branding">
			<h3 class="section-title">Branding</h3>
			<div class="section-top">
				<div class="img-repo triple-image">
					<?php foreach ($branding_images as $i => $img): ?>
						<img width="464" height="163" class="<?php if($i == 0) echo 'active'; ?>" src="<?php echo $img['image']; ?>" data-gallery-filter='<?php echo json_encode($img['data']); ?>'>
					<?php endforeach; ?>
					<div class="triple-image-cover">
						<div data-gallery="<?php echo $path->image_gallery(array('dir' => 'branding')); ?>"></div>
						<div data-gallery="<?php echo $path->image_gallery(array('dir' => 'branding')); ?>"></div>
						<div data-gallery="<?php echo $path->image_gallery(array('dir' => 'branding')); ?>"></div>
					</div>
				</div>
			</div>
			<div class="section-bottom">
				<h4 class="text-extra-light"><big>who</big> you are is what</h4>
				<h2 class="text-bold uppercase">really matters.</h2>
				<p class="text-helvetica light">Every project must have it´s own identity, his own view of the world. To achieve that goal Vertigo compromizes to create a visual system that matches who you are as a brand.</p>
				<a href="<?php print $path->image_gallery(array('dir' => 'branding', 'filter' => '*')); ?>" class="icon plus-sign">+</a>
			</div>
			<ol class="slider-indicators">
				<?php for ($i = 0; $i<count($branding_images); $i++): ?>
					<li data-go-to="<?php echo $i; ?>" class="<?php if($i == 0) echo 'active'; ?>"></li>
				<?php endfor; ?>
			</ol>
		</section>
	</div>
</div>
<div class="container mobile-social" id="Mobile">
	<div class="row">
		<div class="col-sm-4 col-md-3 reset-bs-padding">
			<h3 class="section-title">Mobile Apps</h3>
			<div class="section-bottom mobile-section-bottom">
				<h4 class="text-extra-light"><big>we</big> can take your ideas</h4>
				<h2 class="text-bold uppercase">everywhere</h2>
				<p class="text-helvetica light">We can help you create a better user experience, allowing your business to reach a bigger audience in a fun, more engaging way than ever before.</p>
				<a href="<?php print $path->image_gallery(array('dir' => 'mobile', 'filter' => '*')); ?>" class="icon plus-sign">+</a>
			</div>
		</div>
		<section class="slider mobile col-sm-8 col-md-6 reset-bs-padding">
			<div class="img-repo-border">
				<a href="#" class="icon-circle-arrow slider-go-next"></a>
				<div class="img-repo">
					<?php foreach ($mobile_images as $i => $img): ?>
						<img class="<?php if($i == 0) echo 'active'; ?>" src="<?php echo $img['image']; ?>" data-gallery="<?php echo $path->image_gallery(array('dir' => 'mobile', 'filter' => $img['data'])); ?>">
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<section class="hidden-sm hidden-xs col-md-3 reset-bs-padding social">
			<h3 class="section-title">Social</h3>
			<div class="section-bottom">
				<div class="social-links">
					<a href="#"><i class="icon social facebook"></i></a>
					<a href="#"><i class="icon social twitter"></i></a>
					<a href="#"><i class="icon social instagram"></i></a>
				</div>
			</div>
		</section>
	</div>
</div>
<div class="container" id="Web">
	<div class="row web">
		<h3 class="section-title">Web design</h3>
		<section class="col-xs-6 col-sm-4 col-md-3 slider web">
			<div class="img-repo-border">
				<div class="img-repo">
					<?php foreach ($web_0_images as $i => $img): ?>
						<img class="<?php if($i == 0) echo 'active'; ?>" src="<?php echo $img['image']; ?>" data-gallery="<?php echo $path->image_gallery(array('dir' => 'web', 'filter' => $img['data'])); ?>">
					<?php endforeach; ?>
				</div>
				<a href="<?php print $path->image_gallery(array('dir' => 'web', 'filter' => '*')); ?>" class="icon plus-sign">+</a>
			</div>
			<ol class="slider-indicators">
				<?php for ($i = 0; $i<count($web_0_images); $i++): ?>
					<li data-go-to="<?php echo $i; ?>" class="<?php if($i == 0) echo 'active'; ?>"></li>
				<?php endfor; ?>
			</ol>
		</section>
		<section class="col-xs-6 col-sm-4 col-md-3 slider web">
			<div class="img-repo-border">
				<div class="img-repo">
					<?php foreach ($web_1_images as $i => $img): ?>
						<img class="<?php if($i == 0) echo 'active'; ?>" src="<?php echo $img['image']; ?>" data-gallery="<?php echo $path->image_gallery(array('dir' => 'web', 'filter' => $img['data'])); ?>">
					<?php endforeach; ?>
				</div>
				<a href="<?php print $path->image_gallery(array('dir' => 'web', 'filter' => '*')); ?>" class="icon plus-sign">+</a>
			</div>
			<ol class="slider-indicators">
				<?php for ($i = 0; $i<count($web_1_images); $i++): ?>
					<li data-go-to="<?php echo $i; ?>" class="<?php if($i == 0) echo 'active'; ?>"></li>
				<?php endfor; ?>
			</ol>
		</section>
		<section class="col-xs-6 col-sm-4 col-md-3 slider web">
			<div class="img-repo-border">
				<div class="img-repo">
					<?php foreach ($web_2_images as $i => $img): ?>
						<img class="<?php if($i == 0) echo 'active'; ?>" src="<?php echo $img['image']; ?>" data-gallery="<?php echo $path->image_gallery(array('dir' => 'web', 'filter' => $img['data'])); ?>">
					<?php endforeach; ?>
				</div>
				<a href="<?php print $path->image_gallery(array('dir' => 'web', 'filter' => '*')); ?>" class="icon plus-sign">+</a>
			</div>
			<ol class="slider-indicators">
				<?php for ($i = 0; $i<count($web_2_images); $i++): ?>
					<li data-go-to="<?php echo $i; ?>" class="<?php if($i == 0) echo 'active'; ?>"></li>
				<?php endfor; ?>
			</ol>
		</section>
		<section class="col-xs-6 col-sm-4 col-md-3 slider web hidden-sm">
			<div class="img-repo-border">
				<div class="img-repo">
					<?php foreach ($web_3_images as $i => $img): ?>
						<img class="<?php if($i == 0) echo 'active'; ?>" src="<?php echo $img['image']; ?>" data-gallery="<?php echo $path->image_gallery(array('dir' => 'web', 'filter' => $img['data'])); ?>">
					<?php endforeach; ?>
				</div>
				<a href="<?php print $path->image_gallery(array('dir' => 'web', 'filter' => '*')); ?>" class="icon plus-sign">+</a>
			</div>
			<ol class="slider-indicators">
				<?php for ($i = 0; $i<count($web_3_images); $i++): ?>
					<li data-go-to="<?php echo $i; ?>" class="<?php if($i == 0) echo 'active'; ?>"></li>
				<?php endfor; ?>
			</ol>
		</section>
	</div>
</div>
<div class="container">
	<div class="row prints-packaging">
		<section class="col-sm-6 slider prints-packaging" id="Prints">
			<h3 class="section-title">Prints & Editorial</h3>
			<div class="img-repo-border">
				<div class="img-repo">
					<?php foreach ($print_images as $i => $img): ?>
						<img class="<?php if($i == 0) echo 'active'; ?>" src="<?php echo $img['image']; ?>" data-gallery="<?php echo $path->image_gallery(array('dir' => 'print', 'filter' => $img['data'])); ?>">
					<?php endforeach; ?>
				</div>
				<a href="<?php print $path->image_gallery(array('dir' => 'print', 'filter' => '*')); ?>" class="icon plus-sign">+</a>
			</div>
			<ol class="slider-indicators">
				<?php for ($i = 0; $i<count($print_images); $i++): ?>
					<li data-go-to="<?php echo $i; ?>" class="<?php if($i == 0) echo 'active'; ?>"></li>
				<?php endfor; ?>
			</ol>
		</section>
		<section class="col-sm-6 slider prints-packaging" id="Packaging">
			<h3 class="section-title">Packaging</h3>
			<div class="img-repo-border">
				<div class="img-repo triple-image">
					<?php foreach ($packaging_images as $i => $img): ?>
						<img class="<?php if($i == 0) echo 'active'; ?>" src="<?php echo $img['image']; ?>" data-gallery-filter='<?php echo json_encode($img['data']); ?>'>
					<?php endforeach; ?>
					<div class="triple-image-cover">
						<div data-gallery="<?php echo $path->image_gallery(array('dir' => 'packaging')); ?>"></div>
						<div data-gallery="<?php echo $path->image_gallery(array('dir' => 'packaging')); ?>"></div>
						<div data-gallery="<?php echo $path->image_gallery(array('dir' => 'packaging')); ?>"></div>
					</div>
				</div>
				<a href="<?php print $path->image_gallery(array('dir' => 'packaging', 'filter' => '*')); ?>" class="icon plus-sign">+</a>
			</div>
			<ol class="slider-indicators">
				<?php for ($i = 0; $i<count($packaging_images); $i++): ?>
					<li data-go-to="<?php echo $i; ?>" class="<?php if($i == 0) echo 'active'; ?>"></li>
				<?php endfor; ?>
			</ol>
		</section>
	</div>
</div>
<?php 
$extra_js = <<<EXTRA_JS
	VD.home_init_page('$media');
EXTRA_JS
?>