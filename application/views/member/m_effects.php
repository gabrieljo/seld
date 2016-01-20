<?php include('inc/m_header.php') ?>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2">
			<h1>Canvas</h1>
			<canvas id="myCanvas" width="800" height="320"></canvas>
			<hr>
			<?php
			$options = array('Arial'=>'Arial', 'Agisarang'=>'Agisarang');
			echo ' Font: ' . form_dropdown('font-family', $options, 'Agisarang', 'id="font-family"');
			$options = array('20px'=>'20pt', '30px'=>'30pt', '40px'=>'40pt', '60px'=>'60pt', '80px'=>'80pt');
			echo ' Font Size: '.form_dropdown('font-size', $options, '80px', 'id="font-size"');
			$options = array('red'=>'Red', 'black'=>'Black', 'blue'=>'Blue', 'green'=>'Green');
			echo ' Color: ' . form_dropdown('color', $options, 'red', 'id="color"');
			$options = array('normal'=>'Normal', 'bold'=>'Bold');
			?>
			<hr>
			<textarea name="nm" id="ta" class="form-control" rows="4"></textarea>
			<br>
			<button>Save</button>
			<input id="slider_shape_opacity" type="range" min="0" max="100" step="5">
		</div>
	</div>
</div>
<style>
#myCanvas{border: 1px solid #f00;}
</style>
<script>
$(function(){
	var canvas = document.getElementById("myCanvas");
	var context = canvas.getContext("2d");
	$('#ta').keyup(function(){
		updateCanvas(canvas, context);
	});
	$('select').change(function(){
		updateCanvas(canvas, context);
	});
	$('#slider_shape_opacity').change(function(){
		var vl = $(this).val();
		$('#myCanvas').css('zoom', vl+'%');
	});

	$('button').click(function(){
		var dataURL = canvas.toDataURL();
		$.ajax({
		  type: "POST",
		  url: base_url() + "u/saveimg",
		  data: {  
		     imgBase64: dataURL
		  }
		}).done(function(o) {
		  console.log('saved'); 
		  // If you want the file to be visible in the browser 
		  // - please modify the callback in javascript. All you
		  // need is to return the url to the file, you just saved 
		  // and than put the image in your browser.
		});
	});
});
function updateCanvas(canvas, context){
	context.clearRect(0, 0, canvas.width, canvas.height);
	x = 100;//canvas.width / 2; 
	y = 150;//canvas.height / 2;
	maxWidth = 200;
	lineHeight = parseInt($('#font-size').val()) + 10;

	var vl = $('#ta').val();
	context.font = $('#font-size').val() + " " + $('#font-family').val();
	context.fillStyle = $('#color').val();
	
	context.lineWidth = 3;

	// Create gradient
	/*var gradient = context.createLinearGradient(0,0,canvas.width,0);
	gradient.addColorStop("0","magenta");
	gradient.addColorStop("0.5","blue");
	gradient.addColorStop("1.0","red");
	// Fill with gradient
	context.fillStyle=gradient;*/

	//context.textAlign = 'center';
	context.strokeStyle = $('#color').val();
	context.strokeText(vl, x, y);
	//context.fillText(vl,x,y);

	// Shadow Color
	context.shadowColor = "black";
	context.shadowOffsetX = 5;
	context.shadowOffsetY = 7;
	context.shadowBlur = 20;

	//wrapText(context, vl, x, y, maxWidth, lineHeight);
}

function wrapText(context, text, x, y, maxWidth, lineHeight) {
	var words = text.split(' ');
	var line = '';

	for(var n = 0; n < words.length; n++) {
	  var testLine = line + words[n] + ' ';
	  var metrics = context.measureText(testLine);
	  var testWidth = metrics.width;
	  if (testWidth > maxWidth && n > 0) {
	    context.fillText(line, x, y);
	    line = words[n] + ' ';
	    y += lineHeight;
	  }
	  else {
	    line = testLine;
	  }
	}
	context.fillText(line, x, y);
}
</script>
<?php include('inc/m_footer.php') ?>