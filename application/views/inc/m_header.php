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

<div class="navbar navbar-default seld-nav">
<div class="container-fluid">
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<?=anchor('u/create', inc('logox.png'), array('class'=>'navbar-brand'))?>
	</div>

	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<ul class="nav navbar-nav">
			
		</ul>
		<?php
		$settings = isset($email) ? $email : 'sudarshan.sky38@gmail.com';
		?>
		<ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$settings?> <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><?=anchor('u/profile', '<span class="glyphicon glyphicon-user"></span> Profile')?></li>
					<li><?=anchor('u/settings', '<span class="glyphicon glyphicon-cog"></span> Settings')?></li>
					<li role="separator" class="divider"></li>
					<li><?=anchor('u/logout', '<span class="glyphicon glyphicon-off"></span> Logout')?></li>
				</ul>
			</li>
		</ul>
	</div><!-- /.navbar-collapse -->
</div><!-- /.container-fluid -->
</div>
<div class="seld-status">
	<div class="aside">
		<div id="status_title"><?=isset($status_title) ? $status_title : 'SELD Creative Editor';?></div>
	</div>
	<div class="article">
		<div id="status_content"><?=isset($status_msg) ? $status_msg : '';?></div>
	</div>
</div>

<div class="seld-body">
	<div class="container-fluid">
		<div class="row">