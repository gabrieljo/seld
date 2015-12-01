var seld = {
	settings:{
		allowScroll : true
	},
	prepareLinks: function(){
		var total = $('.article-pages .page').length;
		if (total > 1){
			var op = '';
			for (i=1; i<=total; i++){
				op+= '<li data-ref="page' + i + '" ';
				if (i == 1){
					op+= ' class="active" ';
				}
				op+= '></li>';
			}
			// margin.
			var m = (total * 26 ) / 2;
			$('#quick_page_links').html(op).css('margin-top', '-' + m + 'px');
		}
	},
	navigatePage: function(){
		
		if (!$(this).hasClass('active')){
			var prevPageIndex 	= $('#quick_page_links').attr('data-prev-page');
			var curPageIndex	= $('#quick_page_links li').index(this);

			$('.page').removeClass('prev');
			$('.page:eq('+prevPageIndex+')').addClass('prev');

			seld.resetAnim();

			$('.page, #quick_page_links li').removeClass('active');
			$('.page:eq('+curPageIndex+')').addClass('active');
			$(this).addClass('active');

			// decide direction on prev selection.
			$('#quick_page_links').attr('data-prev-page', curPageIndex);

			var mg = (curPageIndex - prevPageIndex > 0) ? 100 : -100;
			$('.page:eq(' + curPageIndex + ')').css('margin-top', mg+'%').animate({marginTop:0}, {duration:800, complete: function(){seld.settings.allowScroll=true;seld.initAnim()}});

			// page specific
			if (curPageIndex == 1){
				$('#myVideo').get(0).play();
			}
			else{
				$('#myVideo').get(0).pause();
			}
		}
	},
	triggerNav: function(dir){
		if (seld.settings.allowScroll == true){
			seld.settings.allowScroll = false;
			if (dir > 0){
				if ($('#quick_page_links li.active').prev().length > 0){
					$('#quick_page_links li.active').prev().trigger('click');					
				}
				else{
					seld.settings.allowScroll = true; // reset scroll
				}
			}
			else{
				if ($('#quick_page_links li.active').next().length > 0){
					$('#quick_page_links li.active').next().trigger('click');					
				}
				else{
					seld.settings.allowScroll = true; // reset scroll
				}
			}
		}
	},
	resetAnim: function(){
		var ref = $('.page.prev');
		var t 	= ref.find('.anim').length;

		for (i=0; i<t; i++){
			var o 		= $('.page.active .anim:eq(' + i + ')');
			var iStyle	= o.data('istyle');
			var duration= 200;
			var style 	= {};

			if (iStyle!=''){
				iStyle 	= iStyle.split(';');
				for (j=0; j<iStyle.length; j++){
					prop = iStyle[j].split(':');
					if (prop[0] != '' && prop[1] != ''){
						style[prop[0]] = prop[1];
					}
				}
			}
			o.animate(style, duration);
		}
	},
	initAnim: function(){
		var ref = $('.page.active');
		var t 	= ref.find('.anim').length;

		for (i=0; i<t; i++){
			var o 		= $('.page.active .anim:eq(' + i + ')');
			var fStyle 	= o.data('fstyle');
			var duration= o.attr('data-duration') == null ? 500 : o.attr('data-duration');
			var style 	= {};

			if (fStyle!=''){
				fStyle 	= fStyle.split(';');
				for (j=0; j<fStyle.length; j++){
					prop = fStyle[j].split(':');
					if (prop[0] != '' && prop[1] != ''){
						style[prop[0]] = prop[1];
					}
				}
			}
			o.animate(style, duration);
		}
	},
	init: function(){
		seld.prepareLinks();
		seld.initAnim();
		$('#quick_page_links').on('click', 'li', seld.navigatePage);
		// trigger with scroll
		$('.page').on('mousewheel', function(event) {
	    	//console.log(event.deltaX, event.deltaY, event.deltaFactor);
	    	seld.triggerNav(event.deltaY);
		});
	}
};