<?php include('inc/header.php') ?>

<div class="col-sm-12">
	<h3>
		SELD Articles
		<?php
		$options = array(
					'all' => 'All',
					'page' => 'Pages',
					'news' 	=> 'News',
					'notice' => 'Notices',
					'qna' => 'QandA'
					);
		echo anchor('a/article/', '<span class="glyphicon glyphicon-plus"></span> Add Article', array('class'=>'btn btn-info btn-sm pull-right'));
		echo form_dropdown('listFilter', $options, $type, 'class="form-controls pull-right" style="font-size:11px;padding: 6px;margin-right:10px;width:120px;"');
		?>
	</h3> <hr>

	<table class="table table-hover">
		<thead>
			<th width="30"><span class="glyphicon glyphicon-th"></span></th>
			<th>Details</th>
			<th width="120">Type</th>
			<th width="120">Status</th>
			<th width="160">Action</th>
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
						<small>Created on <?=date('jS M, Y', strtotime($article->art_created_at))?></small>
					</td>
					<td><strong><?=strtoupper($article->art_type)?></strong></td>
					<td><?=ucfirst($article->art_status)?></td>
					<td>
						<?=anchor('a/article/' . $article->art_uid, '<span class="glyphicon glyphicon-pencil"></span> Edit', array('class'=>'btn btn-info btn-sm'))?>
						<?=anchor('a/articleDelete/' . $article->art_uid . '/' . base64_encode(current_url()), '<span class="glyphicon glyphicon-trash"></span> Delete', array('class'=>'btn btn-danger btn-sm btnDelete'))?>
					</td>
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

	//Filter
	$('select[name="listFilter"]').change(function(){
		var vl = $(this).val();
		window.location.href = '<?=base_url()?>a/articles/' + vl;
	});

	$('.btnDelete').click(function(){
		if (confirm('Are you sure you want to delete?')){
			return true;
		}
		return false;
	});
});
</script>

<?php include('inc/footer.php') ?>