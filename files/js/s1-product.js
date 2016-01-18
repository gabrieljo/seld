var step = {
	animateLinks: function(){
		var t = $(this).find('.navbtns');
		t.css({'left':'20px', 'opacity':'0'});		// reset position.
		t.animate({left : '0',opacity: 1}, 500);	// animate
	},
	resetAnimationLinks: function(){
		var t = $(this).find('.navbtns');
		t.css({'left':'20px', 'opacity':'0'});		// reset position.
	},
	init: function(){
		$('.product-list li').hover(step.animateLinks, step.resetAnimationLinks);
	}
};