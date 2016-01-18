var step = {
	totalPages:1,
	scale:1,
	currentPageIndex:0,
	sourceEncrypt: function(){
		/**
		 * This method will encrypt Source of the contents. 
		 * Used only at the begining
		 * 
		 * Update step.updatePad() && applyBackgroundAll() after adding more Background Properties like background-image.
		 */
		var sn = 1;
		$('#design-pages li').each(function(){
			// find canvas_properties
			var printPad = $(this).find('.print_pads');
			if (printPad.length == 1 && (printPad.attr('style') != null || printPad.attr('style') != null)){
				var styles = 'background-color:' + printPad.css('background-color');
				$('#node' + sn).attr('data-style', styles);
			}
			var html = encodeURI($(this).html());
			$(this).html(html);
			sn++;
		});
		sn = 1;
		$('#design-theme-pages li').each(function(){
			// find canvas_properties
			var printPad = $(this).find('.print_pads');
			if (printPad.length == 1 && (printPad.attr('style') != null || printPad.attr('style') != null)){
				var styles = 'background-color:' + printPad.css('background-color');
				$('#theme-node' + sn).attr('data-style', styles);
			}
			var html = encodeURI($(this).html());
			$(this).html(html);
			sn++;
		});
	},
	resetPad: function(){
		/**
		 * this method will reset the Pad to theme default.
		 */
		step.currentPageIndex = 0;
		var totalPages = $('#design-pages li').length;
		for (i=0; i<totalPages; i++){
			$('#design-pages li:eq(' + i + ')').html($('#design-theme-pages li:eq(' + i + ')').html());
			$('#design-pages li:eq(' + i + ')').attr('data-style', $('#design-theme-pages li:eq(' + i + ')').attr('data-style'));
		}
		// bring the HTML from theme if selected from theme!
		step.displayActivePad();
		$('#myModalReset').modal('hide');
	},
	prepareCanvas: function(){
		/**
		 * This is the first method called by the system. 
		 * This should initialize all the tools for the EDITOR.
		 */
		step.sourceEncrypt();

		step.setCanvasScaleFit();

		// get canvas properties
		var o 			= $('ul#design-pages');
		var width 		= o.data('width');
		var height 		= o.data('height');
		// set the canvas dimension	
		$('#pad').css({'width':width+'px', 'height':height+'px'});
		
		// Remove Overlay
		$('.seld-status, .seld-footer').remove();
		$('#editor_overlay').addClass('hidden');		

		step.displayActivePad();
	},
	preparePadLayers: function(){
		/**
		 * This method will arrange the layers of the Design Page
		 * in terms of the z-index positioning.
		 * 
		 * This method should only be called by displayActivePad().
		 */
		var divs = new Array();
		$('#pad .layer').each(function(){
			var index = parseInt($(this).css('z-index')) - 10;
			divs[index] = '<div id="' + $(this).attr('id') + '" class="' + $(this).attr('class') + '" data-type="' + $(this).attr('data-type') + '" style="' + $(this).attr('style') + '">' + $(this).html() + '</div>';
		});
		var html = '';
		for (i=0; i<divs.length; i++){
			if (divs[i] != undefined){
				html += divs[i];
			}
		}

		// update pad bg
		var bg = $('#design-pages li:eq(' + step.currentPageIndex + ')').attr('data-style');
		$('#pad').html(html);

		// get all the PAD styles.
		var allBg = bg.split(';');
		for (i=0; i<allBg.length; i++){
			if (allBg[i] != ''){
				var style = allBg[i].split(':');
				$('#pad').css(style[0], style[1]);
			}
		}
		// Reset Options
		var bgColor = $('#pad').css('background-color');
		$('.dToolOptionFontColorCanvas').val(rgb2hex(bgColor));
	},
	updatePad: function(){
		/**
		 * This method will update the CONTENT STORE by updating user pad.
		 * Used when changing current page OR right before saving.
		 */
		var html = encodeURI($('#pad').html());
		$('#design-pages li:eq(' + step.currentPageIndex + ')').html(html);
		
		// Update Pad Styles
		var bg = $('#pad').css('background-color');
		$('#design-pages li:eq(' + step.currentPageIndex + ')').attr('data-style', 'background-color:'+bg);
	},
	displayActivePad: function(){
		/**
		 * This method will load the SOURCE CONTENT to the ACTIVE EDITOR PAD.
		 * The page to load is dependet on step.currentPageIndex
		 */
		$('#editor_overlay').removeClass('hidden');

		$('#design-tools-options').removeClass().addClass('current-canvas');

		var pageSide 	= $('ul.pad-face li.active').length == 0 || $('ul.pad-face li.active').text() == 'Front' ? 0 : 1;
		var pageNumber 	= $('ul.pad-pagination li.active').length == 0 ? 1 : parseInt($('ul.pad-pagination li.active').text());
		var designPages = $('#design-pages').attr('data-pages');

		step.currentPageIndex 	= (designPages * pageSide) + pageNumber - 1;
		//console.log(step.currentPageIndex);
		var refHTML 			= decodeURI($('#design-pages li:eq(' + step.currentPageIndex + ')').html());
		$('#pad').html(refHTML);
		step.preparePadLayers();

		// populate Textareas
		/*var totalText = $('#pad .layer-text-container').length;
		for (i=0; i<totalText; i++){
			var ref = $('#pad .layer-text-container:eq(' + i + ') textarea');
			var text = ref.data('content');
			ref.val(decodeURI(text));
		}*/

		// Update Layers
		$('#layers').html('');
		step.updateLayer({update:step.updateLayerPosition});

		// Remove Existent active layer
		$('#pad .layer').removeClass('active');

		$('#editor_overlay').addClass('hidden');
	},
	setActiveImageTab: function(){
		$('.image-options h3 span').removeClass('active');
		$(this).addClass('active');
		var ref = $(this).data('ref');
		$('#image-options-select, #image-options-upload').addClass('hidden');
		$('#'+ref).removeClass('hidden');
	},
	setCurrentPage: function(){
		/**
		 * this method will set the current page number
		 * from editor_footer options
		 */
		step.updatePad();
		// change active page class
		$('ul.pad-pagination li').removeClass('active');
		$(this).addClass('active');
		// load content
		step.displayActivePad();
	},
	setCurrentFace: function(){
		/**
		 * this method will set the current face to "front" or "back" based on the selection
		 * from editor_footer options
		 */
		step.updatePad();
		// change active page class
		$('ul.pad-face li').removeClass('active');
		$(this).addClass('active');
		// load content
		step.displayActivePad();
	},
	setCanvasScale: function(){
		var val = $( "#slider" ).slider( "option", "value" );
		$('#pad').css('zoom', val+'%');
		$('#slider_zoom').text(val+'%');
		step.scale = val;
	},
	setCanvasScaleFull: function(){
		$('#slider_zoom').text('100%');
		$('#slider').slider( "option", "value", 100);
		$('#pad').css('zoom', '100%');
		step.scale = 100;
	},
	setCanvasScaleFit: function(){
		// get canvas properties
		var o 			= $('ul#design-pages');
		var padding 	= 55*2;
		var width 		= o.data('width');
		var height 		= o.data('height');

		var sc_width 	= parseInt($('#canvas').width()) - padding;
		var sc_height 	= parseInt($('#canvas').height()) - padding;

		// resize on basis of orientation.
		if (width >= height){
			// Landscape
			ratio = Math.floor(sc_width / width * 100);
		}
		else{
			// Potrait
			ratio = Math.floor(sc_height / height * 100);
		}
		// set Scale
		ratio = ratio > 200 ? 200 : ratio; // Max 200%
		step.scale = ratio <= 0 ? 1 : ratio;//$('#pad').css('zoom');

		$('#pad').css('zoom', ratio+'%');
		$('#slider_zoom').text(ratio+'%');
	},
	updateLayer: function(){
		/**
		 * this method will update layers list and re-arrange the z-index of the layers
		 * on the basis of the layers order.
		 */
		var o 			= $('#pad');
		var t 			= o.find('.layer').length;
		var layers 		= '';
		var textCount 	= 0;
		var imageCount 	= 0;
		var shapeCount 	= 0;

		for (i=0; i<t; i++){
			var ref = o.find('.layer:eq(' + i+')');
			var id 	= ref.attr('id');

			if (ref.data('type') == 'image'){
				imageCount++;
				layers += '<li data-id="'+id+'" class="layer-image" data-id="34343"><input type="checkbox" name="show[]" value="1" checked="checked"> Image ' + imageCount + '<span class="glyphicon glyphicon-trash pull-right" title="Delete Layer"></span></li>';
			}
			else if (ref.data('type') == 'text'){
				textCount++;
				layers += '<li data-id="'+id+'" class="layer-text" data-id="123456"><input type="checkbox" name="show[]" value="1" checked="checked"> Text ' + textCount + '<span class="glyphicon glyphicon-trash pull-right" title="Delete Layer"></span></li>';
			}
			else if (ref.data('type') == 'rect'){
				shapeCount++;
				layers += '<li data-id="'+id+'" class="layer-shape" data-id="843456"><input type="checkbox" name="show[]" value="1" checked="checked"> Shape ' + shapeCount + '<span class="glyphicon glyphicon-trash pull-right" title="Delete Layer"></span></li>';
			}
		}
		//console.log(t, layers); 
		$('#layers').html(layers);
		step.setLayerSortable();

		// Unbind previous Plugins
		$('#pad .layer').find('.ui-resizable-handle').remove();
		$('#pad .layer').find('.ui-rotatable-handle').remove();
		step.updateLayerPosition();
	},
	setLayerVisibility: function(){
		var id = $(this).parent().data('id');
		var ref = $('#pad #'+id);
		$(this).is(':checked') ? ref.css('display', 'block') : ref.css('display', 'none');
	},
	setLayerSortable: function(){
		/**
		 * this method will enable layers list to be sortable.
		 */
		$('#layers').sortable({update:step.updateLayerPosition, axis:"y", containment: "parent"});
	},
	updateLayerPosition: function(){
		// Update Layer Order and layer's z-indexes
		var startIndex = 10;
		$('#layers li').each(function(e){
			id = $(this).data('id');
			$('#pad #' + id).css('z-index', ++startIndex);
		});
	},
	findCurrentLayer: function(){
		/**
		 * this method will highlight the current layer.
		 */
		var ref = $(this).attr('data-id');
		$('.layer').removeClass('highlight');
		$('#' + ref).addClass('highlight');
	},
	setLayerUIFunctions: function(){
		/**
		 * this method will enable UI functionality of the active layer.
		 * 
		 * Called every time a new layer is active or focused.
		 * Functionality comprises of "Draggable", "Rotatable" and "Resizable"
		 */
		$('#pad .layer.active').resizable({autoHide: true});
		$('#pad .layer.active').rotatable({wheelRotate: false});

		// Layer Draggable with zoom
		$('#pad .layer').draggable({
			drag: function(evt, ui){
				var zoom 	= step.scale / 100;
				zoom 		= zoom <= 0 ? 1 : zoom;
				var factor 	= (1 / zoom) -1;
				//console.log(factor);
				if (zoom != 1){
		            ui.position.top 	+= Math.round((ui.position.top - ui.originalPosition.top)  * factor);
		            ui.position.left 	+= Math.round((ui.position.left- ui.originalPosition.left) * factor);
				}
			}
		});

		$('#pad .layer').draggable({disabled: true});

		$('#pad .layer.active').draggable({disabled: false});
	},
	removeLayerUIFunctions: function(){
		/**
		 * This method will disable all the UI functionality.
		 * Mainly used when there is no active layers OR,
		 * Pad Properties is on display.
		 */
		$('#pad .layer').draggable();
		$('#pad .layer').draggable({disabled: true});
	},
	setActiveLayer: function(){
		/**
		 * this method will set the layer as active and display the options as per 
		 * the layer type.
		 */
		$('ul#layers li').removeClass('active');
		$(this).addClass('active');

		var id = $('ul#layers li.active').data('id');
		$('#pad .layer').removeClass('active');
		$('#'+id).addClass('active');

		// Set Options - Get a current layer type
		//$('#design-tools-options-wrapper').addClass('hidden');
		var currentLayer = $('#layers li.active');

		if (currentLayer.length == 1){
			if (currentLayer.hasClass('layer-text')){
				$('#design-tools-options').removeClass().addClass('current-text');
				step.setActiveLayerToolsProperties('text');

				$('#text-options-textarea').select();
			}
			else if(currentLayer.hasClass('layer-image')){
				$('#design-tools-options').removeClass().addClass('current-image');
				step.setActiveLayerToolsProperties('image');
			}
			else if(currentLayer.hasClass('layer-shape')){
				$('#design-tools-options').removeClass().addClass('current-shape');
				step.setActiveLayerToolsProperties('shape');
			}
			//$('#design-tools-options-wrapper').removeClass('hidden');
		}

		step.setLayerUIFunctions();
	},
	setActiveLayerToolsProperties: function(mode){
		/**
		 * this method will set the active layer properties
		 * Style options for the selected text.
		 */
		var target = $('.layer.active');
		if (mode == 'text'){
			// set text properties.
			var o = target.find('.textarea');
			// textarea
			$('#text-options-textarea').val(o.html());
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
		/**
		 * this method is triggered when user clicks on layer element in the editor PAD
		 * find first if the element is layer
		 */
		var o = $(this);
		$('#pad .layer').removeClass('active');

		// click on master
		if ($(this).hasClass('layer') || $(this).hasClass('textarea')){
			$('#pad .layer').removeClass('active');
			if (o.hasClass('layer')){
				o.addClass('active');
				$('#layers li[data-id="' + o.attr('id') + '"]').trigger('click');
			}
			else{
				// .textarea
				o.parent().addClass('active');
				$('#layers li[data-id="' + o.parent().attr('id') + '"]').trigger('click');
			}
		}
		else{
			// display canvas options
			$('#design-tools-options').removeClass().addClass('current-canvas');
			$('#layers li').removeClass('active');

			step.removeLayerUIFunctions();
		}
		return false;
	},
	deleteLayer: function(){
		/**
		 * this method will confirm user to delete the selected layer.
		 */
		if (confirm('This layer will be deleted!')){
			var o = $(this).parent();
			var id = o.data('id');
			$('#pad #'+id).remove();
			o.remove();
		}
	},
	useTool: function(){
		/**
		 * this method will simply define the functionality of the left-hand side toolbar
		 */
		var type = $(this).data('type');
		switch (type){
			case "text":
				step.designToolText();
				break;
			case "image":
				step.designToolImage();
				break;
			case "preview":
				$('#pad, #canvas').toggleClass('preview');
				break;
			case "copy":
				step.designToolCopyLayer();
				break;
			case "delete":
				step.designToolDelete();
				break;

			case "reset":
				step.designToolReset();
				break;

			case "save":
				step.saveDesign();
				break;

			case "info":
				$('.seld-nav').css('z-index', 90);
				$('#myModalInfo').modal('show');
				$('#design_title').focus();
				break;

			case "rect":
				step.designToolRectangle();
				break;
		}		
	},
	designToolText: function(){
		/**
		 * this method will add a text field in the design pad.
		 */
		var o = $('ul#design-pages li:eq(' + step.currentPageIndex + ')');
		var id = Date.now();
		//var h = '<div id="layer-'+id+'" class="layer layer-text-container" data-type="text" style="transform:none"><span class="glyphicon glyphicon-move"></span><textarea data-content="" placeholder="Write here..." style="font-family:Arial; font-size: 16px; line-height: 16px; color: #000000; font-weight: normal; font-style:normal; text-align:left;"></textarea></div>';
		var h = '<div id="layer-'+id+'" class="layer layer-text-container" data-type="text" style="transform:none;width:400px;height:80px;"><div class="textarea" style="font-family:Agisarang; font-size: 48px; line-height: 48px; color: #000000; font-weight: normal; font-style:normal; text-align:left;"></div></div>';
		$('#pad').append(h);
		step.updateLayer();

		// select new layer
		$('#pad #layer-'+id).trigger('click');
		$('#text-options-textarea').select();
	},
	designToolImage: function(){
		var o = $('ul#design-pages li:eq(' + step.currentPageIndex + ')');
		var id = Date.now();
		//var h = '<div id="layer-'+id+'" class="layer layer-image-container" data-type="image"><span class="glyphicon glyphicon-move"></span></div>';
		var h = '<div id="layer-'+id+'" class="layer layer-image-container" data-type="image"><img src="' + base_url()+ 'files/img/design/icon-img.png" /></div>';
		$('#pad').append(h);
		step.updateLayer();
		$('#pad #layer-'+id).trigger('click');
	},
	designToolRectangle: function(){
		var id = Date.now();
		var h = '<div id="layer-'+id+'" class="layer layer-rect-container" data-type="rect"></div>';
		$('#pad').append(h);
		step.updateLayer();
		$('#pad #layer-'+id).trigger('click');
	},
	designToolCopyLayer: function(){
		/**
		 * this method will copy the current selected layer.
		 * layer can be image or text.
		 */
		var ref = $('#layers li.active');
		var newIndex = $('#layers li').length + 11;

		if (ref.length == 1){

			var id 	= ref.attr('data-id');
			var o 	= $('#'+id);

			if (ref.hasClass('layer-text')){
				// text
				$('.dTool[data-type="text"]').trigger('click');
				ref 		= $('#layers li.active');
				var newId 	= ref.attr('data-id');
				var newObj 	= $('#'+newId);
				var newLeft = parseInt(o.css('left')) + 50;
				var newTop 	= parseInt(o.css('top')) + 50;

				newObj.attr('style', o.attr('style'));
				newObj.find('.textarea').html(o.find('.textarea').html());
				newObj.find('.textarea').attr('style', o.find('.textarea').attr('style'));
				newObj.css({'left':newLeft, 'top':newTop, 'z-index':newIndex});
			}
			else{
				// image
				$('.dTool[data-type="image"]').trigger('click');
				ref 		= $('#layers li.active');
				var newId 	= ref.attr('data-id');
				var newObj 	= $('#'+newId);
				var newLeft = parseInt(o.css('left')) + 50;
				var newTop 	= parseInt(o.css('top')) + 50;

				var imgSrc 	= o.find('img').length == 1 ? o.find('img').attr('src') : '';

				if (imgSrc != ''){
					newObj.find('img').attr('src', imgSrc);//append('<img src="' + imgSrc + '" />');
				}
				newObj.attr('style', o.attr('style'));
				newObj.css({'left':newLeft, 'top':newTop, 'z-index':newIndex});
			}
		}
	},
	designToolDelete: function(){
		/** 
		 * this will delete the selected active layer
		 */
		if ($('#layers li.active').length == 1){
			$('#layers li.active span.glyphicon-trash').trigger('click');
		}
	},
	designToolReset: function(){
		/**
		 * this method will reset the design by clearing all the user added content.
		 */
		$('.seld-nav').css('z-index', 90);
		$('#myModalReset').modal('show');
		//$('.seld-nav').css('z-index', 100);
	},
	saveDesign: function(){
		/**
		 * This method will update the user changes in Database
		 * The source must be decoded before sending to save.
		 */
		step.updatePad(); // Update pad to layers.
		
		var pageSide 	= parseInt($('#design-pages').attr('data-faces'));
		var pageNumber 	= parseInt($('#design-pages').attr('data-pages'));
		var totalPages 	= pageSide * pageNumber;
		var sep 		= '';
		var html		= '';
		var id 			= $('#design-pages').attr('data-ref');

		for (i=0; i<totalPages; i++){
			var pageHtml= decodeURI($('#design-pages li:eq(' + i + ')').html());
			var style 	= $('#design-pages li:eq(' + i + ')').attr('data-style');
			
			// find if div has class print_pads
			var total 	= pageHtml.split('class="print_pads"').length;
			if (total > 1){
				// print_pads exists.
				pageHtml= pageHtml.replace('class="print_pads"', 'data-old-class="print_pads"');
			}
			html += sep + '<div class="print_pads" style="' + style + '">' + pageHtml + '</div>';

			// get styles
			sep = '||==||';
		}

		//console.log(html)
		$.post(base_url()+'u/save', {'id':id, 'title':encodeURIComponent($('#design_title').val()), 'description':encodeURIComponent($('#design_description').val()), 'content':html});
		var currentDate = new Date();
		var day 		= currentDate.getDate();
		var month 		= currentDate.getMonth() + 1;
		var year 		= currentDate.getFullYear();
		var my_date 	= year + '-' + month + '-' + day;
		$('#last_save_msg i').text('' + my_date);
	},
	useToolOption: function(){
		/**
		 * this method will handle action requests from the editor toolbar.
		 * Most of the options are for "TEXT" tool type.
		 */
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
			}
		}
	},
	layerToolFont: function(id, val){
		/**
		 * this method will change the current text font-family
		 */
		var ref = $('#'+id).find('.textarea');
		ref.css('font-family', val);
	},
	layerToolBold: function(id, tool){
		/**
		 * this method will toggle the font-weight of current text.
		 */
		var ref = $('#'+id).find('.textarea');
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
		/**
		 * this method will toggle font-style of current text.
		 */
		var ref = $('#'+id).find('.textarea');
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
		/**
		 * this method alters the font-size of current text.
		 */
		var ref = $('#'+id).find('.textarea');
		ref.css('font-size', val+'px');
	},
	layerToolFontHeight: function(id, val){
		/**
		 * this method alters the line-height of current text.
		 */
		var ref = $('#'+id).find('.textarea');
		ref.css('line-height', val+'px');
	},
	layerToolFontColor: function(){
		/**
		 * this method will change the color of current text.
		 */
		var val = $('.dToolOptionFontColor').val();
		var ref = $('.layer.active').find('.textarea');
		ref.css('color', val);
	},
	layerToolFontColorCanvas: function(){
		var val = $('.dToolOptionFontColorCanvas').val();
		var ref = $('#pad').css('background-color', val);
	},
	layerToolShapeColorCanvas: function(){
		var val = $('.dToolOptionShapeColor').val();
		var ref = $('#pad .layer.active').css('background-color', val);
	},
	layerToolAlign: function(id, val){
		/**
		 * this method will change current text's text-alignment
		 */
		var ref = $('#'+id).find('.textarea');
		ref.css('text-align', val);
		$('.dToolOption[data-type="left-align"], .dToolOption[data-type="right-align"], .dToolOption[data-type="center-align"]').removeClass('active');
	},
	selectUserImage: function(){
		/**
		 * this method will select the user image and load it to the container.
		 * replace if image already exist.
		 */
		var imgSrc 	= $(this).parent().find('img').attr('src');
		imgSrc 		= imgSrc.replace("/thumbs", "");

		if ($('.layer-image-container.active img').length > 0){
			$('.layer-image-container.active img').attr('src', imgSrc);
		}
		else{
			$('.layer-image-container.active').append('<img src="' + imgSrc + '" />');
		}
	},
	updateText: function(){
		/**
		 * this method will update user text when anykey is pressed.
		 */
		 var vl = $(this).val();
		 vl = vl.replace(/\r?\n/g, '<br />');
		$('.layer.active .textarea').html(vl);
	},
	keyboardKeys: function(){
		var ref = $(document);
		// Save Key
		ref.bind('keydown', 'ctrl+s', function(e){$('.dTool[data-type="save"]').trigger('click');e.preventDefault()});
		// Delete Layer
		ref.bind('keydown', 'del', function(e){$('.dTool[data-type="delete"]').trigger('click');e.preventDefault()});
		// Copy Layer
		ref.bind('keydown', 'ctrl+c', function(e){$('.dTool[data-type="copy"]').trigger('click');e.preventDefault()});
		// New Text
		ref.bind('keydown', 'ctrl+e', function(e){$('.dTool[data-type="text"]').trigger('click');e.preventDefault()});
		// New Image
		ref.bind('keydown', 'ctrl+i', function(e){$('.dTool[data-type="image"]').trigger('click');e.preventDefault()});
		// Preview
		ref.bind('keydown', 'ctrl+p', function(e){$('.dTool[data-type="preview"]').trigger('click');e.preventDefault()});
	},
	applyBackgroundAll: function(){
		if ($(this).is(':checked')){
			if (confirm('Apply Background Color to all pages?')){
				var bg = $('#pad').css('background-color');
				$('#design-pages li').attr('data-style', 'background-color:' + bg);
			}
			$(this).attr('checked', false);
		}
	},
	init: function(){
		/**
		 * this method is for user initialization of seld editor 
		 */
		step.prepareCanvas();

		// menu
		$('body').on('click', 	'ul#layers li', step.setActiveLayer);
		$('#editor_properties').on('mouseover', 'ul#layers li', step.findCurrentLayer);
		$('#editor_properties').on('mouseout', function(){ $('.layer').removeClass('highlight')});
		$('body').on('change', 	'ul#layers li input[type="checkbox"]', 	step.setLayerVisibility);
		// design tools
		$('ul#design-tools .dTool').click(step.useTool);

		$('ul#design-tools-options .dToolOption').click(step.useToolOption);
		$('ul#design-tools-options .dToolOptionDropdown').change(step.useToolOption);
		// Color Picker
		$('.dToolOptionFontColor').colorpicker({format:'hex'}).on('changeColor.colorpicker', function(e){step.layerToolFontColor()});
		$('.dToolOptionFontColorCanvas').colorpicker({format:'hex'}).on('changeColor.colorpicker', function(e){step.layerToolFontColorCanvas()});
		$('.dToolOptionShapeColor').colorpicker({format:'hex'}).on('changeColor.colorpicker', function(e){step.layerToolShapeColorCanvas()});

		// layers
		step.setLayerSortable();
		//$('#pad').on('click', 	'.layer', 			step.setActivePad);
		$('#canvas_cell').on('click', 'div',  step.setActivePad);
		//$('#canvas').on('click', '*:not(.layer, .layer textarea)', step.setActivePadReset);
		$('body').on('click', 	'#layers .glyphicon-trash', step.deleteLayer);

		step.setLayerSortable();

		// Image Tabs
		$('.image-options h3 span').click(step.setActiveImageTab);

		// user images
		$('body').on('click', '#my-images-list .img-wrapper .btn', step.selectUserImage);

		// canvas slider
		$( "#slider" ).slider({min:2, step:2, max:200, slide:step.setCanvasScale, value:step.scale});
		step.setCanvasScale();

		// textarea update
		$('#text-options-textarea').keyup(step.updateText);

		// Canvas Options
		$('ul.pad-pagination li').click(step.setCurrentPage);
		$('ul.pad-face li').click(step.setCurrentFace);
		$('.glyphicon-fullscreen').click(step.setCanvasScaleFull);
		$('.glyphicon-resize-small').click(step.setCanvasScaleFit);

		$('#myModalReset, #myModalInfo').on('hidden.bs.modal', function (e) {
			$('.seld-nav').css('z-index', 100);
		});
		// Reset Theme
		$('#confirm_pad_reset').click(step.resetPad);

		// Apply pad background to all
		$('#apply_background_all').click(step.applyBackgroundAll);

		// keyboard keys
		step.keyboardKeys();
	}
};

/**
 * this method will change the RGB value to Hexadecimal notation of the color code.
 */
function rgb2hex(orig){
	var rgb = orig.replace(/\s/g,'').match(/^rgba?\((\d+),(\d+),(\d+)/i);
	return (rgb && rgb.length === 4) ? "#" +
	("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
	("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
	("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : orig;
}