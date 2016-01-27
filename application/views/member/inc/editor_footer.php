<div id="editor_footer">
	<div class="pull-left">
		<small id="last_save_msg"></small>
	</div>
	<ul class="pull-right">
		<?php

		$options = array();
		if ($total_pages > 1){
			for ($i=1; $i<=$total_pages; $i++){
				$options[''.$i] = 'Page '.$i;
			}

			if ($total_pages > 1){
		?>
			<li class="canvas_pagination" data-type="next" title="Next Page (Alt+Right)"><button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-chevron-right"></span></button></li>
			<li class="canvas_pagination" data-type="prev" title="Previous Page (Alt+Left)"><button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-chevron-left"></span></button></li>
			<li class="canvas_pagination" data-type="view" title="View Pages (Alt+v)"><button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-th"></span></button></li>
		<?php
			}
		?>
			<li title="Current Page"><?=form_dropdown('seldpage-number', $options, 1, 'id="seldpage-number"')?></li>
		<?php
		}
		?>

		<li title="Original Size (Ctrl+F)"><span class="glyphicon glyphicon-fullscreen"></span></li>
		<li title="Fit to Screen (Ctrl+Q)"><span class="glyphicon glyphicon-resize-small"></span></li>
		<li><div id="slider_zoom">100%</div></li>
		<li>
			<span class="glyphicon glyphicon-plus-sign" style="position:relative;top:3px;"></span>
		</li>
		<li id="slider-container">
			<input id="canvas_zoom" type="range" min="1" max="150" step="1" value="100">				
		</li>
		<li>
			<span class="glyphicon glyphicon-minus-sign" style="position:relative;top:3px;"></span>
		</li>
	</ul>
</div><!-- #editor_footer -->