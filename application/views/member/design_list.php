<?php include('inc/header.php') ?>

<?=inc('m_list.js')?>

<div class="col-md-12">
	<h3 class="pull-left">My Designs</h3>
	<?=anchor('m/create', '<span class="glyphicon glyphicon-plus"></span> Create New', array('class'=>'btn btn-lg pull-right btn-sm btn-success'))?>
	<div class="cf"></div>
	<hr>
	<table class="table table-bordered table-hover">
		<thead>
			<th width="50">S.N.</th>
			<th>Title</th>
			<th width="120">Status</th>
			<th width="120">Type</th>
			<th width="280">Date</th>
			<th width="180">Action</th>
		</thead>
		<?php
		if ($total == 0){
		?>
		<tfoot>
			<td colspan="6" class="text-danger text-center">
				<h3>Create your first design now!</h3>
				<?=anchor('m/create', '<span class="glyphicon glyphicon-plus"></span> Create New', array('class'=>'btn btn-lg btn-sm btn-success'))?>
				<br><br>
			</td>
		</tfoot>
		<?php
		}
		?>
		<tbody>
			<?php
			$sn = 0;
			$tb = base64_encode(current_url());
			foreach ($products->result() as $product):

				if ($product->pr_status == 'new'){
					$cls = 'success';
				}
				else if ($product->pr_status == 'pending'){
					$cls = 'danger';
				}
				else if ($product->pr_status == 'designing'){
					$cls = '';
				}
				else{
					$cls = '';
				}

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
			?>
			<tr class="<?=$cls?>">
				<td><?=++$sn?></td>
				<td>
					<?=anchor('m/delete/' . $product->pr_uid . '/' . $tb, '<span class="glyphicon glyphicon-trash"></span> Delete', array('class'=>'btn btn-danger btn-sm pull-right btnDelete', 'title'=>'Delete Design'))?>
					<?=($product->pr_title == '') ? '<i>No Title</i>' : $product->pr_title?>
					<p class="text-muted"><?=$product->pr_description?></p>
				</td>
				<td><?=ucfirst($product->pr_status)?></td>
				<td><?=$product->d_pr_name?></td>
				<td>
					<small>Created</small>: <?=$product->pr_created_at?> <br>
					<small>Last Updated</small>: <?=$product->pr_updated_at?>
				</td>
				<td>
					<?php
						echo anchor('m/create/' . $product->pr_uid, '<span class="glyphicon glyphicon-folder-open"></span> Open ', array('class'=>'btn btn-xs btn-primary'));
						echo '&nbsp;';
						if ($product->pr_options != '' && $product->pr_options != 'new' && $product->pr_contents != ''){
							/*echo anchor('m/publish/' . $product->pr_uid, '<span class="glyphicon glyphicon-export" title="Print Now"></span> Publish', array('class'=>'btn btn-xs btn-info', 'title'=>'Click to Publish Design'));*/
						}

						if (file_exists('./files/products/' . $product->pr_uid . '/design/page-1.png')){
							echo '<button class="btn btn-xs btn-info preview" data-target="' . $product->pr_uid . '" data-pages="' . $total_pages . '"><span class="glyphicon glyphicon-eye-open"></span> Preview</button>';
						}
					?>
				</td>
			</tr>					
			<?php
			endforeach;
			?>
		</tbody>
	</table>
	<?=$pagination?>
</div>

<!-- File Info -->
<div class="canvas_file_info overlay hidden"></div>
	<div class="canvas_file_info wrapper hidden"><div id="preview_wrapper"></div></div>
<script>$(design.init)</script>

<?php include('inc/footer.php') ?>