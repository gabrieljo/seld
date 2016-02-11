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
					<input type="text" value="#ffffff" maxlength="7" style="width:50px" class="dToolOptionInput isColorPicker" id="seldCanvas-bgColor" data-type="seldCanvas-bgColor" data-default="#ffffff" readonly="readonly" />
				</div>
			</li>
			<?php
			// Display option only if more than one page available.
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
				<div class="col-xs-4 nopadding">Shape</div>
				<div class="col-xs-6 nopading">
					<div class="dToolOption" style="width:80px;">
						<div class="dToolOption dToolOptionButton hasBorder hasGroup groupedOptions" data-target=".seldshape-rectangle-options" data-type="seldshape-type" data-value="rectangle" title="Rectangle">
							<span class="glyphicon glyphicon-stop"></span>
						</div>
						<div class="dToolOption dToolOptionButton hasBorder hasGroup groupedOptions" data-target=".seldshape-circle-options" data-type="seldshape-type" data-value="circle" title="Circle">
							<span class="glyphicon glyphicon-record"></span>
						</div>
					</div>
				</div>
			</li>
			<li class="shape-options separator"></li>
			<li class="shape-options">
				<div class="col-xs-4 nopadding">Width</div>
				<div class="col-xs-6 nopading">
					<div class="dToolOption" style="width:80px;">
						<span class="glyphicon glyphicon-text-color"></span>
						<input type="text" value="" maxlength="7" style="width:50px" class="dToolOptionInput" data-type="seldshape-width" id="seldshape-width" data-default="1" /> pt
					</div>
				</div>
			</li>
			<li class="shape-options">
				<div class="col-xs-4 nopadding">Height</div>
				<div class="col-xs-6 nopading">
					<div class="dToolOption" style="width:80px;">
						<span class="glyphicon glyphicon-text-color"></span>
						<input type="text" value="" maxlength="7" style="width:50px" class="dToolOptionInput" data-type="seldshape-height" id="seldshape-height" data-default="1" /> pt
					</div>
				</div>
			</li>
			<li class="shape-options seldshape-rectangle-options">
				<div class="col-xs-4 nopadding">Rotation</div>
				<div class="col-xs-6 nopading">
					<div class="dToolOption" style="width:80px;">
						<span class="glyphicon glyphicon glyphicon-repeat"></span>
						<input type="text" value="0" maxlength="3" style="width:30px" class="dToolOptionInput" data-type="seldshape-rotation" id="seldshape-rotation" data-default="0" /> °
					</div>
				</div>
			</li>			
			<li class="shape-options separator"></li>
			<li class="shape-options">
				<div class="col-xs-4 nopadding">Opacity</div>
				<div class="col-xs-6 nopading"><input id="seldshape-opacity" class="dToolOptionDropdown" data-type="seldshape-opacity" type="range" min="1" max="100" step="1" /></div>
			</li>
			<li class="shape-options">
				<div class="col-xs-4 nopadding">Fill Color</div>
				<div class="col-xs-6 nopading">
					<span class="glyphicon glyphicon-text-background"></span>
					<input type="text" value="#ffffff" maxlength="7" style="width:50px" class="dToolOptionInput isColorPicker" data-type="seldshape-color" id="seldshape-color" data-default="#ffffff" readonly="readonly" />
				</div>
			</li>
			<li class="shape-options">
				<div class="col-xs-4 nopadding">Gradient</div>
				<div class="col-xs-6">
					<div class="dToolOption dToolOptionButton hasBorder hasGroup" data-target=".seldshape-groupGradient" data-type="seldshape-gradient" title="Gradient" id="seldshape-gradient">
						<span class="glyphicon glyphicon-adjust"></span>
					</div>
				</div>
			</li>
			<li class="shape-options seldshape-groupGradient">
				<div class="col-xs-4 nopadding">Color</div>
				<div class="col-xs-7">
					<input type="text" value="" maxlength="7" style="width:50px" class="isColorPicker dToolOptionInput" data-type="seldshape-gradientColor" id="seldshape-gradientColor" data-default="#777777" readonly="readonly" />
				</div>
			</li>

			<li class="shape-options separator"></li>
			<li class="shape-options">
				<div class="col-xs-4 nopadding">Border Size</div>
				<div class="col-xs-6 nopading">
					<div class="dToolOption" style="width:80px;">
						<span class="glyphicon glyphicon-text-width"></span>
						<input type="text" value="0" maxlength="3" style="width:30px" class="dToolOptionInput" data-type="seldshape-borderSize" id="seldshape-borderSize" data-default="0" />
					</div>
				</div>
			</li>
			<li class="shape-options">
				<div class="col-xs-4 nopadding">Border Color</div>
				<div class="col-xs-6 nopading">
					<span class="glyphicon glyphicon-text-background"></span>
					<input type="text" value="#ffffff" maxlength="7" style="width:50px" class="dToolOptionInput isColorPicker" data-type="seldshape-borderColor" id="seldshape-borderColor" data-default="#ffffff" readonly="readonly" />
				</div>
			</li>


			<li class="text-options" style="margin-bottom:0;">
				<h3>Text Options</h3>
				<textarea id="seldtext-value" class="dToolOptionInput" data-type="seldtext-value" rows="5" placeholder="Write here...">Write here...</textarea>
			</li>
			<li class="text-options separator"></li>
			<li class="text-options">
				<div class="col-xs-4 nopadding">
					Presets
				</div>
				<div class="col-xs-6 nopadding">
					<span class="glyphicon glyphicon-fire"></span> 
					<button class="btn btn-info btn-xs dToolOptionButton hasGroup" data-type="seldtext-viewPresets" id="seldtext-viewPresets" data-target=".seldtext-preset" title="Select from Preset">view Effects</button>
				</div>
			</li>
			<li class="text-options seldtext-preset">
				<div id="seldtext-presets">
					<h4>Presets <span class="pull-right btn-close-presets" title="Close Presets">&times;</span></h4>
					<div id="seldtext-presets-wrapper">
						<ul id="seldtext-presetsList"></ul>
					</div><!-- .seldtext-presets-wrapper -->
				</div>
			</li>
			<li class="text-options separator"></li>
			<li class="text-options">
				<div class="col-xs-4 nopadding">Opacity</div>
				<div class="col-xs-6 nopading"><input id="seldtext-opacity" class="dToolOptionDropdown" data-type="seldtext-opacity" type="range" min="1" max="100" step="1" /></div>
			</li>
			<li class="text-options separator"></li>
			<li class="text-options">
				<div class="col-xs-4 nopadding">Font</div>
				<div class="col-xs-6 nopadding">
					<div class="dToolOption" style="width:90px;">
						<span class="glyphicon glyphicon-font"></span>
						<?php
						$options = array(
										'Arial' 	=> 'Arial',
										'Georgia'	=> 'Georgia',
										'Verdana'	=> 'Verdana',
										'agisarang'	=> 'Agisarang'
										);
						echo form_dropdown('opt-font', $options, 'Arial', 'class="dToolOptionDropdown" id="seldtext-font" data-type="seldtext-font"');
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
						$options = array();
						for ($i=4; $i<=500; $i++){
							$options[''.$i] = $i.'pt';
						}
						echo form_dropdown('opt-font-size', $options, 7 , 'class="dToolOptionDropdown" id="seldtext-size" data-type="seldtext-size"');
						?>
					</div>
				</div>
			</li>
			<li class="text-options">
				<div class="col-xs-4 nopadding">Font Color</div>
				<div class="col-xs-6 nopadding">
					<div class="dToolOption" style="width:80px;">
						<span class="glyphicon glyphicon-text-color"></span>
						<input type="text" value="#5367ce" maxlength="7" style="width:50px" class="dToolOptionInput isColorPicker" data-type="seldtext-color" id="seldtext-textcolor" data-default="#000000" readonly="readonly" />
					</div>
				</div>
			</li>
			<li class="text-options">
				<div class="col-xs-4 nopadding">Gradient</div>
				<div class="col-xs-6">
					<div class="dToolOption dToolOptionButton hasBorder hasGroup" data-target=".seldtext-groupGradient" data-type="seldtext-gradient" title="Gradient" id="seldtext-gradient">
						<span class="glyphicon glyphicon-adjust"></span>
					</div>
				</div>
			</li>
			<li class="text-options seldtext-groupGradient">
				<div class="col-xs-4 nopadding">Color</div>
				<div class="col-xs-7">
					<input type="text" value="" maxlength="7" style="width:50px" class="isColorPicker dToolOptionInput" data-type="seldtext-gradientColor" id="seldtext-gradientColor" data-default="#777777" readonly="readonly" />
				</div>
			</li>
			<li class="text-options separator"></li>
			<li class="text-options">
				<div class="col-xs-4 nopadding">Font Style</div>
				<div class="col-xs-6">
					<div class="dToolOption dToolOptionButton hasBorder" id="seldtext-bold" data-type="seldtext-bold" title="Bold">
						<span class="glyphicon glyphicon-bold"></span>
					</div>
					<div class="dToolOption dToolOptionButton hasBorder" id="seldtext-italic" data-type="seldtext-italic" title="Italic">
						<span class="glyphicon glyphicon-italic"></span>
					</div>
				</div>
			</li>
			<li class="text-options">
				<div class="col-xs-4 nopadding">Alignment</div>
				<div class="col-xs-7">
					<div class="dToolOption dToolOptionButton hasBorder groupedOptions" data-type="seldtext-align" data-value="left" title="Left Align">
						<span class="glyphicon glyphicon-align-left"></span>
					</div>
					<div class="dToolOption dToolOptionButton hasBorder groupedOptions" data-type="seldtext-align" data-value="center" title="Center Align">
						<span class="glyphicon glyphicon-align-center"></span>
					</div>
					<div class="dToolOption dToolOptionButton hasBorder groupedOptions" data-type="seldtext-align" data-value="right" title="Right Align">
						<span class="glyphicon glyphicon-align-right"></span>
					</div>
				</div>				
			</li>
			<li class="text-options separator"></li>
			<li class="text-options">
				<div class="col-xs-4 nopadding">Text Shadow</div>
				<div class="col-xs-6">
				<div class="dToolOption dToolOptionButton hasBorder hasGroup" data-target=".seldtext-groupShadow" data-type="seldtext-shadow" id="seldtext-shadow" title="Text Shadow">
						<span class="glyphicon glyphicon-text-background"></span>
					</div>
				</div>
			</li>
			<li class="text-options seldtext-groupShadow">
				<div class="col-xs-4 nopadding">Color</div>
				<div class="col-xs-7">
					<input type="text" value="#5367ce" maxlength="7" style="width:50px" class="dToolOptionInput isColorPicker" data-type="seldtext-shadowColor" id="seldtext-shadowColor" data-default="aaaaaa" readonly="readonly" />
				</div>
			</li>
			<li class="text-options seldtext-groupShadow">
				<div class="col-xs-4 nopadding">Offset X</div>
				<div class="col-xs-7">
					<input type="text" value="" maxlength="4" min="0" max="100" style="width:40px" class="dToolOptionInput" data-type="seldtext-shadowX" id="seldtext-shadowX" data-default='0' />
				</div>
			</li>
			<li class="text-options seldtext-groupShadow">
				<div class="col-xs-4 nopadding">Offset Y</div>
				<div class="col-xs-7">
					<input type="text" value="" maxlength="4" min="0" max="100" style="width:40px" class="dToolOptionInput" data-type="seldtext-shadowY" id="seldtext-shadowY" data-default="0" />
				</div>
			</li>
			<li class="text-options seldtext-groupShadow">
				<div class="col-xs-4 nopadding">Blur</div>
				<div class="col-xs-7">
					<input type="text" value="" maxlength="4" min="0" max="100" style="width:40px" class="dToolOptionInput" data-type="seldtext-shadowBlur" id="seldtext-shadowBlur" data-default="0" />
				</div>
			</li>
			<li class="text-options separator"></li>
			<li class="text-options">
				<div class="col-xs-4 nopadding">Text Stroke</div>
				<div class="col-xs-6">
					<div class="dToolOption dToolOptionButton hasBorder hasGroup" data-target=".seldtext-groupStroke" id="seldtext-stroke" data-type="seldtext-stroke" title="Text Stroke">
						<span class="glyphicon glyphicon-pencil"></span>
					</div>
				</div>
			</li>
			<li class="text-options seldtext-groupStroke">
				<div class="col-xs-4 nopadding">Color</div>
				<div class="col-xs-7">
					<input type="text" value="" maxlength="7" style="width:50px" class="isColorPicker dToolOptionInput" data-type="seldtext-strokeColor" id="seldtext-strokeColor" data-default="000000" readonly="readonly" />
				</div>
			</li>
			<li class="text-options seldtext-groupStroke">
				<div class="col-xs-4 nopadding">Line Width</div>
				<div class="col-xs-7">
					<input type="text" value="" maxlength="7" style="width:50px" class="dToolOptionInput" data-type="seldtext-strokeSize" id="seldtext-strokeSize" data-default="1" />
				</div>
			</li>
			<li class="text-options separator"></li>
			<li class="text-options">
				<div class="col-xs-4">Rotation</div>
				<div class="col-xs-6">
					<span class="glyphicon glyphicon glyphicon-repeat"></span>
					<input type="text" value="0" maxlength="3" style="width:30px" class="dToolOptionInput" data-type="seldtext-rotation" id="seldtext-rotation" data-default="0" /> °
				</div>
			</li>

			<li class="image-options">
				<h3>Image Options</h3>
				<div class="col-xs-4 nopadding">Width</div>
				<div class="col-xs-6 nopading">
					<div class="dToolOption" style="width:80px;">
						<span class="glyphicon glyphicon-text-color"></span>
						<input type="text" value="" maxlength="7" style="width:50px" class="dToolOptionInput" data-type="seldimage-width" id="seldimage-width" data-default="1" /> pt
					</div>
				</div>
			</li>
			<li class="image-options">
				<div class="col-xs-4 nopadding">Height</div>
				<div class="col-xs-6 nopading">
					<div class="dToolOption" style="width:80px;">
						<span class="glyphicon glyphicon-text-color"></span>
						<input type="text" value="" maxlength="7" style="width:50px" class="dToolOptionInput" data-type="seldimage-height" id="seldimage-height" data-default="1" /> pt
					</div>
				</div>
			</li>
			<li class="image-options seldimage-rectangle-options">
				<div class="col-xs-4 nopadding">Rotation</div>
				<div class="col-xs-6 nopading">
					<div class="dToolOption" style="width:80px;">
						<span class="glyphicon glyphicon glyphicon-repeat"></span>
						<input type="text" value="0" maxlength="3" style="width:30px" class="dToolOptionInput" data-type="seldimage-rotation" id="seldimage-rotation" data-default="0" /> °
					</div>
				</div>
			</li>			
			<li class="image-options separator"></li>
			<li class="image-options">
				<div class="col-xs-4 nopadding">Opacity</div>
				<div class="col-xs-6 nopading"><input id="seldimage-opacity" class="dToolOptionDropdown" data-type="seldimage-opacity" type="range" min="1" max="100" step="1" /></div>
			</li>

			<li class="image-options separator"></li>
			<li class="image-options">
				<div class="col-xs-4 nopadding">Border Size</div>
				<div class="col-xs-6 nopading">
					<div class="dToolOption" style="width:80px;">
						<span class="glyphicon glyphicon-text-width"></span>
						<input type="text" value="0" maxlength="3" style="width:30px" class="dToolOptionInput" data-type="seldimage-borderSize" id="seldimage-borderSize" data-default="0" />
					</div>
				</div>
			</li>
			<li class="image-options">
				<div class="col-xs-4 nopadding">Border Color</div>
				<div class="col-xs-6 nopading">
					<span class="glyphicon glyphicon-text-background"></span>
					<input type="text" value="#ffffff" maxlength="7" style="width:50px" class="dToolOptionInput isColorPicker" data-type="seldimage-borderColor" id="seldimage-borderColor" data-default="#ffffff" readonly="readonly" />
				</div>
			</li>
			<li class="image-options separator"></li>
			<li class="image-options">
				<div class="col-xs-4">Images</div>
				<div class="col-xs-6">
					<button class="btn btn-xs btn-info" id="launch_imageListModal">View List</button>
				</div>
			</li>
		</ul>
	</div>
</div><!-- #canvas_options -->