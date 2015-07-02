/**
 * Panel 
 * @since 1.0
**/

;(function( $ ){

	/** Panel Navigation **/
	$('.era-panel-nav li').on('click',function( event ){

		event.preventDefault();
		event.stopPropagation();

		var $self 		= $(this);
		var $page_id	= $self.data('id');

		$self.addClass('active');
		$self.siblings().removeClass('active');
		$self.siblings().find('.active').removeClass('active');

		// Open Page
		var $page = $('.era-panel-page-'+$page_id);
		$('.era-panel-page').stop(true,true).slideUp(150);
		$page.stop(true,true).slideDown(200);	

	});

}(jQuery));