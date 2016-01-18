<?php
echo form_open("u/create/" . $product->pr_uid. "/theme", array('class'=>'form-horizontal', 'id'=>'frmSettings'));

/*if (isset($type)){
	include($page."-{$type}.php");
}*/

// put default items on display.
$col1 = $col2 = $col3 = '';

foreach ($options->result() as $opt){
	$load = unserialize($opt->d_op_load);

	$name_field = $opt->d_op_name;
	$name_field.= $load['type'] == 'default' ? '' : '-' . $opt->d_op_id;
	$class 		= $load['type'] == 'default' ? '' : 'hidden';
	$ajax_load 	= $load['type'] == 'default' ? '' : 'ajax_load';

	$options 	= unserialize($opt->d_op_options);
	$value 		= isset($form[''.$opt->d_op_name]) ? $form[''.$opt->d_op_name] : $opt->d_op_default;
	$attr 		= 'class="form-control control-form input-xs ' . $ajax_load . '" id="form-control-' . $opt->d_op_id . '" data-id="' . $opt->d_op_id . '" data-dep-id="' . $opt->d_op_dep_id . '" data-dep-val="' . $opt->d_op_dep_val . '" data-name="' . $opt->d_op_name . '" ';
	$field 		= '<h4>' . $opt->d_op_attr . '</h4>';

	if ($opt->d_op_col == 3){
		$field.= '<div class="form-group form-group-sm ' . $class . '">
					<label for="' . $opt->d_op_name . '" class="col-sm-6 control-label">' . $opt->d_op_title . '</label>
					<div class="col-sm-6">' . form_dropdown($name_field, $options, $value, $attr) . '</div>
				</div>';
	}
	else{
		$field.= '<div class="form-group form-group-sm col-sm-10 ' . $class . '">
					<label for="' . $opt->d_op_name . '">' . $opt->d_op_title . '</label>
					<div>' . form_dropdown($name_field, $options, $value, $attr) . '</div>
				</div>';
	}

	// Assign to specific columns
	if ($opt->d_op_col == 1){
		$col1.= $field;
	}
	else if ($opt->d_op_col == 2){
		$col2.= $field;
	}
	else{
		$col3.= $field;
	}
}
?>
<div class="container-fluid inner-content">
	<div class="row">
		<div class="col-sm-3 col-sm-offset-1">
		<?=$col1?></div><!-- #col-1 -->
		<div class="col-sm-3 nopadding"><?=$col2?></div><!-- #col-2 -->
		<div class="col-sm-4 col-sm-offset-0"><?=$col3?></div><!-- col-3 -->
	</div>
	<hr>
	<button name="frmsubmit" class="btn btn-primary pull-right">Save and continue <span class="glyphicon glyphicon-chevron-right"></span></button>
</div>
<?php
echo form_close();
?>
<script>$(step.init)</script>