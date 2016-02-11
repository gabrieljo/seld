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

<script>
var importDesign = {
	validate: function(){
		var o = $('#title');
		if (o.val() == '' || o.val.trim() == ''){
			alert('Title can not be empty!');
			o.focus();
			return false;
		}

		o = $('#content');
		if (o.val() == ''){
			alert('Select your Design file to upload!');
			o.focus();
			return false;
		}

		o = $('#preview');
		if (o.val() == ''){
			alert('Preivew can not be empty!')
			o.focus();
			return false;
		}
	},
	init: function(){
		$('form#frm-import').submit(importDesign.validate);
	}
};

$(importDesign.init);
</script>
<?php include('./application/views/inc/n_footer.php') ?>