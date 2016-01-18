/**
 * SELD Creative Editor
 * @author 	Sudarshan Shakya
 * @date  	2015-12-31
 * 
 * SELD Creative Editor is HTML5 Canvas based editor for designers.
 * 	The designing will be saved in binary form.
 * 
 */

/**
 * SELD Creative Editor
 * @author 	Sudarshan Shakya
 * @date  	2015-12-31
 * 
 * SELD Creative Editor is HTML5 Canvas based editor for designers.
 * 	The designing will be saved in binary form.
 * 
 * All the functions will be within the object named "Step".
 * Only private function's name will being with underscore "_".
 * Private functions only accessible from another private function
 * 		begins with double underscore "__".
 */


/**
 * ===================================================================================================================
 * S E L D   P A G E ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * ===================================================================================================================
 */
function SeldPage(w, h, p, c){
	/**
	 * SeldPage
	 *
	 * this contains the meta information of the Page
	 * Can only be auto-initialized, and not from any action or user.
	 */
	this.name 		= 'canvas';
	this.bgColor 	= c || '#FFFFFF'; 	// background color of canvas
	this.page 		= p || 1; 			// canvas page number
	this.width 		= w || 0; 			// canvas width
	this.height 	= h || 0; 			// canvas height
	this.valid 		= false; 			// if false, it will be re-printed. // need to set it as true after drawing.

	this.delete 	= false;
	this.visibility = 'visible';
}
SeldPage.prototype.draw = function(ctx){
	/**
	 * this method will paint the background of the canvas.
	 */
	ctx.rect(0, 0, this.width, this.height);
	ctx.fillStyle = this.bgColor;
	ctx.fill();
}

/**
 * ===================================================================================================================
 * S E L D   T E X T ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * ===================================================================================================================
 */
function SeldText(x, y, v, fill){
	/**
	 * SeldText
	 * this function holds the information of SELD TEXT Field.
	 */
	this.valid 		= false; 			// if false, it will be re-printed. // need to set it as true after drawing.
	this.id 			= createID();	// Layer ID
	this.name 			= 'text';		// used to determine when clicked on canvas.
	this.title 			= 'New Text';	// Used for user to identify layer.
	this.visibility 	= 'visible'; 	// used for show/hide of object
	this.delete 		= false; 		// this is used as flag for deleting object.
	this.page 			= 1; 			// assigned to design page number	
	this.x 				= x || 0; 		// position x
	this.y 				= y || 0;		// position y
	this.rotation 		= 0;			// in degrees.
	this.opacity 		= 1;
	this.width			= 100;			// width
	this.height			= 50; 			// height

	this.value 			= v || 'Type Here..';// Text Value
	this.color 			= fill || '#000000';// Default text color
	this.fontFamily		= 'Arial'; 		// font family
	this.fontSize 		= 20; 			// font Size
	this.lineHeight		= 20;			// Line Height
	this.fontWeight		= 'normal'; 	// Font Weight
	this.fontStyle 		= 'normal';		// font Style
	this.align 			= 'left';		// alignment: left, right, center
	this.angle 			= 0; 			// rotation angle.

	this.stroke			= false; 		// fill Style
	this.strokeSize 	= 5; 			// Stroke Width
	this.strokeColor 	= '#000000'; 	// stroke color.
	
	this.shadow 		= false;		// true/false for text shadow
	this.shadowColor 	= '#000000'; 	// shadow color
	this.shadowX 		= 2; 			// shadow offset x
	this.shadowY 		= 2;			// shadow offset y
	this.shadowBlur 	= 5;			// shadow blur

	this.gradient 		= false; 		// determine if the text must be gradient fill.
	this.gradientColor 	= '#FF0000'; 	// second end of gradient color.
}
SeldText.prototype.draw = function(ctx){
	/**
	 * this will draw the text to the canvas.
	 *
	 */
	var font = this.fontStyle 	== 'normal' ? '' : 'italic ';
	font 	+= this.fontWeight 	== 'normal' ? '' : ' bold ';
	font 	+= this.fontSize + 'px ' + this.fontFamily;
	
	ctx.font 		= font;	
	ctx.globalAlpha = this.opacity;
	this.lineHeight = this.fontSize; // padding
	ctx.textAlign 	= this.align;

	// Keep account for line-breaks, need to find maxWidth
	var allText 	= this.value.split("\n");
	var maxWidth 	= 0;
	for (i=0; i<allText.length; i++){
		var width 	= ctx.measureText(allText[i]).width;
		maxWidth 	= width > maxWidth ? width : maxWidth;
	}

	//Re-calculate width and height.
	this.width 	= maxWidth;
	this.height = allText.length * this.lineHeight + (this.lineHeight * 0.5);

	/**
	 * Draw as per the rotation requested.
	 *
	 * Save the canvas instance, translate as required,
	 * and restore the instance.
	 */
	ctx.save();

	var deg = this.rotation % 360;
	var rad = deg * Math.PI / 180;

	ctx.translate(this.x+this.width/2, this.y+this.height/2);
	ctx.rotate(rad);

	// Text Shadow Options
	if (this.shadow){
		ctx.shadowColor 	= this.shadowColor;
		ctx.shadowOffsetX 	= this.shadowX;
		ctx.shadowOffsetY 	= this.shadowY;
		ctx.shadowBlur 		= this.shadowBlur;
	}

	// Text Gradient
	if (this.gradient){
		// x1, y1, x2, y2.
		var gradient = ctx.createLinearGradient(-this.width/2, -this.height/2, this.width, this.height);
		gradient.addColorStop(0, this.color);
		gradient.addColorStop(1, this.gradientColor);
		// addColorStop(0.5 , 'green');

		ctx.fillStyle = gradient;
	}
	else{
		ctx.fillStyle 	= this.color;
	}

	/**
	 * Alignment Fix.
	 */
	//var alignFix = this.align == 'left' ? this.x : (this.align == 'center' ? (this.x+maxWidth/2) : this.x+maxWidth);
	var alignFix = this.align == 'left' ? -this.width/2 : (this.align == 'center' ? (-this.width/2+maxWidth/2) : -this.width/2+maxWidth);

	for (i=0; i<allText.length; i++){
		//var y = this.y + (this.lineHeight * (i+1));
		var y = -this.height/2 + (this.lineHeight * (i+1));

		if (this.stroke){
			ctx.strokeStyle = this.strokeColor;
			ctx.lineWidth 	= this.strokeSize;
			ctx.strokeText(allText[i], alignFix, y);
		}
		ctx.fillText(allText[i], alignFix, y);
	}	
	ctx.restore();
}

/**
 * ===================================================================================================================
 * S E L D   I M A G E ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * ===================================================================================================================
 */
function SeldImage(x, y, w, h, fill){
	/**
	 * SeldText
	 * this function holds the information of SELD Image Field.
	 */
	this.valid 			= false; 			// if false, it will be re-printed. // need to set it as true after drawing.
	this.id 			= createID();
	this.name 			= 'image';
	this.title 			= 'New Image';	// Used for user to identify layer.
	this.visibility 	= 'visible';
	this.delete 		= false; 		// this is used as flag for deleting object.
	this.page 			= 1;
	this.x 				= x || 0; 		// position x
	this.y 				= y || 0;		// position y
	this.rotation 		= 0;			// in degrees.
	this.opacity 		= 1;

	this.myImage		= null;

	this.width			= w || 100;		// width
	this.height			= h || 100;		// height
	this.oWidth 		= 100; 			// Original width of image
	this.oHeight  		= 100; 			// Original height of image.
	this.color 			= fill || '#FFFFFF';// Default image color
	this.src 			= ''; 			// Source of image file

	this.borderSize 	= 0; 			// Stroke size.
	this.borderColor 	= '#555555';
}
SeldImage.prototype.draw = function(ctx){
	/**
	 * this will draw the image to the canvas.
	 *
	 * need to create variables, as imageObj objet will overload 'this' 
	 *   	originally refering to the SeldImage object.
	 */
	var draw 	= this;
	var x = draw.x;
	var y = draw.y;
	var w = draw.width;
	var h = draw.height;
	var ow= draw.oWidth;
	var oh= draw.oHeight;
		
	ctx.globalAlpha = this.opacity;

	// create image object for first 
	/*if (draw.myImage == null){
		var imageObj = new Image();
		imageObj.onload = function(){			
			// src, sx, sy, sw, sh, dx, dy, dw, dh
			//ctx.drawImage(imageObj, 0, 0, ow, oh, x, y, w, h);
			ctx.drawImage(imageObj, 0, 0, ow, oh, -w/2, -h/2, w, h);

			// req for re-draw after image load.
			draw.valid = false;
		}
		imageObj.src = draw.src;
		draw.myImage = imageObj;
	}*/

	/**
	 * Draw as per the rotation requested.
	 *
	 * Save the canvas instance, translate as required,
	 * and restore the instance.
	 */
	ctx.save();

	var deg = draw.rotation % 360;
	var rad = deg * Math.PI / 180;

	ctx.translate(draw.x+draw.width/2, draw.y+draw.height/2);
	ctx.rotate(rad);

	if (draw.src == ""){
		ctx.fillStyle 	= draw.color;
		ctx.fillRect(-this.width/2, -this.height/2, w, h);
	}
	else{
		//var imageObj = draw.myImage;
		//if (imageObj != null) ctx.drawImage(imageObj, 0,0, ow, oh, -this.width/2, -this.height/2, w, h);

		var imageObj = new Image();
		imageObj.src = draw.src;
		ctx.drawImage(imageObj, 0, 0, ow, oh, -w/2, -h/2, w, h);
	}

	/**
	 * draw a border around image if requested.
	 */
	if (this.borderSize > 0){
		ctx.lineWidth 	= this.borderSize;
		ctx.strokeStyle = this.borderColor;
		
		ctx.strokeRect(-this.width/2-this.borderSize/2, -this.height/2-this.borderSize/2, this.width+this.borderSize, this.height+this.borderSize);
	}

	// restore
	ctx.restore();
}

/**
 * ===================================================================================================================
 * S E L D   S H A P E ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * ===================================================================================================================
 */
function SeldShape(x, y, w, h, color){
	/**
	 * SeldShape
	 * this function holds the information of SELD shape Field.
	 */
	this.valid 			= false; 		// if false, it will be re-printed. // need to set it as true after drawing.
	this.id 			= createID();
	this.name 			= 'shape';
	this.title 			= 'New Shape';	// Used for user to identify layer.
	this.visibility 	= 'visible';
	this.delete 		= false; 		// this is used as flag for deleting object.
	this.page 			= 1;

	this.x 				= x || 0; 		// position x
	this.y 				= y || 0;		// position y
	this.rotation 		= 0;			// in degrees.
	this.type 			= 'rectangle';	// rectangle, circle.

	this.width			= w || 100;		// width
	this.height			= h || 100;		// height
	this.color 			= color || '#FFFF00';// Default color
	
	this.borderSize 	= 1; 			// Stroke size.
	this.borderColor 	= '#000000';

	this.gradient 		= false;		// fill gradient
	this.gradientColor 	= '#ffffff';

	this.opacity 		= 1;
}
SeldShape.prototype.draw = function(ctx){
	/**
	 * this will draw the shape to the canvas.
	 */
	ctx.fillStyle 	= this.color;
	ctx.globalAlpha = this.opacity;


	// gradient fill
	if (this.gradient == true){
		var gradient = ctx.createLinearGradient(-this.width/2, -this.height/2, this.width, this.height);
		gradient.addColorStop(0, this.color);
		gradient.addColorStop(1, this.gradientColor);			
		
		ctx.fillStyle = gradient;
	}
	
	/**
	 * Draw as per the rotation requested.
	 *
	 * Save the canvas instance, translate as required,
	 * and restore the instance.
	 */
	ctx.save();

	//var deg = this.rotation < 0 || this.rotation > 359 ? 0 : this.rotation;
	var deg = this.rotation % 360;
	var rad = deg * Math.PI / 180;

	ctx.translate(this.x+this.width/2, this.y+this.height/2);
	ctx.rotate(rad);

	if (this.type == 'circle'){
		// take radius as the smaller size between height and width.
		var ref 	= this.width > this.height ? this.height : this.width;
		var radius 	= ref/2;

		// draw circle
		ctx.beginPath();
		ctx.arc(0, 0, radius, 0, 2*Math.PI, false);
		ctx.fill();
		
		if (this.borderSize > 0){
			ctx.lineWidth 	= this.borderSize;
			ctx.strokeStyle = this.borderColor;			
			ctx.stroke();
		}
	}
	else{
		// draw Rect
		ctx.fillRect(-this.width/2, -this.height/2, this.width, this.height);

		if (this.borderSize > 0){
			ctx.lineWidth 	= this.borderSize;
			ctx.strokeStyle = this.borderColor;

			ctx.strokeRect(-this.width/2-this.borderSize/2, -this.height/2-this.borderSize/2, this.width+this.borderSize, this.height+this.borderSize);
		}
	}

	// restore
	ctx.restore();
}

var design = {
	shapes: [], 		// queued shapes.
	ctx: null, 			// drawing context
	currentPage:1,		// current page number to publish
	totalPages:1,		// total pages to print
	totalObjects:0, 		//
	bufferTime: 100,		// time between drawing two objects
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
		var total 		= pages * faces;
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

		design.__drawObject(1, 0);
	},
	__drawObject: function(page, index){		
		if (index < design.shapes.length){
			var obj = design.shapes[index];
			design.__showProgress(design.totalObjects, design.shapes.length);

			if (obj.page == page){
				// same page contents.
				if (obj.name == 'image'){
					// need timeout to make sure image was loaded.
					var img = new Image();
					img.src = obj.src;
					$(img).on('load', function(){
						design.totalObjects++;
						obj.draw(design.ctx);
						setTimeout(function(){
							design.totalObjects++;
							design.__drawObject(page, ++index);
						}, design.bufferTime);
					});
				}
				else{
					obj.draw(design.ctx);
					setTimeout(function(){
						design.totalObjects++;
						design.__drawObject(page, ++index);
					}, design.bufferTime);
				}
			}
		}
		else if(page <= design.totalPages){
			// push action to save image.
			design.__saveImage(page);

			// Clear context.
			design.ctx.clearRect(0, 0, this.width, this.height);

			setTimeout(function(){
				design.totalObjects++;
				design.__drawObject(++page, 0);
			}, design.bufferTime);
		}

		if (design.totalObjects >= design.shapes.length){
			setTimeout(function(){
				design.__publish();
			}, design.bufferTime);
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

		var folder = $('#design-pages').attr('data-folder');
		var li = '';
		for (var i=0; i<design.totalPages; i++){
			random = Math.random();
			li += '<li><img src="' + base_url() + 'files/products/' + folder + '/page-' + (i+1) + '.png?req=' + random  +'" /></li>';
		}
		$('#preview_publish').html(li);
	},
	init: function(){
		design.loadContent();
	}
}

function createID(){
	/**
	 * this will create unique ID for the layer. 
	 *  	this is necessary for #layers to identify object.
	 */ 
	return 'layer-' + Date.now();
}