<?php include('inc/m_header.php') ?>

<div class="col-sm-3">
	<h3>마이페이지</h3>
	<div class="box">
		Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque erat arcu, bibendum quis purus eu, ullamcorper malesuada quam.
	</div>
</div>

<div class="col-sm-9">
	<h2>디자인을 내손으로</h2>	
	<hr>
	<?=anchor('m/create', 		'디자인 하기', 		array('class'=>'btn btn-success'))?> 	<br><br><br>
	<?=anchor('m/create', 		'내가 주문한 디자인', 	array('class'=>'btn btn-info'))?> 		<br><br><br>
	<?=anchor('m/designs', 		'내가 저장한 디자인', 	array('class'=>'btn btn-primary'))?>
</div>

<?php include('inc/m_footer.php') ?>