<?php include('inc/header.php') ?>

<div class="col-sm-12">
	<h3>
		SELD Articles
		<?=anchor('a/article/', '<span class="glyphicon glyphicon-plus"></span> Add Article', array('class'=>'btn btn-info btn-sm pull-right'))?>
	</h3> <hr>

	<table class="table table-hover">
		<thead>
			<th width="30"><span class="glyphicon glyphicon-th"></span></th>
			<th>Details</th>
			<th width="120">Type</th>
			<th width="120">Action</th>
		</thead>
		<tbody>
			<?php
			$sn = 0;
			foreach ($articles->result() as $article){
			?>
				<tr>
					<td><?=++$sn?>.</td>
					<td>
						<h4>
							<?=$article->art_title?> <br>
						</h4>
						<small>Joined SELD on <?=date('dS M, Y', strtotime($article->art_created_at))?></small>
					</td>
					<td><?=anchor('a/orders/' . $article->art_uid, 'View Transactions')?></td>
					<td><?=anchor('a/designs/' . $article->art_uid, 'View designs')?></td>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</div>

<script>
$(function(){
	$('form#frmSearch').attr('action', '<?=current_url()?>').removeClass('hidden');
});
</script>

<?php include('inc/footer.php') ?>