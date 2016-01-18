<?php 

include('inc/m_header.php');

echo inc(array('s4-canvas.classes.js', 'm_publish.js'));


$total_pages= $prop['page'] * $prop['face'];

?>
<div id="design-pages" class="hidden" data-width="<?=intval($paper->d_sz_width)?>" data-height="<?=intval($paper->d_sz_height)?>" data-faces="<?=$prop['face']?>" data-pages="<?=$prop['page']?>" data-ref="<?=$product->pr_uid?>" data-folder="<?=$folder?>"><?=$product->pr_contents?></div>

<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<h3>Publish Design</h3>
			<hr>
			<div class="col-xs-6 col-xs-offset-3 hidden">
				<div class="progress hidden" id="progress-bar">
					<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
						<span class="sr-only">0% Complete</span>
					</div>
				</div>
				<div class="text-center" id="progress-status">0% Complete</div>
			</div>
			<div class="cf"></div>
			<canvas id="pad" width="" height="" style="border:1px solid #ccc;display: none;"></canvas>
			
			<div id="preview_publish_wrapper" class="text-center">
				<button class="btn btn-success" id="publish_now">Publish Now</button>
				<br><br>
				<?=anchor('u/preview/'.$product->pr_uid, 'Preview', array('class'=>'btn btn-danger', 'target'=>'_blank'))?>
			</div>

			<dl class="dl-horizontal hidden">
				<dt>Design Type</dt>
				<dd>Business Card</dd>

				<dt>Paper Size</dt>
				<dd>A4</dd>
			</dl>
		</div>
	</div>
</div>
<style>
#progress-bar{margin:40px 0 10px;}
#progress-status{margin-bottom: 40px;font-weight: bold;color:#aaa;}
</style>
<script>$(design.init)</script>

<?php include('inc/m_footer.php') ?>