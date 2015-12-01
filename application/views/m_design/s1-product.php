<ul class="product-list">
	<?php
	foreach ($list->result() as $item):
	?>
	<li>
		<div class="row">
			<div class="col-xs-2">
				<?=inc('design/' . $item->d_pr_image, array('class'=>'product-img'))?>
			</div>
			<div class="col-xs-10">
				<?=anchor('u/create/theme/'. $item->d_pr_uid, 'Design Now', array('class'=>'btn btn-primary btn-lg pull-right navbtns'))?>
				<h4><?=$item->d_pr_name?></h4>
				<p class="text-primary"><?=$item->d_pr_description?></p>
			</div>
		</div>
	</li>
	<?php
	endforeach;
	?>
</ul>