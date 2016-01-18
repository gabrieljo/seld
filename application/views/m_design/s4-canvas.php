<?php

/**
 * SELD Creative Editor
 * @author  	Sudarshan Shakya
 * @date 		2015-12-31
 * @version 	1.2
 * 
 * Prepare the backend for the SELD Creative Editor
 */


/**
 * Load external scripts / plugins required for the editor.
 */
$inc = array(
			'jquery-ui.min.js', 
			'ui/jquery-ui.min.css',
			'ui/jquery-ui.structure.min.css',
			'ui/jquery-ui.theme.min.css',
			'bootstrap-colorpicker.min.js',
			'bootstrap-colorpicker.min.css',
			's4-canvas.classes.js',
			'jquery.hotkeys.js',
			'jquery.upload.js'
		);
echo inc($inc);


/**
 * Prepare backend container for holding Design pages contents
 * 
 * User saved design contents for all pages or theme default
 * 		whichever superceeds and is available.
 */
$contents 	= $product->pr_status == 'new' ? ($product->pr_th_id == 0 ? '' : $theme->d_th_contents) : $product->pr_contents;
$total_pages= $prop['page'];// * $prop['face'];


/**
 * Prepare the tools required by the editor.
 * These are the basic tools like, Save, Copy, Insert Text located at the left side-bar.
 *
 * Tools is an array of ("TYPE", "TITLE", "VALUE").
 */
$tools = array();
/*$tools[] = array(	'save', 
					'Save Design (Ctrl+S)', 
					'<span class="glyphicon glyphicon-floppy-disk"></span>'
				);
$tools[] = array('seperator');*/
$tools[] = array(	'text', 
					'Insert Text (Ctrl+E)', 
					//'<span class="glyphicon glyphicon glyphicon-text-width"></span>',
					inc('icon-text.png', array('style'=>'width:100%;height:100%;')),
					''
				);
$tools[] = array(	'image', 
					'Insert Image (Ctrl+I)', 
					'<span class="glyphicon glyphicon-picture"></span>',
					''
				);
$tools[] = array(	'shape', 
					'Insert Shape', 
					//'<span class="glyphicon glyphicon-stop"></span>',
					inc('icon-shape.png', array('style'=>'width:100%;height:100%;')),
					''
				);
$tools[] = array('seperator');
$tools[] = array(	'layers', 
					'Layers (Ctrl+L)', 
					'<span class="glyphicon glyphicon-sort-by-attributes"></span>',
					''
				);
$tools[] = array(	'copy', 
					'Copy (Ctrl+C)', 
					'<span class="glyphicon glyphicon-duplicate"></span>',
					'requireLayerSelection disabled'
				);
$tools[] = array(	'paste', 
					'Paste (Ctrl+V)', 
					'<span class="glyphicon glyphicon glyphicon-paste"></span>',
					''
				);
$tools[] = array(	'delete', 
					'Delete (Del)', 
					'<span class="glyphicon glyphicon-trash"></span>',
					'requireLayerSelection disabled'
				);
$contents_tool = '';
for ($i=0; $i<count($tools); $i++){
	$c = $tools[$i];
	if ($c[0] == 'seperator')
		$contents_tool .= '<li class="seperator"></li>';
	else{
		$cls = $c[3];
		$contents_tool.= '<li title="' . $c[1] . '"><div class="dTool ' . $cls . '" data-type="' . $c[0] . '">' . $c[2] . '</div></li>';		
	}
}


?>
<div id="editor_wrapper">
	<div id="design-pages" class="hidden" data-width="<?=intval($paper->d_sz_width)?>" data-height="<?=intval($paper->d_sz_height)?>" data-faces="<?=$prop['face']?>" data-pages="<?=$prop['page']?>" data-ref="<?=$product->pr_uid?>" data-total="<?=$total_pages?>"><?=$contents?></div>

	<div id="editor_menu">
		<div id="design-tools-wrapper">
			<ul id="design-tools"><?=$contents_tool?></ul>
		</div>
	</div><!-- #editor_menu -->

	<div id="canvas">
		<div id="canvas_wrapper">
			<div id="canvas_cell">
				<div id="canvas_ghost"><div id="selectionObject" class="hidden"></div></div>
				<canvas id="pad" class="isCanvas" width="" height=""></canvas>
			</div>
		</div>
	</div><!-- #canvas -->

	<div id="editor_properties">
		<div class="head">
			<button id="saveCanvas" class="btn btn-primary btn-sm dTool" data-type="save"><span class="glyphicon glyphicon-floppy-disk"></span></button>
			<!-- <button class="btn btn-success btn-sm" id="saveimagebutton"><span class="glyphicon glyphicon-star"></span></button> -->
			<?=anchor('u/create/' . $product->pr_uid . '/settings', '<span class="glyphicon glyphicon-cog"></span> Settings', array('class'=>'btn btn-danger btn-sm pull-right'))?>
			<div class="cf"></div>
		</div>

		<div class="section" id="top_section">
			<?php include('./application/views/inc/m_editor_options.php') ?>
		</div><!-- .section#top -->

		<div id="copyright">
			&copy; <?=date('Y')?>. Creative-Edge
		</div>
	</div><!-- #editor_properties -->

	<?php include('./application/views/inc/m_editor_footer.php') ?>
</div><!-- #editor_wrapper -->

<div class="hidden" id="layer_overlay">
	<h3>Layers <span class="pull-right close_parent" data-target="#layer_overlay" title="Close">&times;</span></h3>
	<div id="layers_wrapper"><ul id="layers"></ul></div><!-- #layers_wrapper -->
</div><!-- .section#bottom_section -->

<div id="editor_overlay">
	<div class="editor_msg">
		<div class="progress">
			<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
				<span class="sr-only">0% Complete</span>
			</div>
		</div>
		<div id="overlay_status">
			Loading Images [0/0]
		</div>
	</div>
</div>

<div id="loading_page" class="hidden">
	
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

<!-- Image List Options -->
<div class="modal fade" id="imageListModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Images</h4>
			</div>
			<div class="modal-body">
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

							// get dimension of ORIGINAL image.
							$orig_path = str_replace('thumbs/', '', $filename);
							list($width, $height) = getimagesize($orig_path);
						    echo '<li><div class="img-wrapper"><img src="'. base_url() . $filename . '" data-width="'. $width . '" data-height="'. $height . '" /></div></li>';
						}
						?>
					</ul>
					<div class="cf"></div>					
				</div>
			</div>
			<div class="modal-footer">
				<div class="row" id="selectImageRow">
					<div class="col-xs-8">
						<div id="image-options-upload">
							<div class="image-upload-main" data-ref="<?=base_url()?>">
								<div id="status"></div>
								<div class="itemWrap" id="image_uploader">
									<button type="file" class="btn btn-info btn-sm">Upload Image</button>
									<small class="text-warning">You can click on upload button or drag your photos here.</small>
								</div>
							</div>
							<input type="hidden" name="frm_photos" id="frm_photos" value="">					
						</div><!-- #image-options-upload -->
					</div>
					<div class="col-xs-4">
						<div id="select_image_preview" class="hidden">
							<img src="" data-width="0" data-height="0" />
							<div class="text-center text-danger"><span class="glyphicon glyphicon-ok"></span> Click here to select!</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>