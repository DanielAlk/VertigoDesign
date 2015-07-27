<div class="container image-gallery">
	<div class="image-gallery-content">
		<div class="close-gallery">
			<a href="#" class="icon-circle-arrow"></a>
			<h3 class="text-extra-light"><big>Go</big> back to the</h3>
			<h2 class="text-bold uppercase">main site</h2>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="thumbnails">
					<div class="thumbnails-body">
						<?php foreach($gallery as $filename => $image): ?>
							<span>
								<img class="thumb" alt="<?php echo $filename; ?>" src="<?php echo $image['thumb']; ?>" data-width="<?php echo $image['data'][0]; ?>" data-height="<?php echo $image['data'][1]; ?>" data-full="<?php echo $image['full']; ?>">
							</span>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="full-image">
					<img class="full" alt="" src="">
					<div class="gallery-arrow gallery-arrow-left"></div>
					<div class="gallery-arrow gallery-arrow-right"></div>
				</div>
			</div>
		</div>
	</div>
</div>