<?php
include('./application/views/inc/n_header.php');
?>

<div class="container seldMemberWrapper">
	<div class="row">
		<div class="col-sm-12">
			<h3>User Logs</h3>
			<hr>
			<table class="table table-condensed">
				<thead>
					<th width="30">S.N.</th>
					<th>Title</th>
					<th>IP</th>
					<th>Type</th>
					<th width="180">Date</th>
				</thead>
				<tbody>
					<?php
					$sn = $startIndex;
					foreach ($logs->result() as $item):
					?>
					<tr>
						<td><?=++$sn?></td>
						<td><?=$item->log_title?></td>
						<td><?=$item->log_ip?></td>
						<td><?=ucfirst($item->log_type)?></td>
						<td><?=$item->log_created_at?></td>
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