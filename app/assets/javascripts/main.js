$(function() {
	$(document).ajaxError(function(event, xhr, settings, thrownError) {
		console.log(arguments);
	});
});

VD = {};

VD.home_init_page = function(device) {
	if (device == 'desktop') VD.match_media = window.matchMedia('(min-width: 768px) and (max-width: 991px)');
	else VD.match_media = window.matchMedia('(orientation: portrait)');

	VD.scroll_to();

	VD.slider();
	VD.slider_with_animation();

	VD.section_animation();
	$('.slider.highlight').slider_with_animation();

	var $normalSliders = $('.slider.branding, .slider.mobile, .slider.web, .slider.prints-packaging');
	var slidersLength = $normalSliders.length;
	$normalSliders.each(function(i) {
		$(this).slider({ delay: Math.floor(Math.random()*slidersLength*1000) });
	});

	$('a.icon.plus-sign').click(function(e){
		e.preventDefault();
		VD.image_gallery($(this).attr('href'));
	});

	$('[data-gallery]').click(function(e) {
		e.preventDefault();
		var $this = $(this);
		if ($this.is('img')) VD.image_gallery($this.data().gallery);
		else if ($this.parent('.triple-image-cover').length) {
			var index;
			$this.parent('.triple-image-cover').children('div').each(function(i) {
				if ($(this).is($this)) { index = i; return; }
			});
			var $img = $this.parents('.img-repo').find('img.active');
			if ($img.data().galleryFilter[index]) {
				var dir = $this.data().gallery+$img.data().galleryFilter[index]+'/';
				VD.image_gallery(dir);
			}
		};
	});
};

VD.section_animation = function() {
	var $slider, $img_repo, $images, $slider_indicators;

	var set_elements = function() {
		$slider = $('.slider.animation');
		$img_repo = $slider.find('.img-repo');
		$images = $img_repo.find('img');
		$slider_indicators = $slider.find('.slider-indicators li');
	};

	var stop_video = function() {
		$('#youtube_video').remove();
		$slider_indicators.off('click', stop_video);
		init($(this));
	};

	var insert_and_play_video = function(e) {
		var video_id = $(e.target).data().videoId;
		$slider.slider(false);
		set_elements();
		$('<div>', { class: 'youtube-video', id: 'youtube_video' }).appendTo($img_repo);
		VD.createPlayerElement(video_id, 
			function(event) { 
				event.target.playVideo();
				$slider_indicators.click(stop_video);
			}, 
			function(event) {
				if (event.data === 0 || event.data === 2) {
					$('#youtube_video').remove();
					$slider_indicators.off('click', stop_video);
					init();
				};
			}
		);
	};

	var init = function($element) {
		$images.click(insert_and_play_video);
		$slider.slider($element);
	};

	$(document).on('youTubeReady', init);
	set_elements();
	VD.callYouTubeApi();
};

VD.scroll_to = function() {
	var $body = $('html, body');
	var $window = $(window);
	var $elements = $('[data-scroll-to]');
	$elements.each(function() {
		var $target = $('#'+$(this).data('scroll-to'));
		$target.addClass('highlightable');
	});
	$elements.click(function(e) {
		e.preventDefault();
		if ($(this).is(':focus')) { $(this).blur(); }
		var max_scroll = $body.height() - $window.height();
		var $target = $('#'+$(this).data('scroll-to'));
		var offset = $target.offset().top - 50;
		offset < max_scroll || (offset = max_scroll);
		$target.addClass('white-glow');
		$body.finish();
		$body.animate({ scrollTop: [ offset, 'easeOutQuad' ] }, function() {
			$target.removeClass('white-glow');
		});
	});
};

VD.slider = function() {
	jQuery.fn.extend({
		slider: function(options) {
			var remove_functionality = options === false;
			var $go_to_index = options instanceof jQuery ? options : false;
			var speed = typeof options == 'number' ? options : (function() {
				if (typeof options == 'object' && typeof options.speed == 'number') return options.speed;
				else return 8000;
			}());
			var delay = typeof options === 'object' && typeof options.delay === 'number' ? options.delay : false;
			return this.each(function() {
				var $slider = $(this);
				var $images = $slider.find('.img-repo img');
				var $texts = $slider.find('.text-repo>div');
				var $indicators = $slider.find('.slider-indicators li');
				var $next_slide_btn = $slider.find('.slider-go-next');
				var $all = $images.add($texts).add($indicators);

				var set = function() {
					var timer = {};

					var goTo = function(index) {
						var $active_img = $images.filter('.active');
						var $active_text = $texts.filter('.active');
						var $active_indicator = $indicators.filter('.active');
						index = typeof index == 'number' ? index : (function() {
							if ($active_img.is($images.last())) return 0;
							else return $images.index($active_img.next());
						}());
						var $next_img = $images.eq(index);
						var $next_text = $texts.eq(index);
						var $next_indicator = $indicators.eq(index);
						var $actives = $active_img.add($active_text);
						var $nexts = $next_img.add($next_text);
						$slider.trigger('slide');
						$actives.fadeOut(400);
						$nexts.fadeIn(400, function() {
							if (!$(this).is($images)) return;
							$all.removeClass('active');
							$next_indicator.addClass('active');
							$nexts.addClass('active');
							$slider.trigger('slid');
						});
					};

					timer.set = function() {
						this.timer = setInterval(goTo, speed);
					};

					timer.reset = function() {
						$all.stop(true,true);
						clearInterval(this.timer);
						this.set();
					};

					var next = function(e) {
						e.preventDefault();
						var index, $this = $(this);
						if ($this.hasClass('slider-go-next')) {
							if ($images.filter('.active').is($images.last())) index = 0;
							else index = $images.filter('.active').next().index();
						} else index = $this.data().goTo;
						if ($images.eq(index).is($images.filter('.active'))) return false;
						timer.reset();
						goTo(index);
						return false;
					};

					$indicators.add($next_slide_btn).click(next);
					
					if (delay) setTimeout(function() { timer.set(); }, delay);
					else timer.set();
				};

				var unset = function() {
					$all.stop(true,true);
					clone_remove_and_append();
				};

				var clone_remove_and_append = function() {
					var $clone = $slider.clone();
					var $parent = $slider.parent();
					var $slider_siblings = $parent.children();
					var index, method, $element;
					$slider_siblings.each(function(i) {
						if ($(this).is($slider)) {
							index = i;
							return;
						};
					});
					if ($slider.is($slider_siblings.last())) {
						method = 'appendTo';
						$element = $parent;
					} else if ($slider.is($slider_siblings.first())) {
						method = 'prependTo';
						$element = $parent;
					} else {
						method = 'before';
						$element = $slider_siblings.eq(index+1);
					};
					$slider.remove();
					$clone[method]($element);
				};

				if (remove_functionality) unset();
				else set();
				if ($go_to_index) $go_to_index.click();
			});
		}
	});
};

VD.slider_with_animation = function() {
	jQuery.fn.extend({
		slider_with_animation: function(options) {
			var remove_functionality = options === false;
			var speed = typeof options == 'number' ? options : (function() {
				if (typeof options == 'object' && typeof options.speed == 'number') return options.speed;
				else return 12000;
			}());
			return this.each(function() {
				var $slider = $(this);
				var $images = $slider.find('.img-repo img');
				var $texts = $slider.find('.text-repo>div');
				var $indicators = $slider.find('.slider-indicators li');
				var $all = $images.add($texts).add($indicators);

				var set = function() {
					var $img_repo = $slider.find('.img-repo');

					var goTo = function(index) {
						var $active_img = $images.filter('.active');
						var $active_text = $texts.filter('.active');
						var $active_indicator = $indicators.filter('.active');
						index = typeof index == 'number' ? index : (function() {
							if ($active_img.is($images.last())) return 0;
							else return $images.index($active_img.next());
						}());
						var $next_img = $images.eq(index);
						var $next_text = $texts.eq(index);
						var $next_indicator = $indicators.eq(index);
						var $actives = $active_img.add($active_text);
						var $nexts = $next_img.add($next_text);
						$slider.trigger('slide');
						$actives.fadeOut(400);
						$nexts.fadeIn(400, function() {
							if (!$(this).is($images)) return;
							$all.removeClass('active');
							$all.attr('style','');
							$next_indicator.addClass('active');
							$nexts.addClass('active');
							$slider.trigger('slid');
							animate();
						});
					};

					var next = function(e) {
						var index = $(this).data().goTo;
						if ($images.eq(index).is($images.filter('.active'))) return false;
						var this_index = $indicators.index($indicators.filter('.active'));
						$all.stop(true,false);
						$all.removeClass('active');
						$images.eq(this_index).add($texts.eq(this_index)).add($indicators.eq(this_index)).addClass('active');
						goTo(index);
						return false;
					};

					var animate = function() {
						var $active_img = $images.filter('.active');
						var repo_width = $img_repo.width();
						var repo_height = $img_repo.height();
						var img_width = $active_img.width();
						var img_height = $active_img.height();
						var left = [0 - (img_width - repo_width), VD.random_easing()];
						var top = [0 - (img_height - repo_height), VD.random_easing()];
						$active_img.animate({ top: top, left: left }, speed, next);
					};

					VD.match_media.addListener(next);
					$indicators.click(next);
					$(window).load(animate);
				};

				var unset = function() {
					var $all_firsts = $images.first().add($texts.first()).add($indicators.first());

					$all.stop(true,false);
					$all.removeClass('active');
					$all.attr('style','');
					$all_firsts.addClass('active');

					var $clone = $slider.clone();
					var $parent = $slider.parent();
					$slider.remove();
					$clone.appendTo($parent);
				};

				if (remove_functionality) unset();
				else set();
			});
		}
	});
};

VD.callYouTubeApi = function() {
	var tag = document.createElement('script');
	tag.src = "https://www.youtube.com/iframe_api";
	var firstScriptTag = document.getElementsByTagName('script')[0];
	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
};

VD.createPlayerElement = function(video_id, onReady, onStateChange) {
	VD.player = new YT.Player('youtube_video', {
		height: '274',
		width: '416',
		videoId: video_id,
		playerVars: {
			autoplay: 1,
			theme: 'light',
			showinfo: 0,
			origin: 'http://localhost'
		},
		events: {
			'onReady': onReady,
			'onStateChange': onStateChange
		}
	});
};

VD.random_easing = function() {
	var easing_functions = ['linear','swing','easeOutQuad','easeInOutQuad',
		'easeOutCubic','easeInOutCubic','easeOutQuart','easeInOutQuart',
		'easeOutQuint','easeInOutQuint','easeOutExpo','easeInOutExpo',
		'easeOutSine','easeInOutSine'];
	var random_pick = Math.floor(Math.random()*easing_functions.length);
	return easing_functions[random_pick];
};

//GLOBAL FUNCTION FOR YOUTUBE
var onYouTubeIframeAPIReady = function() {
	$(document).trigger('youTubeReady');
};

VD.image_gallery = function(url) {
	VD.image_gallery.initiated || (function() {
		VD.image_gallery.initiated = true;
		VD.image_gallery.dataConstructor = function() {
			this.$gallery = $('.image-gallery');
			this.$images = this.$gallery.find('div.thumbnails span');
			this.$first_image = this.$images.first();
			this.$container = this.$gallery.find('div.full-image');
			this.$full_image = this.$container.find('img');
			this.$next = this.$gallery.find('.gallery-arrow-right');
			this.$prev = this.$gallery.find('.gallery-arrow-left');

			this.$thumbnails_window = this.$gallery.find('div.thumbnails');
			this.$thumbnails_body = this.$thumbnails_window.find('.thumbnails-body');
			this.window_width = this.$thumbnails_window.width();
			this.container_width = this.$container.width();
			this.window_left = this.$thumbnails_window.offset().left;
			this.thumb_width = this.$images.length > 1 ? this.$images.eq(1).offset().left - this.$images.eq(0).offset().left : this.$images.first().width();
			this.scroll_width = this.$thumbnails_window.get(0).scrollWidth - this.window_width;
			VD.image_gallery.data = this;
			return this;
		};

		VD.image_gallery.load_image = function($span) {
			var data = VD.image_gallery.data;
			var $image = $span.find('img');
			var height = $image.data('height') * data.container_width / $image.data('width');
			data.$images.removeClass('active');
			$span.addClass('active');
			VD.image_gallery.active_thumb_center();
			data.$full_image.attr({ src: $image.data('full'), alt: $image.attr('alt') });
			data.$container.animate( { height: [ height, 'easeOutQuad' ] } );
		};

		VD.image_gallery.thumb_click = function(e) {
			e.preventDefault();
			VD.image_gallery.load_image($(this));
		};

		VD.image_gallery.active_thumb_center = function() {
			var data = VD.image_gallery.data;
			if (!data.scroll_width) return;
			var $active = data.$images.filter('.active');
			var $body = data.$thumbnails_body;
			var $thumbs = data.$images;
			var body_left = Number($body.css('left').replace('px', ''));
			var active_left = $active.offset().left - body_left;
			var left = data.window_left - active_left + data.window_width/2 - data.thumb_width/2;
			$body.stop(true, false);
			$body.animate({left: left});
		};

		VD.image_gallery.go = function(prev_or_next, e) {
			console.log(prev_or_next, e);
			var m = ['prev', 'next'], c = ['first', 'last'];
			var first_or_last = c[m.indexOf(prev_or_next)];
			var opposite = c[Number(!m.indexOf(prev_or_next))];

			var $images = VD.image_gallery.data.$images;
			var $active_image = $images.filter('.active');
			var $image = $active_image.is($images[first_or_last]()) ? $images[opposite]() : $active_image[prev_or_next]();
			VD.image_gallery.load_image($image);
		};

		VD.image_gallery.next = function(e) { VD.image_gallery.go('next', e) };
		VD.image_gallery.prev = function(e) { VD.image_gallery.go('prev', e) };

		VD.image_gallery.keydown = function(e) {
			switch(e.which) {
				case 39: VD.image_gallery.next(); break;
				case 37: VD.image_gallery.prev(); break;
				case 27: $('.image-gallery, .close-gallery a').trigger('click'); break;
			}
		};

		$(document).on('click', '.image-gallery', function(e) {
			if ($(e.target).is($('.image-gallery, .close-gallery a'))) {
				e.preventDefault();
				var $this = $(this);
				$(document).off('keydown', VD.image_gallery.keydown);
				$this.fadeOut(function() { $this.remove() });
				$('body').removeClass('gallery-open');
			}
		});
	}());

	$.get(url+'?12233221', function(gallery) {
		$('body').addClass('gallery-open').append(gallery);

		var data = new VD.image_gallery.dataConstructor();
		
		$(document).keydown(VD.image_gallery.keydown);

		data.$images.click(VD.image_gallery.thumb_click);

		data.$next.click(VD.image_gallery.next);
		data.$prev.click(VD.image_gallery.prev);

		VD.image_gallery.load_image(data.$first_image);
	});
}