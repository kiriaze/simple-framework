jQuery( document ).ready( function($) {

	// ACF 5

	var	$quote 			= $('[data-name="simple-quote"]').hide(),
		$quoteAuthor 	= $('[data-name="simple-quote-author"]').hide(),
		$linkUrl  		= $('[data-name="simple-link-url"]').hide(),
		$linkText  		= $('[data-name="simple-link-text"]').hide(),
		$videoUrl 		= $('[data-name="simple-video-url"]').hide(),
		$audioUrl 		= $('[data-name="simple-audio-url"]').hide(),
		$postArea	   	= $('#postdivrich');
		$postFormat    	= $('#post-formats-select input[name="post_format"]'),

		$acfPostbox		= $('[data-name="simple-quote"]').parents('.postbox.acf-postbox.default').hide();

	$postFormat.each(function() {

		var $this = $(this);

		if( $this.is(':checked') ) {
			changePostFormat( $this.val() );
		}

	});

	$postFormat.change(function() {

		changePostFormat( $(this).val() );

	});

	function changePostFormat( val ) {

		$quote.hide();
		$quoteAuthor.hide();
		$linkUrl.hide();
		$linkText.hide();
		$videoUrl.hide();
		$audioUrl.hide();
		$postArea.hide();
		$acfPostbox.hide();

		if( val === 'quote' ) {

			$acfPostbox.show();
			$quote.show();
			$quoteAuthor.show();

		} else if( val === 'link' ) {

			$acfPostbox.show();
			$linkUrl.show();
			$linkText.show();

		} else if( val === 'video' ) {

			// $acfPostbox.show();
			// $videoUrl.show();
			$postArea.show();

		} else if( val === 'audio' ) {

			$acfPostbox.show();
			$audioUrl.show();

		} else if( val === 'aside' ) {

			$postArea.show();

		} else if( val === 'gallery' ) {

			$postArea.show();

		} else if( val === '0' ) {

			$postArea.show();

		}
	}
});