<?php
include('./application/views/inc/n_header.php');
?>

<div class="container seldMemberWrapper">
	<div class="row">
		<div class="col-sm-12">
			<h3>Watchlist</h3>
			<hr>
			<table class="table table-condensed">
				<thead>
					<th width="30">S.N.</th>
					<th>Title</th>
					<th width="180">Added On</th>
					<th width="80">Action</th>
				</thead>
				<tbody>
					<?php
					$sn = $startIndex;
					foreach ($watchlist->result() as $item):
					?>
					<tr>
						<td><?=++$sn?></td>
						<td>
							<?=anchor('market/design/' . $item->pr_uid . '/' . url_title($item->pr_title), ucfirst($item->pr_title))?> <br>
							<?=$item->pr_mk_description?>
						</td>
						<td><?=$item->fav_created_at?></td>
						<td><?=anchor('market/favourite/' . $item->pr_uid, 'Remove', array('class'=>'btn btn-sm btn-danger'))?></td>
					</tr>
					<?php
					endforeach;
					?>
				</tbody>
			</table>
			<?=$pagination?>
		</div>
	</div>
</div>


<script>
$(function(){

});
</script>
<?php include('./application/views/inc/n_footer.php') ?>