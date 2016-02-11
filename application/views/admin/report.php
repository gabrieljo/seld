<?php include('inc/header.php') ?>

<div class="col-sm-12">
	<h3>SELD Users <small>Designers</small></h3><hr>

	<table class="table table-hover">
		<thead>
			<th width="30"><span class="glyphicon glyphicon-th"></span></th>
			<th>Details</th>
			<th width="120">Total Designs</th>
		</thead>
		<tbody>
			<?php
			$sn = 0;
			foreach ($users->result() as $user){
			?>
				<tr>
					<td><?=++$sn?>.</td>
					<td>
						<h4>
							<?=$user->cl_firstname . ' ' . $user->cl_lastname?> <br>
							<small class="text-warning"><?=$user->cl_email?></small> <br>
						</h4>
						<small>Joined SELD on <?=date('dS M, Y', strtotime($user->cl_created_at))?></small>
					</td>
					<td><?=anchor('a/designs/' . $user->cl_uid, 'View ' . $user->total . ' designs')?></td>
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