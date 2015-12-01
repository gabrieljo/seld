<div id="canvas_options">
	<div id="design-tools-options-wrapper">
		<ul id="design-tools-options">
			<li class="text-options">
				<h3>Text Options</h3>
				<div class="col-xs-4 nopadding">Font</div>
				<div class="col-xs-6 nopadding">
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
				</div>
			</li>
			<li class="text-options">
				<div class="col-xs-4 nopadding">Font Size</div>
				<div class="col-xs-6 nopadding">
					<div class="dToolOption" style="width:90px;">
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
				</div>
			</li>
			<li class="text-options">
				<div class="col-xs-4 nopadding">Line Height</div>
				<div class="col-xs-6 nopadding">
					<div class="dToolOption" style="width:88px;">
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
				</div>
			</li>
			<li class="text-options">
				<div class="col-xs-4 nopadding">Font Color</div>
				<div class="col-xs-6 nopadding">
					<div class="dToolOption" style="width:80px;">
						<span class="glyphicon glyphicon-text-color"></span>
						<input type="text" value="#5367ce" maxlength="7" style="width:50px" class="dToolOptionFontColor" data-type="color" id="textColorSelect" />
					</div>
				</div>
			</li>
			<li class="text-options">
				<div class="col-xs-4 nopadding">Rotation</div>
				<div class="col-xs-6 nopadding">
					<div class="dToolOption" style="width:80px;">
						<span class="glyphicon glyphicon-repeat"></span>
						<input type="text" value="0" maxlength="3" style="width:30px" class="dToolOptionRotation" data-type="angle" id="textRotation" />°
					</div>
				</div>
			</li>
			<li class="text-options separator"></li>
			<li class="text-options">
				<div class="col-xs-4 nopadding">Font Style</div>
				<div class="col-xs-6">
					<div class="dToolOption hasBorder" data-type="bold" title="Bold">
						<span class="glyphicon glyphicon-bold"></span>
					</div>
					<div class="dToolOption hasBorder" data-type="italic" title="Italic">
						<span class="glyphicon glyphicon-italic"></span>
					</div>
				</div>
			</li>
			<li class="text-options">
				<div class="col-xs-4 nopadding">Alignment</div>
				<div class="col-xs-7">
					<div class="dToolOption hasBorder layerPropertyAlignment" data-type="left-align" title="Left Align">
						<span class="glyphicon glyphicon-align-left"></span>
					</div>
					<div class="dToolOption hasBorder layerPropertyAlignment" data-type="center-align" title="Center Align">
						<span class="glyphicon glyphicon-align-center"></span>
					</div>
					<div class="dToolOption hasBorder layerPropertyAlignment" data-type="right-align" title="Right Align">
						<span class="glyphicon glyphicon-align-right"></span>
					</div>
				</div>				
			</li>
			<li class="image-options">
				<h3>Image Options</h3>
				<div id="image-options-upload">
					<button class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-upload"></span> Upload New Image</button>
				</div>
				<div id="image-options-select" class="hidden">
					<button class="btn btn-sm btn-success"><span class="glyphicon glyphicon-ok"></span> Select Image</button>
				</div>
				<div>
					<ul id="my-images-list">
					<?php
					/**
					 * LOAD ALL THE CLIENT'S IMAGES
					 */
					$folder = './files/uploads/'.$folder.'/thumbs/';
					foreach(glob($folder.'*') as $filename){
						$img = basename($filename);
						if ($img == 'index.html'){ continue; }
						
					    echo '<li><div class="img-wrapper"><img src="'. base_url() . $filename . '" /></div></li>';
					}
					?>
					</ul>
					<div class="cf"></div>
				</div>
			</li>
			<!-- <li class="image-options">
				<div class="dToolOption hasBorder" data-type="upload">
					<span class="glyphicon glyphicon-picture"></span> 
				</div>
			</li>
			<li class="image-options">
				<div class="dToolOption hasBorder" data-type="myfiles">
					<span class="glyphicon glyphicon-folder-open"></span> 
					Open my images
				</div>
			</li> -->
		</ul>
	</div>
</div><!-- #canvas_options -->