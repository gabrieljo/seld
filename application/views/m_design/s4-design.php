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
				'jquery.ui.rotatable.css',
				'jquery.upload.js',
				'jquery.hotkeys.js',
				'upload.js'
			);
echo inc($inc);

// For Default theme options 
$theme_defaults_html 	= $product->pr_th_id == 0 ? '' : $theme->d_th_contents;
$theme_pieces 			= explode("||==||", $theme_defaults_html);

// For user progress
if ($product->pr_status == 'new'){
	$content 	=  $product->pr_th_id == 0 ? '' : $theme->d_th_contents;
	$pieces 	= explode("||==||", $content);	
}
else{
	$pieces = explode("||==||", $product->pr_contents);	
}
?>

<div id="editor_wrapper">
	<ul id="design-pages" class="hidden" data-width="<?=$paper->d_sz_width?>" data-height="<?=$paper->d_sz_height?>" data-faces="<?=$prop['face']?>" data-pages="<?=$prop['page']?>" data-ref="<?=$product->pr_uid?>">
		<?php
		//echo $pieces[0];exit;
		$theme_default = '';
		$total_pages = $prop['page'] * $prop['face'];
		for ($i=1; $i<=$total_pages; $i++){
			echo '<li id="node'.$i.'" data-style="">' . @$pieces[$i-1] . '</li>';
			// for theme
			$theme_default.= '<li id="theme-node'.$i.'" data-style="">' . @$theme_pieces[$i-1] . '</li>';
		}
		?>
	</ul>
	<ul id="design-theme-pages" class="hidden">
		<?=$theme_default?>
	</ul>

	<div id="editor_menu">
		<div id="design-tools-wrapper">
			<ul id="design-tools">
				<li title="Save Design (Ctrl+S)">
					<div class="dTool" data-type="save">
						<span class="glyphicon glyphicon-floppy-disk"></span>
					</div>
				</li>
				<li title="Preview Design (Ctrl+P)">
					<div class="dTool" data-type="preview">
						<span class="glyphicon glyphicon-eye-open"></span>
					</div>
				</li>
				<li title="File Information">
					<div class="dTool" data-type="info">
						<span class="glyphicon glyphicon-tag"></span>
					</div>
				</li>
				<li class="seperator"></li>
				<li title="Insert Text (Ctrl+E)">
					<div class="dTool" data-type="text">
						<span class="glyphicon glyphicon-pencil"></span>
					</div>
				</li>
				<li title="Insert Image (Ctrl+I)">
					<div class="dTool" data-type="image">
						<span class="glyphicon glyphicon-picture"></span>
					</div>
				</li>
				<li title="Insert Rectangle">
					<div class="dTool" data-type="rect">
						<span class="glyphicon glyphicon-stop"></span>
					</div>
				</li>
				<li title="Copy Layer (Ctrl+C)">
					<div class="dTool" data-type="copy">
						<span class="glyphicon glyphicon-duplicate"></span>
					</div>
				</li>
				<li title="Delete Layer (Del)">
					<div class="dTool" data-type="delete">
						<span class="glyphicon glyphicon-trash"></span>
					</div>
				</li>
				<li class="seperator"></li>
				<li title="Open other designs">
					<div class="dTool" data-type="list">
						<?=anchor('u/list_designs', '<span class="glyphicon glyphicon-folder-open"></span>', array('id'=>'open_files_link'))?>
					</div>
				</li>
				<li title="Reset Design to Theme Default (Ctrl+R)">
					<div class="dTool" data-type="reset">
						<span class="glyphicon glyphicon-refresh"></span>
					</div>
				</li>
			</ul>
		</div>
	</div><!-- #editor_menu -->

	<div id="canvas" class="preview">
		<div id="canvas_wrapper">
			<div id="canvas_cell">
				<div id="pad" class="preview isCanvas"></div>
			</div>
		</div>
	</div><!-- #canvas -->

	<div id="editor_properties">
		<div class="head">			
			<?=anchor('u/create/' . $product->pr_uid . '/settings', '<span class="glyphicon glyphicon-cog"></span> Change Settings', array('class'=>'btn btn-danger btn-sm pull-right'))?>
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
		<div class="pull-left">
			<small id="last_save_msg">Last Saved : <i><?=$product->pr_updated_at?></i></small>
		</div>
		<ul class="pull-right">
			<?php
			if ($prop['page'] > 1){
			?>
			<li>
				<ul class="pad-pagination">
					<?php
					for ($i=1; $i<=$prop['page']; $i++){
						$cls = $i==1 ? 'class="active"' : '';
						echo '<li ' . $cls .'>' . $i . '</li>';
					}
					?>
					<div class="cf"></div>
				</ul>
			</li>
			<li>
				<span class="glyphicon glyphicon-pencil" style="position:relative;top:3px;"></span> Page
			</li>
			<?php
			}
			if ($prop['face'] == 2){
			?>
			<li>
				<ul class="pad-face">
					<li class="active">Front</li>
					<li>Back</li>
					<div class="cf"></div>
				</ul>
			</li>
			<li>
				<span class="glyphicon glyphicon-grain" style="position:relative;top:3px;"></span> Face
			</li>
			<?php
			} // Face Option
			?>
			<li title="Original Size"><span class="glyphicon glyphicon-fullscreen"></span></li>
			<li title="Fit to Screen"><span class="glyphicon glyphicon-resize-small"></span></li>
			<li><div id="slider_zoom">100%</div></li>
			<li id="slider-container">
				<span class="glyphicon glyphicon-zoom-in" style="position:relative;top:3px;"></span>
				<div id="slider"></div>
			</li>
		</ul>
	</div><!-- #editor_footer -->
</div><!-- #editor_wrapper -->

<div id="editor_overlay">
	<div class="editor_msg">
		Loading...
	</div>
</div>

<!-- Notification for clearing user changes! -->
<div class="modal fade" id="myModalReset" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Discard Changes?</h4>
			</div>
			<div class="modal-body">
				<p class="text-danger">
					<strong><span class="glyphicon glyphicon-info-sign"></span> Are you sure you want to discard your changes? </strong><br>
					This will remove all your changes and reset the theme.
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" id="confirm_pad_reset" class="btn btn-danger">Confirm Reset</button>
			</div>
		</div>
	</div>
</div>

<!-- File Information! -->
<div class="modal fade" id="myModalInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" title="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">File Information?</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<div class="form-group">
						<label for="design_title" class="col-sm-3 control-label">Title</label>
						<div class="col-sm-8">
						<input type="text" class="form-control" id="design_title" name="design_title" placeholder="File Title" value="<?=$product->pr_title?>">
						</div>
					</div>
					<div class="form-group">
						<label for="inputPassword3" class="col-sm-3 control-label">Description</label>
						<div class="col-sm-8">
						<textarea name="design_description" id="design_description" rows="6" class="form-control" placeholder="File description" style="resize:none;"><?=$product->pr_description?></textarea>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>