<?php include('inc/header.php') ?>

<div class="col-sm-12">
	<h3>
		SELD Article
		<?=anchor('a/articles/', '<span class="glyphicon glyphicon-list"></span> View Articles', array('class'=>'btn btn-warning btn-sm pull-right'))?>
	</h3> <hr>

	<?php
	// Load all CSS/JS framework
	$sub_load = array('ckeditor/ckeditor.js');
	echo inc($sub_load);
	?>
	<div class="row">
	    <div id="main-contents">
	        <div class="col-xs-8 col-xs-offset-2">
	            <div class="box">
	                <div class="box-header">
	                    <div class="box-name">
	                        <span>Article</span>
	                    </div>
	                </div><!--box-header-->
	                <div class="box-content no-padding">
	                    <div id="datatable-3-wrapper" class="dataTables_wrapper">
	                        <div class="clearfix"></div>                        

	                        <div class="box-content">
	                            <div class="col-sm-12">
								<?=form_open('a/article/'.$id.'/'.$tb, array('class'=>'form-horizontal', 'id'=>'frm_article_form'))?>
									<div class="form-group">
										<label for="art_title" class="col-sm-2 control-label">Title</label>
										<div class="col-sm-8">
											<input type="text" class="form-control" id="art_title" name="art_title" placeholder="Title" value="<?=$form['art_title']?>" required autoFocus>
										</div>
									</div>
									<div class="form-group">
										<label for="m_email" class="col-sm-2 control-label">Type</label>
										<div class="col-sm-3">
											<?php
											$options = array(
																'notice' => 'Notice',
																'page' 	=> 'Page',
																'news' => 'News',
																'qna' => 'QandA'
															);
											echo form_dropdown('art_type', $options, $form['art_type'], 'class="form-control" id="art_type"');
											?>
										</div>
									</div>
									<div class="form-group">
										<label for="m_phone" class="col-sm-2 control-label">Contents</label>
										<div class="col-sm-9">
											<textarea name="art_contents" id="art_contents" rows="10"><?=$form['art_contents']?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label for="m_passwd_c" class="col-sm-2 control-label">Status</label>
										<div class="col-sm-5">
										<?php
											$status0 = $form['art_status'] == 'unpublished' ? TRUE : FALSE;
											$status1 = $status0 == TRUE ? FALSE : TRUE;

											echo form_radio('art_status', 'published', $status1) . ' Published ';
											echo form_radio('art_status', 'Unpublished', $status0) . ' Unpublished' ;
										?>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<button type="submit" name="frmsubmit" value="save" class="btn btn-info">Save</button>
										</div>
									</div>
								<?=form_close()?>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div><!--box-->
	        </div>
	    </div><!--main-contents-->
	</div>
</div>

<script>
$(function(){
	CKEDITOR.replace('art_contents'); // editor$();
});
</script>

<?php include('inc/footer.php') ?>