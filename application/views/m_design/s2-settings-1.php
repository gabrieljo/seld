<div class="container-fluid inner-content">
	<div class="row">
		<div class="col-sm-3 col-sm-offset-1">
			<h4>1. Select Paper (용지선택)</h4>
			<div class="form-group form-group-sm col-sm-10">
				<label for="set-size">1. Size</label>
				<?php
				$options = array(
								'A8'=>'A8'
							);
				echo form_dropdown('set-size', $options, @$form['set-size'], 'class="form-control input-xs" id="set-size"');
				?>
			</div>
		</div>
		<div class="col-sm-3 nopadding">
			<h4>2. Print Option</h4>
			
		</div>
		<div class="col-sm-4 col-sm-offset-0">
			<h4>3. Processing method after</h4>
		</div>
	</div>
	<hr>
	<button name="frmsubmit" class="btn btn-primary pull-right">Save and continue <span class="glyphicon glyphicon-chevron-right"></span></button>
</div>