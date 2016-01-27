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
			'seld/canvas.classes.js',
			'jquery.hotkeys.js',
			'jquery.upload.js'
		);

/**
 * Prepare backend container for holding Design pages contents
 * 
 * User saved design contents for all pages or theme default
 * 		whichever superceeds and is available.
 *
 * Total Pages is depends if the canvas type is "leaflet" and on number of
 *   	folding lines.
 * Canvas->type = 2 means type Leaflet.
 */
$contents 		= $product->pr_status == 'new' ? ($product->pr_th_id == 0 ? '' : $theme->d_th_contents) : $product->pr_contents;
$total_pages 	= intval($canvas->type) == 2 ? ($canvas->page * ($canvas->fold + 1)) : $canvas->page;


/**
 * Prepare the tools required by the editor.
 * These are the basic tools like, Save, Copy, Insert Text located at the left side-bar.
 *
 * Tools is an array of ("TYPE", "TITLE", "VALUE").
 */
$tools = array();

$tools[] = array(	'text', 
					'Insert Text (Ctrl+E)', 
					//'<span class="glyphicon glyphicon glyphicon-text-width"></span>',
					inc('icon-text.png', array('style'=>'width:100%;height:auto;')),
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
					inc('icon-shape.png', array('style'=>'width:100%;height:auto;')),
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

/**
 * load header and files.
 */
include('inc/header.php');
echo inc($inc); 
?>

<div class="article"><div id="body-wrapper">

<div id="editor_wrapper">
	<div id="design-pages" class="hidden" data-width="<?=$canvas->width?>" data-height="<?=$canvas->height?>"  data-pages="<?=$canvas->page?>" data-fold="<?=$canvas->fold?>" data-orientation="<?=$canvas->orientation?>" data-ref="<?=$product->pr_uid?>" data-type="<?=$canvas->type?>"><?=$contents?></div>

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
			<button id="openInfo" class="btn btn-primary btn-sm dTool" data-type="info" title="File information settings (Ctrl+I)"><span class="glyphicon glyphicon-cog"></span> Settings</button>
			<button id="saveCanvas" class="btn btn-success btn-sm dTool" data-type="save" title="Save Changes (Ctrl+S)"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
			<div class="cf"></div>
		</div>

		<div class="section" id="top_section">
			<?php include('./application/views/member/inc/editor_options.php') ?>
		</div><!-- .section#top -->

		<div id="copyright">
			&copy; <?=date('Y')?>. Creative-Edge
		</div>
	</div><!-- #editor_properties -->

	<?php include('./application/views/member/inc/editor_footer.php') ?>
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

<div id="loading_page" class="hidden"></div>

<!-- Notification for clearing user changes! -->


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
						$folder = './files/products/'.$product->pr_uid.'/thumbs/';
						$total 	= 0;
						foreach (glob($folder.'*') as $filename){
							$img = basename($filename);						
							if ($img == 'index.html'){ continue; }

							// get dimension of ORIGINAL image.
							$orig_path = str_replace('thumbs/', '', $filename);
							list($width, $height) = getimagesize($orig_path);
						    echo '<li><div class="img-wrapper"><img src="'. base_url() . $filename . '" data-width="'. $width . '" data-height="'. $height . '" /></div></li>';

						    $total++;
						}
						?>
					</ul>
					<?php
					if ($total == 0){
						echo '<h3 class="text-danger text-center" id="no_img_uploaded">No image uploaded!</h3>';
					}
					?>
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

</div></div><!-- .article.body-wrapper -->

<?php

echo inc("seld/canvas.js");

if ($product->pr_status == 'new'){
	echo '<script>$(step.initCreate)</script>';
}
else{
	echo '<script>$(step.init)</script>';
}

include('inc/footer.php');
?>

<!-- File Info -->
<div class="canvas_file_info overlay hidden"></div>
<div class="canvas_file_info wrapper hidden">	
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
				<h3>
					<span class="glyphicon glyphicon-cog"></span> File Info Settings 
					<button type="button" class="close hidden" title="Close" id="btn_file_info_settings_close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</h3>
				<div class="form-horizontal hidden" id="file_info_save_btn">
					<div class="form-group">
						<label for="canvas_title" class="col-sm-3 control-label"><span class="text-danger">*</span>Title</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="canvas_title" placeholder="File Name" value="<?=$product->pr_title?>">
						</div>
					</div>
					<div class="form-group">
						<label for="inputPassword3" class="col-sm-3 control-label">Description</label>
						<div class="col-sm-7">
							<textarea name="canvas_description" id="canvas_description" cols="30" rows="4" class="form-control" placeholder="File Description"><?=$product->pr_description?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"></label>
						<div class="col-sm-7">
							<button type="button" id="btnupdate_file_info" class="btn btn-sm btn-info"><span class="glyphicon glyphicon-floppy-disk"></span> Update</button>
						</div>
					</div>
				</div>
			</div>
		</div><!-- .row -->
		<div class="row" id="canvas_file_options">
			<div class="col-xs-12">
				<div class="mycol active" id="mycol-type">
				 	<h3>Select Type</h3>
				 	<div class="mycol-container">
				 		<ul id="file_design_type">
				 			<?php
				 			foreach ($d_types->result() as $item){
				 			?>
				 				<li data-ref="<?=$item->d_pr_id?>">
				 					<?=inc('design/' . $item->d_pr_image, array('class'=>'product-img'))?>
				 					<h4><?=$item->d_pr_name?></h4>
									<p class="text-primary"><?=$item->d_pr_description?></p>
								</li>
				 			<?php
				 			}
				 			?>
				 		</ul>
				 	</div>
				</div>
				<div class="mycol" id="mycol-options">
					<h3>Options</h3>
					<form action="" id="frm_canvas_options">
						<div class="mycol-container">
					 		<small>Select Type!</small>
					 	</div>
					 </form>
				</div>
				<div class="mycol full hidden" id="mycol-themes">
					<h3>Themes <button class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-menu-left" id="btn-back-type" title="Go Back"></span></button></h3>
				 	<div class="mycol-container">
				 		<small>Not available.</small>
				 	</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="canvas_status_saving hidden text-center">
	<?=inc('loading.gif')?>
	<h3 class="text-primary">Exporting Design...</h3>
	Please Wait!
</div>

<div class="canvas_pages_view overlay hidden"></div>
<div class="canvas_pages_view wrapper hidden"><ul id="canvas_pages"></ul><div class="cf"></div></div>