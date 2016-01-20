<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<ul class="product-themes">
				<?php
				foreach ($themes->result() as $theme):
				?>
				<li>
					<div class="theme-preview">
						<?=inc('design/themes/'.$theme->d_th_image)?>
					</div>
					<div class="theme-buttons">
						<?=anchor('u/create/' . $product->pr_uid . '/design/' . $theme->d_th_uid, '<span class="glyphicon glyphicon-ok"></span> Select', array('class'=>'btn btn-primary btn-sm', 'title'=>'Select Theme'))?>
						<?=anchor('u/favourite/' . $theme->d_th_uid, '<span class="glyphicon glyphicon-star"></span>', array('class'=>'btn btn-warning btn-sm pull-right link-add-favourite', 'title'=>'Add to Favourites'))?>
					</div>
					<div class="theme-description">
						<h3><?=$theme->d_th_name?></h3>
						<?=$theme->d_th_description?>
					</div>
				</li>
				<?php
				endforeach;

				if ($total == 0){
					echo '<h3 class="text-warning"><span class="glyphicon glyphicon-exclamation-sign"></span> ' . $empty_msg . '</h3>';
					echo anchor('u/create/' . $product->pr_uid . '/design/no-theme', 'Design Now', array('class'=>'btn btn-lg btn-primary'));
				}
				?>
			</ul>
			<div class="cf"></div>
			<?=$pagination?>
		</div>
	</div>
</div>