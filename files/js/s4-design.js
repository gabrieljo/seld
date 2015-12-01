var step = {
	totalPages:1,
	scale:1,
	currentPageIndex:0,
	width:500,
	height:400,
	initialTrim: function(){
		$('.seld-status, .seld-footer').remove();
		// Initial saved layers TRIM
		$('.layer').find('.ui-resizable-handle').remove();
		$('.layer').find('.ui-rotatable-handle').remove();		
	},
	designMenu: function(){
		w = 120;//parseInt($('.aside').width())-61;
		//$('#design-tools-wrapper').css('width', w+'px')
	},
	prepareCanvas: function(){
		var o 			= $('ul#design-pages');
		step.scale 		= o.data('scale');
		step.width 		= o.data('width');
		step.height 	= o.data('height');
		step.currentPageIndex	= 0;
		$('#pad').addClass('ap1');
		step.updateLayer({update:step.updateLayerPosition});
		// Populate Textareas.
		var totalText = $('#pad .layer-text-container').length;
		for (i=0; i<totalText; i++){
			var ref = $('#pad .layer-text-container:eq(' + i + ') textarea');
			var text = ref.data('content');
			ref.val(decodeURI(text));
		}
	},
	setCanvasScale: function(){
		var val = $( "#slider" ).slider( "option", "value" );
		//console.log(val);
		$('#pad').css('zoom', val+'%');
	},
	updateLayer: function(){
		//var o = $('ul#design-pages li:eq(' + step.currentPageIndex + ')');
		var o 			= $('#pad');
		var t 			= o.find('.layer').length;
		var layers 		= '';
		var textCount 	= 0;
		var imageCount 	= 0;

		for (i=0; i<t; i++){
			var ref = o.find('.layer:eq(' + i+')');
			var id 	= ref.attr('id');

			if (ref.data('type') == 'image'){
				imageCount++;
				layers += '<li data-id="'+id+'" class="layer-image" data-id="34343"><input type="checkbox" name="show[]" value="1" checked="checked"> Image ' + textCount + '<span class="glyphicon glyphicon-trash pull-right" title="Delete Layer"></span></li>';
			}
			else if (ref.data('type') == 'text'){
				textCount++;
				layers += '<li data-id="'+id+'" class="layer-text" data-id="123456"><input type="checkbox" name="show[]" value="1" checked="checked"> Text ' + textCount + '<span class="glyphicon glyphicon-trash pull-right" title="Delete Layer"></span></li>';
			}
		}
		//console.log(t, layers);
		$('#layers').html(layers);
		step.setLayerSortable();
		$('#design-tools-options-wrapper').addClass('hidden'); // Hide options
		$('.layer').resizable({autoHide: true});
	},
	setLayerVisibility: function(){
		var id = $(this).parent().data('id');
		var ref = $('#'+id);
		$(this).is(':checked') ? ref.css('display', 'block') : ref.css('display', 'none');
	},
	setLayerSortable: function(){
		$('#layers').sortable({update:step.updateLayerPosition, axis:"y", containment: "parent"});
	},
	updateLayerPosition: function(){
		// Update Layer Order and layer's z-indexes
		var startIndex = 10;
		$('#layers li').each(function(e){
			id = $(this).data('id');
			$('#' + id).css('z-index', ++startIndex);
		});
	},
	setActiveLayer: function(){
		$('ul#layers li').removeClass('active');
		$(this).addClass('active');
		var id = $('ul#layers li.active').data('id');
		$('#pad .layer').removeClass('active');
		$('#'+id).addClass('active');

		// Set Options - Get a current layer type
		$('#design-tools-options-wrapper').addClass('hidden');
		var currentLayer = $('#layers li.active');
		if (currentLayer.length == 1){
			if (currentLayer.hasClass('layer-text')){
				$('#design-tools-options').removeClass('current-image').addClass('current-text');
				step.setActiveLayerToolsProperties('text');
			}
			else{
				$('#design-tools-options').removeClass('current-text').addClass('current-image');
				step.setActiveLayerToolsProperties('image');
			}
			$('#design-tools-options-wrapper').removeClass('hidden');
		}
	},
	setActiveLayerToolsProperties: function(mode){
		var target = $('.layer.active');
		if (mode == 'text'){
			// set text properties.
			var o = target.find('textarea');
			// font-family
			var v = o.css('font-family');
			$('select[name="opt-font"] option[value="' + v + '"]').prop('selected', true);
			// font-size
			v = parseInt(o.css('font-size'));
			$('select[name="opt-font-size"] option[value="' + v + '"]').prop('selected', true);
			// line-height
			v = parseInt(o.css('line-height'));
			$('select[name="opt-font-height"] option[value="' + v + '"]').prop('selected', true);
			// font-color
			v = o.css('color');
			$('.dToolOptionFontColor').val(rgb2hex(v));
			// font-weight
			v = o.css('font-weight');
			v == 'bold' ? $('.dToolOption[data-type="bold"]').addClass('active') : $('.dToolOption[data-type="bold"]').removeClass('active');
			// font-style
			v = o.css('font-style');
			v == 'italic' ? $('.dToolOption[data-type="italic"]').addClass('active') : $('.dToolOption[data-type="italic"]').removeClass('active');
			// font-alignment
			v = o.css('text-align') == '' ? 'left' : o.css('text-align');
			$('.dToolOption[data-type="left-align"], .dToolOption[data-type="right-align"], .dToolOption[data-type="center-align"]').removeClass('active');
			$('.dToolOption[data-type="' + v + '-align"]').addClass('active');
			// rotation
			v = target.css('transform');
			//console.log(v);
		}
	},
	setActivePadReset: function(){
		$('#pad .layer, #layers li').removeClass('active');
	},
	setActivePad: function(){
		$('#pad .layer').removeClass('active');
		var o = $(this);
		o.addClass('active');
		$('#layers li[data-id="' + o.attr('id') + '"]').trigger('click');
		// show 
	},
	deleteLayer: function(){
		if (confirm('This layer will be deleted!')){
			var o = $(this).parent();
			var id = o.data('id');
			$('#'+id).remove();
			o.remove();
		}
	},
	updateLayerText: function(){
		/*
		 * this method will update text layer values as textarea data
		 */
		var vl = $(this).val();
		$(this).attr('data-content', encodeURI(vl));
	},
	useTool: function(){
		var type = $(this).data('type');
		switch (type){
			case "text":
				step.designToolText();
				break;
			case "image":
				step.designToolImage();
				break;
			case "preview":
				$('#pad').toggleClass('preview');
				break;
			case "copy":
				break;
		}
		$('.layer').draggable({containment: "#canvas"});
		//step.updateLayer();
	},
	designToolText: function(){
		var o = $('ul#design-pages li:eq(' + step.currentPageIndex + ')');
		var id = Date.now();
		var h = '<div id="layer-'+id+'" class="layer layer-text-container" data-type="text" style="transform:none"><span class="glyphicon glyphicon-move"></span><textarea data-content="" placeholder="Write here..." style="font-family:Arial; font-size: 16px; line-height: 16px; color: #000000; font-weight: normal; font-style:normal; text-align:left;"></textarea></div>';
		$('#pad').append(h);
		step.updateLayer();

		// select new layer
		$('#layer-'+id).trigger('click');
	},
	designToolImage: function(){
		var o = $('ul#design-pages li:eq(' + step.currentPageIndex + ')');
		var id = Date.now();
		var h = '<div id="layer-'+id+'" class="layer layer-image-container" data-type="image"><span class="glyphicon glyphicon-move"></span></div>';
		$('#pad').append(h);
		step.updateLayer();
		$('#layer-'+id).trigger('click');
	},
	saveDesign: function(){
		//var data = {'content':$('#pad').html()};
		//data = JSON.stringify(data);
		//console.log(data);
		$.post(base_url()+'u/save', {'content':$('#pad').html()});
	},
	useToolOption: function(){
		var type 	= $(this).data('type');
		var layer 	= $('#layers li.active');
		if (layer.length == 1){
			var ref = layer.data('id');
			switch (type){
				case "font":
					var vl = $(this).val();
					step.layerToolFont(ref, vl);
					break;
				case "bold":
					step.layerToolBold(ref, $(this));
					break;
				case "italic":
					step.layerToolItalic(ref, $(this));
					break;
				case "size":
					var vl = $(this).val();
					step.layerToolFontSize(ref, vl);
					break;
				case "height":
					var vl = $(this).val();
					step.layerToolFontHeight(ref, vl);
					break;
				case "color":
					var vl = $('.dToolOptionFontColor').val();
					//console.log(vl);
					step.layerToolFontColor(ref, vl);
					break;
				case "left-align":
					step.layerToolAlign(ref, 'left');
					$(this).addClass('active');
					break;
				case "right-align":
					step.layerToolAlign(ref, 'right');
					$(this).addClass('active');
					break;
				case "center-align":
					step.layerToolAlign(ref, 'center');
					$(this).addClass('active');
					break;

				case "upload":
					step.layerToolUpload();
					break;					
				case "myfiles":
					step.layerToolFiles();
					break;
			}
		}
	},
	layerToolFont: function(id, val){
		var ref = $('#'+id).find('textarea');
		ref.css('font-family', val);
	},
	layerToolBold: function(id, tool){
		var ref = $('#'+id).find('textarea');
		var val = ref.css('font-weight');
		if (val == "bold"){
			ref.css('font-weight', 'normal');
			tool.removeClass('active');
		}
		else{
			ref.css('font-weight', 'bold');
			tool.addClass('active');
		}
	},
	layerToolItalic: function(id, tool){
		var ref = $('#'+id).find('textarea');
		var val = ref.css('font-style');
		if (val == "italic"){
			ref.css('font-style', 'normal');
			tool.removeClass('active');
		}
		else{
			ref.css('font-style', 'italic');
			tool.addClass('active');
		}
	},
	layerToolFontSize: function(id, val){
		var ref = $('#'+id).find('textarea');
		ref.css('font-size', val+'px');
	},
	layerToolFontHeight: function(id, val){
		var ref = $('#'+id).find('textarea');
		ref.css('line-height', val+'px');
	},
	layerToolFontColor: function(){
		var val = $('.dToolOptionFontColor').val();
		var ref = $('.layer.active').find('textarea');
		ref.css('color', val);
	},
	layerToolAlign: function(id, val){
		var ref = $('#'+id).find('textarea');
		ref.css('text-align', val);
		$('.dToolOption[data-type="left-align"], .dToolOption[data-type="right-align"], .dToolOption[data-type="center-align"]').removeClass('active');
	},
	layerToolUpload: function(){
		console.log('here');
	},
	layerToolFiles: function(){
		console.log('here');
	},
	selectActiveUserImage: function(){

		if (!$(this).hasClass('active')){
			$('#my-images-list .img-wrapper').removeClass('active');			
		}
		$(this).toggleClass('active');

		if ($('#my-images-list .img-wrapper.active').length == 1){
			$('#image-options-select').removeClass('hidden');
			$('#image-options-upload').addClass('hidden');
		}
		else{
			$('#image-options-upload').removeClass('hidden');
			$('#image-options-select').addClass('hidden');
		}
	},
	selectUserImage: function(){
		// this method will select the user image and load it to the container.
		if ($('#my-images-list .img-wrapper.active').length == 1 && $('.layer-image-container.active').length == 1){
			var imgSrc = $('#my-images-list .img-wrapper.active img').attr('src');
			imgSrc = imgSrc.replace("/thumbs", "");
			if ($('.layer-image-container.active img').length > 0){
				$('.layer-image-container.active img').attr('src', imgSrc);
			}
			else{
				$('.layer-image-container.active').append('<img src="' + imgSrc + '" />');
			}
		}
	},
	init: function(){
		step.initialTrim();

		$('#tools').addClass('design-menu');
		step.designMenu();
		$(window).resize(step.designMenu);
		step.prepareCanvas();
		// menu
		$('body').on('click', 'ul#layers li', step.setActiveLayer);
		$('body').on('change', 'ul#layers li input[type="checkbox"]', step.setLayerVisibility);
		// design tools
		$('ul#design-tools .dTool').click(step.useTool);

		$('ul#design-tools-options .dToolOption').click(step.useToolOption);
		$('ul#design-tools-options .dToolOptionDropdown').change(step.useToolOption);
		// Color Picker
		$('.dToolOptionFontColor').colorpicker({format:'hex'}).on('changeColor.colorpicker', function(e){step.layerToolFontColor()});

		// layers
		step.setLayerSortable();
		$('#pad').on('click', '.layer', step.setActivePad);
		$('body').on('blur', '.layer textarea', step.updateLayerText);
		//$('#canvas').on('click', '*:not(.layer, .layer textarea)', step.setActivePadReset);
		$('body').on('click', '#layers .glyphicon-trash', step.deleteLayer);

		// save design
		$('#save_design').click(step.saveDesign);

		// Events for saved layers
		//$('.layer').draggable({containment: "#canvas"});
		$('.layer').draggable({containment: "body"});
		$('.layer').resizable({autoHide: true});
		$('.layer').rotatable({wheelRotate: false});

		step.setLayerSortable();

		// user images
		$('body').on('click', '#my-images-list .img-wrapper', step.selectActiveUserImage);
		$('#image-options-select').click(step.selectUserImage);

		// canvas slider
		$( "#slider" ).slider({min:5, slide:step.setCanvasScale, value:100});
		step.setCanvasScale();
	}
};

function rgb2hex(orig){
	var rgb = orig.replace(/\s/g,'').match(/^rgba?\((\d+),(\d+),(\d+)/i);
	return (rgb && rgb.length === 4) ? "#" +
	("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
	("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
	("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : orig;
}