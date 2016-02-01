/**
 * SELD Creative Editor
 * @author 	Sudarshan Shakya
 * @date  	2015-12-31
 * 
 * SELD Creative Editor is HTML5 Canvas based editor for designers.
 * 	The designing will be saved in binary form.
 * 
 * All the functions will be within the object named "Step".
 */


/**
 * ===================================================================================================================
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ S E L D   S T E P ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * ===================================================================================================================
 */

 var step = {
 	seld:{},				// Canvas ref object.
	seldCanvas:'',			// JS object of SELD Editor CANVAS
	seldCanvasObjects:[],	// array of canvas objects (contexts).
	selectionIndex:0, 		// Selection index of seldCanvas.shapes[INDEX]
	selectionGhost: $('#selectionObject'), 	// selection ghost to imitate dragging, rotating and resizing.
	preloadImages:[],		// array of images to be loaded.
	currentPage:1,			// Current Active Page Number.
	ghostCopy:null, 		// ghost copy for referencing Layer copy and paste.
	presets:[], 			// List of SeldText objects for presets.
	prepareCanvas: function(){
		/**
		 * This is the first method called by the system. 
		 * This should initialize all the tools required by the EDITOR.
		 */
		
		// get canvas properties
		var o 					= $('#design-pages');
		step.seld.id 			= o.attr('data-ref');
		step.seld.width 		= parseInt(o.data('width'));
		step.seld.fullWidth		= parseInt(o.data('width'));
		step.seld.height 		= parseInt(o.data('height'));
		step.seld.pages 		= parseInt(o.data('pages'));
		step.seld.fold 			= parseInt(o.data('fold'));
		step.seld.type 			= parseInt(o.data('type'));
		step.seld.orientation 	= o.data('orientation');

		/**
		 * Calculate total pages.
		 * for "Leaflet" keep account of folding numbers.
		 * the total pages will be 
		 * 	Number of sides * (Total Folding + 1)
		 */
		step.seld.totalPage = step.seld.type == 2 ? (step.seld.pages * (step.seld.fold+1)) : step.seld.pages;

		/**
		 * Divide Canvas width if type is "leaflet"
		 * and folding is more than 0.
		 */
		if (step.seld.type == 2 && step.seld.fold > 0){
			step.seld.width = Math.ceil(step.seld.width / step.seld.fold);
		}
		
		/**
		 * Create main Canvas to refer drawing.
		 */
		step.seldCanvas	= new CanvasState(document.getElementById('pad'), step.seld.width, step.seld.height);
		$('#pad,.sub-canvas, #canvas_ghost').css({'width':step.seld.width, 'height':step.seld.height}).attr({'width':step.seld.width, 'height':step.seld.height});

		// paint bg color
		step.seldCanvas.bgColor = '#ffffff';

		// Load saved contents.
		step._loadProgress();

		// preprare tools
		step._initTools();

		// Initial fit zoom of canvas.
		step.zoomCanvasFit();

		// Clear extra elements and remove overlay
		$('.seld-status, .seld-footer').remove();
	},
	_loadProgress: function(){
		/**
		 * this will pre-load the saved design
		 * 
		 * Also, this will update the seldCanvas.shapes for further modifications.
		 * -- the new object shall be created after removing deleted objects.
		 */
		var data 	= $('#design-pages').html();
		var shapes 	= [];

		/**
		 * load the SeldPage instances first.
		 * load canvasObject 
		 */
		for (var i=1; i<=step.seld.totalPage; i++){
			var seld = new SeldPage(step.seld.width, step.seld.height, i);
			shapes.push(seld);
		}

		if (data != ''){
			var objs 	= JSON.parse(data);

			for (var i=0; i<objs.length; i++){
				var obj 	= objs[i];
				/**
				 * separate canvas page & objects
				 */
				if (obj.name == 'canvas'){
					var page = obj.page;
					/**
					 * loop through added pages, and update info.
					 */
					var total = shapes.length;
					for (k=0; k<total; k++){
						if (obj.page == shapes[k].page){
							shapes[k].bgColor 	= obj.bgColor;
							shapes[k].width 	= obj.width;
							shapes[k].height 	= obj.height;
							shapes[k].valid 	= false; // required for initial drawing.
						}
					}
				}
				else if (obj.delete == false){
					var seld = obj.name == 'text' ? new SeldText() : obj.name == 'image' ? new SeldImage() : new SeldShape();

					for (var key in obj){
						seld[key] = obj[key];
					}
					seld.valid = false;
					/**
					 * Reset image Object for initial loading.
					 * and queue the image for window loading.
					 */
					if (seld.name == 'image'){
						seld.myImage = null;
						// change image host.
						//seld.src = seld.src.replace('192.168.1.8', 'localhost');
						step.preloadImages.push(seld.src);
					}
					//step.seldCanvas.addShape(seld);
					shapes.push(seld);
				}
			}
		}
		
		// update seld shapes
		step.seldCanvas.shapes = shapes;
		step.seldCanvas.valid = false;

		// ==== Display the options for the canvas page 1.
		var first = step.seldCanvas.shapes[0];
		if (first){
			first.options();
		}			

		// Load the display layers
		step.updateLayer();
		
		// show progress bar. and load tools
		step._loadFiles();
	},
	_loadPage: function(){
		/**
		 * this will change the active current page number
		 */
		$('.seld-nav').addClass('hidden');
		$('#loading_page').removeClass('hidden');
		var vl = parseInt($('#seldpage-number').val());

		step.currentPage 			= vl;
		step.seldCanvas.currentPage = vl;

		// clear selection of previous page.
		step.seldCanvas.selection 	= null;
		step.seldCanvas.valid 		= false;

		// load options for current page.
		step.selectionIndex = 0;
		for (var i=0; i<step.seld.totalPage; i++){
			if (step.seldCanvas.shapes[i].name == 'canvas' && step.seldCanvas.shapes[i].page == vl){
				step.selectionIndex = i;
				step.selectLayer(i);
			}
		}

		// update layers list
		step.updateLayer();

		/**
		 * invalidate all visible objects in new page.
		 */
		for (var i=0; i<step.seldCanvas.shapes.length; i++){
			var ref = step.seldCanvas.shapes[i];
			ref.valid = false;
		}

		setTimeout(function(){
			step.seldCanvas.valid = false;
			$('#loading_page').addClass('hidden');
			$('.seld-nav').removeClass('hidden');
		}, 100);
	},
	_loadFiles: function(){
		/**
		 * this will load all required images.
		 */
		var total = step.preloadImages.length;
		$('#overlay_status').text('Loading Images [0/' + total + ']');

		if (total > 0){
			step._loadImageByIndex(0);
		}
		else{
			$('#editor_overlay').addClass('hidden');
		}
	},
	_loadImageByIndex: function(i){
		/**
		 * this will load the current indexed image and
		 * call itself to load new one.
		 */
		var total 	= step.preloadImages.length;

		if (total > 0){
			var done 	= i / total * 100;
			var img 	= i<total ? step.preloadImages[i] : null;

			$('#editor_overlay .progress-bar').attr('aria-valuenow', done).css('width', done+'%').find('span.sr-only').text(done + '% Complete');

			if (i < total){
				$('<img src="'+ img +'">').load(function(){
					$('#overlay_status').text('Loading Images [' + (i+1) + '/' + total + ']');
					step._loadImageByIndex(++i);
				});
			}
			else{
				// loading complete.
				setTimeout(function(){
					$('#editor_overlay').addClass('hidden');
				}, 500);
			}
		}
	},
	navigatePage: function(dir){
		/**
		 * this will navigate canvas page.
		 * direction can be next or previous.
		 * navigate page from page 1 to page 
		 */
		var page 		= dir || 'next';
		var current 	= parseInt($('#seldpage-number').val());

		if (dir == 'next'){
			page = current >= step.seld.totalPage ? 1 : current+1;
		}
		else{
			page = current <= 1 ? step.seld.totalPage : current-1;
		}

		$('#seldpage-number option[value="' + page + '"]').prop('selected', true);
		step._loadPage();
	},
	zoomSetCanvas: function(){
		/**
		 * this method will set the zoom when user changes the slider.
		 */
		var vl 	= $('#canvas_zoom').val();

		step._zoomSet(vl);
	},
	zoomInCanvas: function(){
		/**
		 * this method will increase the canvas zoom
		 */
		var max = $('#canvas_zoom').attr('max');
		var vl 	= parseInt($('#canvas_zoom').val());
		var nvl = vl+5;
		nvl = nvl > max ? max : nvl;

		step._zoomSet(nvl);
	},
	zoomOutCanvas: function(){
		/**
		 * this method will decreas the canvas zoom
		 */
		var min = $('#canvas_zoom').attr('min');
		var vl 	= parseInt($('#canvas_zoom').val());
		var nvl = vl-5;
		nvl = nvl < min ? min : nvl;

		step._zoomSet(nvl);
	},
	zoomCanvasFit: function(){
		/**
		 * this method will zoom the canvas for best fit to the screen size.
		 *  	if too small, the canvas will be set to max of 200% scale.
		 */
		var padding 	= 55*2;
		var width 		= step.seld.width;
		var height 		= step.seld.height;

		var sc_width 	= parseInt($('#canvas').width())  - padding;
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
		ratio = ratio > 100 ? 100 : ratio; // Max 200%

		step._zoomSet(ratio);
	},
	zoomCanvasFull: function(){
		/**
		 * this method will display the 100% view of canvas.
		 */
		step._zoomSet(100);
	},
	_zoomSet: function(amount){
		/**
		 * this will set the zoom amount to the required elements.
		 */
		amount = amount < 1 ? 1 : amount > 200 ? 200 : amount;

		$('#slider_zoom').text(amount+'%');	// Slider Value display text
		$('#canvas_zoom').val(amount); 		// Slider value
		step.seldCanvas.scale = amount; 	// keep track of zoom in %
		$('#pad, #canvas_ghost').css('zoom', amount+'%'); 	// implement css zoom
	},
	updateLayer: function(){
		/**
		 * this will update the contents of the layer for sorting/editing/deleting
		 */
		$('#layers').html('');
		for (var i=0; i<step.seldCanvas.shapes.length; i++){
			var o = step.seldCanvas.shapes[i];
			if (o.page == step.currentPage && o.delete == false && o.name != 'canvas'){
				var li = '<li data-id="' + o.id + '"> ';
				var img = o.name=='text' ? 'text-size' : o.name=='image' ? 'picture' : 'modal-window';
				var chk = o.visibility == 'visible' ? 'checked="checked"' : '';
				li += '<span class="glyphicon glyphicon-' + img + ' sortorder"></span> ';
				li += '<input type="checkbox" name="show[]" value="1" ' + chk + ' data-type="visibility"> ';
				li += '<input data-type="name" type="text" value="' + o.title + '" /> ';
				li += ' <span class="glyphicon glyphicon-trash pull-right" data-type="delete" title="Delete Layer"></span>';
				li += '</li>';
				$('#layers').append(li);
			}
		}
		//step.selectLayer(-1);
	},
	_updateLayerCanvas: function(){
		/**
		 * this will update layers position in Canvas and re-draw
		 *
		 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		 * NEED TO CHANGE LATER TO ENHANCE PERFORMANCE.
		 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		 */
		var newOrderIds	= [];
		$('#layers li').each(function(e){
			newOrderIds.push($(this).attr('data-id'));
		});
		newOrderIds.reverse();

		var newShape 	= [];
		for (var i=0; i<step.seldCanvas.shapes.length; i++){
			var o = step.seldCanvas.shapes[i];
			if (o.page == step.currentPage && o.name != 'canvas'){
				/**
				 * the last id of the list must be added to "newShape" object first
				 * each time after insertion, the id must be removed.
				 */
				var lastIndex 	= newOrderIds.length - 1;
				var lastId 		= newOrderIds[lastIndex];
				for (var k=0; k<step.seldCanvas.shapes.length; k++){
					var ref = step.seldCanvas.shapes[k];
					if (ref.id == lastId){
						ref.valid = false;
						newShape.push(ref);
						// remove from queue
						newOrderIds.pop();
					}
				}
			}
			else{
				// other page object
				newShape.push(o);
			}
		}
		step.seldCanvas.shapes = newShape;
		step.seldCanvas.valid = false;

		// Req. draw all objects.
	},
	layerAction: function(){
		/**
		 * this will make layer changes interms of visibility, title and delete.
		 */
		var type 	= $(this).attr('data-type');
		var ref 	= $(this).parent().attr('data-id');
		
		// find object
		for (var i=0; i<step.seldCanvas.shapes.length; i++){
			var o 	= step.seldCanvas.shapes[i];
			var id 	= String(o.id);

			if (id == ref){
				switch (type){
					case 'name':
						o.title = $(this).val();
						break;
					case 'visibility':
						o.visibility = $(this).is(':checked') ? 'visible' : 'hidden';
						break;
					case 'delete':
						if (confirm('Do you want to delete this layer?\r\nthis can not be undone.')){
							o.delete = true;
							step.updateLayer();
						}
						break;
				}
				// remove
				step.seldCanvas.selection = null;
				step.seldCanvas.valid = false;
				o.valid = false;
				continue;
			}
		}
	},
	layerActionApplyBg: function(){
		/**
		 * this will apply current page's background to all pages.
		 */
		if ($(this).is(':checked')){
			if (confirm('Apply Background Color to all pages?')){
				var bg = $('#seldCanvas-bgColor').val();
				
				// apply bg color to all pages.
				for (var i=0; i<step.seldCanvas.shapes.length; i++){
					if (step.seldCanvas.shapes[i].name == 'canvas'){
						step.seldCanvas.shapes[i].bgColor = bg;
					}
				}
			}
			$(this).attr('checked', false);
		}
	},
	performLayerAction: function(obj, type, value){
	},
	performMenuAction: function(){
		/**
		 * this method will perform requested left menu actions.
		 * 
		 * Void action if menu-option is layer-selection dependent and is disabled.
		 */
		var type 	= $(this).data('type');

		if ($(this).hasClass('requireLayerSelection') && step.selectionIndex<0){
			
			return false;
		}

		switch (type){
			/**
			 * this menu action will add text in the canvas
			 */
			case 'text':
				var text 		= new SeldText(40,40);
				text.fontSize 	= 48;
				text.fontFamily = 'Arial';
				text.page 		= step.currentPage;
				step.seldCanvas.addShape(text);
				break;

			/**
			 * this menu action will add image
			 * Open the image selection panel.
			 */
			case 'image':
				var img = new SeldImage(50,50, 120, 120);
				img.src = base_url() + 'files/img/placeholder.png';
				img.myImage = null;
				img.page 		= step.currentPage;

				step.seldCanvas.addShape(img);

				setTimeout(function(){
					step.selectionIndex = step.seldCanvas.shapes.length - 1;
					$('#launch_imageListModal').trigger('click');
				}, 200);
				break;

			/**
			 * this menu action will add shape.
			 */
			case 'shape':
				var shape = new SeldShape(20,70,50,50,'#EEEEEE');
				shape.page = step.currentPage;
				step.seldCanvas.addShape(shape);
				break;

			case 'layers':
				$('#layer_overlay').toggleClass('hidden');
				var vl = $('#layer_overlay').hasClass('hidden') ? 'hidden' : 'visible';
				localStorage.setItem('tool-layers', vl);
				break;

			case 'copy':
				shape = step.seldCanvas.shapes[step.selectionIndex];
				var seld = shape.name=='text' ? new SeldText() : (shape.name=='image' ? new SeldImage() : new SeldShape());
				for (var key in shape){
					seld[key] = shape[key];
				}
				step.ghostCopy = seld;
				break;

			case 'paste':
				if (step.ghostCopy != null){
					var obj 	= step.ghostCopy;
					obj.id 		= createID();
					obj.title 	= obj.title + ' - Copy';
					obj.value 	= obj.value + ' - Copy';
					obj.x 		= obj.x + 50;
					obj.y 		= obj.y + 50;
					obj.page 	= step.currentPage;
					step.seldCanvas.addShape(obj);

					obj.valid = false;
					step.seldCanvas.valid = false;
					// make multiple-paste in the future.
					step.ghostCopy = null;
				}
				break;

			case 'delete':
				shape = step.seldCanvas.shapes[step.selectionIndex];
				var id 		= shape.id;
				$('#layers li[data-id="' + id + '"] .glyphicon-trash').trigger('click');
				break;
		}
		step.updateLayer();
	},
	loadImage: function(){
		/**
		 * this will load the selected image and perform first selection 
		 *  	by calculating ratio, the image size will be kept original Or
		 * 		as wide or high as available canvas space.
		 */
		var ref 		= $(this).find('img');
		var thumbSrc 	= ref.attr('src');
		var oWidth 		= parseInt(ref.attr('data-width'));
		var oHeight 	= parseInt(ref.attr('data-height'));
		var origSrc 	= thumbSrc.replace('thumbs/', '');
		var maxWidth 	= step.seldCanvas.width;
		var maxHeight 	= step.seldCanvas.height;

		var shape 		= step.seldCanvas.shapes[step.selectionIndex];
		shape.src 		= origSrc;
		shape.oWidth 	= oWidth;
		shape.oHeight 	= oHeight;
		shape.myImage 	= null;

		// Calculate Width/Height ratio.
		var ratio 		= oHeight > 0 ? oWidth / oHeight : 1;
		// Calculate Width or Height
		if (oWidth > oHeight){
			// landscape
			shape.width 	= oWidth > maxWidth ? maxWidth : oWidth;
			shape.height 	= shape.width / ratio;
		}
		else{
			// potrait
			shape.height 	= oHeight > maxHeight ? maxHeight : oHeight;
			shape.width 	= shape.height / ratio;
		}

		// re-position x,y incase image is too big.
		if (oWidth > maxWidth || oHeight > maxHeight){
			shape.x 	= 0;
			shape.y 	= 0;
		}
		
		shape.valid = false;
		step.seldCanvas.valid = false;

		$('#imageListModal').modal('hide');
	},
	performDesignOption: function(){
		/**
		 * this will perform the design option actions.
		 * the tool must have data-target set to continue
		 */
		var attr  = $(this).attr('data-type');
		var value = '';
		if ($(this).hasClass('dToolOptionButton')){
			// for grouped buttons
			if ($(this).hasClass('groupedOptions')){
				$('.dToolOptionButton[data-type="' + attr + '"]').removeClass('active');
				$(this).addClass('active');
				value = $(this).attr('data-value');
			}
			else{
				$('.dToolOptionButton[data-type="' + attr + '"]').toggleClass('active');
			}

			// Dependent Group Options
			step._selectLayerOptionsGroup();
		}
		else{
			value = $(this).val();
		}
		step.performDesignOptionAction(attr, value);
	},
	_validateOptionValue: function(){
		/**
		 * this will make sure, the option value is not empty
		 */
		var vl 		= $(this).val();
		var attr 	= $(this).attr('data-default');

		if (vl == ''){
			if (typeof attr !== typeof undefined && attr !== false){
				var defaultValue = $(this).attr('data-default');
				$(this).val(defaultValue);				
			}
		}
	},
	performDesignOptionColor: function(target, value){
		/**
		 * this method will deal with colorpicker library to 
		 * first find the target and then update object.
		 */
		var type 	= $('#'+target).attr('data-type');
		var value 	= $('#'+target).val();
		step.performDesignOptionAction(type, value);
	},
	performDesignOptionAction: function(type, value){
		/**
		 * this will perform the design option actions.
		 * the tool must have dataType set to apply action.
		 */
		var shape 	= step.selectionIndex >= 0 ? step.seldCanvas.shapes[step.selectionIndex] : step.seldCanvas;

		switch (type){
			/**
			 * SeldCanvas - Options
			 */
			case 'seldCanvas-bgColor':
				shape.bgColor = value;
				break;

			/**
			 * SeldText - Options
			 */
			case 'seldtext-font':
				shape.fontFamily = value;
				break;
			case 'seldtext-size':
				shape.fontSize = parseInt(value);
				break;
			case 'seldtext-bold':
				shape.fontWeight = shape.fontWeight == 'normal' ? 'bold' : 'normal';
				break;
			case 'seldtext-italic':
				shape.fontStyle = shape.fontStyle == 'normal' ? 'italic' : 'normal';
				break;
			case 'seldtext-align':
				shape.align = value;
				break;
			case 'seldtext-value':
				shape.value = value;
				break;
			case 'seldtext-color':
				shape.color = value;
				break;
			case 'seldtext-shadow':
				shape.shadow = shape.shadow == true ? false : true;
				break;
			case 'seldtext-shadowColor':
				shape.shadowColor = value;
				break;
			case 'seldtext-shadowX':
				var val = parseInt(value);
				//val = val < 0 ? 0 : val > 100 ? 100 : val;
				val = val < 0 ? 0 : val;
				shape.shadowX = val;
				break;
			case 'seldtext-shadowY':
				var val = parseInt(value);
				//val = val < 0 ? 0 : val > 100 ? 100 : val;
				val = val < 0 ? 0 : val;
				shape.shadowY = val;
				break;
			case 'seldtext-shadowBlur':
				var val = parseInt(value);
				val = val < 0 ? 0 : val > 100 ? 100 : val;
				shape.shadowBlur = val;
				break;
			case 'seldtext-stroke':
				shape.stroke = shape.stroke == true ? false : true;
				break;
			case 'seldtext-strokeSize':
				var val = parseInt(value);
				shape.strokeSize = val;
				break;
			case 'seldtext-strokeColor':
				shape.strokeColor = value;
				break;
			case 'seldtext-gradient':
				shape.gradient = shape.gradient == true ? false : true;
				break;
			case 'seldtext-gradientColor':
				shape.gradientColor = value;
				break;
			case 'seldtext-rotation':
				// Min value 0, max value 359
				var vl = isNaN(value) ? 0 : parseInt(value);
				vl = vl < 0 ? 0 : vl > 359 ? 0 : vl;
				shape.rotation = vl;
				break;
			case 'seldtext-opacity':
				// divide value by 100.
				var vl = value / 100;
				shape.opacity = vl;
				break;

			/**
			 * SeldShape - Options
			 */
			case 'seldshape-type':
				shape.type = value;
				break;
			case 'seldshape-color':
				shape.color = value;
				break;
			case 'seldshape-opacity':
				// divide value by 100.
				var vl = value / 100;
				shape.opacity = vl;
				break;
			case 'seldshape-width':
				var vl = Math.ceil(parseInt(value) * 100 / 100);
				shape.width = vl;
				break;
			case 'seldshape-height':
				var vl = Math.ceil(parseInt(value) * 100 / 100);
				shape.height = vl;
				break;
			case 'seldshape-rotation':
				// Min value 0, max value 359
				var vl = isNaN(value) ? 0 : parseInt(value);
				vl = vl < 0 ? 0 : vl > 359 ? 0 : vl;
				shape.rotation = vl;
				break;
			case 'seldshape-borderSize':
				var vl = parseInt(value);
				vl = vl < 0 ? 0 : vl;
				shape.borderSize = parseInt(vl);
				break;
			case 'seldshape-borderColor':
				shape.borderColor = value;
				break;
			case 'seldshape-gradient':
				shape.gradient = shape.gradient == true ? false : true;
				break;
			case 'seldshape-gradientColor':
				shape.gradientColor = value;
				break;

			/**
			 * SeldImage Options
			 */
			case 'seldimage-width':
				var vl = parseInt(value);
				vl = vl < 1 ? 1 : vl;
				shape.width = vl;
				break;
			case 'seldimage-height':
				var vl = parseInt(value);
				vl = vl < 1 ? 1 : vl;
				shape.height = vl;
				break;
			case 'seldimage-rotation':
				var vl = parseInt(value);
				vl 	= vl % 360;
				shape.rotation = vl;
				break;
			case 'seldimage-opacity':
				// divide value by 100.
				var vl = value / 100;
				shape.opacity = vl;
				break;
			case 'seldimage-borderSize':
				var vl = parseInt(value);
				vl = vl < 0 ? 0 : vl;
				shape.borderSize = parseInt(vl);
				break;
			case 'seldimage-borderColor':
				shape.borderColor = value;
				break;
		}

		shape.valid = false;
		step.seldCanvas.valid = false; // req to redraw update.
	},
	selectLayer: function(shapeIndex){
		/**
		 * this will select the current layer and update step.selectedIndex
		 * this will track if current selection is object or NULL.
		 */
		step.selectionIndex = shapeIndex;
		if (shapeIndex >= 0){
			var shape = step.seldCanvas.shapes[shapeIndex];
			// load options
			shape.options();

			$('#design-tools-options').removeClass().addClass('current-' + shape.name);
			// layer selection dependent options
			$('.requireLayerSelection').removeClass('disabled');
			// check visibility of group options
			step._selectLayerOptionsGroup();
		}
		else{
			$('#design-tools-options').removeClass().addClass('current-canvas');
			step.seldSelection = null;
			// layer selection dependent option
			$('.requireLayerSelection').addClass('disabled');
		}
	},
	_selectLayerOptionsGroup: function(){
		/**
		 * this will check the group options display .
		 */
		$('.hasGroup').each(function(){
			var target = $(this).attr('data-target');
			$(this).hasClass('active') ? $(target).removeClass('hidden') : $(target).addClass('hidden');
		});
	},
	toggleLayerVisibility: function(target){
		/**
		 * this will toggle slected layer visibility
		 * ignore type:canvas
		 */
		action = target || 'all';

		if (target == 'current'){
			var obj = step.seldCanvas.shapes[step.selectionIndex];
			if (obj && obj.type != 'canvas'){
				$('#layers li[data-id="' + obj.id + '"] input[type="checkbox"]').trigger('click');
			}			
		}
		else{
			var total = step.seldCanvas.shapes.length;
			for (var i=0; i<total; i++){
				var obj = step.seldCanvas.shapes[i];
				if (obj.name != 'canvas' && obj.page == step.currentPage){
					$('#layers li[data-id="' + obj.id + '"] input[type="checkbox"]').trigger('click');
				}
			}
		}
	},
	_moveSelectedObject: function(direction, m){
		/**
		 * this will move the selected object to 4 directions.
		 * 
		 * mode can be 'normal : 5px', 'snap : to the edges', 'fine : 1px'
		 * ignore the request if any option is currently selected or focused.
		 */
		var focus 	= $('.dToolOptionInput:focus').length;

		if (focus == 0){
			/**
			 * now check if the object selected is not canvas.
			 */

			var shape = step.seldCanvas.shapes[step.selectionIndex];
			if (shape != null && shape.name != 'canvas'){
				
				var mode	= m || 'normal';
				if (mode == 'snap'){
					switch (direction){
						case 'left':
							shape.x = 0;
							break;
						case 'right':
							shape.x = step.seldCanvas.width - shape.width;
							break;
						case 'up':
							shape.y = 0;
							break;
						case 'down':
							shape.y = step.seldCanvas.height - shape.height;
							break;
					}
				}
				else{
					// move by step.
					var stepPixels 	= mode=='normal' ? 5 : 1;
					switch (direction){
						case 'left':
							shape.x -= stepPixels;
							break;
						case 'right':
							shape.x += stepPixels;
							break;
						case 'up':
							shape.y -= stepPixels;
							break;
						case 'down':
							shape.y += stepPixels;
							break;
					}
				}
				// invalidate to re-draw
				shape.valid = false;
				step.seldCanvas.valid = false;
			}			
		}
	},
	save: function(){
		/**
		 * this will save the objects in JSON format.
		 * 
		 * Export Each layer as PNG after saving the content.
		 */
		$('.canvas_file_info.overlay, .canvas_status_saving').removeClass('hidden');
		var data= step.seldCanvas.shapes != null ? JSON.stringify(step.seldCanvas.shapes) : '';

		$.post(base_url()+'m/save/' + step.seld.id, {'title':encodeURIComponent($('#canvas_title').val()), 'desc':encodeURIComponent($('#canvas_description').val()), 'content':data});

		/**
		 * Loop through all the objects, and export as image (1 image per page).
		 */
		step.exportPage(1);

		return false;
	},
	saveValidation: function(){
		if ($('#canvas_title').val() == ''){
			alert('File name is required.');
			$('#canvas_title').focus();
		}
		else{
			step.save();
			// Hide the overlay.
			$('.canvas_file_info').addClass('hidden');
		}
		return false;
	},
	exportPage: function(page){
		if (page <= step.seld.totalPage){

			step.seldCanvas.exportDraw(page);
			var canvas 	= document.getElementById('pad');
			var dataURL = canvas.toDataURL();

			$.ajax({
				type: 	'post',
				url: 	base_url()+'m/saveImage/' + step.seld.id + '/' + page,
				data: 	{  
							imgBase64: dataURL
				},
				success: function(m){
					step.exportPage(++page);
				},
				error: function(){
					alert('Error Exporting Design!');
					$('.canvas_file_info.overlay, .canvas_status_saving').addClass('hidden');
				}
			});
		}
		else{
			// finish exporting.
			setTimeout(function(){
				$('.canvas_file_info.overlay, .canvas_status_saving').addClass('hidden');
				step._loadPage();
			}, 1000);
		}
	},
	openInfo: function(){
		/**
		 * this will open modal for file info.
		 */
		$('#btn_file_info_settings_close, #file_info_save_btn, .canvas_file_info').removeClass('hidden');
		$('#mycol-type, #mycol-options, #mycol-themes').addClass('hidden');
	},
	closeInfo: function(){
		/**
		 * this will close the file info dialog.
		 */
		$('#btn_file_info_settings_close, #file_info_save_btn, .canvas_file_info').addClass('hidden');
	},
	_initTools: function(){
		/**
		 * this method will prepare and initalize tools necessary.
		 *
		 * Initializations has been divided into different sections
		 * Zoom, Left-Menu Options, SeldObject type options and Keyboard mappings
		 * Text Effects.
		 */
		step._initToolsZoom();
		step._initToolsLeftMenuOptions();
		step._initToolsDesignOptions();
		step._initTextPresets();
		step._initKeyboardMapping();
	},
	_initToolsZoom: function(){
		/**
		 * Initialize zoom options
		 *
		 * Canvas Zoom (zoom-in, zoom-out, fit to screen, Original size)
		 */
		$('.glyphicon-plus-sign').click(step.zoomInCanvas);
		$('.glyphicon-minus-sign').click(step.zoomOutCanvas);
		$('#canvas_zoom').change(step.zoomSetCanvas);
		$('.glyphicon-fullscreen').click(step.zoomCanvasFull);
		$('.glyphicon-resize-small').click(step.zoomCanvasFit);
		//$(window).resize(step.zoomCanvasFit);
	},
	_initToolsLeftMenuOptions: function(){
		/**
		 * Initialize left menu actions
		 */
		$('ul#design-tools .dTool').click(step.performMenuAction);

		// close parent triggers
		$('.close_parent').click(function(){
			var target = $(this).attr('data-target');
			$(target).addClass('hidden');

			if (target == '#layer_overlay'){
				localStorage.setItem('tool-layers', 'hidden');
			}
		});

		/**
		 * this will update layer actions
		 * 
		 * Show/hide object, Delete layer or Rename layer(object) title.
		 */
		$('body').on('change', 	'#layers li input[type="checkbox"]', step.layerAction);
		$('body').on('blur', 	'#layers li input[type="text"]', step.layerAction);
		$('body').on('click', 	'#layers li .glyphicon-trash', step.layerAction);

		/**
		 * this will set sortable ability to the layer's list.
		 */
		$('#layers').sortable({update:step._updateLayerCanvas, axis:"y", containment: "parent"});

		/**
		 * this will enable user to navigate through design pages.
		 */
		$('#seldpage-number').change(step._loadPage);

		/**
		 * this will apply the current background color to all pages.
		 */
		$('#apply_background_all').click(step.layerActionApplyBg);

	},
	_initToolsDesignOptions: function(){

		/**
		 * this will save the canvas objects to database
		 */
		$('#saveCanvas, #btnSeldSave').click(step.save);
		
		/**
		 * Open File Info
		 */
		$('#openInfo, #btnSeldSettings').click(step.openInfo);

		/**
		 * save validation
		 */
		$('#btnupdate_file_info').click(step.saveValidation);

		/**
		 * close file info
		 */
		$('#btn_file_info_settings_close').click(step.closeInfo);

		/**
		 * canvas navigation
		 * navigation buttons and pages view.
		 */
		$('li.canvas_pagination').click(function(){
			var ref = $(this).attr('data-type'); 
			if (ref == 'view'){
				// show view modal
				$('#canvas_pages').html('');
				var totalRows	= Math.ceil(step.seld.totalPage/5);
				var height 		= parseInt($('.canvas_pages_view.wrapper').height()) / totalRows - 15;
				for (var i=1; i<=step.seld.totalPage; i++){
					var css = 'height:' + height + 'px;line-height:' + height + 'px;';
					if (totalRows > 3){
						css += 'font-size:12px;';
					}
					var cls = i == step.currentPage ? 'active' : '';
					$('#canvas_pages').append('<li class="'+cls+'" style="' + css + '">' + i + '</li>');
				}
				$('.canvas_pages_view').removeClass('hidden');
			}
			else{
				step.navigatePage(ref);
			}
		});
		$('.canvas_pages_view.overlay').click(function(){
			$('.canvas_pages_view').addClass('hidden');
		});
		$('body').on('click', '#canvas_pages li', function(){
			var newPage = $(this).text();
			$('#seldpage-number option[value="' + newPage + '"]').prop('selected', true);
			$('.canvas_pages_view').addClass('hidden');
			step._loadPage();
		});

		// editor colorpicker
		$('.isColorPicker').colorpicker({format:'hex'}).on('changeColor.colorpicker', function(e){step.performDesignOptionColor(e.target.id)});

		// trigger option change action
		/**
		 * .dToolOptionDropdown  	=> Option Dropdown
		 * .dToolOptionInput 		=> Option Input field
		 * .dToolOptionButton 		=> Option Button
		 */
		$('.dToolOptionDropdown').change(step.performDesignOption);
		$('.dToolOptionInput').keyup(step.performDesignOption);
		$('.dToolOptionInput').blur(step._validateOptionValue);
		$('.dToolOptionButton').click(step.performDesignOption);

		// Presets Close btn
		$('.btn-close-presets').click(function(){ $('#seldtext-viewPresets').trigger('click') });
		
		/**
		 * this will handle the popup display of the list of uploaded images.
		 * plus there willbe a tab to upload image.
		 */
		$('#launch_imageListModal').click(function(){
			$('.seld-nav').css('z-index', 90);
			$('#imageListModal').modal('show');
		});
		$('#imageListModal').on('hidden.bs.modal', function (e) {
			$('.seld-nav').css('z-index', 100);
		});

		/**
		 * Load image to url
		 */
		$('#select_image_preview').click(step.loadImage);

		$('body').on('dblclick', 	'#my-images-list li', step.loadImage);
		$('body').on('click', 		'#select_image_preview', step.loadImage);
		$('body').on('click', 		'#my-images-list li', function(){
			$('#my-images-list li, 	#select_image_preview').removeClass();
			$(this).addClass('active');
			// preview.
			var src = $(this).find('img').attr('src');
			var w 	= $(this).find('img').attr('data-width');
			var h 	= $(this).find('img').attr('data-height');

			$('#select_image_preview img').attr({'src':src, 'data-width':w, 'data-height':h});
		});

		// image uploader
		fileUpload.init();
		$("#image_uploader").uploadFile(fileUpload.settings());
	},
	_initTextPresets: function(){
		/**
		 * this will load the text presets
		 *
		 * color, fontFamily, fontWeight, fontStyle, gradient, gradientColor, Stroke, StrokeWidth, Shadow, shadowColor, shadowX, shadowY, shadowBlur
		 */

		//    		0 			1			2			3		4			5			6		7				8			9			10			11		12		13
		// 			color, 	fontFamily, fontWeight, fontStyle, gradient, gradientColor, Stroke, Stroke, 	StrokeColor   Shadow, shadowColor, 		scX, 	Y		Blur
		presets = [
				['#0080FF', 'Arial', 	'normal',	'normal', 	true, 	'#000000', 		false, 	0, 			'#ffffff', 		true, 	'#444444', 		0, 		5,		5],
				['#000000', 'Arial', 	'normal',	'normal', 	false, 	'#000000', 		true, 	5, 			'#ffffff', 		true, 	'#aaaaaa', 		0, 		5,		5],
				['#EE313C', 'Georgia', 	'normal',	'normal', 	false, 	'#000000', 		true, 	10,			'#ffffff', 		true, 	'#CCCCCC', 		0, 		5,		10],
				['#C42D22', 'Georgia', 	'normal',	'normal', 	true, 	'#ED593E', 		false, 	0,			'#ffffff',		true, 	'#AAAAAA', 		10, 	5,		12],
				['#4CC8F3', 'Georgia', 	'normal',	'normal', 	false, 	'#000000', 		true, 	10,			'#4863AC',		true, 	'#4965AC', 		0, 		5,		10],

				['#1C3B89', 'Georgia', 	'normal',	'normal', 	true, 	'#41C6F2', 		false, 	0,			'#000000',		true, 	'#D4C027', 		0, 		5,		10],
				['#FFD703', 'Georgia', 	'normal',	'normal', 	true, 	'#EBB009', 		true, 	5,			'#873021',		true, 	'#873021', 		0, 		10,		4],
				['#0298D5', 'Georgia', 	'normal',	'normal', 	false, 	'#000000', 		true, 	8,			'#ffffff',		true, 	'#7a7a7a', 		0, 		0,		20],
			];

		for (var i=0; i<presets.length; i++){

			var txt 			= new SeldText(10, 10, 'Seld Editor');
			txt.fontSize 		= 20;
			txt.align 			= 'center';

			txt.color 			= presets[i][0];
			//txt.fontFamily		= presets[i][1];
			txt.fontWeight		= presets[i][2];
			txt.fontStyle		= presets[i][3];
			txt.gradient 		= presets[i][4];
			txt.gradientColor 	= presets[i][5];

			txt.stroke 			= presets[i][6];
			txt.strokeSize		= presets[i][7];
			
			txt.strokeColor 	= presets[i][8];
			txt.shadow 			= presets[i][9];
			txt.shadowColor		= presets[i][10];
			txt.shadowX			= presets[i][11];
			txt.shadowY			= presets[i][12];
			txt.shadowBlur		= presets[i][13];

			// save the presets
			step.presets.push(txt);

			// list..
			var li 	= '<li data-index="' + i + '"><canvas id="presetCanvas' + i + '" width="200" height="50"></canvas></li>';
			$('#seldtext-presetsList').append(li);

			var canvas = document.getElementById('presetCanvas'+i);
			var context = canvas.getContext('2d');

			txt.draw(context);
		}

		/**
		 * this will select the preset and copy the properties to the 
		 * current SeldText object.
		 */
		$('body').on('click', '#seldtext-presetsList li', function(){
			$('#seldtext-presetsList li').removeClass('active');
			$(this).addClass('active');

			var i = $(this).attr('data-index');
			var txt = step.presets[i];

			// shape 
			var ref = step.seldCanvas.shapes[step.selectionIndex];
			if (ref){
				// copy styles.
				// 	color, 	fontFamily, fontWeight, fontStyle, gradient, gradientColor, Stroke, StrokeWidth, StrokeColor   Shadow, shadowColor, scX, Y Blur
				ref.color 				= txt.color;
				ref.fontFamily 			= txt.fontFamily;
				ref.fontWeight 			= txt.fontWeight;
				ref.fontStyle 			= txt.fontStyle;

				ref.gradient 			= txt.gradient;
				ref.gradientColor 		= txt.gradientColor;

				ref.stroke 				= txt.stroke;
				ref.strokeColor 		= txt.strokeColor;	
				ref.strokeSize 			= txt.strokeSize;

				ref.shadow 				= txt.shadow;
				ref.shadowColor 		= txt.shadowColor;
				ref.shadowBlur 			= txt.shadowBlur;
				ref.shadowX 			= txt.shadowX;
				ref.shadowY 			= txt.shadowY;				

				// invalidate.
				ref.options();
				ref.valid 				= false;
				step.seldCanvas.valid 	= false;
			}
		});
	},
	_initKeyboardMapping: function(){
		/**
		 * this will allow the trigger of events on keyStrokes.
		 */

		var ref = $(document);

		// Save Key
		ref.bind('keydown', 'ctrl+s', function(e){step.save();e.preventDefault()});

		// Delete Layer
		ref.bind('keydown', 'del', function(e){$('.dTool[data-type="delete"]').trigger('click');e.preventDefault()});

		// Copy Layer
		ref.bind('keydown', 'ctrl+c', function(e){$('.dTool[data-type="copy"]').trigger('click');e.preventDefault()});

		// Paste layer
		ref.bind('keydown', 'ctrl+v', function(e){$('.dTool[data-type="paste"]').trigger('click');e.preventDefault()});

		// New Text
		//ref.bind('keydown', 'ctrl+e', function(e){$('.dTool[data-type="text"]').trigger('click');e.preventDefault()});
		// New Image
		//ref.bind('keydown', 'ctrl+i', function(e){$('.dTool[data-type="image"]').trigger('click');e.preventDefault()});

		// Layers View
		ref.bind('keydown', 'ctrl+l', function(e){$('.dTool[data-type="layers"]').trigger('click');e.preventDefault()});

		// File information
		ref.bind('keydown', 'ctrl+i', function(e){$('.dTool[data-type="info"]').trigger('click');e.preventDefault()});

		// Hide current layer
		ref.bind('keydown', 'ctrl+k', function(e){step.toggleLayerVisibility('current');e.preventDefault()});

		// Alter Visibility of all the layers with one key stroke.
		ref.bind('keydown', 'alt+a', function(e){step.toggleLayerVisibility();e.preventDefault()});

		// View 100% - Original Size of canvas
		ref.bind('keydown', 'ctrl+f', function(e){step.zoomCanvasFull();e.preventDefault()});

		// fit to screen
		ref.bind('keydown', 'ctrl+q', function(e){step.zoomCanvasFit();e.preventDefault()});

		// view pages
		ref.bind('keydown', 'alt+v', function(e){$('.canvas_pagination[data-type="view"]').trigger('click');e.preventDefault()})

		/**
		 * this will navigate current design page. next and prev.
		 */
		ref.bind('keydown', 'alt+right', 	function(e){ step.navigatePage('next');e.preventDefault() });
		ref.bind('keydown', 'alt+down',		function(e){ step.navigatePage('next');e.preventDefault() });
		ref.bind('keydown', 'alt+left',		function(e){ step.navigatePage('prev');e.preventDefault() });
		ref.bind('keydown', 'alt+up', 		function(e){ step.navigatePage('prev');e.preventDefault() });


		/**
		 * this will move object to 4 directions.
		 * modes can be normal (5px), fine (1px) and snap (to the edges of canvas)
		 */
		ref.bind('keydown', 'left', 		function(e){ step._moveSelectedObject('left')});
		ref.bind('keydown', 'right', 		function(e){ step._moveSelectedObject('right')});
		ref.bind('keydown', 'up', 			function(e){ step._moveSelectedObject('up')});
		ref.bind('keydown', 'down', 		function(e){ step._moveSelectedObject('down')});

		ref.bind('keydown', 'shift+left', 	function(e){ step._moveSelectedObject('left', 'snap')});
		ref.bind('keydown', 'shift+right', 	function(e){ step._moveSelectedObject('right', 'snap')});
		ref.bind('keydown', 'shift+up', 	function(e){ step._moveSelectedObject('up', 'snap')});
		ref.bind('keydown', 'shift+down', 	function(e){ step._moveSelectedObject('down', 'snap')});

		ref.bind('keydown', 'ctrl+left', 	function(e){ step._moveSelectedObject('left', 'fine')});
		ref.bind('keydown', 'ctrl+right', 	function(e){ step._moveSelectedObject('right', 'fine')});
		ref.bind('keydown', 'ctrl+up', 		function(e){ step._moveSelectedObject('up', 'fine')});
		ref.bind('keydown', 'ctrl+down', 	function(e){ step._moveSelectedObject('down', 'fine')});
	},
	initCreate: function(){
		/**
		 * this will initialize tools required while creating new design.
		 */
		
		// get canvas properties
		var o 					= $('#design-pages');
		step.seld.id 			= o.attr('data-ref');

		$('.canvas_file_info').removeClass('hidden');

		// this will load the options
		$('#file_design_type li').click(function(){
			$('#file_design_type li').removeClass('active');
			$(this).addClass('active');

			var ref = $(this).attr('data-ref');
			$.ajax({
				type: 'post',
				data: '',
				url: base_url() + 'm/ajax/load-options/' + ref,
				success: function(data){
					$('.mycol:eq(1) .mycol-container').html(data);

					formSettings.init();
				},
				failure: function(){
					alert('Unable to load options.');
				}
			});
		});

		/**
		 * this will display the themes for selected settings.
		 */
		$('body').on('click', '#canvas_file_options_selection', function(){
			
			// display themes if available.
			$('#mycol-type, #mycol-options').addClass('hidden');
			$('#mycol-themes').removeClass('hidden');
			
			// load themes.
			var ref = parseInt($('#file_design_type li.active').attr('data-ref'));
			var sz 	= $('select[name="set-size"]').val();

			if (ref > 0){

				// Save Options
				/**
				 * Remove un-selected options before serializing.
				 */
				$('form#frm_canvas_options .form-group.hidden').remove();
				var dt 		= $('form#frm_canvas_options').serialize();
				var id 		= $('#design-pages').attr('data-ref');
				$.post(base_url()+'m/save/' + id + '/options', dt);

				$('#mycol-themes .mycol-container').html('Loading themes...');
				$.ajax({
					type: 'post',
					data: '',
					url: base_url() + 'm/ajax/load-themes/' + ref + '/' + sz,
					success: function(data){
						if (data == ''){
							$('#mycol-themes').addClass('hidden');
							$('#file_info_save_btn').removeClass('hidden');
						}
						else{
							$('#mycol-themes .mycol-container').html(data);
						}
					},
					failure: function(){
						alert('Unable to load themes.');
					}
				});

				return false;
			}
		});

		/**
		 * when theme is selected.
		 */
		$('body').on('click', '.btn-select-theme', function(){

			var ref 	= $(this).attr('data-ref');
			var type 	= $('#file_design_type li.active').attr('data-ref'); 
			$('#mycol-themes').addClass('hidden');
			$('#file_info_save_btn').removeClass('hidden');

			// update db
			var id 		= $('#design-pages').attr('data-ref');
			$.post(base_url()+'m/save/' + id + '/general', {type:type, th_id:ref});
		});

		// go back to type section
		$('#btn-back-type').click(function(){

			$('#mycol-type, #mycol-options').removeClass('hidden');
			$('#mycol-themes').addClass('hidden');
		});

		// click on save button
		$('#btnupdate_file_info').click(function(){
			if ($('#canvas_title').val() == ''){
				alert('File name is required.');
				$('#canvas_title').focus();
			}
			else{
				step.save();
				// Hide the overlay.
				$('.canvas_file_info').addClass('hidden');

				location.reload(true);
			}
			return false;
		});

		// display type1 options
		$('#file_design_type li:eq(0)').trigger('click');
	},
	init: function(){
		step.prepareCanvas();

		// Check cache history for loading defaults
		if (localStorage.getItem('tool-layers') == 'visible'){
			$('#layer_overlay').removeClass('hidden');
		}
	}
}