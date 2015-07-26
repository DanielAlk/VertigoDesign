<div class="container image-gallery">
	<div class="image-gallery-content">
		<a href="#" class="close-gallery">&times;</a>
		<div class="row">
			<div class="col-xs-12">
				<div class="full-image">
					<div class="gallery-arrow gallery-arrow-left"></div>
					<div class="gallery-arrow gallery-arrow-right"></div>
					<img class="full" alt="" src="">
				</div>
				<div class="thumbnails">
					<div class="thumbnails-body">
						<?php foreach($gallery as $filename => $image): ?>
							<span>
								<img class="thumb" alt="<?php echo $filename; ?>" src="<?php echo $image['thumb']; ?>" data-size='<?php echo $image['data'][0].'x'.$image['data'][1]; ?>' data-full="<?php echo $image['full']; ?>">
							</span>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>