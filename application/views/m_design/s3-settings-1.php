<?=form_open("u/create/settings/{$ref}", array('class'=>'form-horizontal'))?>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-4">
			<h5><?=$product?></h5>
			<div class="form-group form-group-sm col-sm-10">
				<label for="exampleInputEmail1">1. Select Paper</label>
				<?php
				$options = array(
								'eco-friendly' 	=> 'Eco-Friendly',
								'epoxy-resin'	=> 'Epoxy Resin',
								'laster'		=> 'Laster',
								'pet'			=> 'PET'
							);
				echo form_dropdown('settings-paper', $options, '', 'class="form-control input-xs"');
				?>
			</div>
			<div class="form-group form-group-sm col-sm-10">
				<label for="exampleInputEmail1">2. Frequency</label>
				<?php
				$options = array(
								'both sides to 8 degrees' 	=> 'Both Sides to 8 degrees Celsius',
								'one sides to 8 degrees'	=> 'One Sides to 8 degrees Celsius',
								'both sides to 5 degrees'	=> 'both sides to 5 degrees Celsius'
							);
				echo form_dropdown('settings-frequency', $options, '', 'class="form-control input-xs"');
				?>
			</div>
			<div class="form-group form-group-sm col-sm-10">
				<label for="exampleInputEmail1">3. Quantity</label>
				<?php
				$options = array(
								'spin-box' 	=> 'Spin Box'
							);
				echo form_dropdown('settings-quantity', $options, '', 'class="form-control input-xs"');
				?>
			</div>
		</div> <!-- .col-xs-4 -->
		<div class="col-xs-8">
			<br>
			<h5>4. Processing method after</h5>
			<div class="form-group form-group-sm">
				<label for="inputEmail3" class="col-sm-3 control-label">Border Radius</label>
				<div class="col-sm-3">
					<input type="checkbox" name="" id=""> Left-Upper <br>
					<input type="checkbox" name="" id=""> Left-Bottom					
				</div>
				<div class="col-sm-3">
					<div class="container-border-radius"></div>
				</div>
				<div class="col-sm-3">
					<input type="checkbox" name="" id=""> Right-Upper <br>
					<input type="checkbox" name="" id=""> Right-Bottom
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="inputEmail3" class="col-sm-3 control-label">Folding Line</label>
				<div class="col-sm-4">
					<?php
					$options = array(
									'1' => '1 Line',
									'4'	=> '4 Lines'
								);
					echo form_dropdown('settings-folding', $options, '', 'class="form-control input-xs"');
					?>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="inputEmail3" class="col-sm-3 control-label">Dotted Line</label>
				<div class="col-sm-4">
					<?php
					$options = array(
									'1' => '1 Line',
									'4'	=> '4 Lines'
								);
					echo form_dropdown('settings-dotted', $options, '', 'class="form-control input-xs"');
					?>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="inputEmail3" class="col-sm-3 control-label">Making Hole</label>
				<div class="col-sm-4">
					<?php
					$options = array(
									'1' => '1 Hole',
									'4'	=> '4 Holes'
								);
					echo form_dropdown('settings-holes', $options, '', 'class="form-control input-xs"');
					?>
				</div>
				<div class="col-sm-2">
					<?php
					$options = array(
									'3' => '3 mm',
									'4'	=> '4 mm',
									'5'	=> '5 mm',
									'6'	=> '6 mm',
									'7'	=> '7 mm',
									'8'	=> '8 mm',
								);
					echo form_dropdown('settings-hole-size', $options, '', 'class="form-control input-xs"');
					?>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="inputEmail3" class="col-sm-3 control-label">Tolling</label>
				<div class="col-sm-2">
					<input type="checkbox" name="" id=""> Yes
				</div>
				<div class="col-sm-2 text-right">
					Painting
				</div>
				<div class="col-sm-2">
					<input type="checkbox" name="" id=""> Yes
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="inputEmail3" class="col-sm-3 control-label">Braille</label>
				<div class="col-sm-4">
					<?php
					$options = array(
									'' 			=> 'None',
									'tooling'	=> 'Tooling',
									'emboss'	=> 'Emboss'
								);
					echo form_dropdown('settings-braille', $options, '', 'class="form-control input-xs"');
					?>
				</div>
			</div>
		</div> <!-- .col-xs-8 -->
	</div>
</div>
<div class="container-fluid bdt">
	<div class="row">
		<div class="col-xs-9">
			<h5>Preview</h5>
		</div><!-- .col-xs-9 -->
		<div class="col-xs-3 bdl">
			<h5>Price Info</h5>
			<p class="text-muted">
				Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. 
			</p>
		</div><!-- .col-xs-3 -->
	</div>
</div>
<?=form_close()?>