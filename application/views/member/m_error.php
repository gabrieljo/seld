<?php include('inc/m_header.php') ?>

<div class="col-sm-4 col-sm-offset-4">
	<h3 class="text-danger"><span class="glyphicon glyphicon-ban-circle"></span> File Not Found</h3>
	<div class="box">
		The file you requested to open has either been moved or deleted.
	</div>
	 <br><br>
	<?=anchor('u/create', '디자인 하기', array('class'=>'btn btn-success'))?> OR, 
	<?=anchor('u/list_designs', '내가 저장한 디자인', array('class'=>'btn btn-primary'))?>
</div>

<?php include('inc/m_footer.php') ?>