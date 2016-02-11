/**
 * SELD Creative Editor
 * @author 		Sudarshan Shakya (Creative Edge)
 * @date 		2016-01-14
 *
 * this contains all the functions (classes/objects) required by 
 * SELD Creative Editor v1.0.1.
 *
 * the list of functions (classes) are:
 * 1. checkContain
 * 2. checkContainHandle
 *
 * 3. SeldPage
 * 		.prototype.draw
 * 		.prototype.contains
 * 		.prototype.options
 *
 * 4. SeldText
 * 		.prototype.draw
 * 		.prototype.contains
 * 		.prototype.options
 *
 * 5. SeldImage
 * 		.prototype.draw
 * 		.prototype.contains
 * 		.prototype.options
 *
 * 6. SeldShape
 * 		.prototype.draw
 * 		.prototype.contains
 * 		.prototype.options
 *
 * 7. SeldHandles
 * 		.prototype.draw
 * 
 * 8. CanvasObjectState
 * 9. createID
 * 10. rgb2hex
 * 
 * 11. CanvasState  ----------------------------- this requires s4-canvas.js ("step" object)
 *  	.prototype.addShape
 * 		.prototype.clear
 * 		.prototype.clearSelection
 * 		.prototype.ghostObject
 * 		.prototype.draw
 * 		.prototype.getMouse
 * 		.prototype.getCursor
 */


/**
 * this will check if the given co-ordinates contains on any object or not.
 * prototype method by objects (SeldText, SeldImage and SeldShape) only.
 *
 * Containment requires co-ordinates translation.
 *  and the translation is defined in the SeldObjects.
 */
function checkContain(obj, mx, my){

	var deg 	= obj.rotation;
	var angle 	= deg * Math.PI / 180;

	/**
	 * initial correction in mouse co-ordinates to balance the translation co-ordinates.
	 */
	mx -= Math.floor(obj.x+obj.width/2);
	my -= Math.floor(obj.y+obj.height/2);

	/**
	 * Translated co-ordinates is formed by applying following formula.
	 */
	mx = Math.floor(mx * Math.cos(-angle) - my * Math.sin(-angle));
	my = Math.floor(mx * Math.sin(-angle) + my * Math.cos(-angle));

	/**
	 * objx and objy needs to be altered for collison detection,
	 * as the object has been translated and origins at the object center (x+w/2, y+h/2).
	 */
	var objx = -obj.width/2;
	var objy = -obj.height/2;

	return objx <= mx && (objx + obj.width >= mx) && objy <= my && (objy + obj.height >= my);
}


/**
 * this will check if current mouse co-ordinates contains any 
 * selected object handles
 *
 * handles include, rotation (arc) and resizing handles 
 * [8 boxes on corners and mid of selected object].
 */
function checkContainHandle(ox, oy){

	/**
	 * Selected object comprises 8 resize handles, indexed from 0 to 7. as:
	 *
	 * 0   1    2
	 * 3        4
	 * 5   6    7
	 *
	 * argumet "handles" will contain maximum of 9 handles.
	 * 8 for resizing, and 1 for rotating.
	 */

	var resize 		= -1; 		// 0~7, resize handle index number. -1 for empty.
	var rot 		= false;	
	var handles 	= step.seldCanvas.handles;

	for (var i=0; i<handles.length; i++){

		var handle 	= handles[i]; 				// current handle
		var deg 	= handle.translation.angle; // selected object rotation angle (in degrees).
		var angle 	= deg * Math.PI / 180; 		// required angle in radian.
		
		// optimize x,y for translation.
		var _x = ox - handle.translation.x;
		var _y = oy - handle.translation.y;
		
		/**
		 * Here the co-ordinates x,y needs to be translated
		 * to the object's rotation.
		 */
		tempX = Math.round(_x * Math.cos(-angle) - _y * Math.sin(-angle));
	  	tempY = Math.round(_x * Math.sin(-angle) + _y * Math.cos(-angle));

	  	/**
	  	 * add padding correction to rotation handle
	  	 * double the selection-area. to increase precision.
	  	 */
	  	if (handle.type == 'rotation'){
	  		tempX += handle.size + handle.size/2;
	  		tempY += handle.size + handle.size/2;
	  	}

		/**
		 * check the containment of the possible handle.
		 */
		//console.log('computed '+i, mx, tempY);
		var contains = handle.x <= tempX && (handle.x + (handle.size*2) >= tempX) && handle.y <= tempY && (handle.y + (handle.size*2) >= tempY);

		/**
		 * return an object indicating if the handle is rotation handle or resize handle.
		 * if resize handle, then the index number of handle.
		 */
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

/**
 * SeldPage
 *
 * this contains the meta information of the Page
 * Can only be auto-initialized, and not from any action or user.
 */
function SeldPage(w, h, p, c){

	this.name 		= 'canvas';
	this.valid 		= false; 			// if false, it will be re-printed. // need to set it as true after drawing.
	this.delete 	= false;
	this.visibility = 'visible';
	
	this.page 		= p || 1; 			// canvas page number
	this.width 		= w || 0; 			// canvas width
	this.height 	= h || 0; 			// canvas height

	this.bgColor 	= c || '#FFFFFF'; 	// background color of canvas
}

/**
 * this method will paint the background of the canvas.
 */
SeldPage.prototype.draw = function(ctx){

	ctx.rect(0, 0, this.width, this.height);

	ctx.fillStyle = this.bgColor;
	ctx.fill();
}

/**
 * this will check if the point of click contains any SeldPage object.
 * if no object selection, this will always be true since it is the backward-most object
 * Page objects will be checked for containment after all the objects. 
 */
SeldPage.prototype.contains = function(mx, my){

	return true; // page is the backmost object, and shall be requested at the end.
}

/**
 * this will populate the SeldPage Options as 
 * assigned to the object.
 */
SeldPage.prototype.options = function(){
	$('#seldCanvas-bgColor').val(this.bgColor);
}



/**
 * ===================================================================================================================
 * S E L D   T E X T ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * ===================================================================================================================
 */

/**
 * SeldText
 * this function holds the information of SELD TEXT Field.
 */
function SeldText(x, y, v, fill){

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

/**
 * this will draw the text to the given canvas context.
 *
 */
SeldText.prototype.draw = function(ctx){

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
		ctx.shadowOffsetX 	= Math.round(this.shadowX * this.fontSize / 100);
		ctx.shadowOffsetY 	= Math.round(this.shadowY * this.fontSize / 100);
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
 * this will check if the point of click contains any SeldText object.
 */
SeldText.prototype.contains = function(mx, my){

	return checkContain(this, mx, my);
}

/**
 * this will populate the SeldText Options as 
 * assigned to the object.
 */
SeldText.prototype.options = function(){
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
	$('#seldtext-shadowY').val(this.shadowY);
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

/**
 * SeldText
 * this function holds the information of SELD Image Field.
 */
function SeldImage(x, y, w, h, fill){
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

/**
 * this will draw the image to the canvas.
 *
 * need to create variables, as imageObj objet will overload 'this' 
 *   	originally refering to the SeldImage object.
 */
SeldImage.prototype.draw = function(ctx){

	/**
	 * need to create variables, as 'this' won't be availble inside imageObj.
	 */
	var draw 	= this;
	var x 		= draw.x;
	var y 		= draw.y;
	var w 		= draw.width;
	var h 		= draw.height;
	var ow 		= draw.oWidth;
	var oh 		= draw.oHeight;
	
	ctx.globalAlpha = this.opacity;

	// create image object for first
	if (draw.myImage == null){
		var imageObj = new Image();
		imageObj.onload = function(){
			// src, sx, sy, sw, sh, dx, dy, dw, dh
			ctx.drawImage(imageObj, 0, 0, ow, oh, -w/2, -h/2, w, h);

			// req for re-draw after image load.
			draw.valid = false;
			if (typeof step !== undefined){
				step.seldCanvas.valid = false;
			}
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
	
	ctx.restore(); 		// restore
}

/**
 * this will check if the point of click contains any SeldText object.
 */
SeldImage.prototype.contains = function(mx, my){

	return checkContain(this, mx, my);
}

/**
 * this will populate the SeldImage Options as 
 * assigned to the object.
 */
SeldImage.prototype.options = function(){

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

/**
 * SeldShape
 * this function holds the information of SELD shape Field.
 */
function SeldShape(x, y, w, h, color){

	this.valid 			= false; 		// if false, it will be re-printed. // need to set it as true after drawing.
	this.id 			= createID();
	this.name 			= 'shape';
	this.title 			= 'New Shape';	// Used for user to identify layer.
	this.visibility 	= 'visible';
	this.delete 		= false; 		// this is used as flag for deleting object.
	
	this.page 			= 1; 			// shape containment page number.
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

/**
 * this will draw the shape to the canvas.
 */
SeldShape.prototype.draw = function(ctx){

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
		var radius 	= Math.round(ref/2);

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

		ctx.fillRect(-this.width/2, -this.height/2, this.width, this.height);

		if (this.borderSize > 0){
			ctx.lineWidth 	= this.borderSize;
			ctx.strokeStyle = this.borderColor;

			ctx.strokeRect(-this.width/2-this.borderSize/2, -this.height/2-this.borderSize/2, this.width+this.borderSize, this.height+this.borderSize);
		}
	}

	ctx.restore(); 		// restore canvas context.
}

/**
 * this will check if the point of click contains any SeldShape object.
 */
SeldShape.prototype.contains = function(mx, my){

	return checkContain(this, mx, my);
}
	
/**
 * this will populate the SeldShape Options as 
 * assigned to the object.
 */
SeldShape.prototype.options = function(){

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

/**
 * this will draw the resize/rotation handles over the selected object.
 */
function SeldHandles(type,x,y,w,h,fill,p,t){

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

/**
 * this will draw selection handles
 */
SeldHandles.prototype.draw = function(ctx){

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

		var half = Math.round(this.size / 2);
		var newX = -half-this.width/2;
		var newY = -half-this.height/2;

		// Update translation for each case.
		var trans = this.translation;

		switch (this.p){
			case 1:
				newX = -half;
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

		ctx.fillRect(this.x, this.y, this.size, this.size);
	}
	else if(this.type == 'rotation'){
		
		/**
		 * Draw circle for rotation.
		 */
		var radius 		= this.size / 2;
		var handleX 	= 0;
		// Correct 'Y' to give space for position #2 resize handle.
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

/**
 * this object (function) will keep track of canvas object details.
 */
function CanvasObjectState(canvas, w, h){

	this.canvas = canvas;
	this.width 	= w;
	this.height = h;
	this.ctx 	= canvas.getContext('2d');

	this._prevState = null; // {x,y,w,h, t:{x,y,angle}} for clearing old rendering
}


/**
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * ------------------------------------------------------------------------------------------------------------------
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 */

/**
 * this will create unique ID for the layer. 
 *  	this is necessary for #layers to identify object.
 */ 
function createID(){

	return 'layer-' + Date.now();
}

/**
 * this method will change the RGB value to 
 * 		Hexadecimal notation of the color code.
 */
function rgb2hex(orig){

	var rgb = orig.replace(/\s/g,'').match(/^rgba?\((\d+),(\d+),(\d+)/i);
	return (rgb && rgb.length === 4) ? "#" +
		("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
		("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
		("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : orig;
}


/**
 * ===================================================================================================================
 * S E L D   C A N V A S   S T A T E ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * ===================================================================================================================
 */

/**
 * this object (function) will keep track of canvas objects
 * and their states.
 */
function CanvasState(canvas, w, h){

	this.canvas 		= canvas;
	this.width 		 	= w;
	this.height 		= h;
	this.ctx 			= canvas.getContext('2d');
	this.scale 			= 100; // 1~200%
	this.currentPage 	= 1;
	this._prevState		= null; 	// {x,y,w,h, t:{x,y,r}} for clearing old rendering

	/**
	 * this will fix the issue of mouse position offsets 
	 * 		to the object top-left co-ordinates.
	 */
	var stylePaddingLeft, stylePaddingTop, styleBorderLeft, styleBorderTop;

	if (document.defaultView && document.defaultView.getComputedStyle){

		this.stylePaddingLeft = parseInt(document.defaultView.getComputedStyle(canvas, null)['paddingLeft'], 10) 	|| 0;
		this.stylePaddingTop  = parseInt(document.defaultView.getComputedStyle(canvas, null)['paddingTop'], 10)  	|| 0;
		this.styleBorderLeft  = parseInt(document.defaultView.getComputedStyle(canvas, null)['borderLeftWidth'], 10)|| 0;
		this.styleBorderTop   = parseInt(document.defaultView.getComputedStyle(canvas, null)['borderTopWidth'], 10) || 0;
	}

	// Fix the co-ordinates caused by fix-position elements in HTML.
	var html 			= document.body.parentNode;
	this.htmlTop 		= html.offsetTop;
	this.htmlLeft 		= html.offsetLeft;

	// keeping track of State. ****
	this.valid 			= false;
	this.shapes 		= [];			// this will hold the different shapes.
	this.handles 		= []; 			// this will hold the selection handles 
	this.selectionType 	= 'canvas';
	this.dragging 		= false;
	this.rotating 		= false;
	this.selection 		= null;
	
	this.dragoffx 		= 0; 			// x offset while dragging.
	this.dragoffy 		= 0;			// y offset while dragging.
	
	this.position 		= {x:0, y:0}; 	// Record mousedown x,y values.
	this.dragResize 	= false;		// flag to determine drag-resizing
	this.resizeHandle 	= -1; 			// 0-7 Handle position.

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

		var mouse 						= myState.getMouse(e);
		var mx 							= mouse.x;
		var my 							= mouse.y;

		var shapes						= myState.shapes;
		var total 						= shapes.length;

		myState.position 				= {x:mx, y:my};

		/**
		 * collision detection.
		 * if the containment is true for either resizing or rotation handle.
		 */
		if (myState.selection && myState.selection.name != 'canvas'){

			/**
			 * this will check if the handles are clicked before checking the shapes.
			 * 
			 * check for the Resize Handles containment, 
			 * 		if selected Object is shape or image.
			 */
			var check = checkContainHandle(mx, my);

			if (check.resizeHandle >= 0 || check.rotation == true){
				
				if (myState.dragResize == false && check.resizeHandle >= 0){
					myState.resizeHandle 	= check.resizeHandle;
					myState.dragResize 		= true;
				}

				// get current selected object. //***
				var mySel 					= step.seldCanvas.shapes[step.selectionIndex];

				myState.dragoffx 			= mx - mySel.x;
				myState.dragoffy 			= my - mySel.y;

				myState.selection 			= mySel;

				// Keep track of the object mouse click offset
				mySel.valid 				= false;
				myState.valid 				= false;

				myState.selectionType 		= mySel.name;
				myState.rotating 			= check.rotation;
				myState.dragging 			= false;

				/**
				 * this will create a ghost object to imitate object movement & resizing.
				 */
				myState.ghostObject();
				return;
			}
		}

		/**
		 * Collison Detection on objects.
		 * Called when collision detection on handles has failed.
		 *
		 * check for click of the canvas objects.
		 * the outermost object should be first to be checked.
		 */
		for (var i=total-1; i>=0; i--){

			var mySel 	= shapes[i];

			// skip checking for other page, deleted and hidden objects.
			if (mySel.delete == true || mySel.page != step.currentPage || mySel.visibility != 'visible') continue;

			if (mySel.contains(mx, my)){

				step.selectLayer(i);

				// Keep track of the object mouse click offset
				mySel.valid 			= false;

				myState.dragoffx 		= mx - mySel.x;
				myState.dragoffy 		= my - mySel.y;
				myState.selection 		= mySel;
				myState.valid 			= false;
				myState.selectionType 	= mySel.name;
				myState.dragging 		= true;
				myState.rotating 		= false;
				myState.dragResize 		= false;

				/**
				 * reset handles.
				 */
				step.seldCanvas.handles.splice(0, step.seldCanvas.handles.length);

				/**
				 * this will create a ghost object to imitate object movement & resizing.
				 */
				myState.ghostObject();
				return;
			}
		}

		/**
		 * if nothing selected, clear
		 */
		if (myState.selection){
			myState.selection = null;
			myState.valid = false;
		}
		
	}, true);
	
	/**
	 * this will trigger necessary events when the mouse is moving over canvas.
	 */
	canvas.addEventListener('mousemove', function(e){

		/**
		 * don't do anything until any object is selected.
		 * Ignore selection for SeldPage, as it doesnot entertain rotation, resizing and dragging.
		 */
		if (!myState.selection || myState.selection.name == 'canvas') return;


		var mouse 		= myState.getMouse(e);
		var mx 			= mouse.x;
		var my 			= mouse.y;

		/**
		 * Drag object from the point of selection and not top-left corner.
		 */
		if (myState.dragging){

			myState.selection.x = mx - myState.dragoffx;
			myState.selection.y = my - myState.dragoffy;
			
			var mySel 			= step.seldCanvas.shapes[step.selectionIndex];

			// Point out the object to be redrawn.
			mySel.valid 		= false;
			myState.valid 		= false; // Something's dragging so we must redraw
			
			/**
			 * selectionGhost for dragging
			 */
			myState.ghostObject('drag');
		}
		else if (myState.rotating){
			/**
			 * this will rotate the object..
			 * 
			 * check inital mouse position with current.
			 * x --> ++, x <-- --.
			 */

			var ref 	= myState.position;
			var dir 	= mx - ref.x;
			var obj 	= myState.shapes[step.selectionIndex];

			// update position for next move.
			myState.position = {x:mx, y:my};


			if (dir < 0){
				obj.rotation-= 5;
			}
			else{
				obj.rotation+= 5;
			}

			obj.rotation = obj.rotation % 360;

			obj.options();
			
			obj.valid 		= false;
			myState.valid 	= false;
		}
		else if (myState.dragResize){
			/**
			 * resize the object on the basis of drag and
			 * drag-direction.
			 */
			var mySel 	= step.seldCanvas.shapes[step.selectionIndex];
			var oldx 	= mySel.x;
			var oldy 	= mySel.y;

			switch (myState.resizeHandle){
				case 0:
					mySel.x 		= mx;
					mySel.y 		= my;
					mySel.width 	+= oldx - mx;
					mySel.height 	+= oldy - my;
					break;
				case 1:
					mySel.y 		= my;
					mySel.height 	+= oldy - my;
					break;
				case 2:
					mySel.y 		= my;
					mySel.width 	= mx - oldx;
					mySel.height 	+= oldy - my;
					break;
				case 3:
					mySel.x 		= mx;
					mySel.width 	+= oldx - mx;
					break;
				case 4:
					mySel.width 	= mx - oldx;
					break;
				case 5:
					mySel.x 		= mx;
					mySel.width 	+= oldx - mx;
					mySel.height 	= my - oldy;
					break;
				case 6:
					mySel.height 	= my - oldy;
					break;
				case 7:
					mySel.width 	= mx - oldx;
					mySel.height 	= my - oldy;
					break;
			}

			mySel.options();

			mySel.valid 	= false;
			myState.valid 	= false;
		}
		else{
			/**
			 * change cursor as required.
			 */
			myState.getCursor(mx, my);
		}

	}, false);

	/**
	 * clear requests of dragging, rotating and resizing on mouseup.
	 */
	canvas.addEventListener('mouseup', function(e){

		myState.ghostObject('hide');
	}, true);

	canvas.addEventListener('mouseout', function(e) {
		
	}, true);
	
	// *** Options
	this.selectionColor = '#000000';
	this.selectionWidth = 1;
	this.interval 		= 30;

	// Draw canvas on each interval.
	setInterval(function() { myState.draw(); }, myState.interval);
}

/**
 * this will add SeldObjects to the array.
 * SeldPage, SeldText, SeldImage, SeldShape
 * Stack the shapes array to display.
 *
 * Clear other selections and make current as selected.
 */
CanvasState.prototype.addShape = function(shape){

	this.shapes.push(shape);
	this.clearSelection();

	this.selection 	= shape;
	this.valid 		= false;
}

/**
 * this will clear the canvas area for drawing.
 * clear the context.
 * ####
 * for performance optimization, instead of clearing whole canvas, (ctx.clearRect(0, 0, this.width, this.height);)
 * 	only the previous saved instance will be cleared.
 */
CanvasState.prototype.clear = function(ctx, prev){

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

	this.showFoldingLine(ctx);
}

CanvasState.prototype.showFoldingLine = function(ctx){
	
	if (step.seld.fold > 0 && step.seld.foldWidth > 0){

		var pos = 0;
		for (var i=0; i<step.seld.fold; i++){

			pos += step.seld.foldWidth;

			ctx.lineWidth = 3;
			ctx.beginPath();
			ctx.moveTo(pos-2, 0);
			ctx.lineTo(pos-2, this.height);

			ctx.strokeStyle = '#444444';
			ctx.stroke();
		}
	}
}

/**
 * this will clear any selection
 * this is called when user adds any new shape.
 */
CanvasState.prototype.clearSelection = function(){

	if (this.selection){
		this.selection 	= null; 	// Clear old selection
		this.valid 		= false;
	}
}

/**
 * this will arrange the width, height, x and y position of the ghost
 * object. to imitate dragging, rotating and resizing.
 */
CanvasState.prototype.ghostObject = function(mode){

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
	else if(action == 'drag'){
		return;
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

		step.selectionGhost.addClass('hidden');
	}
}

/**
 * this method will draw the stacked shapes in the canvas.
 * this is called frequently as the INTERVAL. 
 * But will no nothing unless the canvas is not valid.
 */

/**
 * Each object will need it's own canvas with id Starting with prefix "seld_canvas_page_" followed by index number
 * e.g. seld_canvas_page_0, seld_canvas_page_1 and so on.
 * Minimum number of canvas required is the total number of pages.
 * 
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * shapes[i].draw(ctx); ::: if need to draw objects in one canvas.
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 */
CanvasState.prototype.draw = function(){

	/**
	 * this method will be called frequently,
	 * the function will execute ONLY IF THE canvasState is invalidated.
	 */

	if (!this.valid){

		var ctx 		= this.ctx;
		var shapes 		= this.shapes;
		var shapeCtx 	= null; 		// Shape context. Every object/shape has their own canvas and context.

		/**
		 * This will clear the previous selections on the canvas.
		 * ctx refers to the canvas only for drawing SelectionBorders and SelectionHandles.
		 */
		this.clear(ctx, ctx._prevState);

		for (var i=0; i<shapes.length; i++){

			var shape = shapes[i]; 		// current shape

			/**
			 * find the context for current object.
			 *
			 * if not available, create one and take the reference.
			 */
			var seldCanvasPage 	= 'seld_canvas_page_' + i;
			if ($('#'+seldCanvasPage).length <= 0){

				// create canvas with index position z-index.
				var zIndex = i+1;
				$('#canvas_ghost').append('<canvas class="sub-canvas" id="' + seldCanvasPage + '" width="' + ctx.canvas.width + '" height="' + ctx.canvas.height + '" style="z-index:' + zIndex + '"></canvas>');
				/**
				 * Update step.seldCanvasObjects variable for further reference.
				 */
				step.seldCanvasObjects.push(new CanvasObjectState(document.getElementById(seldCanvasPage), ctx.canvas.width, ctx.canvas.height));
			}

			/**
			 * this will make sure, the current visible, page objects will
			 * be drawn in the canvas.
			 */
			if (shape.valid == false){

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
				if (shape.page == this.currentPage && shape.delete == false && shape.visibility == 'visible'){
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
			 */
			ctx.save();
			var deg 		= mySel.rotation % 360;
			var rad 		= deg * Math.PI / 180;
			var translation = {x:mySel.x+mySel.width/2, y:mySel.y+mySel.height/2, angle:deg};

			/**
			 * Update previous state.
			 */
			ctx._prevState = {x:-mySel.width/2, y:-mySel.height/2, w:mySel.width, h:mySel.height, t:translation};

			ctx.translate(translation.x, translation.y);
			ctx.rotate(rad);

			// this will draw the selection Box.
			ctx.strokeRect(-mySel.width/2, -mySel.height/2, mySel.width, mySel.height);

			/**
			 * Prepare Resizing handles
			 */
			step.seldCanvas.handles = [];
			step.seldCanvas.handles.push(new SeldHandles('rotation', mySel.x, mySel.y, mySel.width, mySel.height, this.selectionColor, 0, translation));

			/**
			 * Add Resizing handles for type shape and image.
			 */
			if (this.selectionType == 'shape' || this.selectionType == 'image'){

				for (var i=0; i<8; i++){
					step.seldCanvas.handles.push(new SeldHandles('resize', mySel.x, mySel.y, mySel.width, mySel.height, this.selectionColor, i, translation));
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

/**
 * This will export the pages with their respective layers drawn on it.
 * 
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * shapes[i].draw(ctx); ::: if need to draw objects in one canvas.
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 */
CanvasState.prototype.exportDraw = function(pageNumber){

	/**
	 * this method will be called frequently,
	 * the function will execute ONLY IF THE canvasState is invalidated.
	 */

	var ctx 		= this.ctx;
	var shapes 		= this.shapes;

	/**
	 * This will clear the previous selections on the canvas.
	 * ctx refers to the canvas only for drawing SelectionBorders and SelectionHandles.
	 */
	this.clear(ctx, null);

	for (var i=0; i<shapes.length; i++){

		var shape = shapes[i]; 		// current shape

		// Skip drawing of shapes that is beyond canvas scope.
		if (shape.x > this.width || shape.y > this.height || shape.x + shape.width < 0 || shape.y + shape.height < 0) continue;

		/**
		 * Only draw the object if visibility is set to visible and delete is false.1
		 */
		if (shape.page == pageNumber && shape.delete == false && shape.visibility == 'visible'){
			shape.draw(ctx);
		}
	}
}

/**
 * this will calculate the canvas co-ordinates
 * the mouse x,y is sampled to get canvas objects x,y after scaling.
 */
CanvasState.prototype.getMouse = function(e){

	var scale 	= parseInt($('#canvas_zoom').val());
	scale 		= scale < 1 ? 1 : scale > 200 ? 200 : scale;

	var oWidth 	= parseInt($('#pad').attr('width'));
	var oHeight = parseInt($('#pad').attr('height'));

	var offset 	= $('#pad').offset();
	var offsetX = Math.round(offset.left * scale / 100);
	var offsetY = Math.round(offset.top  * scale / 100);

	var mx 		= e.pageX - offsetX;
	var my 		= e.pageY - offsetY;

	/**
	 * maximum sampled co-ordinates. (x and y)
	 */
	var maxX 	= oWidth  * scale;
	var maxY 	= oHeight * scale;

	// percent
	var factorX = mx / maxX * 100;
	var factorY = my / maxY * 100;

	mx = ~~(0.5 + factorX * oWidth); 
	my = ~~(0.5 + factorY * oHeight);

	return {x: mx, y: my};
}

/**
 * ===========================================================================
 * Mouse Cursors.
 * Display various cursor on the basis of mouse position.
 * ===========================================================================		 
 */
CanvasState.prototype.getCursor = function(mx, my){

	var check 	= checkContainHandle(mx, my);
	var cursor 	= 'default';

	if (check.resizeHandle >= 0){

		switch (check.resizeHandle){

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
}



/**
 * ===========================================================================
 * File Settings
 * ===========================================================================
 */
var formSettings = {
	initCheck: function(){
		// Make sure all the ajax-dependent are hidden
		$('.ajax_load').parent().parent().addClass('hidden');

		var total 	= $('.control-form').length;
		var ajax 	= $('.control-form.ajax_load').length;
		//console.log(total, ajax);
		$('.control-form.ajax_load').each(function(){
			var o 		= $(this);
			var ref_id 	= $(this).attr('data-dep-id');
			var ref_val = $(this).attr('data-dep-val').toLowerCase();
			var ref 	= $('#form-control-' + ref_id);

			if (!ref.parent().parent().hasClass('hidden') && ref.val().toLowerCase() == ref_val){
				o.parent().parent().removeClass('hidden');
				//console.log(o.attr('data-id'), ref.val(), ref_val);
			}
		});
	},
	changeCheck: function(){
		//console.log('checking');
		formSettings.initCheck();
	},
	validate: function(){
		$('.control-form.ajax_load').each(function(){
			var o = $(this);
			if (!o.parent().parent().hasClass('hidden')){
				var name = o.attr('data-name');
				o.attr('name', name);
			}
			else{
				o.parent().parent().remove();
			}
		});
	},
	init: function(){
		formSettings.initCheck();
		$('.control-form').change(formSettings.changeCheck);
		$('#frmSettings').submit(formSettings.validate);
	}
};



/**
 * ===========================================================================
 * F I L E    U P L O A D 
 * ===========================================================================
 */
var fileUpload = {
	myUploadData: 	[], 			// array for uploaded file names.
	folderName: '',					// Folder Name
	settings: function(){
		return {
		    url: 			base_url() + 'm/upload/' + fileUpload.folderName,
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

						photo += '<li><div class="img-wrapper"><img src="' + base_url + v + '" data-width="' + w + '" data-height="' + h + '" /></div></li>';

						$('#my-images-list').append(photo);
					});
					//var ob = $('input[name="frm_photos"]');
					//vl = ob.val(ob.val() + ',' + k);
				});
			}
			$('#no_img_uploaded').remove();
			fileUpload.myUploadData = new Array();
			$('#my-images-list li:last-child').trigger('click');
		}
	},
	init: function(){
		this.folderName = step.seld.id;
	}
};