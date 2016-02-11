<?php
include('./application/views/inc/n_header.php');
?>

<div class="container seldMemberWrapper">
	<div class="row">
		<div class="col-sm-12 nopadding">
			<h3>
				Import Design
				<?=anchor('m/designs', '<span class="glyphicon glyphicon-list"></span> View Other designs', array('class'=>'btn btn-warning btn-sm pull-right', 'style'=>'margin-right: 10px;'))?>
			</h3>

			<div class="product-preview">
				<?=form_open_multipart('m/import/' . $product->pr_uid, array('class'=>'form-horizontal', 'id'=>'frm-import'))?>
				<div class="row margin-top-80">
					<div class="col-sm-8 col-sm-offset-2 login-frame">
						<div class="login-form">
							<div class="title">Upload info</div>
							<hr>
							<?=$msg == '' ? '' : '<p class="bg-danger" style="padding: 8px;">' . $msg .'</p><br />'?>
							<div class="form-group">
								<label for="title" class="col-sm-3 control-label">*Title</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="title" name="title" placeholder="Company" value="<?=$product->pr_title?>" maxlength="200">
								</div>
							</div>
							<div class="form-group">
								<label for="description" class="col-sm-3 control-label">Description</label>
								<div class="col-sm-9">
									<textarea name="description" id="description" rows="4" class="form-control" placeholder="File Description"><?=$product->pr_description?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="content" class="col-sm-3 control-label">*Design File</label>
								<div class="col-sm-9">
									<input type="file" name="content" id="content">
									<?php
									$folder = './files/products/' . $product->pr_uid . '/';
									if ($product->pr_contents != '' && file_exists($folder . 'design.' . $product->pr_contents)){
										echo anchor(base_url() . 'files/products/' . $product->pr_uid . '/design.' . $product->pr_contents, 'View Design', array('class'=>'btn btn-sm btn-warning', 'target'=>'_bl'));
									}
									?>
									<br>
									<small>Design file in .PDF or .PSD format.</small>
								</div>
							</div>
							<div class="form-group">
								<label for="preview" class="col-sm-3 control-label">*Design Preview</label>
								<div class="col-sm-9">
									<input type="file" name="preview" id="preview">
									<?php
									if ($product->pr_preview != '' && file_exists($folder . 'preview.' . $product->pr_preview)){
										echo inc('../products/' . $product->pr_uid . '/preview.' . $product->pr_preview, array('style'=>'border:1px solid #ccc; padding: 2px; max-width: 200px; max-height:180px; margin-top: 10px;'));
									}
									?>
									<br>
									<small>Preview of the design. Maximum 800x600 px</small>
								</div>
							</div>
							<div class="form-group">
								<label for="previewx" class="col-sm-3 control-label"></label>
								<div class="col-sm-9">
									<button class="btn btn-sm btn-primary">Save Changes</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?=form_close()?>
			</div>
		</div>
	</div>
</div>

<!-- File Info -->
<div class="canvas_file_info overlay hidden"></div>
<div class="canvas_file_info wrapper hidden">	
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
				<h3>
					<span class="glyphicon glyphicon-cog"></span> File Info Settings 
					<button type="button" class="close hidden" title="Close" id="btn_file_info_settings_close" aria-label="Close"><span aria-hidden="true">&times;</span></button>

					<?=anchor('m/designs', 'X', array('class'=>'btn btn-danger btn-xs pull-right', 'title'=>'Close'))?>
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

<script>
var importDesign = {
	seld: {},
	validate: function(){
		var o = $('#title');
		if (o.val() == '' || o.val.trim() == ''){
			alert('Title can not be empty!');
			o.focus();
			return false;
		}
	},
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
	initCreate: function(){
		/**
		 * this will initialize tools required while creating new design.
		 */
		
		// get canvas properties
		var o 					= $('#design-pages');

		$('.canvas_file_info').removeClass('hidden');

		// this will load the options
		$('#file_design_type li').click(function(){
			$('#file_design_type li').removeClass('active');
			$(this).addClass('active');

			var ref = $(this).attr('data-ref');
			$.ajax({
				type: 'post',
				data: '',
				url: base_url() + 'm/ajax/load-options/' + ref,
				success: function(data){
					$('.mycol:eq(1) .mycol-container').html(data);
				},
				failure: function(){
					alert('Unable to load options.');
				}
			});
		});

		/**
		 * this will display the themes for selected settings.
		 */
		$('body').on('click', '#canvas_file_options_selection', function(){
			
			$('form#frm_canvas_options .form-group.hidden').remove();
			var dt 		= $('form#frm_canvas_options').serialize();

			$.post(base_url()+'m/save/' + importDesign.seld.id + '/options', dt);

			var ref 	= 0;
			var type 	= $('#file_design_type li.active').attr('data-ref'); 

			$('.canvas_file_info').addClass('hidden');

			// update db
			var id 		= $('#design-pages').attr('data-ref');
			$.post(base_url()+'m/save/' + importDesign.seld.id + '/general', {type:type, th_id:ref});
			return false;
		});

		// click on save button
		$('#btnupdate_file_info').click(function(){
			if ($('#canvas_title').val() == ''){
				alert('File name is required.');
				$('#canvas_title').focus();
			}
			else{
				step.save();
				// Hide the overlay.
				$('.canvas_file_info').addClass('hidden');

				location.reload(true);
			}
			return false;
		});

		// display type1 options
		$('#file_design_type li:eq(0)').trigger('click');

		$('#frmSettings').submit(importDesign.initCheck);
	},
	init: function(){
		$('form#frm-import').submit(importDesign.validate);
	}
};

importDesign.seld.id = '<?=$product->pr_uid?>';
$(importDesign.init);
<?php
if ($product->pr_status == 'new'){
	echo '$(importDesign.initCreate);';
}
?>
</script>
<?php include('./application/views/inc/n_footer.php') ?>