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


function checkContain(obj, mx, my){
	/**
	 * this method will check if the given mouseclick lies on any object or not.
	 * prototype method by objects (SeldText, SeldImage and SeldShape) only.
	 *
	 * Containment requires co-ordinates translation.
	 */

	var deg 	= obj.rotation;
	var angle 	= deg * Math.PI / 180;

	mx -= Math.floor(obj.x+obj.width/2);
	/**
	 * correction for type SeldText and alignment.
	 */
	if (obj.name == 'text'){

	}
	my -= Math.floor(obj.y+obj.height/2);

	mx = Math.floor(mx * Math.cos(-angle) - my * Math.sin(-angle));
	my = Math.floor(mx * Math.sin(-angle) + my * Math.cos(-angle));

	//var ret = obj.x <= mx && (obj.x + obj.width >= mx) && obj.y <= my && (obj.y + obj.height >= my);
	var objx = - obj.width/2;
	var objy = -obj.height/2;
	return objx <= mx && (objx + obj.width >= mx) && objy <= my && (objy + obj.height >= my);
}
function checkContainHandle(oldX, oldY){
	/**
	 * this will check if click contains any handles
	 * handles include, rotation and resizing.
	 */
	var resize 		= -1; // 0~7
	var rot 		= false;
	var handles 	= step.seldCanvas.handles;

	for (var i=0; i<handles.length; i++){
		var handle 		= handles[i];

		var deg 	= handle.translation.angle;
		var angle 	= deg * Math.PI / 180;
		
		// optimize x,y for translation.
		_x = oldX - parseInt(handle.translation.x);
		_y = oldY - parseInt(handle.translation.y);
		
		/**
		 * Here the co-ordinates x,y needs to be transformed.
		 */
		mx = Math.floor(_x * Math.cos(-angle) - _y * Math.sin(-angle));
	  	my = Math.floor(_x * Math.sin(-angle) + _y * Math.cos(-angle));

	  	/**
	  	 * add padding correction to rotation handle
	  	 * double the selection-area.
	  	 */
	  	var handleSize = handle.size;
	  	if (handle.type == 'rotation'){
	  		handleSize = handle.size * 2;
	  		mx += handle.size + handle.size/2;
	  		my += handle.size + handle.size/2;
	  	}

		/**
		 * check the containment of the handles individually.
		 */
		var contains 	= handle.x <= mx && (handle.x + handleSize >= mx) && handle.y <= my && (handle.y + handleSize >= my);

		if (contains){
			if (handle.type == 'resize'){
				resize 	= handle.p;
			}
			else{
				rot 	= true;
			}
			break;
		}
	}
	return {rotation:rot, resizeHandle:resize};
}


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

	this._prevState	= null; 	// {x, y, w, h} - for clearing old rendering.
}
SeldPage.prototype.draw = function(ctx){
	/**
	 * this method will paint the background of the canvas.
	 */
	ctx.rect(0, 0, this.width, this.height);
	ctx.fillStyle = this.bgColor;
	ctx.fill();
}
SeldPage.prototype.contains = function(mx, my){
	/**
	 * this will check if the point of click contains any SeldPage object.
	 * to save memory, always return true
	 * as page objects will be checked after all the objects.
	 */
	//return checkContain(this, mx, my);
	return true; // page is the backmost object, and shall be requested at the end.
}
SeldPage.prototype.options = function(){
	/**
	 * this will populate the SeldPage Options as 
	 * assigned to the object.
	 */
	$('#seldCanvas-bgColor').val(this.bgColor);
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

	/**
	 * For calculated clearance
	 */
	ctx._prevState = {x:-this.width/2, y:-this.height/2, w:this.width, h:this.height, t:{x:this.x+this.width/2, y:this.y+this.height/2, angle:rad}};

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
SeldText.prototype.contains = function(mx, my){
	/**
	 * this will check if the point of click contains any SeldText object.
	 */
	return checkContain(this, mx, my);
}
SeldText.prototype.options = function(){
	/**
	 * this will populate the SeldText Options as 
	 * assigned to the object.
	 */
	$('#seldtext-value').val(this.value);

	// opacity
	var vl = this.opacity * 100;
	$('#seldtext-opacity').val(vl);

	/**
	 * font family, size and color
	 */
	$('#seldtext-font option[value="' + this.fontFamily + '"]').prop('selected', true);	
	$('#seldtext-size option[value="' + this.fontSize + '"]').prop('selected', true);
	$('#seldtext-textcolor').val(this.color);
	this.gradient == true ? $('#seldtext-gradient').addClass('active') : $('#seldtext-gradient').removeClass('active');
	$('#seldtext-gradientColor').val(this.gradientColor);

	/**
	 * font-styles and alignmnets.
	 */
	this.fontWeight == 'bold' 	? $('#seldtext-bold').addClass('active') 	: $('#seldtext-bold').removeClass('active');
	this.fontStyle == 'italic' 	? $('#seldtext-italic').addClass('active') 	: $('#seldtext-italic').removeClass('active');
	$('.dToolOption[data-type="seldtext-align"]').removeClass('active');
	$('.dToolOption[data-type="seldtext-align"][data-value="' + this.align + '"]').addClass('active');

	/**
	 * Text shadow
	 */
	this.shadow == true ? $('#seldtext-shadow').addClass('active') : $('#seldtext-shadow').removeClass('active');
	$('#seldtext-shadowColor').val(this.shadowColor);
	$('#seldtext-shadowX').val(this.shadowX);
	$('#seldtext-shadowY').val(this.shadowX);
	$('#seldtext-shadowBlur').val(this.shadowBlur);
	
	/**
	 * Text Stroke
	 */
	this.stroke == true ? $('#seldtext-stroke').addClass('active') : $('#seldtext-stroke').removeClass('active');
	$('#seldtext-strokeColor').val(this.strokeColor);
	$('#seldtext-strokeSize').val(this.strokeSize);

	$('#seldtext-rotation').val(this.rotation);
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
	if (draw.myImage == null){
		var imageObj = new Image();
		imageObj.onload = function(){			
			// src, sx, sy, sw, sh, dx, dy, dw, dh
			//ctx.drawImage(imageObj, 0, 0, ow, oh, x, y, w, h);
			ctx.drawImage(imageObj, 0, 0, ow, oh, -w/2, -h/2, w, h);

			// req for re-draw after image load.
			draw.valid = false;
			step.seldCanvas.valid = false;
		}
		imageObj.src = draw.src;
		draw.myImage = imageObj;
	}

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
		var imageObj = draw.myImage;
		if (imageObj != null) ctx.drawImage(imageObj, 0,0, ow, oh, -this.width/2, -this.height/2, w, h);
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
SeldImage.prototype.contains = function(mx, my){
	/**
	 * this will check if the point of click contains any SeldText object.
	 */
	return checkContain(this, mx, my);
}
SeldImage.prototype.options = function(){
	/**
	 * this will populate the SeldImage Options as 
	 * assigned to the object.
	 */
	$('#seldimage-width').val(this.width);
	$('#seldimage-height').val(this.height);
	// rotation
	$('#seldimage-rotation').val(this.rotation);

	// opacity - needs multiplicaiton of 100
	var opacity = this.opacity * 100;
	$('#seldimage-opacity').val(opacity);
	
	// Border prop.
	$('#seldimage-borderSize').val(this.borderSize);
	$('#seldimage-borderColor').val(this.borderColor);
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
SeldShape.prototype.contains = function(mx, my){
	/**
	 * this will check if the point of click contains any SeldShape object.
	 */
	return checkContain(this, mx, my);
}
SeldShape.prototype.options = function(){
	/**
	 * this will populate the SeldShape Options as 
	 * assigned to the object.
	 */
	$('.dToolOption[data-type="seldshape-type"]').removeClass('active');
	$('.dToolOption[data-type="seldshape-type"][data-value="' + this.type + '"]').addClass('active');

	$('#seldshape-width').val(this.width);
	$('#seldshape-height').val(this.height);
	// rotation
	$('#seldshape-rotation').val(this.rotation);

	// opacity - needs multiplicaiton of 100
	var opacity = this.opacity * 100;
	$('#seldshape-opacity').val(opacity);

	$('#seldshape-color').val(this.color);

	this.gradient == true ? $('#seldshape-gradient').addClass('active') : $('#seldshape-gradient').removeClass('active');
	$('#seldshape-gradientColor').val(this.gradientColor);
	
	//
	$('#seldshape-borderSize').val(this.borderSize);
	$('#seldshape-borderColor').val(this.borderColor);
}


/**
 * ===================================================================================================================
 * S E L D   H A N D L E S ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * ===================================================================================================================
 */
function SeldHandles(type,x,y,w,h,fill,p,t){
	/**
	 * this will draw the resize/rotation handles over the selected object.
	 */
	this.type 		= type || 'resize';		// resize, rotation.
	this.x 			= x || 0; 				
	this.y 			= y || 0;

	this.width 		= w || 100;
	this.height		= h || 100;
	this.fill 		= fill || '#000000'; 	
	this.p 			= p || 0; 				// Handle Position required for Resize Handles.
	this.size 		= 10; 					// width&height of the handle

	this.translation= t || {x:0, y:0, angle:0};		// translation [x,y, deg]
}
SeldHandles.prototype.draw = function(ctx){
	/**
	 * this will draw selection handles
	 */
	if (this.type == "resize"){
		/**
		 * Draw Resize Handles - Only for shapes and image type.
		 *
		 * Selection handles comprises of 8 rectangles, drawn on the edges of the object.
		 * 0  1  2
		 * 3     4
		 * 5  6  7
		 */
		ctx.fillStyle 	= this.fill;
		ctx.globalAlpha = 1;

		// update this.x and this.y 
		/*var half = parseInt(this.size / 2);
		var newX = this.x - half;
		var newY = this.y - half;

		switch (this.p){
			case 1:
				newX = this.x + this.width / 2 - half;
				break;
			case 2:
				newX = this.x + this.width - half;
				break;
			case 3:
				newY = this.y + this.height / 2 - half;
				break;
			case 4:
				newX = this.x + this.width - half;
				newY = this.y + this.height / 2 - half;
				break;
			case 5:
				newY = this.y + this.height - half;
				break;
			case 6:
				newX = this.x + this.width / 2 - half;
				newY = this.y + this.height - half;
				break;
			case 7:
				newX = this.x + this.width - half;
				newY = this.y + this.height - half;
		}

		// update x and y.
		this.x = newX;
		this.y = newY;

		//console.log(x1, y1, this.size, this.size);
		ctx.fillRect(this.x, this.y, this.size, this.size);*/

		var half = parseInt(this.size / 2);
		var newX = -half-this.width/2;
		var newY = -half-this.height/2;

		// Update translation for each case.
		var trans = this.translation;

		switch (this.p){
			case 1:
				newX = -half;
				//trans.x -= half;
				break;
			case 2:
				newX = this.width/2 - half;
				break;
			case 3:
				newY = -half;
				break;
			case 4:
				newX = this.width/2 - half;
				newY = -half;
				break;
			case 5:
				newY = this.height/2 - half;
				break;
			case 6:
				newX = -half;
				newY = this.height/2 - half;
				break;
			case 7:
				newX = this.width/2 - half;
				newY = this.height/2 - half;
		}

		// update x and y.
		this.x = newX;
		this.y = newY;

		//console.log(x1, y1, this.size, this.size);
		ctx.fillRect(this.x, this.y, this.size, this.size);
	}
	else if(this.type == 'rotation'){
		/**
		 * Draw circle for rotation.
		 */
		var radius 		= this.size / 2;
		/*var handleX 	= this.x + this.width / 2;
		var handleY 	= this.y - radius * 2;*/
		var handleX 	= 0;
		var handleY 	= -this.height/2-radius*2;

		this.x = handleX;
		this.y = handleY;

		// draw circle.
		ctx.beginPath();
		ctx.arc(handleX, handleY, radius, 0, 2*Math.PI, false);
		ctx.lineWidth 	= 1;
		ctx.strokeStyle = this.strokeStyle;
		ctx.stroke();
	}
}

/**
 * ===================================================================================================================
 * S E L D   C A N V A S    P A G E ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * ===================================================================================================================
 */
function CanvasObjectState(canvas, w, h){
	/**
	 * this object (function) will keep track of canvas object details.
	 */
	this.canvas = canvas;
	this.width 	= w;
	this.height = h;
	this.ctx 	= canvas.getContext('2d');

	this._prevState = null; // {x,y,w,h, t:{x,y,r}} for clearing old rendering
}


/**
 * ===================================================================================================================
 * ===================================================================================================================
 * ===================================================================================================================
 * ===================================================================================================================
 * S E L D   C A N V A S    S T A T E ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * ===================================================================================================================
 * ===================================================================================================================
 * ===================================================================================================================
 * ===================================================================================================================
 */
function CanvasState(canvas, w, h){
	/**
	 * this object (function) will keep track of canvas objects
	 * and their states.
	 */
	this.canvas 	= canvas;
	this.width 		= w;
	this.height 	= h;
	this.ctx 		= canvas.getContext('2d');
	this.scale 		= 100; // 1~200%
	this.currentPage= 1;
	this._prevState	= null; 	// {x,y,w,h, t:{x,y,r}} for clearing old rendering

	/**
	 * this will fix the issue of mouse position offsets 
	 * 		to the object top-left co-ordinates.
	 */
	var stylePaddingLeft, stylePaddingTop, styleBorderLeft, styleBorderTop;

	if (document.defaultView && document.defaultView.getComputedStyle){
		this.stylePaddingLeft = parseInt(document.defaultView.getComputedStyle(canvas, null)['paddingLeft'], 10) || 0;
		this.stylePaddingTop  = parseInt(document.defaultView.getComputedStyle(canvas, null)['paddingTop'], 10)  || 0;
		this.styleBorderLeft  = parseInt(document.defaultView.getComputedStyle(canvas, null)['borderLeftWidth'], 10)  || 0;
		this.styleBorderTop   = parseInt(document.defaultView.getComputedStyle(canvas, null)['borderTopWidth'], 10)   || 0;
	}

	// Fix the co-ordinates caused by fix-position elements in HTML.
	var html 		= document.body.parentNode;
	this.htmlTop 	= html.offsetTop;
	this.htmlLeft 	= html.offsetLeft;

	// keeping track of State. ****
	this.valid 			= false;
	this.shapes 		= [];	// this will hold the different shapes.
	this.handles 		= []; 	// this will hold the selection handles 
	this.selectionType 	= 'canvas';
	this.dragging 		= false;
	this.rotating 		= false;
	this.selection 		= null;
	this.dragoffx 		= 0;
	this.dragoffy 		= 0;
	this.dragResize 	= false;
	this.position 		= {x:0, y:0}; 	// Record mousedown x,y values.
	this.resizeHandle 	= -1; // 0 - 7 

	/**
	 * this will add event listeners to the objects in the canvas.
	 */
	var myState 		= this;

	/**
	 * fix the problem of unwanted double-click selection.
	 */
	canvas.addEventListener('selectstart', function(e){
		e.preventDefault(); 
		return false;
	}, false);

	/**
	 * this will set flags for mouse up, down, dragging and move.
	 */
	canvas.addEventListener('mousedown', function(e){
		var mouse 	= myState.getMouse(e);
		var mx 		= mouse.x;
		var my 		= mouse.y;
		var shapes	= myState.shapes;
		var total 	= shapes.length;
		myState.position = {x:mx, y:my};

		console.log('mouse down now');

		// reset rotating handle
		/*this.dragging 		= false;
		this.rotating 		= false;
		this.dragResize 	= false;*/

		/**
		 * this will check if the handles are clicked before checking the shapes.
		 * 
		 * check for the Resize Handles containment, 
		 * 		if selected Object is shape or image.
		 */
		var check 	= checkContainHandle(mx, my);
		var resize	= check.resizeHandle;
		var rotation= check.rotation;

		if (resize >= 0 || rotation == true){
			/**
			 * this will add more functionality into object interms of resizing & rotating.
			 */
			if (myState.dragResize == false && resize >= 0){
				myState.resizeHandle 	= resize;
				myState.dragResize 		= true; //this.resize >= 0 && this.resize < 8 ? true : false;				
			}
			var mySel = step.seldCanvas.shapes[step.selectionIndex];

			myState.dragoffx 	= mx - mySel.x;
			myState.dragoffy 	= my - mySel.y;

			myState.selection 	= mySel;
			// Keep track of the object mouse click offset
			mySel.valid 		= false;
			myState.valid 		= false;
			myState.selectionType = mySel.name;
			myState.rotating 	= rotation;
			myState.dragging 	= false;

			myState.ghostObject();
			return;
		}
		else{
			/**
			 * check for click from the outer-most object.
			 */
			//this.handles = [];
			//this.resizeHandle = -1;

			for (var i=total-1; i>=0; i--){
				var mySel 	= shapes[i];

				// skip checking for deleted, hidden objects.
				if (mySel.delete == true || mySel.page != step.currentPage || mySel.visibility != 'visible') continue;

				if (mySel.contains(mx, my)){
					step.selectLayer(i);
					mySel.valid 		= false;
					// Keep track of the object mouse click offset
					myState.dragoffx 	= mx - mySel.x;
					myState.dragoffy 	= my - mySel.y;
					myState.selection 	= mySel;
					myState.valid 		= false;
					myState.selectionType = mySel.name;
					myState.dragging 	= true;
					myState.rotating 	= false;
					this.dragResize 	= false;

					/**
					 * focus on textarea if seldText
					 */
					if (mySel.name == 'text'){
						$('#seldtext-value').focus();
					}
					myState.ghostObject();
					return;
				}
			}
		}
		
		// empty return means - NO SELECTION
		// if selected, the DESELECT OTHERS.
		if (myState.selection){
			myState.selection = null; 	// Clear old selection
			myState.valid = false;
		}
	}, true);

	canvas.addEventListener('mousemove', function(e) {
		var mouse 	= myState.getMouse(e);
		var mx 		= mouse.x;
		var my 		= mouse.y;
		var cursor 	= 'default';
		console.log('mouse moving...');


		if (myState.dragging){
			/**
			 * Drag object from the point of selection and not top-left corner.
			 */
			myState.selection.x = mouse.x - myState.dragoffx;
			myState.selection.y = mouse.y - myState.dragoffy;
			
			// Point out the object to be redrawn.
			if (step.selectionIndex >= 0){
				//var mySel = step.seldCanvas.shapes[]
				var mySel = step.seldCanvas.shapes[step.selectionIndex];
				mySel.valid = false;
			}
			myState.valid = false; // Something's dragging so we must redraw
			
			
			/**
			 * imitate the selectionGhost for dragging
			 * 
			 * get x,y,w,h properties from current selected object.
			 *   ref [step.selectionIndex in step.seldCanvas.shapes].
			 */
			var mySel = step.seldCanvas.shapes[step.selectionIndex];

			/*step.selectionGhost.css({
				width: mySel.width,
				height: mySel.height,
			    left: mySel.x,
			    top: mySel.y
			});*/
		}
		else if (myState.rotating){
			/**
			 * this will rotate the object..
			 * 
			 * check inital mouse position with current.
			 * x --> ++, x <-- --.
			 */
			var ref = myState.position;
			var dir = mx - ref.x;
			var obj = myState.shapes[step.selectionIndex];

			myState.position = {x:mx, y:my};

			if (dir < 0){
				obj.rotation-= 5;
				obj.options();
				obj.valid = false;
				myState.valid = false;
			}
			else{
				obj.rotation+= 5;
				obj.options();
				obj.valid = false;
				myState.valid = false;
			}
		}
		else if (myState.dragResize){
			// Resizing Going on.
			var mySel = step.seldCanvas.shapes[step.selectionIndex];
			var oldx = mySel.x;
			var oldy = mySel.y;

			switch (myState.resizeHandle){
				case 0:
					mySel.x = mx;
					mySel.y = my;
					mySel.width += oldx - mx;
					mySel.height += oldy - my;
					break;
				case 1:
					mySel.y = my;
					mySel.height += oldy - my;
					break;
				case 2:
					mySel.y = my;
					mySel.width = mx - oldx;
					mySel.height += oldy - my;
					break;
				case 3:
					mySel.x = mx;
					mySel.width += oldx - mx;
					break;
				case 4:
					mySel.width = mx - oldx;
					break;
				case 5:
					mySel.x = mx;
					mySel.width += oldx - mx;
					mySel.height = my - oldy;
					break;
				case 6:
					mySel.height = my - oldy;
					break;
				case 7:
					mySel.width = mx - oldx;
					mySel.height = my - oldy;
					break;
			}
			mySel.valid = false;
			myState.valid = false;

			// refresh options
			mySel.options();
		}

		/**
		 * display respective mouse cursors..
		 */		
		var check = checkContainHandle(mx, my);
		if (check.resizeHandle >= 0){
			switch (check.resizeHandle) {
				case 0:
					cursor='nw-resize';
					break;
				case 1:
					cursor='n-resize';
					break;
				case 2:
					cursor='ne-resize';
					break;
				case 3:
					cursor='w-resize';
					break;
				case 4:
					cursor='e-resize';
					break;
				case 5:
					cursor='sw-resize';
					break;
				case 6:
					cursor='s-resize';
					break;
				case 7:
					cursor='se-resize';
					break;
			}
		}
		else if(check.rotation == true){
			cursor = 'rotate';
		}
		
		// set cursor
		$('#pad').removeClass().addClass('cursor-'+cursor);

	}, true);
	canvas.addEventListener('mouseup', function(e) {
		console.log('mouse up');

		myState.ghostObject('hide');

		step.selectionGhost.addClass('hidden');

	}, true);

	canvas.addEventListener('mouseout', function(e) {
		//myState.dragging 	= false;
		//myState.rotating 	= false;
		//myState.dragResize 	= false;
	}, true);
	
	// *** Options
	this.selectionColor = '#000000';
	this.selectionWidth = 1;
	this.interval 		= 24;
	// Draw canvas on each interval.
	setInterval(function() { myState.draw(); }, myState.interval);
}
CanvasState.prototype.addShape = function(shape){
	/**
	 * Stack the shapes array to display.
	 *
	 * Clear other selections and make current as selected.
	 */
	this.shapes.push(shape);
	this.clearSelection();
	this.selection = shape;
	this.valid = false;
}
CanvasState.prototype.clear = function(ctx, prev){
	/**
	 * this will clear the canvas area for drawing.
	 * clear the context.
	 * ####
	 * for performance optimization, instead of clearing 
	 * ~~~~~~~~ctx.clearRect(0, 0, this.width, this.height);
	 */

	/**
	 * check if the context has _prevState.
	 */
	var prevState = prev || null;

	if (prevState){

		ctx.save();
		var deg = prevState.t.angle % 360;
		var rad = deg * Math.PI / 180;
		var pad = 100; // padding around object to clear.

		ctx.translate(prevState.t.x, prevState.t.y);
		ctx.rotate(rad);

		ctx.clearRect(prevState.x-pad , prevState.y-pad, prevState.w+pad+pad, prevState.h+pad+pad);

		ctx.restore();
	}
	else{
		// clearout whole section
		ctx.clearRect(0, 0, this.width, this.height);
	}
}
CanvasState.prototype.clearSelection = function(){
	/**
	 * this will clear the any selection
	 */
	if (this.selection){
		this.selection = null; 	// Clear old selection
		this.valid = false;
	}
}
CanvasState.prototype.ghostObject = function(mode){
	/**
	 * this will arrange the width, height, x and y position of the ghost
	 * object. to imitate dragging, rotating and resizing.
	 */
	var action 	= mode || 'show';	
	var myState = this;
	var mySel 	= myState.selection;

	if (action == 'show'){
		return;
		/**
		 * this will display the selectionObject ghost
		 */
		if (myState.dragging == true || myState.rotating == true || myState.dragResize == true){			

			if (mySel.name != 'canvas'){
				/**
				 * selection ghost of objects other than canvas.
				 */
				step.selectionGhost.css({
					width 	: mySel.width,
					height 	: mySel.height,
				    left 	: mySel.x,
				    top 	: mySel.y
				}).removeClass('hidden');
			}
		}
	}
	else{
		/**
		 * this will hide ghost selection.
		 *
		 * also update the new position to the object
		 * /
		if (myState.dragging == true){
			mySel.x = parseInt(step.selectionGhost.css('left'));
			mySel.y = parseInt(step.selectionGhost.css('top'));

			mySel.valid = false;
			myState.valid = false;
		}*/
		myState.dragging 	= false;
		myState.rotating 	= false;
		myState.dragResize 	= false;
	}
}
CanvasState.prototype.draw = function(){
	/**
	 * this method will draw the stacked shapes in the canvas.
	 * this is called frequently as the INTERVAL. 
	 * But will no nothing unless the canvas is not valid.
	 */
	if (!this.valid){
		var ctx 		= this.ctx;
		var shapes 		= this.shapes;
		var shapeCtx 	= null;

		/**
		 * This will clear the previous selections on the canvas.
		 * ctx refers to the canvas only for drawing SelectionBorders and SelectionHandles.
		 */
		this.clear(ctx, ctx._prevState);

		var total = shapes.length;
		for (var i=0; i<total; i++){
			var shape = shapes[i];

			/**
			 * Each object will need it's own canvas with id Starting with prefix "seld_canvas_page_" followed by index number
			 * e.g. seld_canvas_page_0, seld_canvas_page_1 and so on.
			 * Minimum number of canvas required is the total number of pages.
			 * 
			 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			 * shapes[i].draw(ctx); ::: if need to draw objects in one canvas.
			 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			 */

			/**
			 * find the context for current object.
			 *
			 * if not available, create one and take the reference.
			 */
			var seldCanvasPage 	= 'seld_canvas_page_' + i;
			if ($('#'+seldCanvasPage).length <= 0){
				// create canvas.
				var zIndex = i+1;
				$('#canvas_ghost').append('<canvas class="sub-canvas" id="' + seldCanvasPage + '" width="' + ctx.canvas.width + '" height="' + ctx.canvas.height + '" style="z-index:' + zIndex + '"></canvas>');
				/**
				 * Update step.seldCanvasObjects variable for future reference.
				 */
				var canvasObject 	= new CanvasObjectState(document.getElementById(seldCanvasPage), ctx.canvas.width, ctx.canvas.height);
				step.seldCanvasObjects.push(canvasObject);
			}

			/**
			 * this will make sure, the current visible, page objects will
			 * be drawn in the canvas.
			 */
			if (shape.page == this.currentPage && shape.valid == false){

				// Skip drawing of shapes that is beyond canvas scope.
				if (shape.x > this.width || shape.y > this.height || shape.x + shape.width < 0 || shape.y + shape.height < 0) continue;

				var seldContext		= step.seldCanvasObjects[i].ctx;
				shapeCtx 			= seldContext;

				/**
				 * Clear context for re-drawing.
				 */
				this.clear(seldContext, seldContext._prevState);

				/**
				 * Only draw the object if visibility is set to visible and delete is false.1
				 */
				if (shape.delete == false && shape.visibility == 'visible'){
					shape.draw(seldContext);
				}

				// validate object
				shape.valid = true;
			}
		}

		/**
		 * If there is any selection, then draw required handles.
		 */
		if (this.selection != null && shapeCtx != null){
			ctx.strokeStyle = this.color == '#0000FF' ? '#FF0000' : this.selectionColor;
			ctx.lineWidth 	= this.selectionWidth;

			/**
			 * default canvas type doesn't need any handles.
			 */
			if (this.selectionType == 'canvas') return;

			/**
			 * selected object highlight shape.
			 */
			var mySel 		= this.selection;


			/**
			 * Check for rotated object
			 *
			 * Save the context instance, translate canvas, rotate and restore.
			 */
			ctx.save();
			//var deg 		= mySel.rotation < 0 || mySel.rotation > 359 ? 0 : mySel.rotation;
			var deg 		= mySel.rotation % 360;
			var rad 		= deg * Math.PI / 180;
			var translation = {x:mySel.x+mySel.width/2, y:mySel.y+mySel.height/2, angle:deg};

			/**
			 * For effective clearance
			 */
			ctx._prevState = {x:-mySel.width/2, y:-mySel.height/2, w:mySel.width, h:mySel.height, t:translation};

			ctx.translate(translation.x, translation.y);
			ctx.rotate(rad);

			ctx.strokeRect(-mySel.width/2, -mySel.height/2, mySel.width, mySel.height);

			/**
			 * Prepare Resizing handles
			 */
			this.handles = [];
			this.handles.push(new SeldHandles('rotation', mySel.x, mySel.y, mySel.width, mySel.height, this.selectionColor, 0, translation));

			// 8 handles for resizing object.
			if (this.selectionType == 'shape' || this.selectionType == 'image'){
				for (var i=0; i<8; i++){
					this.handles.push(new SeldHandles('resize', mySel.x, mySel.y, mySel.width, mySel.height, this.selectionColor, i, translation));
				}
			}

			/**
			 * Now Draw all the handles.
			 */
			for (var i=0; i<this.handles.length; i++){
				var handle = this.handles[i];
				handle.draw(ctx);
			}

			ctx.restore();
		}
		this.valid = true;
	}
}
CanvasState.prototype.getMouse = function(e) {
	/**
	 * this will create an object with x and y defined. set to the mouse position 
	 * relative to the state's canvas.
	 * /
	var element = this.canvas, offsetX = 0, offsetY = 0, mx, my;

	// Compute the total offset
	if (element.offsetParent !== undefined) {
		do {
			offsetX += element.offsetLeft;
			offsetY += element.offsetTop;
		} while ((element = element.offsetParent));
	}

	// Add padding and border style widths to offset
	// Also add the offsets in case there's a position:fixed bar
	/*
	### PERFORMANCE OPTIMIZATION - by removing any padding and border of the canvas#pad.
	offsetX += this.stylePaddingLeft + this.styleBorderLeft + this.htmlLeft;
	offsetY += this.stylePaddingTop + this.styleBorderTop + this.htmlTop;

	mx = e.pageX - offsetX;// + 3;
	my = e.pageY - offsetY;// - 8;
	*/

	var scale 	= parseInt($('#canvas_zoom').val());
	scale 		= scale < 1 ? 1 : scale > 200 ? 200 : scale;

	var oWidth 	= parseInt($('#pad').attr('width'));
	var oHeight = parseInt($('#pad').attr('height'));

	var offset 	= $('#pad').offset();
	var offsetX = Math.round(offset.left * scale / 100);
	var offsetY = Math.round(offset.top * scale / 100);

	var mx 		= e.pageX - offsetX;
	var my 		= e.pageY - offsetY;

	var maxX 	= oWidth * scale;
	var maxY 	= oHeight * scale;

	// percent
	var factorX = Math.round(mx / maxX * 10000) / 100;
	var factorY = Math.round(my / maxY * 10000) / 100;

	//mx = ~~(0.5 + factorX * oWidth); 
	//my = ~~(0.5 + factorY * oHeight);

	mx = Math.round(factorX * oWidth);
	my = Math.round(factorY * oHeight);

	console.log(mx, my, offsetX, offsetY, factorX, factorY);

	// We return a simple javascript object (a hash) with x and y defined
	return {x: mx, y: my};
}


/**
 * ===================================================================================================================
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ S E L D   S T E P ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * ===================================================================================================================
 */
var step = {
	seldCanvas:'',			// JS object of SELD Editor CANVAS
	seldCanvasObjects:[],	// array of canvas objects.
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
		var o 			= $('#design-pages');
		var width 		= parseInt(o.data('width'));
		var height 		= parseInt(o.data('height'));
		var pages 		= parseInt(o.data('pages'));
		var faces 		= parseInt(o.data('faces'));
		
		step.seldCanvas	= new CanvasState(document.getElementById('pad'), width, height);
		$('#pad,.sub-canvas, #canvas_ghost').css({'width':width, 'height':height}).attr({'width':width, 'height':height});
		// paint bg color
		step.seldCanvas.bgColor = '#ffffff';

		// Load saved contents.
		step._loadProgress({w:width, h:height, p:pages, f:faces});

		// preprare tools
		step._initTools();

		// Initial fit zoom of canvas.
		step.zoomCanvasFit();

		// Clear extra elements and remove overlay
		$('.seld-status, .seld-footer').remove();
	},
	_loadProgress: function(ref){
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
		var total 		= ref.p * ref.f;
		for (var i=1; i<=total; i++){
			var seld = new SeldPage(ref.w, ref.h, i);
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
		var o 			= $('#design-pages');
		var padding 	= 55*2;
		var width 		= o.data('width');
		var height 		= o.data('height');

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
		ratio = ratio > 200 ? 200 : ratio; // Max 200%

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
				step.seldCanvas.addShape(shape);
				break;

			case 'layers':
				$('#layer_overlay').toggleClass('hidden');
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
		 *  	by calculating ratio.
		 */
		var ref 		= $(this).find('img');
		var thumbSrc 	= ref.attr('src');
		var oWidth 		= parseInt(ref.attr('data-width'));
		var oHeight 	= parseInt(ref.attr('data-height'));
		var origSrc 	= thumbSrc.replace('thumbs/', '');

		var shape 		= step.seldCanvas.shapes[step.selectionIndex];
		shape.src 		= origSrc;
		shape.oWidth 	= oWidth;
		shape.oHeight 	= oHeight;
		shape.myImage 	= null;

		// Calculate Width/Height ratio.
		var ratio 		= oHeight > 0 ? oWidth / oHeight : 1;
		shape.height 	= shape.width / ratio;
		
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
			$('#design-tools-options').removeClass().addClass('current-' + shape.name);
			shape.options();
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
		 */
		var id 	= $('#design-pages').attr('data-ref');
		var data= JSON.stringify(step.seldCanvas.shapes);
		$.post(base_url()+'u/saveCanvas', {'id':id, 'data':data});
	},
	saveImage: function(){
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
		$(window).resize(step.zoomCanvasFit);
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
	},
	_initToolsDesignOptions: function(){
		/**
		 * Initialize Design Options
		 */
		$('#saveimagebutton').click(step.saveImage);

		/**
		 * this will save the canvas objects to database
		 */
		$('#saveCanvas').click(step.save);

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
		$("#image_uploader").uploadFile(fileUpload.settings);
	},
	_initTextPresets: function(){
		/*this.id 			= createID();	// Layer ID
		this.name 			= 'text';		// used to determine when clicked on canvas.
		this.title 			= 'New Text';	// Used for user to identify layer.
		this.visibility 	= 'visible'; 	// used for show/hide of object
		this.delete 		= false; 		// this is used as flag for deleting object.
		this.page 			= 1; 			// assigned to design page number	
		this.x 				= x || 0; 		// position x
		this.y 				= y || 0;		// position y

		this.stroke			= false; 		// fill Style
		this.strokeSize 	= 1; 			// Stroke Width

		this.value 			= v || 'Type Here..';// Text Value
		this.color 			= fill || '#000000';// Default text color
		this.width			= 100;			// width
		this.height			= 50; 			// height
		this.fontFamily		= 'Arial'; 		// font family
		this.fontSize 		= 20; 			// font Size
		this.lineHeight		= 20;			// Line Height
		this.fontWeight		= 'normal'; 	// Font Weight
		this.fontStyle 		= 'normal';		// font Style
		this.align 			= 'left';		// alignment: left, right, center
		this.angle 			= 0; 			// rotation angle.

		this.shadow 		= false;		// true/false for text shadow
		this.shadowColor 	= '#000000'; 	// shadow color
		this.shadowX 		= 2; 			// shadow offset x
		this.shadowY 		= 2;			// shadow offset y
		this.shadowBlur 	= 5;			// shadow blur
		*/
		/**
		 * this will load the text presets
		 *
		 * color, fontFamily, fontWeight, fontStyle, gradient, gradientColor, Stroke, StrokeWidth, Shadow, shadowColor, shadowX, shadowY, shadowBlur
		 */
		presets = [
					['#0080FF', 'Arial', 	'normal',	'normal', 	false, 	'#000000', 	false, 	1, 	true, 	'#444444', 2, 2, 5],
					['#000000', 'Arial', 	'normal',	'normal', 	false, 	'#000000', 	true, 	1, 	false, 	'#444444', 2, 2, 5],
					['#333333', 'Arial', 	'normal',	'normal', 	false, 	'#000000', 	false, 	1, 	true, 	'#FFFFFF', 2, 2, 0],					
				];
		for (var i=0; i<presets.length; i++){
			var txt = new SeldText(10, 10, 'Seld Creative');
			txt.fontSize 	= 30;

			txt.color 			= presets[i][0];
			txt.fontFamily		= presets[i][1];
			txt.fontWeight		= presets[i][2];
			txt.fontStyle		= presets[i][3];
			txt.gradient 		= presets[i][4];
			txt.gradientColor 	= presets[i][5];
			txt.stroke 			= presets[i][6];
			txt.strokeSize		= presets[i][7];
			txt.shadow 			= presets[i][8];
			txt.shadowColor		= presets[i][9];
			txt.shadowX			= presets[i][10];
			txt.shadowY			= presets[i][11];
			txt.shadowBlur		= presets[i][12];

			// save the presets
			step.presets.push(txt);

			// list..
			var li 	= '<li data-index="' + i + '"><canvas id="presetCanvas' + i + '" width="200" height="50"></canvas></li>';
			$('#seldtext-presetsList').append(li);

			var canvas = document.getElementById('presetCanvas'+i);
			var context = canvas.getContext('2d');

			txt.draw(context);
		}

		// Select Preset
		$('body').on('click', '#seldtext-presetsList li', function(){
			var i = $(this).attr('data-index');
			var txt = step.presets[i];

			// shape 
			var ref = step.seldCanvas.shapes[step.selectionIndex];
			if (ref){
				// copy styles.
				ref.color 		= txt.color;
				ref.fontFamily 	= txt.fontFamily;
				ref.fontWeight 	= txt.fontWeight;
				ref.fontStyle 	= txt.fontStyle;
				ref.stroke 		= txt.stroke;
				ref.strokeSize 	= txt.strokeSize;
				ref.shadow 		= txt.shadow;
				ref.shadowColor = txt.shadowColor;
				ref.shadowX 	= txt.shadowX;
				ref.shadowY 	= txt.shadowY;
				ref.shadowBlur 	= txt.shadowBlur;

				// invalidate.
				ref.options();
				ref.valid = false;
				step.seldCanvas.valid = false;
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
		// Hide current layer
		ref.bind('keydown', 'ctrl+k', function(e){step.toggleLayerVisibility('current');e.preventDefault()});
		// Alter Visibility of all the layers with one key stroke.
		ref.bind('keydown', 'alt+a', function(e){step.toggleLayerVisibility();e.preventDefault()});

		/**
		 * this will move object to 4 directions.
		 * modes can be normal (5px), fine (1px) and snap (to the edges of canvas)
		 */
		ref.bind('keydown', 'left', 	function(e){ step._moveSelectedObject('left')});
		ref.bind('keydown', 'right', 	function(e){ step._moveSelectedObject('right')});
		ref.bind('keydown', 'up', 		function(e){ step._moveSelectedObject('up')});
		ref.bind('keydown', 'down', 	function(e){ step._moveSelectedObject('down')});
		ref.bind('keydown', 'shift+left', 	function(e){ step._moveSelectedObject('left', 'snap')});
		ref.bind('keydown', 'shift+right', 	function(e){ step._moveSelectedObject('right', 'snap')});
		ref.bind('keydown', 'shift+up', 		function(e){ step._moveSelectedObject('up', 'snap')});
		ref.bind('keydown', 'shift+down', 	function(e){ step._moveSelectedObject('down', 'snap')});
		ref.bind('keydown', 'ctrl+left', 	function(e){ step._moveSelectedObject('left', 'fine')});
		ref.bind('keydown', 'ctrl+right', 	function(e){ step._moveSelectedObject('right', 'fine')});
		ref.bind('keydown', 'ctrl+up', 		function(e){ step._moveSelectedObject('up', 'fine')});
		ref.bind('keydown', 'ctrl+down', 	function(e){ step._moveSelectedObject('down', 'fine')});
	},
	init: function(){
		step.prepareCanvas();
	}
};

var fileUpload = {
	myUploadData: 	[], 			// array for uploaded file names.
	settings: {
	    url: 			$('.image-upload-main').data('ref') + 'u/upload',
	    method: 		"POST",
	    allowedTypes: 	"jpg,jpeg,png,gif",
	    fileName: 		"myfile",
	    multiple: 		false,
	    maxFileCount: 	50,
	    beforeSend:function(){
	    	return false;
	    },
	    onSuccess:function(files,data,xhr){
	        //$("#status").html("<font color='green'>Upload completed</font>");
	        fileUpload.myUploadData.push(data);
	    },
	    afterUploadAll:function(){
	        fileUpload.after_upload();
	        $('.upload-statusbar').remove();
	    },
	    onError: function(files,status,errMsg){  
	        $("#status").html("<font color='red'>Upload has Failed</font>");
	    }
	},
	after_upload: function(){
		if (fileUpload.myUploadData != null){
			var photo 	= '';
			var base_url= $('.image-upload-main').data('ref');
			for (i=0; i<fileUpload.myUploadData.length; i++){
				var arr 	= $.parseJSON(fileUpload.myUploadData[i]);
				$.each(arr, function(k, v){
					// get the filesize information
					var w = 0;
					var h = 0;
					var tmpImg = new Image();
					var source = base_url + v;
					tmpImg.src = source.replace('thumbs/', '');

					$(tmpImg).on('load', function(){
						w = tmpImg.width;
						h = tmpImg.height;

						photo += '<li><div class="img-wrapper"><img src="' + base_url + v + '" width="' + w + '" height="' + h + '" /></div></li>';

						$('#my-images-list').append(photo);
					});
					//var ob = $('input[name="frm_photos"]');
					//vl = ob.val(ob.val() + ',' + k);
				});
			}			
			fileUpload.myUploadData = new Array();
			$('#my-images-list li:last-child').trigger('click');
		}
	}
};

function createID(){
	/**
	 * this will create unique ID for the layer. 
	 *  	this is necessary for #layers to identify object.
	 */ 
	return 'layer-' + Date.now();
}
function rgb2hex(orig){
	/**
	 * this method will change the RGB value to 
	 * 		Hexadecimal notation of the color code.
	 */
	var rgb = orig.replace(/\s/g,'').match(/^rgba?\((\d+),(\d+),(\d+)/i);
	return (rgb && rgb.length === 4) ? "#" +
		("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
		("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
		("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : orig;
}