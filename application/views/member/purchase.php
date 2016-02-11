<?php
include('./application/views/inc/n_header.php');
?>

<div class="container seldMemberWrapper">
	<div class="row">
		<div class="col-sm-12">
			<h3>
				My Purchase 
				<?=anchor('m/purchases', '<span class="glyphicon glyphicon-chevron-left"></span> Go Back', array('class'=>'btn btn-danger btn-sm pull-right'))?>
			</h3>
			<hr>
			<dl class="dl-horizontal">
				<dt>Invoice #</dt>
				<dd><?=str_pad($purchase->pc_id, 8, '0', STR_PAD_LEFT)?></dd>

				<dt>Total</dt>
				<dd>₩<?=number_format($purchase->pc_amount)?></dd>
			</dl>
			<table class="table table-condensed">
				<thead>
					<th width="30">S.N.</th>
					<th width="180">Title</th>
					<th>Author</th>
					<th width="120" class="text-right">Price</th>
					<th width="180" class="text-center">Design</th>
				</thead>
				<tbody>
					<?php
					$sn = 0;
					foreach ($items->result() as $item):
					?>
					<tr>
						<td><?=++$sn?></td>
						<td><?=anchor('market/design/' . $item->pr_uid, $item->pr_title, array('target'=>'_blank'))?></td>
						<td><?=sprintf("%s %s", $item->cl_firstname, $item->cl_lastname)?></td>
						<td class="text-right"><strong>₩<?=number_format($item->pci_price)?></strong></td>
						<th><?=anchor('m/createFromTheme/' . $item->pr_uid, 'Create from this theme', array('class'=>'btn btn-primary btn-sm pull-right'))?></th>
					</tr>
					<?php
					endforeach;
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<script>
$(function(){

});
</script>
<?php include('./application/views/inc/n_footer.php') ?>