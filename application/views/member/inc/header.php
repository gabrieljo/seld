<?php
$title = isset($title) ? $title : SITE_TITLE;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<title><?=$title?></title>
	<link rel="icon" type="image/png" href="<?=base_url()?>files/img/favicon.png">
	<?php
	// Load all CSS/JS framework
	$load = array(
				'bootstrap.min.css',
				'style.css',
				'jquery.js',
				'bootstrap.min.js'
			);
	echo inc($load);
	?>
	<script>
	function base_url(){
		return '<?=base_url()?>';
	}
	</script>
</head>
<body>

<div class="navbar navbar-danger seld-nav">
<div class="container-fluid">
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<?=anchor('m', inc('main/logo.png'), array('class'=>'navbar-brand'))?>
	</div>

	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<button class="btn btn-primary btn-sm" style="margin:8px 0 0 20px;" id="btnSeldSettings"><span class="glyphicon glyphicon-cog"></span> Settings</button>
		<button class="btn btn-success btn-sm" style="margin:8px 0 0;" id="btnSeldSave"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>

		<?=anchor('m/designs', '<span class="glyphicon glyphicon-triangle-left"></span> My Designs', array('class'=>'btn btn-danger btn-xs pull-right', 'style'=>'padding: 5px 10px;margin:8px -24px 0;'))?>
	</div><!-- /.navbar-collapse -->
</div><!-- /.container-fluid -->
</div>
<div class="seld-status">
	<div class="aside">
		<div id="status_title"><?=isset($status_title) ? $status_title : '';?></div>
	</div>
	<div class="article">
		<div id="status_content"><?=isset($status_msg) ? $status_msg : '';?></div>
	</div>
</div>

<div class="seld-body">
	<div class="container-fluid">
		<div class="row">