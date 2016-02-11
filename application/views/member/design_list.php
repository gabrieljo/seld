<?php include('./application/views/inc/n_header.php') ?>

<?=inc('m_list.js')?>

<div class="container seldMemberWrapper">
	<div class="row">

		<div class="col-md-12">
			<h3 class="pull-left">My Designs</h3>
			<button class="btn btn-sm btn-warning pull-right" style="margin-left:5px;" data-toggle="modal" data-target="#myThemes"><span class="glyphicon glyphicon-list"></span> From My Themes</button>			
			<?=anchor('m/import', '<span class="glyphicon glyphicon-upload"></span> Upload', array('class'=>'btn btn-lg pull-right btn-sm btn-primary', 'style'=>'margin-left:5px;'))?>
			<?=anchor('m/create', '<span class="glyphicon glyphicon-plus"></span> 디차인', array('class'=>'btn btn-lg pull-right btn-sm btn-success'))?>
			<div class="cf"></div>
			<hr>
			<table class="table table-bordered table-hover">
				<thead>
					<th width="50">S.N.</th>
					<th>Details</th>
					<th width="120">Status</th>
					<th width="150">Type</th>
					<th width="240">Action</th>
				</thead>
				<?php
				if ($total == 0){
				?>
				<tfoot>
					<td colspan="5" class="text-danger text-center">
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
					$sn = ($page - 1 ) * $total_perpage;
					$tb = base64_encode(current_url());
					foreach ($products->result() as $product):

						$status = $product->pr_status;
						$cls 	= $status=='new' ? 'info' : ($status=='pending' ? 'warning' : '');

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
						$total_pages 	= $canvas->page; //intval($product->pr_type) == 2 ? ($canvas->page * ($canvas->fold + 1)) : $canvas->page;

						if ($product->pr_src == 'seld'){
					?>
					<tr class="<?=$cls?>">
						<td><?=++$sn?></td>
						<td>
							<?php					
							if ($status == 'pending' || $status == 'completed'){

								echo anchor('m/copy/' . $product->pr_uid . '/' . $tb, '<span class="glyphicon glyphicon-duplicate"></span> Copy', array('class'=>'btn btn-warning btn-sm pull-right btnCopy', 'title'=>'Copy Design')) . ' ';
							}
							else{

								echo anchor('m/delete/' . $product->pr_uid . '/' . $tb, '<span class="glyphicon glyphicon-trash"></span> Delete', array('class'=>'btn btn-danger btn-sm pull-right btnDelete', 'title'=>'Delete Design'));
							}

							// product title with open link.
							$title = ($product->pr_title == '') ? 'No Title' : $product->pr_title;
							echo ($status == 'new' || $status == 'designing') ? anchor('m/create/' . $product->pr_uid, '<strong class="text-primary">'.$title.'</strong>', array('title'=>'Click to Edit')) : '<strong class="text-danger"><i>'.$title.'</i></strong>';
							?>
							
							<p class="text-muted"><?=$product->pr_description?></p>
							<i class="text-muted" style="font-size:9px;">
								<small>Created</small>: <?=$product->pr_created_at?>
								<?php
								if ($product->pr_updated_at != ''){
									echo ', Last Updated: ' . $product->pr_updated_at;
								}
								?>
							</i>
						</td>
						<td><strong class="text-<?=$cls?>"><?=ucfirst($status)?></strong></td>
						<td><?=$product->d_pr_name?></td>
						<td>
							<?php
								if (file_exists('./files/products/' . $product->pr_uid . '/design/page-1.png')){
									echo '<button class="btn btn-sm btn-info preview" data-target="' . $product->pr_uid . '" data-type="seld" data-pages="' . $total_pages . '"><span class="glyphicon glyphicon-eye-open"></span> Preview</button>';

									if ($status == 'designing'){
										echo '&nbsp;';
										echo anchor('m/publish/' . $product->pr_uid, '<span class="glyphicon glyphicon-check"></span> Publish ', array('class'=>'btn btn-sm btn-success'));
									}
								}
							?>
						</td>
					</tr>					
					<?php
					}
					else{
					?>
					<tr class="<?=$cls?>">
						<td><?=++$sn?></td>
						<td>
							<?php					

							echo anchor('m/delete/' . $product->pr_uid . '/' . $tb, '<span class="glyphicon glyphicon-trash"></span> Delete', array('class'=>'btn btn-danger btn-sm pull-right btnDelete', 'title'=>'Delete Design'));

							// product title with open link.
							$title = ($product->pr_title == '') ? 'No Import Title' : $product->pr_title;
							echo ($status == 'new' || $status == 'designing') ? anchor('m/import/' . $product->pr_uid, '<strong class="text-primary">'.$title.'</strong>', array('title'=>'Click to Edit')) : '<strong class="text-danger"><i>'.$title.'</i></strong>';
							?>
							
							<p class="text-muted"><?=$product->pr_description?></p>
							<i class="text-muted" style="font-size:9px;">
								<small>Created</small>: <?=$product->pr_created_at?>
								<?php
								if ($product->pr_updated_at != ''){
									echo ', Last Updated: ' . $product->pr_updated_at;
								}
								?>
							</i>
						</td>
						<td><strong class="text-<?=$cls?>"><?=ucfirst($status)?></strong></td>
						<td>User Upload</td>
						<td>
							<?php
								if ($product->pr_preview != '' && file_exists('./files/products/' . $product->pr_uid . '/preview.' . $product->pr_preview)){
									echo '<button class="btn btn-sm btn-info preview" data-target="' . $product->pr_uid . '" data-type="upload" data-ext="' . $product->pr_preview . '" data-pages="1"><span class="glyphicon glyphicon-eye-open"></span> Preview</button>';
									echo '&nbsp;';
									echo anchor('m/publish/' . $product->pr_uid, '<span class="glyphicon glyphicon-check"></span> Publish ', array('class'=>'btn btn-sm btn-success'));
								}
							?>
						</td>
					</tr>
					<?php
					}
					endforeach;
					?>
				</tbody>
			</table>
			<?=$pagination?>
		</div>

	</div>
</div>

<!-- THeme list. -->
<div class="modal fade" tabindex="-1" role="dialog" id="myThemes">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">My Themes</h4>
				</div>
			<div class="modal-body">
				<ul class="product-themes">
					<?php
					$sn = 0;
					foreach ($themes->result() as $item){
						echo '<li>
                                <div class="theme-preview"><img src="' . base_url() . 'files/products/' . $item->pr_uid . '/design/thumbs/page-1.png" /></div>
                                <div class="theme-buttons">
                                	' . anchor('m/createFromTheme/' . $item->pr_uid, '<span class="glyphicon glyphicon-ok"></span> Select Theme', array('class'=>'btn btn-sm btn-primary btn-select-theme')) . '
                                </div>
                                <div class="theme-description">
                                    <h3>' . $item->pr_title . '</h3>' . $item->pr_description . '
                                </div>
                            </li>';
                           $sn++;
					}
					?>
				</ul>
				<div class="cf"></div>

				<?php
				if ($sn == 0){
					echo '<h3>No themes available</h3>';
					echo anchor('market', 'View themes', array('class'=>'btn btn-success btn-sm'));
				}
				?>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- File Info -->
<div class="canvas_file_info overlay hidden"></div>
<div class="canvas_file_info wrapper hidden"><div id="preview_wrapper"></div></div>
<script>$(design.init)</script>
<?php include('./application/views/inc/n_footer.php') ?>