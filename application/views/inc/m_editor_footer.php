<div id="editor_footer">
	<div class="pull-left">
		<small id="last_save_msg"></small>
	</div>
	<ul class="pull-right">
		<?php
		/*if ($prop['page'] > 1){
		?>
		<li>
			<ul class="pad-pagination">
				<?php
				for ($i=1; $i<=$prop['page']; $i++){
					$cls = $i==1 ? 'class="active"' : '';
					echo '<li ' . $cls .'>' . $i . '</li>';
				}
				?>
				<div class="cf"></div>
			</ul>
		</li>
		<li>
			<span class="glyphicon glyphicon-pencil" style="position:relative;top:3px;"></span> Page
		</li>
		<?php
		}
		if ($prop['face'] == 2){
		?>
		<li>
			<ul class="pad-face">
				<li class="active">Front</li>
				<li>Back</li>
				<div class="cf"></div>
			</ul>
		</li>
		<li>
			<span class="glyphicon glyphicon-grain" style="position:relative;top:3px;"></span> Face
		</li>
		<?php
		} // Face Option*/

		$options = array();
		if ($total_pages > 1){
			for ($i=1; $i<=$total_pages; $i++){
				$options[''.$i] = 'Page '.$i;
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
			<input id="canvas_zoom" type="range" min="1" max="200" step="1" value="100">				
		</li>
		<li>
			<span class="glyphicon glyphicon-minus-sign" style="position:relative;top:3px;"></span>
		</li>
	</ul>
</div><!-- #editor_footer -->