<?php
// $prop 
// width, height, scale, face & page
$inc = array(
				'jquery-ui.min.js', 
				'ui/jquery-ui.min.css', 		
				'ui/jquery-ui.structure.min.css', 		
				'ui/jquery-ui.theme.min.css',
				'bootstrap-colorpicker.min.js',	
				'bootstrap-colorpicker.min.css',
				'jquery.ui.rotatable.js',
				'jquery.ui.rotatable.css'
			);
echo inc($inc);
?>

<div id="editor_wrapper">
	<ul id="design-pages" class="hidden" data-width="<?=$prop['width']?>" data-height="<?=$prop['height']?>" data-scale="<?=$prop['scale']?>">
		<?php
		for ($i=1; $i<=$prop['page']; $i++){
			echo '<li id="node'.$i.'"></li>';
		}
		?>
	</ul>

	<div id="editor_menu">
		<div id="design-tools-wrapper">
			<ul id="design-tools">
				<li title="Preview">
					<div class="dTool" data-type="preview">
						<span class="glyphicon glyphicon-eye-open"></span>
					</div>
				</li>
				<li title="Insert Text">
					<div class="dTool" data-type="text">
						<span class="glyphicon glyphicon-pencil"></span>
					</div>
				</li>
				<li title="Insert Image">
					<div class="dTool" data-type="image">
						<span class="glyphicon glyphicon-picture"></span>
					</div>
				</li>
				<li title="Copy">
					<div class="dTool" data-type="copy">
						<span class="glyphicon glyphicon-duplicate"></span>
					</div>
				</li>
			</ul>
		</div>
	</div><!-- #editor_menu -->

	<div id="canvas">
		<div id="canvas_wrapper">
			<div id="canvas_cell">
				<div id="pad" class="preview"><?=$product->pr_contents?></div>
			</div>
		</div>
	</div><!-- #canvas -->

	<div id="editor_properties">
		<div class="head">
			<button id="save_design" class="btn btn-danger btn-sm pull-right"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
			<button class="btn btn-default btn-sm pull-right"><span class="glyphicon glyphicon-eye-open"></span> Preview</button>
			<div class="cf"></div>
		</div>

		<div class="section" id="top_section">
			<?php include('./application/views/inc/m_editor_options.php') ?>
		</div><!-- .section#top -->

		<div class="section" id="bottom_section">
			<h3 style="margin:-35px 0 0 1px;">Layers</h3>
			<ul id="layers">
				<li class="layer-text" data-id="123456"><input type="checkbox" name="show[]" value="1" checked="checked"> Text</li>
				<li class="layer-image" data-id="34343"><input type="checkbox" name="show[]" value="1" checked="checked"> Image</li>
			</ul>
		</div><!-- .section#bottom_section -->

		<div id="copyright">
			&copy; <?=date('Y')?>. Creative-Edge
		</div>
	</div><!-- #editor_properties -->

	<div id="editor_footer">
		<ul class="pull-right">
			<li>
				<span class="glyphicon glyphicon-pencil" style="position:relative;top:3px;"></span>
				<?=$design_page?>
			</li>
			<li>
				<span class="glyphicon glyphicon-grain" style="position:relative;top:3px;"></span>
				<?=$design_face?>
			</li>
			<li id="slider-container">
				<span class="glyphicon glyphicon-zoom-in" style="position:relative;top:3px;"></span>
				<div id="slider"></div>
			</li>
		</ul>
	</div><!-- #editor_footer -->
</div><!-- #editor_wrapper -->