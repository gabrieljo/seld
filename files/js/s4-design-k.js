var step = {
	totalPages:1,
	scale:1,
	currentPageIndex:0,
	width:500,
	height:400,
	initialTrim: function(){
		// Initial saved layers TRIM
		$('.layer').find('.ui-resizable-handle').remove();
	},
	designMenu: function(){
		w = parseInt($('.aside').width())-61;
		$('#design-tools-wrapper').css('width', w+'px')
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
		$('#layers').sortable({update:step.updateLayerPosition, axis:"y", containment: "parent", handle:"glyphicon-move"});
	},
	updateLayerPosition: function(){
		// Update Layer Order and layer's z-indexes
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
			}
			else{
				$('#design-tools-options').removeClass('current-text').addClass('current-image');
			}
			$('#design-tools-options-wrapper').removeClass('hidden');
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
			case "save":
				step.designToolSave();
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
		var h = '<div id="layer-'+id+'" class="layer layer-text-container" data-type="text"><span class="glyphicon glyphicon-move"></span><textarea data-content="" placeholder="Write here..."></textarea></div>';
		$('#pad').append(h);
		step.updateLayer();
	},
	designToolImage: function(){
		var o = $('ul#design-pages li:eq(' + step.currentPageIndex + ')');
		var id = Date.now();
		var h = '<div id="layer-'+id+'" class="layer layer-image-container" data-type="image"><span class="glyphicon glyphicon-move"></span></div>';
		$('#pad').append(h);
		step.updateLayer();
	},
	designToolSave: function(){
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
					step.layerToolBold(ref);
					break;
				case "italic":
					step.layerToolItalic(ref);
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
					console.log(vl);
					step.layerToolFontColor(ref, vl);
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
	layerToolBold: function(id){
		var ref = $('#'+id).find('textarea');
		var val = ref.css('font-weight');
		(val == "bold") ? ref.css('font-weight', 'normal') : ref.css('font-weight', 'bold');
	},
	layerToolItalic: function(id){
		var ref = $('#'+id).find('textarea');
		var val = ref.css('font-style');
		(val == "italic") ? ref.css('font-style', 'normal') : ref.css('font-style', 'italic');
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
	layerToolUpload: function(){
		console.log('here');
	},
	layerToolFiles: function(){
		console.log('here');
	},
	init: function(){
		step.initialTrim();

		$('#tools').addClass('design-menu');
		step.designMenu();
		$(window).resize(step.designMenu);
		step.prepareCanvas();
		// menu
		$('.aside').on('click', 'ul#layers li', step.setActiveLayer);
		$('.aside').on('change', 'ul#layers li input[type="checkbox"]', step.setLayerVisibility);
		// design tools
		$('ul#design-tools .dTool').click(step.useTool);

		$('ul#design-tools-options .dToolOption').click(step.useToolOption);
		$('ul#design-tools-options .dToolOptionDropdown').change(step.useToolOption);
		// Color Picker
		$('.dToolOptionFontColor').colorpicker({format:false}).on('changeColor.colorpicker', function(e){step.layerToolFontColor()});

		// layers
		step.setLayerSortable();
		$('#pad').on('click', '.layer', step.setActivePad);
		$('body').on('blur', '.layer textarea', step.updateLayerText);
		//$('#canvas').on('click', '*:not(.layer, .layer textarea)', step.setActivePadReset);
		$('.aside').on('click', '#layers .glyphicon-trash', step.deleteLayer);

		// Events for saved layers
		$('.layer').draggable({containment: "#canvas"});
		$('.layer').resizable({autoHide: true});
	}
};