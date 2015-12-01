var step = {
	animateLinks: function(){
		var t = $(this).find('.theme-buttons');
		t.css({'margin-top':'-60px', 'opacity':'0'});		// reset position.
		t.animate({marginTop : '-50px',opacity: 1}, 500);	// animate
	},
	resetAnimationLinks: function(){
		var t = $(this).find('.theme-buttons');
		t.css({'margin-top':'-50px', 'opacity':'0'});		// reset position.
	},
	addFavourite: function(){
		var url = $(this).attr('href');
		$.get(url);
		$(this).removeClass('btn-warning').addClass('btn-success');
		return false;
	},
	init: function(){
		$('ul.product-themes li').hover(step.animateLinks, step.resetAnimationLinks);
		$('a.link-add-favourite').click(step.addFavourite);
	}
};