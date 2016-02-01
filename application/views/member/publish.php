<?php
include('./application/views/inc/n_header.php');
echo inc('m_publish.js');
?>

<div class="container seldMemberWrapper">
	<div class="row">
		<div class="col-sm-7 nopadding">
			<h3>
				Product Info 
				<?=anchor('m/designs', '<span class="glyphicon glyphicon-list"></span> View Other designs', array('class'=>'btn btn-warning btn-sm pull-right', 'style'=>'margin-right: 10px;'))?>
			</h3>
			<hr>

			<div class="product-preview">
				<?php
				/**
				 * get total pages in the design. 
				 */
				$canvas = (object) array('page'=>1, 'fold'=>0);
		        if ($product->pr_options != ''){
		            
		            $options = unserialize(@$product->pr_options);

		            // get total pages.
		            $pages = @$options['set-pages'];
		            if ($product->pr_type == '2' || $product->pr_type == '1'){ // type leaflet or business card.
		                $canvas->page = strtolower($pages) == 'single side' ? 1 : 2; // single side OR double side.

		                // get folding lines
		                $fold = @$options['set-folding-paper'];
		                $canvas->fold = $fold == '' ? 0 : intval($fold);
		            }
		            else{
		                $canvas->page = intval($pages) <= 0 ? 1 : intval($pages);
		            }
		        }
				$total_pages 	= intval($product->pr_type) == 2 ? ($canvas->page * ($canvas->fold + 1)) : $canvas->page;
				$total_pages > 2 ? 2 : $total_pages;

				$li = '';
				for ($i=1; $i<=$total_pages; $i++){
					$li.= sprintf('<li class="list-item-%d"><img src="%s" /></li>', $i, base_url().'files/products/'.$product->pr_uid.'/design/thumbs/page-' . $i . '.png');
				}

				$type = $total_pages > 4 ? 4 : $total_pages;
				echo '<ul class="product_info product_info_preview_' . $type . '">' . $li . '</ul>';
				?>				
			</div>

			<dl class="dl-horizontal">
				<dt>Title</dt>
				<dd class="text-capitalize"><?=$product->pr_title?></dd>

				<dt>Description</dt>
				<dd><?=$product->pr_description?></dd>

				<dt></dt><dd><hr></dd>

				<dt></dt>
				<dd>
					<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#orderPrintModal"><span class="glyphicon glyphicon-print"></span> Print Order</button>
					<small class="text-warning">Click to create a NEW PRINT ORDER for your design.</small>
					<br><br>
				</dd>

				<dt>History</dt>
				<dd>
					<table class="table table-condensed">
						<thead>
							<th>#</th>
							<th>Amount</th>
							<th>Quantity</th>
							<th>
								Status 
							</th>
						</thead>
						<tbody>
							<?php
							$sn = 0;
							foreach ($history->result() as $item){
							?>
							<tr>
								<td><?=++$sn?></td>
								<td class="text-right"><strong>₩ <span><?=number_format($item->or_amount)?></span></strong></td>
								<td class="text-center"><?=$item->or_quantity?></td>
								<td>
									<?php
									if ($item->or_status == 'awaiting-payment'){
										echo anchor('m/publish/' . $product->pr_uid . '/payment/' . $item->or_uid, 'Unpaid', array('class'=>'btn btn-danger btn-sm', 'title'=>'Click to Pay')) . ' - Order not received.';
									}
									else{
										echo '<i class="text-muted">Payment date: ' . $item->or_payment_date . '</i> <br />';
										echo 'Status: ' . $item->or_status . ' - Order received';
									}
									?>
								</td>
							</tr>
							<?php
							}
							?>
						</tbody>
					</table>
				</dd>
			</dl>
		</div>
		<div class="col-sm-5 nopadding">
			<div class="panel panel-info">
				<div class="panel-heading"><h3><span class="glyphicon glyphicon-barcode"></span> SELD Market</h3></div>
				<div class="panel-body">
					<?php
					if ($product->pr_mk_status == 'unlisted'){
					?>
						<div class="text-center marketInfo">
							<p class="text-danger">
								This design is not in SELD Market yet! <br>
								You can put your design to the market and start making money now.
							</p>
							<hr>
							<button class="btn btn-success btn-lg btnAddToMarket"><span class="glyphicon glyphicon-plus-sign"></span> Add to Market</button>
							<hr>
							<p class="text-muted">
								Once you put your design is in market, you will no-longer be able to edit this design. However you can always make a copy of the current design and build on it to make a completely new one.
							</p>
						</div>
					<?php
					}
					else{
					?>
					<dl class="dl-horizontal marketInfo">
						
						<dt></dt>
						<dd>
							<button class="btn btn-warning btn-xs pull-right btnAddToMarket"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
						</dd>

						<dt>Design Views</dt>
						<dd><strong><?=$product->pr_mk_hits?></strong></dd>

						<dt>Market Published</dt>
						<dd><?=date('dS M, Y h:i A', strtotime($product->pr_mk_created_at))?></dd>

						<dt>Price</dt>
						<dd class="text-capitalize"><strong>₩ <?=number_format($product->pr_mk_orig_price)?></strong></dd>
						
						<?php
						if ($product->pr_mk_price > 0){
						?>
						<dt>Special Price</dt>
						<dd><strong>₩ <?=number_format($product->pr_mk_price)?></strong></dd>
						<?php
						}
						?>

						<dt>Description</dt>
						<dd><?=$product->pr_mk_description?></dd>

						<dt></dt>
						<dd><hr></dd>
							
						<dt>Total Buyers</dt>
						<dd>0</dd>

						<dt>Total Earnings</dt>
						<dd><h4>₩ <strong><?=number_format('0')?></strong></h4></dd>

						<dt>Recent Purchases</dt>
						<dd>
							N/A
							<!-- <table class="table table-hover">
								<tr>
									<td width="20">1</td>
									<td><small>2016-01-28 05:30PM</small></td>
									<td>Mr. Sam</td>
								</tr>
							</table> -->
						</dd>
					</dl>
					<?php
					} // Market Item Details
					?>
					<div class="marketForm hidden">
							<?=form_open('m/publish/' . $product->pr_uid, array('class'=>'form-horizontal', 'id'=>'frmAddMarket', 'data-status'=>$product->pr_mk_status))?>
							<p class="text-primary text-center marketAddInfoTop">
								Add a price for your design for the buyers in SELD Market. Also add a brief description about your design.
							</p>
							<div class="form-group">
								<label for="mk_orig_price" class="col-sm-4 control-label">*Price</label>
								<div class="col-sm-4">
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-addon">₩</div>
											<input type="text" class="form-control" id="mk_orig_price" name="mk_orig_price" placeholder="Amount" value="<?=$product->pr_mk_orig_price?>" maxlength="6">
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="mk_price" class="col-sm-4 control-label">Special Price</label>
								<div class="col-sm-4">
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-addon">₩</div>
											<input type="text" class="form-control" id="mk_price" name="mk_price" placeholder="Amount" value="<?=$product->pr_mk_price?>" maxlength="6">
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class=" col-sm-12">
									<textarea name="mk_description" id="mk_description" rows="4" class="form-control" placeholder="Description"><?=($product->pr_mk_description == '') ? $product->pr_description : $product->pr_mk_description?></textarea>
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-offset-4 col-sm-10">
									<button type="submit" class="btn btn-info" id="addToMarketButton"><span>Add to Market</span></button>
								</div>
								<div class="text-center marketAddTnC"><i><small>*By Adding to Market, you accept SELD Terms and Conditions.</small></i></div>
							</div>
							<input type="hidden" name="frmAddMarket" value="1">
							<?=form_close()?>
						</div>
				</div>
			</div>
		</div>		
	</div>
</div>

<div class="modal fade" id="orderPrintModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<?=form_open('m/publish/' . $product->pr_uid . '/payment', array('id'=>'frmPrintOrder'))?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Order Print</h4>
			</div>
			<div class="modal-body">
				<?php 
				$tr = '';
				$sn = 0;
				$total = 0;
				foreach ($particulars as $item){
					$tr.= '	<tr>
								<td>' . ++$sn . '</td>
								<td>' . $item->title . '</td>
								<td>' . $item->desc . '</td>
								<td class="text-right">₩ ' . number_format($item->rate) . '</td>
							</tr>';
					$total += $item->rate;
				}
				?>
				<table class="table table-bordered table-condensed">
					<thead>
						<th width="30">#</th>
						<th>Print Option</th>
						<th>Description</th>
						<th width="140">Amount</th>
					</thead>
					<tfoot>
						<tr>
							<td colspan="3" class="text-right">Rate per page</td>
							<td colspan="1" class="text-right text-warning"><strong>₩ <?=number_format($total)?></strong></td>
						</tr>
						<tr>
							<td colspan="3" class="text-right">Rate per Set <i>(<?=$page_rate?>x)</i></td>
							<td colspan="1" class="text-right text-primary"><strong>₩ <?=number_format($total*$page_rate)?></strong></td>
						</tr>
						<tr>
							<td colspan="3" class="text-right">Quantity</td>
							<td><input type="number" name="printQuantity" id="printQuantity" class="form-control" style="text-align:right;font-weight:bold;" placeholder="Min 100." value="100" data-rate="<?=$total*$page_rate?>"></td>
						</tr>
						<tr>
							<td colspan="3" class="text-right">Total Amount</td>
							<td class="text-right"><strong>₩ <span id="totalPrintAmount"><?=number_format($total*$page_rate*100)?></span></strong></td>
						</tr>
					</tfoot>
					<tbody>
						<?=$tr?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				<button type="submit" name="frmSubmit" value="1" class="btn btn-primary">Pay and Order Now</button>
			</div>
			<?=form_close()?>
		</div>
	</div>
</div>

<div class="modal fade" id="orderPaymnetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<?=form_open('m/publish/' . $product->pr_uid . '/pay/' . $action_id, array('id'=>'frmPayOrder'))?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Order Payment</h4>
			</div>
			<div class="modal-body">
				<div class="text-center" style="padding: 50px 0;">
					<?=anchor('m/publish/' . $product->pr_uid . '/pay/' . $action_id, inc('btn-paypal.png', array('width'=>'250px;')))?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
			<?=form_close()?>
		</div>
	</div>
</div>

<script>$(design.init)</script>
<script>
$(function(){

	<?php
	if ($action == 'payment' && $action_id != ''){
	?>
		$('#orderPaymnetModal').modal('show');
	<?php
	}
	?>
});
</script>
<?php include('./application/views/inc/n_footer.php') ?>