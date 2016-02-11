<?php include('inc/header.php') ?>

<?=inc('printThis.js')?>

	<div class="col-sm-12">
		<h2>
			Orders
			<?php
			$options = array(
							'all' => 'View All',
							'awaiting-payment' 		=> 'Awaiting Payment',
							'completed' 			=> 'Completed Orders',
							'cancelled' 			=> 'Cancelled Orders',
							'awaiting-confirmation' => 'Pending'
						);
			echo form_dropdown('frmView', $options, $view, 'class="pull-right" id="frmView" style="font-size:12px;padding:5px 15px;font-weight:bold;"');
			?>
			<small class=" pull-right" style="font-size:12px;"> <span class="glyphicon glyphicon-filter"></span> Filter Records: </small>
		</h2>

		<table class="table table-condensed table-bordered">
			<thead>
				<th width="30">S.N.</th>
				<th>Client</th>
				<th width="150">Date</th>
				<th width="80">Options</th>
				<th width="250">Design</th>
				<th width="40">Quantity</th>
				<th width="100">Rate</th>
				<th width="100">Amount</th>
				<th width="120">Status</th>
			</thead>
			<tbody>
				<?php
				$sn = 0;
				foreach ($orders->result() as $order){
					$status = $order->or_status == 'awaiting-payment' ? 'default' : 'danger';
					$uid = $order->pr_uid;
				?>
				<tr>
					<td><?=++$sn?>.</td>
					<td><?=sprintf('%s %s', $order->cl_firstname, $order->cl_lastname)?></td>
					<td><small><?=date('jS M, Y H:iA')?></small></td>
					<td><button class="btn btn-sm btn-info btnViewOptions" data-ref="<?=$order->pr_uid?>">View Options</button></td>
					<td>
						<?php
						if ($order->or_status == 'awaiting-confirmation'){

							if ($order->or_file != ''){
								echo anchor(base_url()."files/products/{$uid}/design/" . $order->or_file, 'Download File', array('target'=>'_blank', 'class'=>'linkFile'));
								echo ' <button class="btn btn-sm btn-warning btnUpdateLink" data-ref="' . $order->or_uid . '">Update Status</button>';
							}
							else{
								// link
								$id = md5(time().rand(1000,99999));
								echo '<div class="generatePDF" data-id="' . $order->or_pr_id . '" data-name="' . $id . '" data-order="' . $order->or_uid . '">';

									echo '<button class="btn btn-sm btn-success btnGenerate">Generate File</button>';
									echo anchor(base_url()."files/products/{$uid}/design/{$id}.pdf", 'Download File', array('target'=>'_blank', 'class'=>'linkFile hidden'));
									echo ' <button class="btn btn-sm btn-warning hidden btnUpdateLink" data-ref="' . $order->or_uid . '">Update Status</button>';
									echo '<div class="progress hidden">
											  <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="100" style="width: 1%">
											    <span class="sr-only">Generating</span>
											  </div>
											</div>';

								echo '</div>';
							}
						}
						else if($order->or_status == 'awaiting-payment'){
							echo '<strong class="text-danger">N/A - Awaiting Payment</strong>';
						}
						else{
							echo '<strong>' . ucfirst($order->or_status) . '</strong>';
							echo ' <button class="btn btn-sm btn-warning btnUpdateLink" data-ref="' . $order->or_uid . '">Update Status</button>';
						}
						?>

					</td>
					<td><?=$order->or_quantity?></td>
					<td><i>₩ <?=number_format($order->or_rate)?></i></td>
					<td class="text-right"><strong>₩ <?=number_format($order->or_amount)?></strong></td>
					<td class="text-<?=$status?>"><?=ucfirst($order->or_status)?></td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
		<?php
		if ($total_rows == 0){
			echo '<h3 class="text-danger">No records found!</h3>';
		}
		?>
		<?=$pagination?>
	</div>

<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Update Status</h4>
			</div>
			<?=form_open(current_url(), array('id'=>'frmStatus'))?>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-2 col-xs-offset-1">
						<span class="glyphicon glyphicon-print text-muted" style="font-size:28px;"></span>
					</div>
					<div class="col-xs-4">
						<strong>Order Status</strong> <br>
						<small>Select the status of the order.</small>
					</div>
					<div class="col-xs-4">
						<?php
						$options = array(
										'completed'				=> 'Completed',
										'cancelled'				=> 'Cancelled'
									);
						echo form_dropdown('status', $options, '', 'class="form-control"');
						?>
					</div>
					<div class="col-xs-12">
						<br>
						<textarea name="note" id="frmnote" rows="4" class="form-control" placeholder="Order Note"></textarea>					
					</div>
				</div>
				<input type="hidden" name="uid" id="frmOrderId" value="">
				<div class="cf"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary" id="btnFrmStatusUpdate">Update</button>
			</div>
			<?=form_close()?>
		</div>
	</div>
</div>

<div class="modal fade" id="optionsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Print Options</h4>
			</div>
			<div class="modal-body">
				<div id="printOptionsView"></div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" id="printOptions"><span class="glyphicon glyphicon-print"></span> Print</button>
			</div>
		</div>
	</div>
</div>

<script>
var manager = {
	progress: {
	},
	generatePDF: function(){
		/**
		 * this will generate PDF for the print.
		 */

		// show progressbar and hide buttons.
		var p = $(this).parent();
		p.find('.progress').removeClass('hidden');
		p.find('.btnGenerate').addClass('hidden');

		var name = p.attr('data-name');
		var id = 'id_' + name;
		manager.progress.id = false;
		manager.updateProgress(name, 1);

		// req to generate PDF.
		$.ajax({
			type: 'post',
			data: '',
			url: base_url() + 'a/pdf/' + p.attr('data-id') + '/' + name,
			success: function(data){
				manager.progress.id = true;
				p.find('.progress').addClass('hidden');
				p.find('.linkFile, .btnUpdateLink').removeClass('hidden');

				// Update Name.
				$.post(base_url()+'a/pdfgenerate/' + p.attr('data-order') + '/' + name);
			},
			error: function(){
				p.find('.progress, .btnUpdateLink').addClass('hidden');
				p.find('.btnGenerate').removeClass('hidden');
				alert('Unable to Generate PDF.\r\nTry Again!');
			}
		});
	},
	updateProgress: function(n, step, id){

		current = step || 1;
		var p = $('.generatePDF[data-name="' + n + '"]');
		
		if (current <= 100 && manager.progress.id == false){

			var newStep = current + 1;

			p.find('.progress-bar').attr('aria-valuenow', current).css('width', current+'%');

			setTimeout(function(){
				manager.updateProgress(n, newStep, id);
			}, 500);
		}
		else{
			p.find('.progress-bar').attr('aria-valuenow', '100').dequeue().animate({
				width: '100%'
			}, 300);
		}
	},
	updateLink: function(){
		$('#statusModal').modal('show');

		var ref = $(this).attr('data-ref');
		$('#frmOrderId').val(ref);
	},
	validate: function(){
		var o = $('#frmnote');
		if (o.val().trim() == ''){
			alert('Enter your comments for the status change!');
			return false;
		}
	},
	init: function(){
		/**
		 * this will initialize js.
		 */
		$('.btnGenerate').click(manager.generatePDF);

		$('#frmView').change(function(){
			var vl = $(this).val();
			window.location.href = base_url() + 'a/dashboard/' + vl;
		});

		$('.btnUpdateLink').click(manager.updateLink);

		$('.printCheckbox').click(function(){$(this).toggleClass('active')});

		$('form#frmStatus').submit(manager.validate);

		$('.btnViewOptions').click(function(){
			var ref = $(this).attr('data-ref');
			$('#printOptionsView').html('Loading Options... Please wait');
			$('#optionsModal').modal('show');
			$.ajax({
				type: 'get',
				data: '',
				url: base_url() + 'a/loadOptions/' + ref,
				success: function(data){
					$('#printOptionsView').html(data);
				},
				failure: function(){
					alert('Unable to load options');
				}
			});
		});

		$('#printOptions').click(function(){
			$('#printOptionsView').printThis();
		});
	}
}

$(manager.init);
</script>
<?php include('inc/footer.php') ?>