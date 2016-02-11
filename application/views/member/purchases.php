<?php
include('./application/views/inc/n_header.php');
?>

<div class="container seldMemberWrapper">
	<div class="row">
		<div class="col-sm-12">
			<h3>My Purchases</h3>
			<hr>
			<table class="table table-condensed">
				<thead>
					<th width="30">S.N.</th>
					<th width="180">Date</th>
					<th>Invoice #</th>
					<th>Total Items</th>
					<th width="120" class="text-right">Amount</th>
					<th width="80" class="text-right">View</th>
				</thead>
				<tbody>
					<?php
					$sn = $startIndex;
					foreach ($purchases->result() as $item):
					?>
					<tr>
						<td><?=++$sn?></td>
						<td><?=date('jS M, Y', strtotime($item->pc_created_at))?></td>
						<td><?=str_pad($item->pc_id, 8, '0', STR_PAD_LEFT)?></td>
						<td><?=$item->pc_items?></td>
						<td class="text-right"><strong>₩<?=number_format($item->pc_amount)?></strong></td>
						<td><?=anchor('m/purchase/' . $item->pc_uid, 'View', array('class'=>'btn btn-info btn-xs pull-right'))?></td>
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