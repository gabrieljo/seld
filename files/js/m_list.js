/**
 * this contains all the requried functions to handle
 * user's design list.
 * 
 * Method for generating previews and delete confirmation.
 */

var design = {
	deleteConfirmation: function(){

		if (confirm('Are you sure you want to delete this design?\r\nThis can\'t be undone.')){
			return true;
		}
		return false;
	},
	loadPreview: function(){

		var loadImg = '';
		$('.canvas_file_info').removeClass('hidden');
		$('.seld-nav').css('z-index', 90);

		var ref = $('.canvas_file_info.wrapper #preview_wrapper');
		ref.html('<img src="' + base_url() + 'files/img/loading.gif" style="margin-bottom: 20px;" /> <br /><strong>Loading Preview...</strong>');

		var totalPages 	= $(this).attr('data-pages');
		var folder 		= $(this).attr('data-target');

		var html = '<div id="preview-main"><div id="preview-inner"></div></div><ul>';
		for (var i=1; i<=totalPages; i++){
			html+= '<li><img src="' + base_url() + 'files/products/' + folder + '/design/page-' + i + '.png?load=' + Math.random() + '" /></li>';
		}

		setTimeout(function(){
			ref.html(html+'</ul>');
			// select the first one
			ref.find('li:eq(0)').trigger('click');
		}, 400);
	},
	init: function(){

		$('a.btnDelete').click(design.deleteConfirmation);

		/**
		 * this will load preview, if doesn't exist, make one.
		 */
		$('button.preview').click(design.loadPreview);

		// close preview
		$('.canvas_file_info.overlay').click(function(){
			$('.canvas_file_info').addClass('hidden');
			$('.seld-nav').css('z-index', 100);
		});

		/**
		 * preview image thumbnails.
		 */
		$('body').on('click', '#preview_wrapper ul li', function(){
			var img = $(this).find('img').clone();
			$('#preview-inner').html(img);
		});
	}
};