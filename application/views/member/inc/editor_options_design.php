<div id="canvas_options">
	<div id="design-tools-options-wrapper">
		<ul id="design-tools-options" class="current-canvas">
			<li class="canvas-options">
				<h3>Canvas Options</h3>
			</li>
			<li class="canvas-options">
				<div class="col-xs-4">Background</div>
				<div class="col-xs-6">
					<span class="glyphicon glyphicon-tint"></span>
					<input type="text" value="#ffffff" maxlength="7" style="width:50px" class="dToolOptionFontColorCanvas" data-type="color" id="textColorSelect" />
				</div>
			</li>
			<?php
			// Display option only if more than one page available.
			$total_pages = $prop['page'] * $prop['face'];
			if ($total_pages > 1){
			?>
			<li class="canvas-options">
				<div class="col-xs-12">
					<label><input type="checkbox" id="apply_background_all" style="position:relative;top:4px;zoom:140%;"> Apply to all pages</label>
				</div>
			</li>
			<?php
			}
			?>
			<li class="shape-options">
				<h3>Shape Options</h3>
				<div class="col-xs-4 nopadding">Background</div>
				<div class="col-xs-6 nopading">
					<span class="glyphicon glyphicon-text-background"></span>
					<input type="text" value="#ffffff" maxlength="7" style="width:50px" class="dToolOptionShapeColor" data-type="bgcolor" id="shapeColorSelect" />
				</div>
			</li>
			<li class="shape-options">
				<div class="col-xs-4 nopadding">Opacity</div>
				<div class="col-xs-6 nopading"><input id="slider_shape_opacity" type="range" min="0" max="100" step="5" /></div>
			</li>
			<li class="shape-options">
				<div class="col-xs-4 nopadding">Border Color</div>
				<div class="col-xs-6 nopading">Select</div>
			</li>
			<li class="text-options">
				<h3>Text Options</h3>
				<div class="col-xs-4 nopadding">Font</div>
				<div class="col-xs-6 nopadding">
					<div class="dToolOption" style="width:90px;">
						<span class="glyphicon glyphicon-font"></span>
						<?php
						$options = array(
										'agisarang'	=> 'Agisarang'
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
						$opts = array(8, 10, 12, 13, 14, 15, 16, 18, 20, 22, 24, 26, 28, 30, 32, 36, 40, 48, 56, 64, 72, 80, 96, 128, 156, 180, 196, 212, 240, 264, 300);
						$options = array();
						foreach ($opts as $o){
							$options[''.$o] = $o.'pt';
						}
						echo form_dropdown('opt-font-size', $options, 12 , 'class="dToolOptionDropdown" data-type="size"');
						?>
					</div>
				</div>
			</li>
			<li class="text-options hidden">
				<div class="col-xs-4 nopadding">Line Height</div>
				<div class="col-xs-6 nopadding">
					<div class="dToolOption" style="width:88px;">
						<span class="glyphicon glyphicon-text-height"></span>
						<?php
						//$opts = array(8, 10, 12, 13, 14, 15, 16, 18, 20, 22, 24, 26, 28, 30, 32, 36, 40, 48, 56, 64, 72, 80, 96);
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
						<input type="text" value="#5367ce" maxlength="7" style="width:50px" class="dToolOptionFontColor isColorPicker" data-type="color" id="textColorSelect" />
					</div>
				</div>
			</li>
			<li class="text-options hidden">
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
			<li class="text-options hidden">
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
			<li class="text-options separator"></li>
			<li class="text-options">
				<textarea id="text-options-textarea" rows="5" placeholder="Write here...">Write here...</textarea>
			</li>
			<li class="image-options">
				<h3>
					<div><span class="active" data-ref="image-options-select">Select Image</span></div>
					<div><span data-ref="image-options-upload">Upload</span></div>
				</h3>
				<div id="image-options-upload" class="hidden">
					<div class="image-upload-main" data-ref="<?=base_url()?>">
						<div id="status"></div>
						<div class="itemWrap" id="image_uploader">
							<button class="btn btn-info btn-sm">Upload</button>
						</div>
						<small>You can click on upload button or drag your photos here.</small>
					</div>
					<input type="hidden" name="frm_photos" id="frm_photos" value="">					
				</div>
				<div id="image-options-select">
					<ul id="my-images-list">
					<?php
					/**
					 * LOAD ALL THE CLIENT'S IMAGES
					 */
					$folder = './files/uploads/'.$folder.'/thumbs/';
					foreach(glob($folder.'*') as $filename){
						$img = basename($filename);
						if ($img == 'index.html'){ continue; }
						
					    echo '<li><div class="img-wrapper"><button class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-ok"></span> Select</button><img src="'. base_url() . $filename . '" /></div></li>';
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