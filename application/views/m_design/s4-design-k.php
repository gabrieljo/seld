<?php
// $prop 
// width, height, scale, face & page
$inc = array(
				'jquery-ui.min.js', 			
				'ui/jquery-ui.min.css', 		
				'ui/jquery-ui.structure.min.css', 		
				'ui/jquery-ui.theme.min.css',
				'bootstrap-colorpicker.min.js',	
				'bootstrap-colorpicker.min.css'
			);
echo inc($inc);
?>
<ul id="design-pages" class="hidden" data-width="<?=$prop['width']?>" data-height="<?=$prop['height']?>" data-scale="<?=$prop['scale']?>">
	<?php
	for ($i=1; $i<=$prop['page']; $i++){
		echo '<li id="node'.$i.'"></li>';
	}
	?>
</ul>
<div id="canvas_options">
	<div id="design-tools-options-wrapper">
		<ul id="design-tools-options">
			<li class="text-options">
				<div class="dToolOption" style="width:90px;">
					<span class="glyphicon glyphicon-font"></span>
					<?php
					$options = array(
									'Arial'		=> 'Arial',
									'Verdana'	=> 'Verdana',
									'Courier'	=> 'Courier',
									'Georgia'	=> 'Georgia'
									);
					echo form_dropdown('opt-font', $options, 'Arial', 'class="dToolOptionDropdown" data-type="font"');
					?>
				</div>
			</li>
			<li class="text-options">
				<div class="dToolOption" style="width:78px;">
					<span class="glyphicon glyphicon-text-size"></span>
					<?php
					$opts = array(8, 10, 12, 13, 14, 15, 16, 18, 20, 22, 24, 26, 28, 30, 32, 36, 40, 48, 56, 64, 72, 80, 96);
					$options = array();
					foreach ($opts as $o){
						$options[''.$o] = $o.'pt';
					}
					echo form_dropdown('opt-font-size', $options, '12', 'class="dToolOptionDropdown" data-type="size"');
					?>
				</div>
			</li>
			<li class="text-options">
				<div class="dToolOption" style="width:78px;">
					<span class="glyphicon glyphicon-text-height"></span>
					<?php
					$opts = array(8, 10, 12, 13, 14, 15, 16, 18, 20, 22, 24, 26, 28, 30, 32, 36, 40, 48, 56, 64, 72, 80, 96);
					$options = array();
					foreach ($opts as $o){
						$options[''.$o] = $o.'pt';
					}
					echo form_dropdown('opt-font-height', $options, '12', 'class="dToolOptionDropdown" data-type="height"');
					?>
				</div>
			</li>
			<li class="text-options">
				<div class="dToolOption" style="width:80px;">
					<span class="glyphicon glyphicon-text-color"></span>
					<input type="text" value="#5367ce" maxlength="7" style="width:50px" class="dToolOptionFontColor" data-type="color" id="textColorSelect" />
				</div>
			</li>		
			<li class="text-options">
				<div class="dToolOption hasBorder" data-type="bold" title="Bold">
					<span class="glyphicon glyphicon-bold"></span>
				</div>
			</li>
			<li class="text-options">
				<div class="dToolOption hasBorder" data-type="italic" title="Italic">
					<span class="glyphicon glyphicon-italic"></span>
				</div>
			</li> 
			<li class="image-options">
				<div class="dToolOption hasBorder" data-type="upload">
					<span class="glyphicon glyphicon-picture"></span> 
					Upload Image
				</div>
			</li>
			<li class="image-options">
				<div class="dToolOption hasBorder" data-type="myfiles">
					<span class="glyphicon glyphicon-folder-open"></span> 
					Open my images
				</div>
			</li>
		</ul>
	</div>
</div>
<div id="canvas">
	<div id="pad" class="">
		<?=$product->pr_contents?>
	</div>
</div>


