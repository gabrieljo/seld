<?php include('inc/header.php') ?>

<?=inc('m_list.js')?>

<div class="col-sm-12">
	<h3>SELD Client Designs <small><?=sprintf("%s %s (%s)", $client->cl_firstname, $client->cl_lastname, $client->cl_email)?></small></h3><hr>
	
	<table class="table table-hover">
		<thead>
			<th width="30"><span class="glyphicon glyphicon-th"></span></th>
			<th>Name</th>
			<th width="200">Details</th>
			<th width="150">History</th>
			<th width="120">Preview</th>
		</thead>
		<tbody>
			<?php
			$sn = 0;
			foreach ($designs->result() as $design){
				if ($design->pr_status == 'new') continue;

				/**
				 * get total pages in the design. 
				 */
				$canvas = (object) array('page'=>1, 'fold'=>0);
		        if ($design->pr_options != ''){
		            
		            $options = unserialize(@$design->pr_options);

		            // get total pages.
		            $pages = @$options['set-pages'];
		            if ($design->pr_type == '2' || $design->pr_type == '1'){ // type leaflet or business card.
		                $canvas->page = strtolower($pages) == 'single side' ? 1 : 2; // single side OR double side.

		                // get folding lines
		                $fold = @$options['set-folding-paper'];
		                $canvas->fold = $fold == '' ? 0 : intval($fold);
		            }
		            else{
		                $canvas->page = intval($pages) <= 0 ? 1 : intval($pages);
		            }
		        }
				$total_pages 	= intval($design->pr_type) == 2 ? ($canvas->page * ($canvas->fold + 1)) : $canvas->page;
			?>
			<tr>
				<td><?=++$sn?>.</td>
				<td>
					<strong class="text-primary"><?=$design->pr_title?></strong> <br>
					<small><?=$design->pr_description?></small>
				</td>
				<td>
					<small>Created On: <?=date('dS M, Y', strtotime($design->pr_created_at))?></small>
				</td>
				<td>N/A</td>
				<td>
					<?php
					if (file_exists('./files/products/' . $design->pr_uid . '/design/page-1.png')){
						echo '<button class="btn btn-sm btn-info preview" data-target="' . $design->pr_uid . '" data-pages="' . $total_pages . '"><span class="glyphicon glyphicon-eye-open"></span> Preview</button>';
					}
					?>
				</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</div>

<!-- File Info -->
<div class="canvas_file_info overlay hidden"></div>
<div class="canvas_file_info wrapper hidden"><div id="preview_wrapper"></div></div>
<script>
$(design.init);
</script>

<?php include('inc/footer.php') ?>