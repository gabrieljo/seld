/**
 * SELD Creative Editor
 * @author 	Sudarshan Shakya
 * @date  	2015-12-31
 * 
 * SELD Creative Editor is HTML5 Canvas based editor for designers.
 * 	The designing will be saved in binary form.
 * 
 */

var design = {
	shapes: [], 		// queued shapes.
	ctx: null, 			// drawing context
	currentPage:1,		// current page number to publish
	totalPages:1,		// total pages to print
	totalObjects:0, 	//
	bufferTime: 3000,	// time between drawing two objects
	loadContent: function(){
		/**
		 * this will load the contents.
		 */
		var o 			= $('#design-pages');
		var width 		= parseInt(o.data('width'));
		var height 		= parseInt(o.data('height'));
		var pages 		= parseInt(o.data('pages'));
		var faces 		= parseInt(o.data('faces'));

		$('#pad').attr({'width':width, 'height':height});

		var canvas = document.getElementById('pad');
		design.ctx = canvas.getContext('2d');

		// load data next.

		var data 	= $('#design-pages').html();
		var shapes 	= [];

		/**
		 * load the SeldPage instances first.
		 */
		var total 			= pages * faces;
		design.totalPages 	= total;

		for (var i=1; i<=total; i++){
			var seld = new SeldPage(width, height, i);
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
				else if (obj.delete == false && obj.visibility=='visible'){
					var seld = obj.name == 'text' ? new SeldText() : obj.name == 'image' ? new SeldImage() : new SeldShape();

					for (var key in obj){
						seld[key] = obj[key];
					}
					seld.valid = false;
					/**
					 * Reset image Object for initial loading.
					 */
					if (seld.name == 'image'){
						seld.myImage = null;
					}
					//step.seldCanvas.addShape(seld);
					shapes.push(seld);
				}
			}
		}
		// update seld shapes
		design.shapes = shapes;
	},
	__publishNode: function(index){
		if (index < design.shapes.length){
			
			var obj = design.shapes[index];
			design.__showProgress(design.totalObjects, design.shapes.length);

			if (obj.name == 'shape' || obj.name == 'text' || obj.name == 'image'){

				if (obj.name == 'image' && parseInt(obj.rotation) == 0){
					// do nothing
				}
				else{
					design.ctx.clearRect(0, 0, design.ctx.canvas.width, design.ctx.canvas.height);
					obj.draw(design.ctx);
					design.__saveImage(obj.id);
				}
			}

			design.totalObjects++;
			setTimeout(function(){
				design.__publishNode(++index);
			}, design.bufferTime);
		}
		else{
			design.__publish();
		}
	},
	__showProgress: function(current, total){
		if (total > 0){
			var share = Math.ceil(100 / total);
			var progress = share * current;
			progress = progress < 0 ? 0 : progress > 100 ? 100 : progress;
			var ref = $('#progress-bar');
			ref.find('.progress-bar').attr('aria-valuenow', progress).css('width', progress+'%');
			$('#progress-status').text(progress+'% Complete');
		}
	},
	__saveImage: function(page){
		var canvas 	= document.getElementById('pad');
		design.ctx 	= canvas.getContext('2d');

		var dataURL = canvas.toDataURL();
		$.ajax({
			type: "POST",
			url: base_url() + "u/saveimg",
			data:{  
					folder: $('#design-pages').attr('data-folder'),
					name: page,
					imgBase64: dataURL
				}
			}).done(function(o){
		});
	},
	__publish: function(){
		$('#progress-bar, #progress-status').remove();

		/*var folder = $('#design-pages').attr('data-folder');
		var li = '';
		for (var i=0; i<design.totalPages; i++){
			random = Math.random();
			li += '<li><img src="' + base_url() + 'files/products/' + folder + '/page-' + (i+1) + '.png?req=' + random  +'" /></li>';
		}
		$('#preview_publish').html(li);*/
	},
	init: function(){
		design.loadContent();

		$('#publish_now').click(function(){
			$('.progress').removeClass('hidden');
			design.__publishNode(0);
		});

	}
}